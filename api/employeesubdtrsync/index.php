<?php

	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET");

	function syncResponse($statusCode, $payload) {
		http_response_code($statusCode);
		echo json_encode($payload);
		exit;
	}

	// The same employee ID, date, and token convention used by employeesubdtr.
	foreach (array('id' => 'Employee ID', 'year' => 'Year', 'month' => 'Month', 'token' => 'Token') as $parameter => $label) {
		if (!isset($_GET[$parameter]) || trim((string) $_GET[$parameter]) === '') {
			syncResponse(400, array('status' => 'error', 'message' => "Missing or empty '{$label}' parameter."));
		}
	}

	$employeeId = trim((string) $_GET['id']);
	$year = filter_var($_GET['year'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 2000, 'max_range' => 2100)));
	$month = filter_var($_GET['month'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 12)));
	$day = null;
	$token = trim((string) $_GET['token']);

	if ($year === false || $month === false) {
		syncResponse(400, array('status' => 'error', 'message' => 'Please provide a valid year and month.'));
	}

	if (isset($_GET['day']) && trim((string) $_GET['day']) !== '') {
		$day = filter_var($_GET['day'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => cal_days_in_month(CAL_GREGORIAN, $month, $year))));
		if ($day === false) {
			syncResponse(400, array('status' => 'error', 'message' => 'Please provide a valid day for the selected month.'));
		}
	}

	if ($token !== "0a7a004339f450a46fe7b34767c54577") {
		syncResponse(401, array('status' => 'Access denied', 'message' => 'API Key is not Valid.'));
	}

	try {
		require_once '../../lib/env.php';

		$cnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$cnn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		$query = "SELECT dtrcode, nameday, dayno, monthno, monthname, yearno,
			amtimein, amtimeout, pmtimein, pmtimeout,
			attendance_gps_location_am_in, attendance_gps_location_am_out,
			attendance_gps_location_pm_in, attendance_gps_location_pm_out,
			modified_at, created_at
			FROM employee_dtr_sub_tbl
			WHERE emp_idcode = :id AND yearno = :year AND monthno = :month";
		$params = array(':id' => $employeeId, ':year' => $year, ':month' => $month);

		if ($day !== null) {
			$query .= " AND dayno = :day";
			$params[':day'] = $day;
		}

		$query .= " ORDER BY dayno ASC";
		$stmt = $cnn->prepare($query);
		$stmt->execute($params);
		$timeLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

		syncResponse(200, array(
			'status' => 'success',
			'message' => count($timeLogs) ? 'Time logs synchronized successfully.' : 'No time logs found for the selected period.',
			'employee_id' => $employeeId,
			'year' => $year,
			'month' => $month,
			'count' => count($timeLogs),
			'time_logs' => $timeLogs
		));
	} catch (PDOException $exception) {
		syncResponse(500, array('status' => 'error', 'message' => 'Database error: ' . $exception->getMessage()));
	} catch (Exception $exception) {
		syncResponse(500, array('status' => 'error', 'message' => $exception->getMessage()));
	}

?>

<?php

	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET");

	if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
		http_response_code(400);
		echo json_encode([
			"status" => "error",
			"message" => "Missing or empty 'Employee ID' parameter."
		]);
		exit;
	} elseif (!isset($_GET['year']) || empty(trim($_GET['year']))) {
		http_response_code(400);
		echo json_encode([
			"status" => "error",
			"message" => "Missing or empty 'Year' parameter."
		]);
		exit;
	} elseif (!isset($_GET['month']) || empty(trim($_GET['month']))) {
		http_response_code(400);
		echo json_encode([
			"status" => "error",
			"message" => "Missing or empty 'Month' parameter."
		]);
		exit;
	} elseif (!isset($_GET['day']) || empty(trim($_GET['day']))) {
		http_response_code(400);
		echo json_encode([
			"status" => "error",
			"message" => "Missing or empty 'Day' parameter."
		]);
		exit;
	} elseif (!isset($_GET['token']) || empty(trim($_GET['token']))) {
		http_response_code(400);
		echo json_encode([
			"status" => "error",
			"message" => "Missing or empty 'Token' parameter."
		]);
		exit;
	}

	$employeeid = isset(trim($_GET['id'])) ? trim($_GET['id']) : null;
	$yearno = isset(trim($_GET['year'])) ? trim($_GET['year']) : null;
	$monthno = isset(trim($_GET['month'])) ? trim($_GET['month']) : null;
	$dayno = isset(trim($_GET['day'])) ? trim($_GET['day']) : null;
	$amtimein = isset(trim($_GET['amtimein'])) ? trim($_GET['amtimein']) : null;
	$amtimeout = isset(trim($_GET['amtimeout'])) ? trim($_GET['amtimeout']) : null;
	$pmtimein = isset(trim($_GET['pmtimein'])) ? trim($_GET['pmtimein']) : null;
	$pmtimeout = isset(trim($_GET['pmtimeout'])) ? trim($_GET['pmtimeout']) : null;
	$gpslocamin = isset(trim($_GET['gpslocamin'])) ? trim($_GET['gpslocamin']) : null;
	$gpslocamout = isset(trim($_GET['gpslocamout'])) ? trim($_GET['gpslocamout']) : null;
	$gpslocpmin = isset(trim($_GET['gpslocpmin'])) ? trim($_GET['gpslocpmin']) : null;
	$gpslocpmout = isset(trim($_GET['gpslocpmout'])) ? trim($_GET['gpslocpmout']) : null;
	$token = isset(trim($_GET['token'])) ? trim($_GET['token']) : null;

	try {
		require_once '../../lib/env.php';

		$cnn = null;

		$cnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$query = "SELECT * FROM employee_dtr_sub_tbl WHERE emp_idcode = :id LIMIT 1";
		$stmt = $cnn->prepare($query);
		
		$stmt->bindParam(":id", $employee_id);
		$stmt->execute();
	} catch (PDOException $e) {
		http_response_code(500);
		echo json_encode([
			"status" => "error",
			"message" => "Database error: " . $e->getMessage()
		]);
	} catch (Exception $e) {
		http_response_code(500);
		echo json_encode([
			"status" => "error",
			"message" => $e->getMessage()
		]);
	}

?>
<?php

	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET");

	// 1. Parameter Validation
	if (!isset($_GET['id']) || trim($_GET['id']) === '') {
		http_response_code(400);
		echo json_encode(["status" => "error", "message" => "Missing or empty 'Employee ID' parameter."]);
		exit;
	} elseif (!isset($_GET['year']) || trim($_GET['year']) === '') {
		http_response_code(400);
		echo json_encode(["status" => "error", "message" => "Missing or empty 'Year' parameter."]);
		exit;
	} elseif (!isset($_GET['month']) || trim($_GET['month']) === '') {
		http_response_code(400);
		echo json_encode(["status" => "error", "message" => "Missing or empty 'Month' parameter."]);
		exit;
	} elseif (!isset($_GET['day']) || trim($_GET['day']) === '') {
		http_response_code(400);
		echo json_encode(["status" => "error", "message" => "Missing or empty 'Day' parameter."]);
		exit;
	} elseif (!isset($_GET['token']) || trim($_GET['token']) === '') {
		http_response_code(400);
		echo json_encode(["status" => "error", "message" => "Missing or empty 'Token' parameter."]);
		exit;
	}

	// 2. Sanitize and Extract Input
	$employeeid = trim($_GET['id']);
	$yearno     = (int)trim($_GET['year']);
	$monthno    = (int)trim($_GET['month']);
	$dayno      = (int)trim($_GET['day']);
	$token      = trim($_GET['token']);

	// Time and GPS parameters (defaulting to empty string "" instead of null to prevent NOT NULL SQL errors)
	$amtimein    = isset($_GET['amtimein']) ? trim($_GET['amtimein']) : null;
	$amtimeout   = isset($_GET['amtimeout']) ? trim($_GET['amtimeout']) : null;
	$pmtimein    = isset($_GET['pmtimein']) ? trim($_GET['pmtimein']) : null;
	$pmtimeout   = isset($_GET['pmtimeout']) ? trim($_GET['pmtimeout']) : null;

	$gpslocamin  = isset($_GET['gpslocamin']) ? trim($_GET['gpslocamin']) : null;
	$gpslocamout = isset($_GET['gpslocamout']) ? trim($_GET['gpslocamout']) : null;
	$gpslocpmin  = isset($_GET['gpslocpmin']) ? trim($_GET['gpslocpmin']) : null;
	$gpslocpmout = isset($_GET['gpslocpmout']) ? trim($_GET['gpslocpmout']) : null;

	if ($token === "0a7a004339f450a46fe7b34767c54577") {
		try {
			require_once '../../lib/env.php';

			$cnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
			$cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// 3. Check if record exists for this employee/year/month/day
			$query = "SELECT COUNT(*) FROM employee_dtr_sub_tbl WHERE emp_idcode = :id AND yearno = :year AND monthno = :month AND dayno = :day";
			$stmt = $cnn->prepare($query);
			$stmt->bindParam(":id", $employeeid);
			$stmt->bindParam(":year", $yearno);
			$stmt->bindParam(":month", $monthno);
			$stmt->bindParam(":day", $dayno);
			$stmt->execute();
			
			$recordExists = $stmt->fetchColumn() > 0;

			if (!$recordExists) {
				// --- NO RECORD FOUND: GENERATE ALL DAYS FOR THAT MONTH ---
				$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthno, $yearno);
				$monthName   = date("M", mktime(0, 0, 0, $monthno, 10)); // e.g., "Jul"

				// 1. Format the month with a leading zero (e.g., 7 -> "07")
				$formattedMonth = str_pad($monthno, 2, "0", STR_PAD_LEFT);
				
				// 2. Generate the dtrcode (e.g., "87654321-2026-07")
				$dtrcode = $employeeid . "-" . $yearno . "-" . $formattedMonth;

				// 3. Added dtrcode to the INSERT column list and parameters
				$insertQuery = "INSERT INTO employee_dtr_sub_tbl 
					(dtrcode, emp_idcode, yearno, monthno, monthname, dayno, nameday, amtimein, amtimeout, pmtimein, pmtimeout, attendance_gps_location_am_in, attendance_gps_location_am_out, attendance_gps_location_pm_in, attendance_gps_location_pm_out) 
					VALUES 
					(:dtrcode, :id, :year, :month, :monthname, :day, :nameday, :amtimein, :amtimeout, :pmtimein, :pmtimeout, :gpslocamin, :gpslocamout, :gpslocpmin, :gpslocpmout)";
				
				$insertStmt = $cnn->prepare($insertQuery);
				$cnn->beginTransaction();

				for ($d = 1; $d <= $daysInMonth; $d++) {
					// 3-letter day name (e.g., "Wed")
					$nameday = date("D", mktime(0, 0, 0, $monthno, $d, $yearno));

					// Populate time/GPS data only for target day; use empty string "" for rest to bypass NOT NULL constraint
					$currAmIn   = ($d == $dayno && $amtimein !== null) ? $amtimein : "";
					$currAmOut  = ($d == $dayno && $amtimeout !== null) ? $amtimeout : "";
					$currPmIn   = ($d == $dayno && $pmtimein !== null) ? $pmtimein : "";
					$currPmOut  = ($d == $dayno && $pmtimeout !== null) ? $pmtimeout : "";
					$currGpsAin = ($d == $dayno && $gpslocamin !== null) ? $gpslocamin : "";
					$currGpsAout= ($d == $dayno && $gpslocamout !== null) ? $gpslocamout : "";
					$currGpsPin = ($d == $dayno && $gpslocpmin !== null) ? $gpslocpmin : "";
					$currGpsPout= ($d == $dayno && $gpslocpmout !== null) ? $gpslocpmout : "";

					$insertStmt->execute([
						":dtrcode"      => $dtrcode, // Bind the formatted dtrcode here
						":id"           => $employeeid,
						":year"         => $yearno,
						":month"        => $monthno,
						":monthname"    => $monthName,
						":day"          => $d,
						":nameday"      => $nameday,
						":amtimein"     => $currAmIn,
						":amtimeout"    => $currAmOut,
						":pmtimein"     => $currPmIn,
						":pmtimeout"    => $currPmOut,
						":gpslocamin"  => $currGpsAin,
						":gpslocamout" => $currGpsAout,
						":gpslocpmin"  => $currGpsPin,
						":gpslocpmout" => $currGpsPout
					]);
				}

				$cnn->commit();

				http_response_code(200);
				echo json_encode([
					"status" => "success",
					"message" => "Monthly DTR generated and time logged successfully."
				]);

			} else {
				// --- RECORD EXISTS: UPDATE ONLY SET PARAMETERS ---
				$updateFields = [];
				$params = [
					":id"    => $employeeid,
					":year"  => $yearno,
					":month" => $monthno,
					":day"   => $dayno
				];

				if ($amtimein !== null) {
					$updateFields[] = "amtimein = :amtimein";
					$params[':amtimein'] = $amtimein;
				}
				if ($amtimeout !== null) {
					$updateFields[] = "amtimeout = :amtimeout";
					$params[':amtimeout'] = $amtimeout;
				}
				if ($pmtimein !== null) {
					$updateFields[] = "pmtimein = :pmtimein";
					$params[':pmtimein'] = $pmtimein;
				}
				if ($pmtimeout !== null) {
					$updateFields[] = "pmtimeout = :pmtimeout";
					$params[':pmtimeout'] = $pmtimeout;
				}
				if ($gpslocamin !== null) {
					$updateFields[] = "attendance_gps_location_am_in = :gpslocamin";
					$params[':gpslocamin'] = $gpslocamin;
				}
				if ($gpslocamout !== null) {
					$updateFields[] = "attendance_gps_location_am_out = :gpslocamout";
					$params[':gpslocamout'] = $gpslocamout;
				}
				if ($gpslocpmin !== null) {
					$updateFields[] = "attendance_gps_location_pm_in = :gpslocpmin";
					$params[':gpslocpmin'] = $gpslocpmin;
				}
				if ($gpslocpmout !== null) {
					$updateFields[] = "attendance_gps_location_pm_out = :gpslocpmout";
					$params[':gpslocpmout'] = $gpslocpmout;
				}

				if (!empty($updateFields)) {
					$updateQuery = "UPDATE employee_dtr_sub_tbl SET " . implode(", ", $updateFields) . " 
									WHERE emp_idcode = :id AND yearno = :year AND monthno = :month AND dayno = :day";
					$stmtUpdate = $cnn->prepare($updateQuery);
					$stmtUpdate->execute($params);
				}

				http_response_code(200);
				echo json_encode([
					"status" => "success",
					"message" => "Time attendance has been updated successfully."
				]);
			}

		} catch (PDOException $e) {
			if (isset($cnn) && $cnn->inTransaction()) {
				$cnn->rollBack();
			}
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
	} else {
		http_response_code(401);
		echo json_encode([
			"status" => "Access denied",
			"message" => "API Key is not Valid."
		]);
	}

?>
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
				// $monthName   = date("M", mktime(0, 0, 0, $monthno, 10)); // e.g., "Jul"
				$monthName = date("F", mktime(0, 0, 0, $monthno, 10)); // e.g., "July"

				// 1. Format the month with a leading zero (e.g., 7 -> "07")
				$formattedMonth = str_pad($monthno, 2, "0", STR_PAD_LEFT);
				
				// 2. Generate the dtrcode (e.g., "20260787654321")
				$dtrcode = $yearno . $formattedMonth . $employeeid;

				// Create the monthly DTR header first when it does not yet exist.  The
				// header is the snapshot used by the DTR view for the employee, office,
				// and approving-officer information.
				$employeeQuery = "SELECT agency_code, agency_name, profileid, bio_location, bio_no,
					emp_name, officeid, officecode, officename, officetitle, officeabrv,
					office_gps_location, type_employee_no, type_employee_abrv, headofficer,
					headtitle, auth_head, auth_title, auth_description, shift_status,
					time_editable, priority_dtr, time_editable_value, allowed_ot
					FROM employee_tbl WHERE emp_idcode = :id LIMIT 1";
				$employeeStmt = $cnn->prepare($employeeQuery);
				$employeeStmt->execute([':id' => $employeeid]);
				$employee = $employeeStmt->fetch(PDO::FETCH_ASSOC);

				if ($employee === false) {
					http_response_code(404);
					echo json_encode([
						"status" => "error",
						"message" => "Employee information was not found."
					]);
					exit;
				}

				// Check the monthly DTR before generating any daily rows.  When it
				// already exists, its dtrcode must also be used by the new sub-DTR
				// rows so the DTR view can join the header and the daily logs.
				$monthlyDtrQuery = "SELECT dtrcode FROM employee_dtr_tbl
					WHERE emp_idcode = :id AND yearno = :year AND monthno = :month
					LIMIT 1";
				$monthlyDtrStmt = $cnn->prepare($monthlyDtrQuery);
				$monthlyDtrStmt->execute([
					':id' => $employeeid,
					':year' => $yearno,
					':month' => $monthno,
				]);
				$monthlyDtr = $monthlyDtrStmt->fetch(PDO::FETCH_ASSOC);
				$monthlyDtrExists = $monthlyDtr !== false;

				$cnn->beginTransaction();

				if (!$monthlyDtrExists) {
					$insertMonthlyDtr = "INSERT INTO employee_dtr_tbl
						(agency_code, agency_name, emp_idcode, dtrcode, yearno, monthno, monthname,
						profileid, bio_location, bio_no, emp_name, officeid, officecode, officename,
						officetitle, officeabrv, office_gps_location, type_employee_no,
						type_employee_abrv, headofficer, headtitle, auth_head, auth_title,
						auth_description, shift_status, time_editable, priority_dtr,
						time_editable_value, allowed_ot)
						VALUES
						(:agency_code, :agency_name, :emp_idcode, :dtrcode, :yearno, :monthno, :monthname,
						:profileid, :bio_location, :bio_no, :emp_name, :officeid, :officecode, :officename,
						:officetitle, :officeabrv, :office_gps_location, :type_employee_no,
						:type_employee_abrv, :headofficer, :headtitle, :auth_head, :auth_title,
						:auth_description, :shift_status, :time_editable, :priority_dtr,
						:time_editable_value, :allowed_ot)";
					$insertMonthlyDtrStmt = $cnn->prepare($insertMonthlyDtr);
					$insertMonthlyDtrStmt->execute([
						':agency_code' => $employee['agency_code'],
						':agency_name' => $employee['agency_name'],
						':emp_idcode' => $employeeid,
						':dtrcode' => $dtrcode,
						':yearno' => $yearno,
						':monthno' => $monthno,
						':monthname' => $monthName,
						':profileid' => $employee['profileid'],
						':bio_location' => $employee['bio_location'],
						':bio_no' => $employee['bio_no'],
						':emp_name' => $employee['emp_name'],
						':officeid' => $employee['officeid'],
						':officecode' => $employee['officecode'],
						':officename' => $employee['officename'],
						':officetitle' => $employee['officetitle'],
						':officeabrv' => $employee['officeabrv'],
						':office_gps_location' => $employee['office_gps_location'],
						':type_employee_no' => $employee['type_employee_no'],
						':type_employee_abrv' => $employee['type_employee_abrv'],
						':headofficer' => $employee['headofficer'],
						':headtitle' => $employee['headtitle'],
						':auth_head' => $employee['auth_head'],
						':auth_title' => $employee['auth_title'],
						':auth_description' => $employee['auth_description'],
						':shift_status' => $employee['shift_status'],
						':time_editable' => $employee['time_editable'],
						':priority_dtr' => $employee['priority_dtr'],
						':time_editable_value' => $employee['time_editable_value'],
						':allowed_ot' => $employee['allowed_ot'],
					]);
				} else {
					$dtrcode = $monthlyDtr['dtrcode'];
				}

				// Generate the daily records after the monthly header has been confirmed.
				$insertQuery = "INSERT INTO employee_dtr_sub_tbl
					(agency_code, agency_name, dtrcode, emp_idcode, yearno, monthno, monthname,
					dayno, nameday, emp_name, bio_location, bio_no, allowed_ot, amtimein,
					amtimeout, pmtimein, pmtimeout, attendance_gps_location_am_in,
					attendance_gps_location_am_out, attendance_gps_location_pm_in,
					attendance_gps_location_pm_out)
					VALUES 
					(:agencycode, :agencyname, :dtrcode, :id, :year, :month, :monthname,
					:day, :nameday, :empname, :biolocation, :biono, :allowedot, :amtimein,
					:amtimeout, :pmtimein, :pmtimeout, :gpslocamin, :gpslocamout,
					:gpslocpmin, :gpslocpmout)";
				
				$insertStmt = $cnn->prepare($insertQuery);

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
						":agencycode"   => $employee['agency_code'],
						":agencyname"   => $employee['agency_name'],
						":dtrcode"      => $dtrcode, // Bind the formatted dtrcode here
						":id"           => $employeeid,
						":year"         => $yearno,
						":month"        => $monthno,
						":monthname"    => $monthName,
						":day"          => $d,
						":nameday"      => $nameday,
						":empname"      => $employee['emp_name'],
						":biolocation"  => $employee['bio_location'],
						":biono"        => $employee['bio_no'],
						":allowedot"    => $employee['allowed_ot'],
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

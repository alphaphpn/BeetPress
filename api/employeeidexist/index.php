<?php

	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET");

	if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
		http_response_code(400);
		echo json_encode([
			"status" => "error",
			"message" => "Missing or empty 'id' parameter."
		]);
		exit;
	}

	$employee_id = trim($_GET['id']);

	try {
		require_once '../../lib/env.php';

		$cnn = null;

		$cnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$query = "SELECT * FROM employee_tbl WHERE emp_idcode = :id LIMIT 1";
		$stmt = $cnn->prepare($query);
		
		$stmt->bindParam(":id", $employee_id);
		$stmt->execute();

		// 6. Check if a record was found
		if ($stmt->rowCount() > 0) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			
			http_response_code(200);
			echo json_encode([
				"status" => "success",
				"exists" => true,
				"message" => "Employee ID found.",
				"data" => [
					"id" => $row['emp_idcode'], 
					"fullname" => $row['emp_name_forid'], 
					"imgdata" => trim($pixloc) . "public/employeeID/" . $row['emp_idcode'] . ".jpeg", 
					"deviceid" => $row['device_id'], 
					"devicename" => $row['device_name'], 
					"typeemployeeno" => $row['type_employee_no'], 
					"typeemployeeabrv" => $row['type_employee_abrv'], 
					"typeemployee" => $row['type_employee'], 
					"officelandmark" => $row['office_landmark'], 
					"officelongitude" => $row['office_longitude'], 
					"officelatitude" => $row['office_latitude'], 
					"officemeter" => $row['office_meter'], 
					"worklocation" => $row['work_location']
				]
			], JSON_UNESCAPED_SLASHES);
		} else {
			http_response_code(404); // Not Found
			echo json_encode([
				"status" => "success",
				"exists" => false,
				"message" => "Employee ID does not exist."
			]);
		}

	} catch (PDOException $e) {
		// Handle any SQL errors gracefully without crashing the API
		http_response_code(500); // Internal Server Error
		echo json_encode([
			"status" => "error",
			"message" => "Database query error: " . $e->getMessage()
		]);
	} catch (Exception $e) {
		http_response_code(500);
		echo json_encode([
			"status" => "error",
			"message" => $e->getMessage()
		]);
	}

?>
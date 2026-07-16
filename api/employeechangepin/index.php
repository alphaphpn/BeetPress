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
	} elseif (!isset($_GET['pincode']) || empty(trim($_GET['pincode']))) {
		http_response_code(400);
		echo json_encode([
			"status" => "error",
			"message" => "Missing or empty 'PIN Code' parameter."
		]);
		exit;
	} elseif (!isset($_GET['xesuredtypr']) || empty(trim($_GET['xesuredtypr']))) {
		http_response_code(400);
		echo json_encode([
			"status" => "error",
			"message" => "Missing or empty 'API Key' parameter."
		]);
		exit;
	}

	$employee_id = trim($_GET['id']);
	$employee_pincode = md5(trim($_GET['pincode']));
	$mesecured = trim($_GET['xesuredtypr']);

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
			
			if ( $mesecured == "0a7a004339f450a46fe7b34767c54577") {
				$update_query = "UPDATE employee_tbl SET pinword=:new_pincode WHERE emp_idcode = :id";
				$update_stmt = $cnn->prepare($update_query);
				
				$update_stmt->bindParam(":id", $employee_id);
				$update_stmt->bindParam(":new_pincode", $employee_pincode);

				$update_stmt->execute();

				http_response_code(200);
				echo json_encode([
					"status" => "success",
					"message" => "Pincode has been updated successfully."
				]);
			} else {
				http_response_code(401);
				echo json_encode([
					"status" => "Access denied",
					"message" => "API Key is not Valid."
				]);
			}
		} else {
			http_response_code(404); // Not Found
			echo json_encode([
				"status" => "success",
				"exists" => false,
				"message" => "Employee ID does not exist."
			]);
		}

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
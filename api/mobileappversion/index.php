<?php

	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET");

	if (!isset($_GET['token']) || trim((string) $_GET['token']) === '') {
		http_response_code(400);
		echo json_encode(array('status' => 'error', 'message' => "Missing or empty 'Token' parameter."));
		exit;
	}

	if (trim((string) $_GET['token']) !== '0a7a004339f450a46fe7b34767c54577') {
		http_response_code(401);
		echo json_encode(array('status' => 'Access denied', 'message' => 'API Key is not Valid.'));
		exit;
	}

	try {
		require_once '../../lib/env.php';

		$cnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$version = $cnn->query("SELECT mobile_app_version FROM mobile_app_version_tbl LIMIT 1")->fetchColumn();
		if ($version === false) {
			http_response_code(404);
			echo json_encode(array('status' => 'error', 'message' => 'Mobile app version is not available.'));
			exit;
		}

		// Success response intentionally contains only the mobile_app_version value.
		echo json_encode($version);
	} catch (PDOException $exception) {
		http_response_code(500);
		echo json_encode(array('status' => 'error', 'message' => 'Database error: ' . $exception->getMessage()));
	}

?>

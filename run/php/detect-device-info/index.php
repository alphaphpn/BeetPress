<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>System Info</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<?php

		function get_client_ip() {
			$ipaddress = '';
			if (isset($_SERVER['HTTP_CLIENT_IP']))
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_X_FORWARDED']))
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_FORWARDED']))
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if(isset($_SERVER['REMOTE_ADDR']))
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}

		function getClientIpAddress() {
			$ipAddress = '';

			// Check for shared internet/proxy IP
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ipAddress = $_SERVER['HTTP_CLIENT_IP'];
			}
			// Check for IP address from proxy servers
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			// Standard remote address
			else {
				$ipAddress = $_SERVER['REMOTE_ADDR'];
			}

			return $ipAddress;
		}
	
		$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$urlhttp = $_SERVER['HTTP_HOST'];
		$url = $_SERVER['REQUEST_URI'];
		$url_path = parse_url($url, PHP_URL_PATH);
		$basename = pathinfo($url_path, PATHINFO_BASENAME);
		$unitserialnumb = shell_exec('wmic bios get serialnumber 2>&1');
		$hddserialnumb = shell_exec('wmic DISKDRIVE GET SerialNumber 2>&1');

		echo 'Unit Name: '.gethostname(); // may output e.g,: sandie
		echo '<br>';
		echo 'Hosted: '.$hostname; // Server IP Address
		echo '<br>';
		echo 'Build by: '.php_uname();
		echo '<br><br>';
		echo 'HTTP: '.$urlhttp;
		echo '<br><br>';
		echo 'URL: '.$url;
		echo '<br><br>';
		echo 'URL Base Name: '.$basename;
		echo '<br><br>';
		echo $unitserialnumb;
		echo '<br><br>';
		echo $hddserialnumb;
		echo '<br><br>';
		echo '<hr>';

		https://www.php.net/manual/en/function.gethostbyaddr.php

		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		// $external_ip = exec('curl http://ipecho.net/plain');
		// echo $external_ip;

		echo '<hr><br>';

		// https://www.w3schools.com/php/php_superglobals_server.asp
		$testrun = $_SERVER['REMOTE_ADDR'];
		echo $testrun;

		echo '<hr><br>';

		$clientIp = getClientIpAddress();
		echo "Client IP Address: " . $clientIp;

		echo '<hr><br>';
		echo 'Current script owner: ' . get_current_user(); // get Username of the Operating System
	?>
</body>
</html>
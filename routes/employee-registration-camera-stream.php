<?php

require_once __DIR__ . '/../lib/core.php';

// This endpoint is deliberately limited to private IPv4 camera addresses and
// known MJPEG paths, so authenticated users cannot use it as a general proxy.
if (!isset($_SESSION['d2s8wu_ustat']) || (int) $_SESSION['d2s8wu_ustat'] !== 1 ||
	!isset($_SESSION['d2s8wu_verified']) || (int) $_SESSION['d2s8wu_verified'] !== 1 ||
	!isset($_SESSION['d2s8wu_xdel']) || (int) $_SESSION['d2s8wu_xdel'] !== 0) {
	http_response_code(403);
	exit('Authentication required.');
}

$cameraUrl = trim((string) ($_GET['url'] ?? ''));
$parts = parse_url($cameraUrl);
$host = $parts['host'] ?? '';
$path = $parts['path'] ?? '';
$port = isset($parts['port']) ? (int) $parts['port'] : 80;

function employeeRegistrationPrivateIpv4(string $ip): bool {
	if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) return false;
	$octets = array_map('intval', explode('.', $ip));
	return $octets[0] === 10 ||
		($octets[0] === 172 && $octets[1] >= 16 && $octets[1] <= 31) ||
		($octets[0] === 192 && $octets[1] === 168);
}

if (($parts['scheme'] ?? '') !== 'http' || !employeeRegistrationPrivateIpv4($host) ||
	$port < 1 || $port > 65535 || !in_array($path, ['/stream', '/video'], true)) {
	http_response_code(400);
	exit('Invalid private camera stream URL.');
}

if (!function_exists('curl_init')) {
	http_response_code(500);
	exit('The server requires the PHP cURL extension to proxy the camera stream.');
}

@ini_set('display_errors', '0');
@set_time_limit(0);
@session_write_close();
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('X-Content-Type-Options: nosniff');

$contentTypeSent = false;
$camera = curl_init($cameraUrl);
curl_setopt_array($camera, [
	CURLOPT_FOLLOWLOCATION => false,
	CURLOPT_CONNECTTIMEOUT => 5,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_USERAGENT => 'BeetPress Employee Registration Camera Proxy',
	CURLOPT_HEADERFUNCTION => static function ($handle, string $header) use (&$contentTypeSent): int {
		if (!$contentTypeSent && stripos($header, 'Content-Type:') === 0) {
			$contentType = trim(substr($header, strlen('Content-Type:')));
			if (stripos($contentType, 'image/') === 0 || stripos($contentType, 'multipart/x-mixed-replace') === 0) {
				header('Content-Type: ' . $contentType);
				$contentTypeSent = true;
			}
		}
		return strlen($header);
	},
	CURLOPT_WRITEFUNCTION => static function ($handle, string $data): int {
		echo $data;
		if (ob_get_level() > 0) @ob_flush();
		flush();
		return strlen($data);
	},
]);

$ok = curl_exec($camera);
if ($ok === false && !headers_sent()) {
	http_response_code(502);
	echo 'Unable to reach the private camera.';
}
curl_close($camera);


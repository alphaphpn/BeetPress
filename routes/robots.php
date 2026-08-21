<?php
	$host = preg_replace('/[^A-Za-z0-9.:-]/', '', (string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
	$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') ? 'https' : 'http';
	$base = dirname(str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php')));
	$base = ($base === '/' || $base === '.' || $base === '\\') ? '' : rtrim($base, '/');
	header('Content-Type: text/plain; charset=UTF-8');
echo "User-agent: *\nAllow: /\nDisallow: /bp-mngr\nDisallow: /dcalendar\nDisallow: /appointment-schedule\nDisallow: /meeting-schedule\nDisallow: /event-schedule\nDisallow: /holidays\nDisallow: /login\nDisallow: /signin\nDisallow: /register\nDisallow: /signup\nSitemap: {$scheme}://{$host}{$base}/sitemap.xml\n";
exit;
?>

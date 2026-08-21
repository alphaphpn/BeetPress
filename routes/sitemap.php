<?php
	$host = preg_replace('/[^A-Za-z0-9.:-]/', '', (string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
	$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') ? 'https' : 'http';
	$base = dirname(str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php')));
	$base = ($base === '/' || $base === '.' || $base === '\\') ? '' : rtrim($base, '/');
	$url = htmlspecialchars("{$scheme}://{$host}{$base}/", ENT_XML1 | ENT_QUOTES, 'UTF-8');
	header('Content-Type: application/xml; charset=UTF-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n  <url><loc>{$url}</loc></url>\n</urlset>\n";
exit;
?>

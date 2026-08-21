<?php
	$seoHost = preg_replace('/[^A-Za-z0-9.:-]/', '', (string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
	$seoScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') ? 'https' : 'http';
	$seoBasePath = trim((string) ($domainhome ?? ''), '/');
	$seoBaseUrl = $seoScheme . '://' . $seoHost . ($seoBasePath !== '' ? '/' . $seoBasePath : '');
	$seoRequestPath = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
	$seoCanonical = $seoScheme . '://' . $seoHost . $seoRequestPath;
	$seoIsHomepage = !empty($the_homepage);
	$seoTitle = trim((string) ($the_htitle ?? 'Province of Zamboanga Sibugay | Official Website'));
	$seoDescription = trim((string) ($the_meta_description ?? 'Official website of the Provincial Government of Zamboanga Sibugay, Philippines.'));
	$seoLogo = $seoBaseUrl . '/assets/media/Logo-eSibugayPH.png';
?>
<!DOCTYPE html>
<html lang="en-PH">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php echo htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8'); ?>">
	<meta name="author" content="Provincial Government of Zamboanga Sibugay">
	<meta name="generator" content="BeetPress">
	<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
	<link rel="canonical" href="<?php echo htmlspecialchars($seoCanonical, ENT_QUOTES, 'UTF-8'); ?>">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="en_PH">
	<meta property="og:site_name" content="Province of Zamboanga Sibugay">
	<meta property="og:title" content="<?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?>">
	<meta property="og:description" content="<?php echo htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8'); ?>">
	<meta property="og:url" content="<?php echo htmlspecialchars($seoCanonical, ENT_QUOTES, 'UTF-8'); ?>">
	<meta property="og:image" content="<?php echo htmlspecialchars($seoLogo, ENT_QUOTES, 'UTF-8'); ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?>">
	<meta name="twitter:description" content="<?php echo htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8'); ?>">
	<meta name="twitter:image" content="<?php echo htmlspecialchars($seoLogo, ENT_QUOTES, 'UTF-8'); ?>">
	<title><?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?></title>
	<?php if ($seoIsHomepage): ?><script type="application/ld+json"><?php echo json_encode(array('@context' => 'https://schema.org', '@type' => 'GovernmentOrganization', 'name' => 'Provincial Government of Zamboanga Sibugay', 'alternateName' => 'eSibugay PH', 'url' => $seoCanonical, 'logo' => $seoLogo, 'email' => 'info@sibugay.gov.ph', 'telephone' => '+63 915 482 6025', 'address' => array('@type' => 'PostalAddress', 'addressRegion' => 'Zamboanga Sibugay', 'addressCountry' => 'PH')), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script><?php endif; ?>
	<link rel="icon" type="image/png" href="<?php echo trim($domainhome).'/assets/media/favicon.png'; ?>">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<!-- <link href="https://fonts.googleapis.com/css2?family=UnifrakturMaguntia&display=swap" rel="stylesheet"> -->
	<!-- <link href="https://fonts.googleapis.com/css2?family=Marck+Script&family=UnifrakturMaguntia&display=swap" rel="stylesheet"> -->
	<link href="<?php echo trim($domainhome); ?>/assets/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/npm/slick-carousel@1.8.1/slick/slick.css">
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/fontawesome/releases/v5.7.0/css/all.css">
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/datatables/1.13.6/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/css/style.css">
	<style>
		.primary-bg-color { background-color: #ffeae6; }
		.primary-bg-color-light { background-color: rgb(255 255 255 / 70%); }
		.secondary-bg-color { background-color: #ff8266; }
		.third-bg-color { background-color: #ffa28d; }
		.second-last-bg-color { background-color: #3c302a; }
		.last-bg-color { background-color: #2d241f; }
		.txt-color-primary { color: #ff8266; }
		.btn.third-bg-color:hover { background-color: #ff8266; }
	</style>
	<script src="<?php echo trim($domainhome); ?>/assets/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/datatables/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/datatables/1.13.6/js/dataTables.bootstrap5.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/ajax/libs/particle-animate/1.0.0/js/particle-animate.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/js/functions.js"></script>
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">
	

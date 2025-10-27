<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="<?php echo trim(isset($the_expires) ? $the_expires : null); ?>">
	<meta http-equiv="refresh" content="<?php echo trim(isset($the_refresh) ? $the_refresh : null); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="google-signin-scope" content="admin dashboard">
	<meta name="google-signin-client_id" content="<?php echo $gauthlogin; ?>">
	<title><?php echo trim(isset($the_htitle) ? $the_htitle : 'eSibugayPH'); ?></title>
	<link rel="icon" type="image/png" href="<?php echo trim($domainhome).'/assets/media/favicon.png'; ?>">
	
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/fontawesome/releases/v5.7.0/css/all.css">
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/ajax/libs/octicons/3.5.0/octicons.min.css">
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/css/startup-bootstrap.css">
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/css/dashboard.css">
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/css/dashboard-style.css">
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/css/style.css">
	<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/datatables/1.13.6/css/dataTables.bootstrap5.min.css">

	<style>
		.primary-bg-color { background-color: #ffeae6; }
		.primary-bg-color-light { background-color: rgb(255 255 255 / 70%); }
		.secondary-bg-color { background-color: #ff8266; }
		.third-bg-color { background-color: #ef7053; }
		.fot-bg-locor { background-color: #f3ceb9 }
		.second-last-bg-color { background-color: #3c302a; }
		.last-bg-color { background-color: #2d241f; }
		.txt-color-primary { color: #ff8266; }
		.btn.third-bg-color:hover { background-color: #ff8266; }
	</style>
	<script src="<?php echo trim($domainhome); ?>/assets/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/fontawesome/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/ajax/libs/particle-animate/1.0.0/js/particle-animate.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/datatables/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/datatables/1.13.6/js/dataTables.bootstrap5.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/charts/loader.js"></script>
</head>

<body class="sb-nav-fixed">
	
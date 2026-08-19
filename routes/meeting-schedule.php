<?php
	include_once "lib/core.php";
	include_once "lib/env.php";
	include_once "lib/function.php";

	if (empty($_SESSION['d2s8wu_ustat']) || empty($_SESSION['d2s8wu_verified']) || !isset($_SESSION['d2s8wu_xdel']) || empty($_SESSION['d2s8wu_ulevel']) || $_SESSION['d2s8wu_ustat'] != 1 || $_SESSION['d2s8wu_verified'] != 1 || $_SESSION['d2s8wu_xdel'] != 0 || !in_array((int) $_SESSION['d2s8wu_ulevel'], array(1, 2, 15, 16, 17), true)) {
		header("location:" . $domainhome);
		exit;
	}

	$the_htitle = "DashPanel: Meeting Schedule";
	$the_refresh = null;
	$the_expires = null;
	$page_title = "Meeting Schedule";
	$breadcrumb = "Meetings for your office";
	include_once "app/theme/{$theme}/dpanel/header.php";
	include_once "app/theme/{$theme}/dpanel/navbar.php";
	include_once "app/theme/{$theme}/dpanel/sidebar.php";
	include_once "app/views/meeting-schedule/index.php";
	include_once "app/theme/{$theme}/dpanel/footer-datatables.php";
?>

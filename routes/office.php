<?php

	include_once "lib/core.php";
	include_once "lib/env.php";
	include_once "lib/function.php";

	if ( !isset($_SESSION["d2s8wu_ustat"]) && !isset($_SESSION["d2s8wu_verified"]) && !isset($_SESSION['d2s8wu_xdel']) && !isset($_SESSION['d2s8wu_ulevel']) ) {
		header("location:".$domainhome);
	} else {
		if ( $_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 1 ||
			$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 2 ||
			$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 15 ||
			$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 16 ||
			$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 17 ) {

			$the_htitle = "DashPanel: Office";
			$the_refresh = null;
			$the_expires = null;
			$page_title = "DashPanel";
			$breadcrumb = "Electronic Provincial Local Government Unit System";
			include_once "app/theme/{$theme}/dpanel/header.php";
			include_once "app/theme/{$theme}/dpanel/navbar.php";
			include_once "app/theme/{$theme}/dpanel/sidebar.php";
			include_once "app/views/office/index.php";
			include_once "app/theme/{$theme}/dpanel/footer-datatables.php";

		} else {
			header("location:".$domainhome);
		}
	}

?>

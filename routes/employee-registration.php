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

			$the_htitle = "DashPanel: Employee Registration";
			$the_refresh = null;
			$the_expires = null;
			$page_title = "Employee Registration";
			$breadcrumb = "Employee Registration";
			include_once "app/theme/{$theme}/dpanel/header.php";
			include_once "app/theme/{$theme}/dpanel/navbar.php";
			include_once "app/theme/{$theme}/dpanel/sidebar.php";
			include_once "app/views/employee-registration/index.php";
			include_once "app/theme/{$theme}/dpanel/footer-datatables.php";

			/** For Web Refresh
			 * null seconds for NO REFRESH
			 * 60 seconds for 1 minute
			 * 300 seconds for 5 minutes
			 * 600 seconds for 10 minutes
			 * 1800 seconds for 30 minutes
			 * 3600 seconds for 1 hour
			**/

		} else {
			header("location:".$domainhome);
		}
	}

?>
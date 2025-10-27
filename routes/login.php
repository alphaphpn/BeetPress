<?php

	include_once "lib/core.php";
	include_once "lib/env.php";
	include_once "lib/function.php";
	$the_htitle = "eSibugay PH: Sign In";
	$the_refresh = null;
	$the_expires = null;

	if ( isset($_SESSION["d2s8wu_ustat"]) && isset($_SESSION["d2s8wu_verified"]) && isset($_SESSION["d2s8wu_xdel"]) ) {
		if ( $_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 ) {
			header("location:".$domainhome);
		}
	}

	include_once "app/theme/{$theme}/template-part/header.php";
	include_once "app/theme/{$theme}/template-part/navbar.php";
	include_once "app/views/login/index.php";
	include_once "app/theme/{$theme}/template-part/footer.php";

?>
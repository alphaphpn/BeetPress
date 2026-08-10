<?php

	include_once "lib/core.php";
	include_once "lib/env.php";
	include_once "lib/function.php";
	$the_htitle = "Downloads";
	$the_refresh = null;
	$the_expires = null;
	include_once "app/theme/{$theme}/template-part/header.php";
	include_once "app/theme/{$theme}/template-part/navbar.php";
	include_once "app/views/downloads/index.php";
	include_once "app/theme/{$theme}/template-part/footer.php";

	/** For Web Refresh
	 * null seconds for NO REFRESH
	 * 60 seconds for 1 minute
	 * 300 seconds for 5 minutes
	 * 600 seconds for 10 minutes
	 * 1800 seconds for 30 minutes
	 * 3600 seconds for 1 hour
	**/

?>
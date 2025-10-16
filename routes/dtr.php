<?php

	include_once "lib/core.php";
	include_once "lib/env.php";
	include_once "lib/function.php";
	$the_htitle = "Dailty Time Record";
	$the_refresh = 180;
	$the_expires = null;
	include_once "app/theme/{$theme}/template-part/header.php";
	include_once "app/theme/{$theme}/template-part/navbar.php";
	include_once "app/views/dtr/index.php";
	include_once "app/theme/{$theme}/template-part/footer.php";

?>
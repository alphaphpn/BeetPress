<?php

	include_once "lib/core.php";
	include_once "lib/env.php";
	include_once "lib/function.php";
	$the_htitle = "Gender and Development";
	$the_refresh = null;
	$the_expires = null;
	include_once "app/theme/{$theme}/template-part/header.php";
	include_once "app/theme/{$theme}/template-part/navbar.php";
	include_once "app/views/gad/index.php";
	include_once "app/theme/{$theme}/template-part/footer.php";

?>
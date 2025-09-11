<?php

	include_once "lib/core.php";
	include_once "lib/env.php";
	include_once "lib/function.php";
	$the_htitle = "Employee Attendance";
	$the_refresh = 20;
	include_once "app/theme/{$theme}/template-part/header.php";
	include_once "app/theme/{$theme}/template-part/navbar.php";
	include_once "app/views/attendance/index.php";
	include_once "app/theme/{$theme}/template-part/footer.php";

?>
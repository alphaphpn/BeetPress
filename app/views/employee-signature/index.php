<?php 

	require_once "lib/session-attendance.php";
	$empidcodeNow = isset($_SESSION["empidcode"]) ? $_SESSION["empidcode"] : null;

	require_once "model/employee/setcurrentemployee.php";

	if ( $shiftstatuscc == 1 ) {
		require_once "model/attendance/forautodtr.php";
	}

	$allowedotjk = isset($allowedotcc) ? $allowedotcc : null;

	require_once "model/employee_dtr_sub/getonedaytimedtr.php";

?>
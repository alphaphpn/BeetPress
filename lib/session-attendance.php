<?php 

	if ( empty($_SESSION["empidcode"]) || empty($_SESSION["biono"]) || empty($_SESSION["empname"]) || empty($_SESSION["employeeactivated"]) ) {
		echo '<script>alert("Access denied! Only Employee is allowed.");window.open("attendance-auth","_self");</script>';
		exit;
	} elseif ( empty($_SESSION["officeid"]) || empty($_SESSION["officecode"]) || empty($_SESSION["officename"]) || empty($_SESSION["officetitle"]) || empty($_SESSION["officeabrv"]) || empty($_SESSION["headofficer"]) || empty($_SESSION["headtitle"]) ) {
		echo '<script>alert("Kindly update your Office and Signatory of your DTR Information");</script>';
	}
	
?>
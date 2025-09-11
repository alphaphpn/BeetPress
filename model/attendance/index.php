<?php 

	if ( empty($_SESSION["empidcode"]) || empty($_SESSION["biono"]) || empty($_SESSION["empname"]) || empty($_SESSION["employeeactivated"]) ) {
		echo '<script>window.open("attendance-auth","_self");</script>';
		exit;
	} else {
		try {
			if ( isset($_POST["emp-time-am-in"]) || isset($_POST["emp-time-am-out"]) || isset($_POST["emp-time-pm-in"]) || isset($_POST["emp-time-pm-out"]) ) {
				include_once "chkdtrexist.php";
			}
		} catch (PDOException $error) {
			$err_msg = $error->getMessage();
			echo "<p>Error: {$err_msg}</p>";
			die;
		}
	}
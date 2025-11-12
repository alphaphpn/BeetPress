<?php

	$fullname_mi = null;

	try {
		if ( isset($_GET["fullname-mi"]) ) {
			$fullname_mi = isset($_GET["fullname-mi"]) ? $_GET["fullname-mi"] : null;

			if ( empty(trim($fullname_mi)) ) {
				echo "<div class='text-danger'>Please entering Employee ID Number.</div>";
			} else {
				require_once "index.php";
				$emplyAcctx = new employeeAcct();

				if ( $emplyAcctx->Search_employeeAcct_EmployeeName($fullname_mi) ) {
					echo "<div class='text-danger'><i class='fas fa-ban'></i> Invalid or Fullname already exist.</div>";
				} else {
					echo "<div class='text-primary'><i class='fas fa-sync'></i> Fullname available</div>";
				}
			}
		}
	} catch (Exception $e) {
		echo "Error. ".$e;
	}
<?php

	$bioempidnmbr = null;

	try {
		if ( isset($_GET["bioempidnmbr"]) ) {
			$bioempidnmbr = isset($_GET["bioempidnmbr"]) ? $_GET["bioempidnmbr"] : null;

			if ( empty(trim($bioempidnmbr)) ) {
				echo "<div class='text-danger'>Please entering Biometric Number.</div>";
			} else {
				require_once "index.php";
				$emplyAcctx = new employeeAcct();

				if ( $emplyAcctx->Search_employeeAcct_BioNmr($bioempidnmbr) ) {
					echo "<div class='text-danger'><i class='fas fa-ban'></i> Biometric Number NOT Available</div>";
				} else {
					echo "<div class='text-primary'><i class='fas fa-sync'></i> Biometric Number available</div>";
				}
			}
		}
	} catch (Exception $e) {
		echo "Error. ".$e;
	}
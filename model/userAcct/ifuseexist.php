<?php

	$use_nameuser = null;

	try {
		if ( isset($_GET["nameuser"]) ) {
			$use_nameuser = isset($_GET["nameuser"]) ? $_GET["nameuser"] : null;

			if ( empty(trim($use_nameuser)) ) {
				echo "<div class='text-danger'>Please entering username.</div>";
			} else {
				require_once "index.php";
				$authAcctx = new authAcct();

				if ( $authAcctx->Search_userAcct_username($use_nameuser) ) {
					echo "<div class='text-danger'><i class='fas fa-ban'></i> Username NOT Available</div>";
				} else {
					echo "<div class='text-primary'><i class='fas fa-sync'></i> Username available</div>";
				}
			}
		}
	} catch (Exception $e) {
		echo "Error. ".$e;
	}
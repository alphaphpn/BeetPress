<?php

	include_once "lib/core.php";
	include_once "lib/env.php";
	include_once "lib/function.php";

	// Auto-migrate once per session — skipped on subsequent page loads
	if (empty($_SESSION['_et_migrated'])) {
		try {
			$_cnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
			$_cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$_tracker_cols = [
				'employee_role'    => "INT(1) NOT NULL DEFAULT 0",
				'office_landmark'  => "TEXT",
				'office_longitude' => "DECIMAL(11,8) DEFAULT NULL",
				'office_latitude'  => "DECIMAL(10,8) DEFAULT NULL",
				'office_meter'     => "DECIMAL(10,2) DEFAULT NULL",
				'online_status'    => "INT(1) NOT NULL DEFAULT 0",
				'device_id'        => "VARCHAR(100) DEFAULT NULL",
				'device_name'      => "VARCHAR(150) DEFAULT NULL",
			];
			foreach ($_tracker_cols as $_col => $_def) {
				$_chk = $_cnn->prepare("SHOW COLUMNS FROM employee_tbl LIKE :col");
				$_chk->execute([':col' => $_col]);
				if ($_chk->rowCount() == 0) {
					$_cnn->exec("ALTER TABLE employee_tbl ADD COLUMN $_col $_def");
				}
			}
			// Add meter column to office_signatory_tbl if missing
			$_chk2 = $_cnn->prepare("SHOW COLUMNS FROM office_signatory_tbl LIKE 'meter'");
			$_chk2->execute();
			if ($_chk2->rowCount() == 0) {
				$_cnn->exec("ALTER TABLE office_signatory_tbl ADD COLUMN meter DECIMAL(10,2) DEFAULT NULL");
			}
			$_cnn = null;
			$_SESSION['_et_migrated'] = 1;
		} catch (PDOException $_e) { /* silently skip if table not ready */ }
	}

	if ( !isset($_SESSION["d2s8wu_ustat"]) && !isset($_SESSION["d2s8wu_verified"]) && !isset($_SESSION['d2s8wu_xdel']) && !isset($_SESSION['d2s8wu_ulevel']) ) {
		header("location:".$domainhome);
	} else {
		if ( $_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 1 ||
			$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 2 ||
			$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 15 ||
			$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 16 ||
			$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 17 ) {

			$the_htitle = "DashPanel: Employee Tracker";
			$the_refresh = null;
			$the_expires = null;
			$page_title = "DashPanel";
			$breadcrumb = "Electronic Provincial Local Government Unit System";
			include_once "app/theme/{$theme}/dpanel/header.php";
			include_once "app/theme/{$theme}/dpanel/navbar.php";
			include_once "app/theme/{$theme}/dpanel/sidebar.php";
			include_once "app/views/employee-tracker/index.php";
			include_once "app/theme/{$theme}/dpanel/footer-datatables.php";

		} else {
			header("location:".$domainhome);
		}
	}

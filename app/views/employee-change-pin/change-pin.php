<?php

	try { 
		if ( isset($_POST["btnChangePIN"]) ) {
			$emploid = isset($_SESSION["empidcode"]) ? $_SESSION["empidcode"] : null;
			$pin = isset($_POST["retypepinInput"]) ? $_POST["retypepinInput"] : null;

			if ( empty(trim($emploid)) || $emploid == null ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Invalid Employee!';
				echo '</div>';
			} elseif ( empty(trim($pin)) || $pin == null ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Invalid PIN!';
				echo '</div>';
			} else {
				include_once "model/employee/index.php";
				$emplyAcctx = new employeeAcct();

				$emplyAcctx->update_employeePIN($emploid,$pin);
			}
		}
	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error @ Change PIN: {$err_msg}</p>";
		die;
	}

?>
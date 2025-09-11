<?php 

	if ( empty($_SESSION["empidcode"]) || empty($_SESSION["biono"]) || empty($_SESSION["empname"]) || empty($_SESSION["employeeactivated"]) ) {
		echo '<script>window.open("attendance-auth","_self");</script>';
		exit;
	} else {
		if ( empty($yrdtr) || empty($monthdtr) || empty($monthName) || empty($stringMonthNo) || empty($dtrCodex) ) {
			echo '<script>window.open("'.trim($url_path).'","_self");</script>';
		} else {

			$daynumberdtr = trim(number_format(date("d")));

			if ( isset($_POST["emp-time-am-in"])  ) {
				$rsltaminx = trim(number_format(date("h"))).trim(":").trim(date("i"));

				$updateSubDTR_am_in = "UPDATE employee_dtr_sub_tbl SET 
					amtimein=:rsltaminx 
					WHERE 
					dtrcode=:dtrcodex AND 
					dayno=:daynumberdtr
				";
				$stmtSubDTR_am_in = $cnn->prepare($updateSubDTR_am_in);
				$stmtSubDTR_am_in->bindParam(':dtrcodex', $dtrCodex);
				$stmtSubDTR_am_in->bindParam(':daynumberdtr', $daynumberdtr);
				$stmtSubDTR_am_in->bindParam(':rsltaminx', $rsltaminx);
				$stmtSubDTR_am_in->execute();

				echo '<script>window.open("'.trim($url_path).'","_self");</script>';
			} elseif ( isset($_POST["emp-time-am-out"]) ) {
				$rsltamoutx = trim(number_format(date("h"))).trim(":").trim(date("i"));

				$updateSubDTR_am_out = "UPDATE employee_dtr_sub_tbl SET 
					amtimeout=:rsltamoutx 
					WHERE 
					dtrcode=:dtrcodex AND 
					dayno=:daynumberdtr
				";
				$stmtSubDTR_am_out = $cnn->prepare($updateSubDTR_am_out);
				$stmtSubDTR_am_out->bindParam(':dtrcodex', $dtrCodex);
				$stmtSubDTR_am_out->bindParam(':daynumberdtr', $daynumberdtr);
				$stmtSubDTR_am_out->bindParam(':rsltamoutx', $rsltamoutx);
				$stmtSubDTR_am_out->execute();

				echo '<script>window.open("'.trim($url_path).'","_self");</script>';
			} elseif ( isset($_POST["emp-time-pm-in"]) ) {
				$rsltpminx = trim(number_format(date("h"))).trim(":").trim(date("i"));

				$updateSubDTR_pm_in = "UPDATE employee_dtr_sub_tbl SET 
					pmtimein=:rsltpminx 
					WHERE 
					dtrcode=:dtrcodex AND 
					dayno=:daynumberdtr
				";
				$stmtSubDTR_pm_in = $cnn->prepare($updateSubDTR_pm_in);
				$stmtSubDTR_pm_in->bindParam(':dtrcodex', $dtrCodex);
				$stmtSubDTR_pm_in->bindParam(':daynumberdtr', $daynumberdtr);
				$stmtSubDTR_pm_in->bindParam(':rsltpminx', $rsltpminx);
				$stmtSubDTR_pm_in->execute();
				echo '<script>window.open("'.trim($url_path).'","_self");</script>';
			} elseif ( isset($_POST["emp-time-pm-out"]) ) {
				$rsltpmoutx = trim(number_format(date("h"))).trim(":").trim(date("i"));

				$updateSubDTR_pm_out = "UPDATE employee_dtr_sub_tbl SET 
					pmtimeout=:rsltpmoutx 
					WHERE 
					dtrcode=:dtrcodex AND 
					dayno=:daynumberdtr
				";
				$stmtSubDTR_pm_out = $cnn->prepare($updateSubDTR_pm_out);
				$stmtSubDTR_pm_out->bindParam(':dtrcodex', $dtrCodex);
				$stmtSubDTR_pm_out->bindParam(':daynumberdtr', $daynumberdtr);
				$stmtSubDTR_pm_out->bindParam(':rsltpmoutx', $rsltpmoutx);
				$stmtSubDTR_pm_out->execute();
				echo '<script>window.open("'.trim($url_path).'","_self");</script>';
			}
		}
		
	}

?>
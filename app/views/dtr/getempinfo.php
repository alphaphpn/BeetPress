<?php

	if ( empty($_SESSION["empidcode"]) || empty($_SESSION["biono"]) || empty($_SESSION["empname"]) || empty($_SESSION["employeeactivated"]) ) {
		echo '<script>window.open("attendance-auth","_self");</script>';
		exit;
	} else {
		try {
			require_once "lib/env.php";

			$cnn = null;
			$empidcode = null;

			$cnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
			$cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Check if Employee is Set
			$empidcode = trim($_SESSION["empidcode"]);

			// Check if DTR Exist
			$yrdtr = trim(date("Y"));
			$monthdtr = trim(date("m"));
			$monthName = trim(date("F"));
			$stringMonthNo = substr(str_repeat(0, 2).$monthdtr, - 2);
			$dtrCodex = trim($yrdtr).trim($stringMonthNo).trim($empidcode);

			$qryDTREmployee = "SELECT * FROM employee_dtr_tbl WHERE emp_idcode=:empidcode AND yearno=:yrdtr AND monthno=:monthdtr";
			$stmntDTREmployee = $cnn->prepare($qryDTREmployee);
			
			$stmntDTREmployee->bindParam(':empidcode', $empidcode);
			$stmntDTREmployee->bindParam(':yrdtr', $yrdtr);
			$stmntDTREmployee->bindParam(':monthdtr', $monthdtr);
			$stmntDTREmployee->execute();
			$countDTREmployee = $stmntDTREmployee->rowCount();

			if ( $countDTREmployee > 0 ) {
				foreach ($stmntDTREmployee as $rowDTREmployee) {
					$empidcodetu = $rowDTREmployee['emp_idcode'];
					$yearnotu = $rowDTREmployee['yearno'];
					$monthnotu = $rowDTREmployee['monthno'];
					$dtrcodetu = $rowDTREmployee['dtrcode'];
					$monthnametu = $rowDTREmployee['monthname'];
					$profileidtu = $rowDTREmployee['profileid'];
					$biolocationtu = isset($rowDTREmployee['bio_location']) ? $rowDTREmployee['bio_location'] : null;
					$bionotu = $rowDTREmployee['bio_no'];
					$empnametu = $rowDTREmployee['emp_name'];
					$officeidtu = $rowDTREmployee['officeid'];
					$officecodetu = $rowDTREmployee['officecode'];
					$officenametu = $rowDTREmployee['officename'];
					$officetitletu = $rowDTREmployee['officetitle'];
					$officeabrvtu = $rowDTREmployee['officeabrv'];
					$officegpslocationtu = $rowDTREmployee['office_gps_location'];
					$typeemployeenotu = $rowDTREmployee['type_employee_no'];
					$typeemployeeabrvtu = $rowDTREmployee['type_employee_abrv'];
					$headofficertu = $rowDTREmployee['headofficer'];
					$headtitletu = $rowDTREmployee['headtitle'];
					$authheadtu = $rowDTREmployee['auth_head'];
					$authtitletu = $rowDTREmployee['auth_title'];
					$authdescriptiontu = $rowDTREmployee['auth_description'];

					$utlatehrtu = isset($rowDTREmployee['utlate_hr']) ? $rowDTREmployee['utlate_hr'] : null;
					$utlatemintu = isset($rowDTREmployee['utlate_min']) ? $rowDTREmployee['utlate_min'] : null;
					$othrtu = isset($rowDTREmployee['ot_hr']) ? $rowDTREmployee['ot_hr'] : null;
					$otmintu = isset($rowDTREmployee['ot_min']) ? $rowDTREmployee['ot_min'] : null;

					$shiftstatustu = $rowDTREmployee['shift_status'];
					$timeeditabletu = $rowDTREmployee['time_editable'];
					$prioritydtrtu = $rowDTREmployee['priority_dtr'];
					$timeeditablevaluetu = $rowDTREmployee['time_editable_value'];
					$allowedottu = $rowDTREmployee['allowed_ot'];
				}
			} else {
				echo '<script>window.open("attendance-auth","_self");</script>';
				exit;
			}
		} catch (PDOException $error) {
			$err_msg = $error->getMessage();
			echo "<p>Error: {$err_msg}</p>";
			die;
		}
	}

?>
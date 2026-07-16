<?php 

	if ( empty($_SESSION["empidcode"]) || empty($_SESSION["biono"]) || empty($_SESSION["empname"]) || empty($_SESSION["employeeactivated"]) ) {
		echo '<script>window.open("attendance-auth","_self");</script>';
		exit;
	} else {
		try {
			require_once "lib/env.php";

			$cnn = null;

			$cnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
			$cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$empidcodeCurrent = trim($_SESSION["empidcode"]);

			// Get Employee Information
			$qryEmployeeInfo = "SELECT * FROM employee_tbl WHERE emp_idcode=:empidcodeCurrent LIMIT 1";
			$stmntEmployeeInfo = $cnn->prepare($qryEmployeeInfo);
			
			$stmntEmployeeInfo->bindParam(':empidcodeCurrent', $empidcodeCurrent);
			$stmntEmployeeInfo->execute();
			$countEmployeeInfo = $stmntEmployeeInfo->rowCount();

			if ($countEmployeeInfo > 0) {
				foreach ($stmntEmployeeInfo as $rowEmployeeInfo) {
					$agencycode = $rowEmployeeInfo['agency_code'];
					$agencyname = $rowEmployeeInfo['agency_name'];
					$profileidz = $rowEmployeeInfo['profileid'];
					$biolocationz = $rowEmployeeInfo['bio_location'];
					$bionoz = $rowEmployeeInfo['bio_no'];
					$empidcodett = $rowEmployeeInfo['emp_idcode'];
					$empnamez = $rowEmployeeInfo['emp_name'];
					$officeidz = $rowEmployeeInfo['officeid'];
					$officecodez = $rowEmployeeInfo['officecode'];
					$officenamez = $rowEmployeeInfo['officename'];
					$officetitlez = $rowEmployeeInfo['officetitle'];
					$officeabrvz = $rowEmployeeInfo['officeabrv'];
					$officegpslocationz = $rowEmployeeInfo['office_gps_location'];
					$typeemployeeabrvz = $rowEmployeeInfo['type_employee_abrv'];
					$typeemployeenoz = $rowEmployeeInfo['type_employee_no'];
					
					$headofficerz = $rowEmployeeInfo['headofficer'];
					$headtitlez = $rowEmployeeInfo['headtitle'];
					$authheadz = $rowEmployeeInfo['auth_head'];
					$authtitlez = $rowEmployeeInfo['auth_title'];
					$authdescriptionz = $rowEmployeeInfo['auth_description'];

					$shiftstatusz = $rowEmployeeInfo['shift_status'];
					$timeeditablez = $rowEmployeeInfo['time_editable'];
					$prioritydtrz = $rowEmployeeInfo['priority_dtr'];
					$timeeditablevaluez = $rowEmployeeInfo['time_editable_value'];

					$allowedotz = $rowEmployeeInfo['allowed_ot'];

					// Check if DTR Exist
					$yrdtr = trim(date("Y"));
					$monthdtr = trim(date("m"));
					$monthName = trim(date("F"));
					$stringMonthNo = substr(str_repeat(0, 2).$monthdtr, - 2);
					$dtrCodex = trim($yrdtr).trim($stringMonthNo).trim($empidcodett);

					$qryDTREmployee = "SELECT * FROM employee_dtr_tbl WHERE emp_idcode=:empidcode AND yearno=:yrdtr AND monthno=:monthdtr";
					$stmntDTREmployee = $cnn->prepare($qryDTREmployee);
					
					$stmntDTREmployee->bindParam(':empidcode', $empidcodett);
					$stmntDTREmployee->bindParam(':yrdtr', $yrdtr);
					$stmntDTREmployee->bindParam(':monthdtr', $monthdtr);
					$stmntDTREmployee->execute();
					$countDTREmployee = $stmntDTREmployee->rowCount();

					if ($countDTREmployee == 0) {
						// insert or add new DTR for Seleceted Employee
						$insertNewDTREmployee = "INSERT INTO employee_dtr_tbl (emp_idcode,yearno,monthno,dtrcode,monthname,profileid,bio_location,bio_no,emp_name,officeid,officecode,officename,officetitle,officeabrv,office_gps_location,type_employee_abrv,headofficer,headtitle,auth_head,auth_title,auth_description,shift_status,time_editable,priority_dtr,time_editable_value,allowed_ot,type_employee_no) VALUES (:empidcode,:yrdtr,:monthdtr,:dtrcodex,:monthname,:profileidz,:biolocationz,:bionoz,:empnamez,:officeidz,:officecodez,:officenamez,:officetitlez,:officeabrvz,:officegpslocationz,:typeemployeeabrvz,:headofficerz,:headtitlez,:authheadz,:authtitlez,:authdescriptionz,:shiftstatusz,:timeeditablez,:prioritydtrz,:timeeditablevaluez,:allowedotzz,:typeemployeenoz)
						";
						$stmntNewDTREmployee = $cnn->prepare($insertNewDTREmployee);
						$stmntNewDTREmployee->bindParam(':empidcode', $empidcodett);
						$stmntNewDTREmployee->bindParam(':yrdtr', $yrdtr);
						$stmntNewDTREmployee->bindParam(':monthdtr', $monthdtr);
						$stmntNewDTREmployee->bindParam(':monthname', $monthName);
						$stmntNewDTREmployee->bindParam(':dtrcodex', $dtrCodex);

						$stmntNewDTREmployee->bindParam(':profileidz', $profileidz);
						$stmntNewDTREmployee->bindParam(':biolocationz', $biolocationz);
						$stmntNewDTREmployee->bindParam(':bionoz', $bionoz);
						$stmntNewDTREmployee->bindParam(':empnamez', $empnamez);
						$stmntNewDTREmployee->bindParam(':officeidz', $officeidz);
						$stmntNewDTREmployee->bindParam(':officecodez', $officecodez);
						$stmntNewDTREmployee->bindParam(':officenamez', $officenamez);
						$stmntNewDTREmployee->bindParam(':officetitlez', $officetitlez);
						$stmntNewDTREmployee->bindParam(':officeabrvz', $officeabrvz);
						$stmntNewDTREmployee->bindParam(':officegpslocationz', $officegpslocationz);
						$stmntNewDTREmployee->bindParam(':typeemployeeabrvz', $typeemployeeabrvz);
						$stmntNewDTREmployee->bindParam(':typeemployeenoz', $typeemployeenoz);
						
						$stmntNewDTREmployee->bindParam(':headofficerz', $headofficerz);
						$stmntNewDTREmployee->bindParam(':headtitlez', $headtitlez);
						$stmntNewDTREmployee->bindParam(':authheadz', $authheadz);
						$stmntNewDTREmployee->bindParam(':authtitlez', $authtitlez);
						$stmntNewDTREmployee->bindParam(':authdescriptionz', $authdescriptionz);

						$stmntNewDTREmployee->bindParam(':shiftstatusz', $shiftstatusz);
						$stmntNewDTREmployee->bindParam(':timeeditablez', $timeeditablez);
						$stmntNewDTREmployee->bindParam(':prioritydtrz', $prioritydtrz);
						$stmntNewDTREmployee->bindParam(':timeeditablevaluez', $timeeditablevaluez);

						$stmntNewDTREmployee->bindParam(':allowedotzz', $allowedotz);
						$stmntNewDTREmployee->execute();

						// Generate SubDTR
						$dayondtr = 1;

						while ($dayondtr <= 31) {
							$getdateloop = trim($yrdtr)."-".trim(substr(str_repeat(0, 2).$monthdtr, - 2))."-".trim(substr(str_repeat(0, 2).$dayondtr, - 2));
							$daynameloop = date('D', strtotime($getdateloop));
							$countstrday = strlen($daynameloop);

							$amtimeinz = "";
							$amtimeoutz = "";
							$pmtimeinz = "";
							$pmtimeoutz = "";

							$isDateValid = isValidDate($getdateloop);

							if ($isDateValid) {
								$daynamehjh = Trim($daynameloop);

								if ( $daynameloop =="Sat" || $daynameloop =="Sun" ) {

								} else {
									$amtimeinz = fn_amtimein();
									$amtimeoutz = fn_amtimeout();
									$pmtimeinz = fn_pmtimein();
									$pmtimeoutz = fn_pmtimeout();
								}
							} else {
								$daynamehjh = Trim("n/a");
							}

							require_once "model/employee/setcurrentemployee.php";
							require_once "model/employee_dtr_sub/index.php";
							$autoDTRatEmp = new employeeDTRSub();
							$gpsinlocationqw = trim($_SESSION["gpsinlocation"]);
							$autoDTRatEmp->autodtr_employeeDTRSub($agencycodecc,$agencynamecc,$empidcodecc,$dtrCodex,$daynameloop,$dayondtr,$monthdtr,$monthName,$yrdtr,$empnamecc,$biolocationcc,$bionocc,$amtimeinz,$amtimeoutz,$pmtimeinz,$pmtimeoutz,$gpsinlocationqw,$gpsinlocationqw,$gpsinlocationqw,$gpsinlocationqw,$allowedotcc);

							$dayondtr++;
						}
					}
				}
			}
		} catch (PDOException $error) {
			$err_msg = $error->getMessage();
			echo "<p>Error: {$err_msg}</p>";
			die;
		}
	}
?>
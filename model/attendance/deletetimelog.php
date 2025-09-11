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

			$del_empidcode = trim($_SESSION["empidcode"]);
			$del_daytoday = trim(number_format(date("d")));
			$del_monthtoday = trim(number_format(date("m")));
			$del_yrtoday = trim(date("Y"));

			$del_amtimein = "";
			$del_amtimeout = "";
			$del_pmtimein = "";
			$del_pmtimeout = "";

			$delQryTimeInOutEmp = "SELECT * FROM employee_dtr_sub_tbl WHERE emp_idcode=:empidcode AND yearno=:yrdtr AND monthno=:monthdtr AND dayno=:dayno";
			$stmntQryTimeInOutEmp = $cnn->prepare($delQryTimeInOutEmp);
			
			$stmntQryTimeInOutEmp->bindParam(':empidcode', $del_empidcode);
			$stmntQryTimeInOutEmp->bindParam(':yrdtr', $del_yrtoday);
			$stmntQryTimeInOutEmp->bindParam(':monthdtr', $del_monthtoday);
			$stmntQryTimeInOutEmp->bindParam(':dayno', $del_daytoday);
			$stmntQryTimeInOutEmp->execute();
			$countQryTimeInOutEmp = $stmntQryTimeInOutEmp->rowCount();

			if ($countQryTimeInOutEmp > 0) {
				foreach ($stmntQryTimeInOutEmp as $rowQryTimeInOutEmp) {
					$del_amtimein = $rowQryTimeInOutEmp['amtimein'];
					$del_amtimeout = $rowQryTimeInOutEmp['amtimeout'];
					$del_pmtimein = $rowQryTimeInOutEmp['pmtimein'];
					$del_pmtimeout = $rowQryTimeInOutEmp['pmtimeout'];
				}
			}

			if ( isset($_POST["delaminwon"]) ) {
				if ( empty(trim($del_amtimein)) ) {
					echo '<div class="alert alert-info alert-dismissible fade show">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'No AM Time-in Registered. ';
					echo '</div>';
				} else {
					echo '<div class="alert alert-warning alert-dismissible fade show">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo '<button type="submit" name="confrmdelaminwon" class="btn w-100 m-0 p-0">Delete AM Time-IN ?</button>';
					echo '</div>';
				}
			} elseif ( isset($_POST["confrmdelaminwon"]) ) {
				if ( empty(trim($del_amtimein)) ) {

				} else {
					$updt_empidcode = trim($_SESSION["empidcode"]);
					$updt_daytoday = trim(number_format(date("d")));
					$updt_monthtoday = trim(number_format(date("m")));
					$updt_yrtoday = trim(date("Y"));

					$qryUpdateQryTimeInOutEmp = "UPDATE employee_dtr_sub_tbl SET amtimein = NULL WHERE emp_idcode=:updtempidcode AND yearno=:updtyrtoday AND monthno=:updtmonthtoday AND dayno=:updtdaytoday";
					$stmntUpdateQryTimeInOutEmp = $cnn->prepare($qryUpdateQryTimeInOutEmp);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtempidcode', $updt_empidcode);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtyrtoday', $updt_yrtoday);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtmonthtoday', $updt_monthtoday);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtdaytoday', $updt_daytoday);
					$stmntUpdateQryTimeInOutEmp->execute();
					
					echo '<script>window.location.reload();</script>';
				}
			} elseif ( isset($_POST["delamoutwon"]) ) {
				if ( empty(trim($del_amtimeout)) ) {
					echo '<div class="alert alert-info alert-dismissible fade show">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'No AM Time-out Registered. ';
					echo '</div>';
				} else {
					echo '<div class="alert alert-warning alert-dismissible fade show">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo '<button type="submit" name="confrmdelamoutwon" class="btn w-100 m-0 p-0">Delete AM Time-IN ?</button>';
					echo '</div>';
				}
			} elseif ( isset($_POST["confrmdelamoutwon"]) ) {
				if ( empty(trim($del_amtimeout)) ) {

				} else {
					$updt_empidcode = trim($_SESSION["empidcode"]);
					$updt_daytoday = trim(number_format(date("d")));
					$updt_monthtoday = trim(number_format(date("m")));
					$updt_yrtoday = trim(date("Y"));

					$qryUpdateQryTimeInOutEmp = "UPDATE employee_dtr_sub_tbl SET amtimeout = NULL WHERE emp_idcode=:updtempidcode AND yearno=:updtyrtoday AND monthno=:updtmonthtoday AND dayno=:updtdaytoday";
					$stmntUpdateQryTimeInOutEmp = $cnn->prepare($qryUpdateQryTimeInOutEmp);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtempidcode', $updt_empidcode);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtyrtoday', $updt_yrtoday);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtmonthtoday', $updt_monthtoday);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtdaytoday', $updt_daytoday);
					$stmntUpdateQryTimeInOutEmp->execute();
					
					echo '<script>window.location.reload();</script>';
				}
			} elseif ( isset($_POST["delpminwon"]) ) {
				if ( empty(trim($del_pmtimein)) ) {
					echo '<div class="alert alert-info alert-dismissible fade show">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'No PM Time-in Registered. ';
					echo '</div>';
				} else {
					echo '<div class="alert alert-warning alert-dismissible fade show">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo '<button type="submit" name="confrmdelpminwon" class="btn w-100 m-0 p-0">Delete PM Time-IN ?</button>';
					echo '</div>';
				}
			} elseif ( isset($_POST["confrmdelpminwon"]) ) {
				if ( empty(trim($del_pmtimein)) ) {

				} else {
					$updt_empidcode = trim($_SESSION["empidcode"]);
					$updt_daytoday = trim(number_format(date("d")));
					$updt_monthtoday = trim(number_format(date("m")));
					$updt_yrtoday = trim(date("Y"));

					$qryUpdateQryTimeInOutEmp = "UPDATE employee_dtr_sub_tbl SET pmtimein = NULL WHERE emp_idcode=:updtempidcode AND yearno=:updtyrtoday AND monthno=:updtmonthtoday AND dayno=:updtdaytoday";
					$stmntUpdateQryTimeInOutEmp = $cnn->prepare($qryUpdateQryTimeInOutEmp);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtempidcode', $updt_empidcode);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtyrtoday', $updt_yrtoday);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtmonthtoday', $updt_monthtoday);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtdaytoday', $updt_daytoday);
					$stmntUpdateQryTimeInOutEmp->execute();
					echo '<script>window.location.reload();</script>';
				}
			} elseif ( isset($_POST["delpmoutwon"]) ) {
				if ( empty(trim($del_pmtimeout)) ) {
					echo '<div class="alert alert-info alert-dismissible fade show">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'No PM Time-out Registered. ';
					echo '</div>';
				} else {
					echo '<div class="alert alert-warning alert-dismissible fade show">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo '<button type="submit" name="confrmdelpmoutwon" class="btn w-100 m-0 p-0">Delete PM Time-OUT ?</button>';
					echo '</div>';
				}
			} elseif ( isset($_POST["confrmdelpmoutwon"]) )  {
				if ( empty(trim($del_pmtimeout)) ) {

				} else {
					$updt_empidcode = trim($_SESSION["empidcode"]);
					$updt_daytoday = trim(number_format(date("d")));
					$updt_monthtoday = trim(number_format(date("m")));
					$updt_yrtoday = trim(date("Y"));

					$qryUpdateQryTimeInOutEmp = "UPDATE employee_dtr_sub_tbl SET pmtimeout = NULL WHERE emp_idcode=:updtempidcode AND yearno=:updtyrtoday AND monthno=:updtmonthtoday AND dayno=:updtdaytoday";
					$stmntUpdateQryTimeInOutEmp = $cnn->prepare($qryUpdateQryTimeInOutEmp);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtempidcode', $updt_empidcode);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtyrtoday', $updt_yrtoday);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtmonthtoday', $updt_monthtoday);
					$stmntUpdateQryTimeInOutEmp->bindParam(':updtdaytoday', $updt_daytoday);
					$stmntUpdateQryTimeInOutEmp->execute();
					
					echo '<script>window.location.reload();</script>';
				}
			}
		} catch (PDOException $error) {
			$err_msg = $error->getMessage();
			echo "<p>Error: {$err_msg}</p>";
			die;
		}
	}
<?php 

	try {

		require_once "lib/cnn.php";

		class employeeDTRSub extends myDatabase {
			// Memory single variable base on Database Table Fieldnames
			Public $empdtrsubautoiddd,
				$agencycodedd,
				$agencynamedd,
				$empidcodedd,
				$dtrcodedd,
				$namedaydd,
				$daynodd,
				$monthnodd,
				$monthnamedd,
				$yearnodd,
				$empnamedd,
				$biolocationdd,
				$bionodd,
				$amtimeindd,
				$amtimeoutdd,
				$pmtimeindd,
				$pmtimeoutdd,
				$lateamdd,
				$latepmdd,
				$utimeamdd,
				$utimepmdd,
				$latemindd,
				$utimemindd,
				$tardymindd,
				$lateutimehourdd,
				$lateutimemindd,
				$overtimehourdd,
				$overtimemindd,
				$attendancegpslocationamindd,
				$attendancegpslocationamoutdd,
				$attendancegpslocationpmindd,
				$attendancegpslocationpmoutdd,
				$allowedotdd,
				$xdeldd,
				$createdbydd,
				$modifiedbydd,
				$modifiedatdd,
				$createdatdd;

			// Memory list variable base on Database Table Fieldnames
			Public $list_empdtrsubautoiddd,
				$list_agencycodedd,
				$list_agencynamedd,
				$list_empidcodedd,
				$list_dtrcodedd,
				$list_namedaydd,
				$list_daynodd,
				$list_monthnodd,
				$list_monthnamedd,
				$list_yearnodd,
				$list_empnamedd,
				$list_biolocationdd,
				$list_bionodd,
				$list_amtimeindd,
				$list_amtimeoutdd,
				$list_pmtimeindd,
				$list_pmtimeoutdd,
				$list_lateamdd,
				$list_latepmdd,
				$list_utimeamdd,
				$list_utimepmdd,
				$list_latemindd,
				$list_utimemindd,
				$list_tardymindd,
				$list_lateutimehourdd,
				$list_lateutimemindd,
				$list_overtimehourdd,
				$list_overtimemindd,
				$list_attendancegpslocationamindd,
				$list_attendancegpslocationamoutdd,
				$list_attendancegpslocationpmindd,
				$list_attendancegpslocationpmoutdd,
				$list_allowedotdd,
				$list_xdeldd,
				$list_createdbydd,
				$list_modifiedbydd,
				$list_modifiedatdd,
				$list_createdatdd;

			// Constructer Memory list variable base on Database Table Fieldnames
			public function __construct() {
				$this->list_empdtrsubautoiddd = array();
				$this->list_agencycodedd = array();
				$this->list_agencynamedd = array();
				$this->list_empidcodedd = array();
				$this->list_dtrcodedd = array();
				$this->list_namedaydd = array();
				$this->list_daynodd = array();
				$this->list_monthnodd = array();
				$this->list_monthnamedd = array();
				$this->list_yearnodd = array();
				$this->list_empnamedd = array();
				$this->list_biolocationdd = array();
				$this->list_bionodd = array();
				$this->list_amtimeindd = array();
				$this->list_amtimeoutdd = array();
				$this->list_pmtimeindd = array();
				$this->list_pmtimeoutdd = array();
				$this->list_lateamdd = array();
				$this->list_latepmdd = array();
				$this->list_utimeamdd = array();
				$this->list_utimepmdd = array();
				$this->list_latemindd = array();
				$this->list_utimemindd = array();
				$this->list_tardymindd = array();
				$this->list_lateutimehourdd = array();
				$this->list_lateutimemindd = array();
				$this->list_overtimehourdd = array();
				$this->list_overtimemindd = array();
				$this->list_attendancegpslocationamindd = array();
				$this->list_attendancegpslocationamoutdd = array();
				$this->list_attendancegpslocationpmindd = array();
				$this->list_attendancegpslocationpmoutdd = array();
				$this->list_allowedotdd = array();
				$this->list_xdeldd = array();
				$this->list_createdbydd = array();
				$this->list_modifiedbydd = array();
				$this->list_modifiedatdd = array();
				$this->list_createdatdd = array();
			}

			// Clearing of data values Memory list variable base on Database Table Fieldnames
			public function clearlist_employeeDTRSub() {
				$this->list_empdtrsubautoiddd = array();
				$this->list_agencycodedd = array();
				$this->list_agencynamedd = array();
				$this->list_empidcodedd = array();
				$this->list_dtrcodedd = array();
				$this->list_namedaydd = array();
				$this->list_daynodd = array();
				$this->list_monthnodd = array();
				$this->list_monthnamedd = array();
				$this->list_yearnodd = array();
				$this->list_empnamedd = array();
				$this->list_biolocationdd = array();
				$this->list_bionodd = array();
				$this->list_amtimeindd = array();
				$this->list_amtimeoutdd = array();
				$this->list_pmtimeindd = array();
				$this->list_pmtimeoutdd = array();
				$this->list_lateamdd = array();
				$this->list_latepmdd = array();
				$this->list_utimeamdd = array();
				$this->list_utimepmdd = array();
				$this->list_latemindd = array();
				$this->list_utimemindd = array();
				$this->list_tardymindd = array();
				$this->list_lateutimehourdd = array();
				$this->list_lateutimemindd = array();
				$this->list_overtimehourdd = array();
				$this->list_overtimemindd = array();
				$this->list_attendancegpslocationamindd = array();
				$this->list_attendancegpslocationamoutdd = array();
				$this->list_attendancegpslocationpmindd = array();
				$this->list_attendancegpslocationpmoutdd = array();
				$this->list_allowedotdd = array();
				$this->list_xdeldd = array();
				$this->list_createdbydd = array();
				$this->list_modifiedbydd = array();
				$this->list_modifiedatdd = array();
				$this->list_createdatdd = array();
			}

			// Search for the Time Record selected Date for Employee
			public function Search_employeeDTRSub($searchempid,$yrno,$monthno,$dayno) {
				$this->clearlist_employeeDTRSub();
				$this->getConnection();

				$selectQuery = "SELECT * FROM employee_dtr_sub_tbl WHERE emp_idcode=:empidcode AND yearno=:yearno AND monthno=:monthno AND dayno=:dayno LIMIT 1";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':empidcode', $searchempid);
				$stmt->bindParam(':yearno', $yrno);
				$stmt->bindParam(':monthno', $monthno);
				$stmt->bindParam(':dayno', $dayno);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_empdtrsubautoiddd[] = $rwRcrd['empdtr_sub_autoid'];
						$this->list_agencycodedd[] = $rwRcrd['agency_code'];
						$this->list_empidcodedd[] = $rwRcrd['emp_idcode'];
						$this->list_dtrcodedd[] = $rwRcrd['dtrcode'];
						$this->list_namedaydd[] = $rwRcrd['nameday'];
						$this->list_daynodd[] = $rwRcrd['dayno'];
						$this->list_monthnodd[] = $rwRcrd['monthno'];
						$this->list_monthnamedd[] = $rwRcrd['monthname'];
						$this->list_yearnodd[] = $rwRcrd['yearno'];
						$this->list_biolocationdd[] = $rwRcrd['bio_location'];
						$this->list_bionodd[] = $rwRcrd['bio_no'];
						$this->list_amtimeindd[] = $rwRcrd['amtimein'];
						$this->list_amtimeoutdd[] = $rwRcrd['amtimeout'];
						$this->list_pmtimeindd[] = $rwRcrd['pmtimein'];
						$this->list_pmtimeoutdd[] = $rwRcrd['pmtimeout'];
						$this->list_lateamdd[] = $rwRcrd['late_am'];
						$this->list_latepmdd[] = $rwRcrd['late_pm'];
						$this->list_utimeamdd[] = $rwRcrd['utime_am'];
						$this->list_utimepmdd[] = $rwRcrd['utime_pm'];
						$this->list_latemindd[] = $rwRcrd['latemin'];
						$this->list_utimemindd[] = $rwRcrd['utimemin'];
						$this->list_tardymindd[] = $rwRcrd['tardymin'];
						$this->list_lateutimehourdd[] = $rwRcrd['lateutime_hour'];
						$this->list_lateutimemindd[] = $rwRcrd['lateutime_min'];
						$this->list_overtimehourdd[] = $rwRcrd['overtime_hour'];
						$this->list_overtimemindd[] = $rwRcrd['overtime_min'];
						$this->list_attendancegpslocationamindd[] = $rwRcrd['attendance_gps_location_am_in'];
						$this->list_attendancegpslocationamoutdd[] = $rwRcrd['attendance_gps_location_am_out'];
						$this->list_attendancegpslocationpmindd[] = $rwRcrd['attendance_gps_location_pm_in'];
						$this->list_attendancegpslocationpmoutdd[] = $rwRcrd['attendance_gps_location_pm_out'];
						$this->list_allowedotdd[] = $rwRcrd['allowed_ot'];
						$this->list_xdeldd[] = $rwRcrd['xdel'];
						$this->list_createdbydd[] = $rwRcrd['createdby'];
						$this->list_modifiedbydd[] = $rwRcrd['modifiedby'];
						$this->list_modifiedatdd[] = $rwRcrd['modified_at'];
						$this->list_createdatdd[] = $rwRcrd['created_at'];

						include "lib/onoffline.php";
						if ( $onlineornot == 1 ) {
							$this->list_agencynamedd[] = utf8_encode($rwRcrd['agency_name']);
							$this->list_empnamedd[] = utf8_encode($rwRcrd['emp_name']);
						} else {
							$this->list_agencynamedd[] = $rwRcrd['agency_name'];
							$this->list_empnamedd[] = $rwRcrd['emp_name'];
						}
					}

					return true;
				} else {
					return false;
				}
			}

			// Auto DTR
			public function autodtr_employeeDTRSub($agencycodedd,$agencynamedd,$empidcodedd,$dtrcodedd,$namedaydd,$daynodd,$monthnodd,$monthnamedd,$yearnodd,$empnamedd,$biolocationdd,$bionodd,$amtimeindd,$amtimeoutdd,$pmtimeindd,$pmtimeoutdd,$attendancegpslocationamindd,$attendancegpslocationamoutdd,$attendancegpslocationpmindd,$attendancegpslocationpmoutdd,$allowedotdd) {
				$this->clearlist_employeeDTRSub();
				$this->getConnection();

				$insertQuery = "INSERT INTO employee_dtr_sub_tbl SET 
					agency_code=:agencycode, 
					agency_name=:agencyname, 
					emp_idcode=:empidcode, 
					dtrcode=:dtrcode, 
					nameday=:nameday, 
					dayno=:dayno, 
					monthno=:monthno, 
					monthname=:monthname, 
					yearno=:yearno, 
					emp_name=:empname, 
					bio_location=:biolocation, 
					bio_no=:biono, 
					amtimein=:amtimein, 
					amtimeout=:amtimeout, 
					pmtimein=:pmtimein, 
					pmtimeout=:pmtimeout, 
					attendance_gps_location_am_in=:attendancegpslocationamin, 
					attendance_gps_location_am_out=:attendancegpslocationamout, 
					attendance_gps_location_pm_in=:attendancegpslocationpmin, 
					attendance_gps_location_pm_out=:attendancegpslocationpmout, 
					allowed_ot=:allowedot";
				$stmt = $this->cnn->prepare($insertQuery);
				$stmt->bindParam(':agencycode', $agencycodedd);
				$stmt->bindParam(':agencyname', $agencynamedd);
				$stmt->bindParam(':empidcode', $empidcodedd);
				$stmt->bindParam(':dtrcode', $dtrcodedd);
				$stmt->bindParam(':nameday', $namedaydd);
				$stmt->bindParam(':dayno', $daynodd);
				$stmt->bindParam(':monthno', $monthnodd);
				$stmt->bindParam(':monthname', $monthnamedd);
				$stmt->bindParam(':yearno', $yearnodd);
				$stmt->bindParam(':empname', $empnamedd);
				$stmt->bindParam(':biolocation', $biolocationdd);
				$stmt->bindParam(':biono', $bionodd);
				$stmt->bindParam(':amtimein', $amtimeindd);
				$stmt->bindParam(':amtimeout', $amtimeoutdd);
				$stmt->bindParam(':pmtimein', $pmtimeindd);
				$stmt->bindParam(':pmtimeout', $pmtimeoutdd);
				$stmt->bindParam(':attendancegpslocationamin', $attendancegpslocationamindd);
				$stmt->bindParam(':attendancegpslocationamout', $attendancegpslocationamoutdd);
				$stmt->bindParam(':attendancegpslocationpmin', $attendancegpslocationpmindd);
				$stmt->bindParam(':attendancegpslocationpmout', $attendancegpslocationpmoutdd);
				$stmt->bindParam(':allowedot', $allowedotdd);
				$stmt->execute();
			}
		}

	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error: {$err_msg}</p>";
		die;
	}
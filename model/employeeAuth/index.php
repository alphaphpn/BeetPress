<?php 

	try {
		if ( isset($_POST["btnEmpLogin"]) ) {
			if ( empty($_POST["employeeID"]) || empty($_POST["pinInput"]) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter valid Employee ID and PIN.';
				echo '</div>';				
			} else {
				// Check if system is On Cloud or at Local
				if ($onlineornot==1) {
					if ( empty($_POST["gpsInput"]) ) {
						echo '<div class="alert alert-danger alert-dismissible fade show">';
							echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
							echo 'Please turn ON your GPS Location.';
						echo '</div>';
					} else {
						// Login the user
						include_once "model/employee/index.php";

						$gpsinlocationgg = trim($_POST["gpsInput"]);

						$empAuthnt = new employeeAcct();

						$empidcode = trim($_POST['employeeID']);
						$pinword = trim($_POST['pinInput']);

						if ( $empAuthnt->fn_employeeAuth($empidcode,$pinword) ) {
							session_start();

							$empAuthnt->fn_employeeAuth($empidcode,$pinword);
							for($i = 0; $i < count($empAuthnt->list_empautoidee); $i++) {
								if ( $empAuthnt->list_activatedee[$i] == 0 ) {
									echo '<div class="alert alert-danger alert-dismissible fade show">';
										echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
										echo 'Your Account is Disabled!';
									echo '</div>';
								} elseif ( $empAuthnt->list_verifiedee[$i] == 0 ) {
									$_SESSION["gpsinlocation"] = $gpsinlocationgg;

									$_SESSION["agencycode"] = $empAuthnt->list_agencycodeee[$i];
									$_SESSION["agencyname"] = $empAuthnt->list_agencynameee[$i];
									$_SESSION["profileid"] = $empAuthnt->list_profileidee[$i];
									$_SESSION["uid"] = $empAuthnt->list_uidee[$i];
									$_SESSION["empidcode"] = $empAuthnt->list_empidcodeee[$i];
									$_SESSION["hrempid"] = $empAuthnt->list_hrempidee[$i];
									$_SESSION["biolocation"] = $empAuthnt->list_biolocationee[$i];
									$_SESSION["biono"] = $empAuthnt->list_bionoee[$i];
									$_SESSION["empname"] = $empAuthnt->list_empnameee[$i];
									$_SESSION["verified"] = $empAuthnt->list_verifiedee[$i];
									$_SESSION["shiftstatus"] = $empAuthnt->list_shiftstatusee[$i];
									$_SESSION["employeeactivated"] = $empAuthnt->list_activatedee[$i];
									$_SESSION["worklocation"] = $empAuthnt->list_worklocationee[$i];
									$_SESSION["timeeditable"] = $empAuthnt->list_timeeditableee[$i];
									$_SESSION["timeeditablevalue"] = $empAuthnt->list_timeeditablevalueee[$i];
									$_SESSION["prioritydtr"] = $empAuthnt->list_prioritydtree[$i];
									$_SESSION["allowedot"] = $empAuthnt->list_allowedotee[$i];
									$_SESSION["typeemployeeabrv"] = $empAuthnt->list_typeemployeeabrvee[$i];
									$_SESSION["gender"] = $empAuthnt->list_genderee[$i];
									$_SESSION["birthday"] = $empAuthnt->list_birthdayee[$i];
									$_SESSION["empage"] = $empAuthnt->list_empageee[$i];
									$_SESSION["officeid"] = $empAuthnt->list_officeidee[$i];
									$_SESSION["officecode"] = $empAuthnt->list_officecodeee[$i];
									$_SESSION["officename"] = $empAuthnt->list_officenameee[$i];
									$_SESSION["officetitle"] = $empAuthnt->list_officetitleee[$i];
									$_SESSION["officeabrv"] = $empAuthnt->list_officeabrvee[$i];
									$_SESSION["oldofficeabrv"] = $empAuthnt->list_oldofficeabrvee[$i];
									$_SESSION["officegpslocation"] = $empAuthnt->list_officegpslocationee[$i];
									$_SESSION["headofficer"] = $empAuthnt->list_headofficeree[$i];
									$_SESSION["headtitle"] = $empAuthnt->list_headtitleee[$i];
									$_SESSION["authhead"] = $empAuthnt->list_authheadee[$i];
									$_SESSION["authtitle"] = $empAuthnt->list_authtitleee[$i];
									$_SESSION["authdescription"] = $empAuthnt->list_authdescriptionee[$i];
									$_SESSION["yearemployed"] = $empAuthnt->list_yearemployedee[$i];
									$_SESSION["yearcalc"] = $empAuthnt->list_yearcalcee[$i];
									$_SESSION["typeemployeeno"] = $empAuthnt->list_typeemployeenoee[$i];
									$_SESSION["typeemployee"] = $empAuthnt->list_typeemployeeee[$i];
									$_SESSION["position"] = $empAuthnt->list_positionee[$i];
									$_SESSION["designation"] = $empAuthnt->list_designationee[$i];
									$_SESSION["mphone"] = $empAuthnt->list_mphoneee[$i];
									$_SESSION["empemail"] = $empAuthnt->list_empemailee[$i];
									$_SESSION["designationat"] = $empAuthnt->list_designationatee[$i];

									echo '<div class="alert alert-warning alert-dismissible fade show">';
										echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
										echo 'Your Account needs to be Verified!';
									echo '</div>';
									echo '<div><span>Go to My <a href="attendance" class="text-decoration-none">Attendance</a></span></div>';
								} else {
									$_SESSION["gpsinlocation"] = $gpsinlocation;

									$_SESSION["agencycode"] = $empAuthnt->list_agencycodeee[$i];
									$_SESSION["agencyname"] = $empAuthnt->list_agencynameee[$i];
									$_SESSION["profileid"] = $empAuthnt->list_profileidee[$i];
									$_SESSION["uid"] = $empAuthnt->list_uidee[$i];
									$_SESSION["empidcode"] = $empAuthnt->list_empidcodeee[$i];
									$_SESSION["hrempid"] = $empAuthnt->list_hrempidee[$i];
									$_SESSION["biolocation"] = $empAuthnt->list_biolocationee[$i];
									$_SESSION["biono"] = $empAuthnt->list_bionoee[$i];
									$_SESSION["empname"] = $empAuthnt->list_empnameee[$i];
									$_SESSION["verified"] = $empAuthnt->list_verifiedee[$i];
									$_SESSION["shiftstatus"] = $empAuthnt->list_shiftstatusee[$i];
									$_SESSION["employeeactivated"] = $empAuthnt->list_activatedee[$i];
									$_SESSION["worklocation"] = $empAuthnt->list_worklocationee[$i];
									$_SESSION["timeeditable"] = $empAuthnt->list_timeeditableee[$i];
									$_SESSION["timeeditablevalue"] = $empAuthnt->list_timeeditablevalueee[$i];
									$_SESSION["prioritydtr"] = $empAuthnt->list_prioritydtree[$i];
									$_SESSION["allowedot"] = $empAuthnt->list_allowedotee[$i];
									$_SESSION["typeemployeeabrv"] = $empAuthnt->list_typeemployeeabrvee[$i];
									$_SESSION["gender"] = $empAuthnt->list_genderee[$i];
									$_SESSION["birthday"] = $empAuthnt->list_birthdayee[$i];
									$_SESSION["empage"] = $empAuthnt->list_empageee[$i];
									$_SESSION["officeid"] = $empAuthnt->list_officeidee[$i];
									$_SESSION["officecode"] = $empAuthnt->list_officecodeee[$i];
									$_SESSION["officename"] = $empAuthnt->list_officenameee[$i];
									$_SESSION["officetitle"] = $empAuthnt->list_officetitleee[$i];
									$_SESSION["officeabrv"] = $empAuthnt->list_officeabrvee[$i];
									$_SESSION["oldofficeabrv"] = $empAuthnt->list_oldofficeabrvee[$i];
									$_SESSION["officegpslocation"] = $empAuthnt->list_officegpslocationee[$i];
									$_SESSION["headofficer"] = $empAuthnt->list_headofficeree[$i];
									$_SESSION["headtitle"] = $empAuthnt->list_headtitleee[$i];
									$_SESSION["authhead"] = $empAuthnt->list_authheadee[$i];
									$_SESSION["authtitle"] = $empAuthnt->list_authtitleee[$i];
									$_SESSION["authdescription"] = $empAuthnt->list_authdescriptionee[$i];
									$_SESSION["yearemployed"] = $empAuthnt->list_yearemployedee[$i];
									$_SESSION["yearcalc"] = $empAuthnt->list_yearcalcee[$i];
									$_SESSION["typeemployeeno"] = $empAuthnt->list_typeemployeenoee[$i];
									$_SESSION["typeemployee"] = $empAuthnt->list_typeemployeeee[$i];
									$_SESSION["position"] = $empAuthnt->list_positionee[$i];
									$_SESSION["designation"] = $empAuthnt->list_designationee[$i];
									$_SESSION["mphone"] = $empAuthnt->list_mphoneee[$i];
									$_SESSION["empemail"] = $empAuthnt->list_empemailee[$i];
									$_SESSION["designationat"] = $empAuthnt->list_designationatee[$i];

									echo "<script>window.open('attendance', '_self');</script>";
								}
							}
						} else {
							echo '<div class="alert alert-danger alert-dismissible fade show">';
								echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
								echo "Invalid Employee ID or PIN";
							echo '</div>';
						}
					}
				} else {
					// Login the user
					include_once "model/employee/index.php";

					$empAuthnt=new employeeAcct();

					$empidcode = trim($_POST['employeeID']);
					$pinword = trim($_POST['pinInput']);

					if ( $empAuthnt->fn_employeeAuth($empidcode,$pinword) ) {
						session_start();

						$empAuthnt->fn_employeeAuth($empidcode,$pinword);
						for($i = 0; $i < count($empAuthnt->list_empautoidee); $i++) {
							if ( $empAuthnt->list_activatedee[$i] == 0 ) {
								echo '<div class="alert alert-danger alert-dismissible fade show">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'Your Account is Disabled!';
								echo '</div>';
							} elseif ( $empAuthnt->list_verifiedee[$i] == 0 ) {
								$_SESSION["gpsinlocation"] = $empAuthnt->list_officegpslocationee[$i];

								$_SESSION["agencycode"] = $empAuthnt->list_agencycodeee[$i];
								$_SESSION["agencyname"] = $empAuthnt->list_agencynameee[$i];
								$_SESSION["profileid"] = $empAuthnt->list_profileidee[$i];
								$_SESSION["uid"] = $empAuthnt->list_uidee[$i];
								$_SESSION["empidcode"] = $empAuthnt->list_empidcodeee[$i];
								$_SESSION["hrempid"] = $empAuthnt->list_hrempidee[$i];
								$_SESSION["biolocation"] = $empAuthnt->list_biolocationee[$i];
								$_SESSION["biono"] = $empAuthnt->list_bionoee[$i];
								$_SESSION["empname"] = $empAuthnt->list_empnameee[$i];
								$_SESSION["verified"] = $empAuthnt->list_verifiedee[$i];
								$_SESSION["shiftstatus"] = $empAuthnt->list_shiftstatusee[$i];
								$_SESSION["employeeactivated"] = $empAuthnt->list_activatedee[$i];
								$_SESSION["worklocation"] = $empAuthnt->list_worklocationee[$i];
								$_SESSION["timeeditable"] = $empAuthnt->list_timeeditableee[$i];
								$_SESSION["timeeditablevalue"] = $empAuthnt->list_timeeditablevalueee[$i];
								$_SESSION["prioritydtr"] = $empAuthnt->list_prioritydtree[$i];
								$_SESSION["allowedot"] = $empAuthnt->list_allowedotee[$i];
								$_SESSION["typeemployeeabrv"] = $empAuthnt->list_typeemployeeabrvee[$i];
								$_SESSION["gender"] = $empAuthnt->list_genderee[$i];
								$_SESSION["birthday"] = $empAuthnt->list_birthdayee[$i];
								$_SESSION["empage"] = $empAuthnt->list_empageee[$i];
								$_SESSION["officeid"] = $empAuthnt->list_officeidee[$i];
								$_SESSION["officecode"] = $empAuthnt->list_officecodeee[$i];
								$_SESSION["officename"] = $empAuthnt->list_officenameee[$i];
								$_SESSION["officetitle"] = $empAuthnt->list_officetitleee[$i];
								$_SESSION["officeabrv"] = $empAuthnt->list_officeabrvee[$i];
								$_SESSION["oldofficeabrv"] = $empAuthnt->list_oldofficeabrvee[$i];
								$_SESSION["officegpslocation"] = $empAuthnt->list_officegpslocationee[$i];
								$_SESSION["headofficer"] = $empAuthnt->list_headofficeree[$i];
								$_SESSION["headtitle"] = $empAuthnt->list_headtitleee[$i];
								$_SESSION["authhead"] = $empAuthnt->list_authheadee[$i];
								$_SESSION["authtitle"] = $empAuthnt->list_authtitleee[$i];
								$_SESSION["authdescription"] = $empAuthnt->list_authdescriptionee[$i];
								$_SESSION["yearemployed"] = $empAuthnt->list_yearemployedee[$i];
								$_SESSION["yearcalc"] = $empAuthnt->list_yearcalcee[$i];
								$_SESSION["typeemployeeno"] = $empAuthnt->list_typeemployeenoee[$i];
								$_SESSION["typeemployee"] = $empAuthnt->list_typeemployeeee[$i];
								$_SESSION["position"] = $empAuthnt->list_positionee[$i];
								$_SESSION["designation"] = $empAuthnt->list_designationee[$i];
								$_SESSION["mphone"] = $empAuthnt->list_mphoneee[$i];
								$_SESSION["empemail"] = $empAuthnt->list_empemailee[$i];
								$_SESSION["designationat"] = $empAuthnt->list_designationatee[$i];

								echo '<div class="alert alert-warning alert-dismissible fade show">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'Your Account needs to be Verified!';
								echo '</div>';
								echo '<div><span>Go to My <a href="attendance" class="text-decoration-none">Attendance</a></span></div>';
							} else {

								$_SESSION["gpsinlocation"] = $gpsinlocationx;

								$_SESSION["agencycode"] = $empAuthnt->list_agencycodeee[$i];
								$_SESSION["agencyname"] = $empAuthnt->list_agencynameee[$i];
								$_SESSION["profileid"] = $empAuthnt->list_profileidee[$i];
								$_SESSION["uid"] = $empAuthnt->list_uidee[$i];
								$_SESSION["empidcode"] = $empAuthnt->list_empidcodeee[$i];
								$_SESSION["hrempid"] = $empAuthnt->list_hrempidee[$i];
								$_SESSION["biolocation"] = $empAuthnt->list_biolocationee[$i];
								$_SESSION["biono"] = $empAuthnt->list_bionoee[$i];
								$_SESSION["empname"] = $empAuthnt->list_empnameee[$i];
								$_SESSION["verified"] = $empAuthnt->list_verifiedee[$i];
								$_SESSION["shiftstatus"] = $empAuthnt->list_shiftstatusee[$i];
								$_SESSION["employeeactivated"] = $empAuthnt->list_activatedee[$i];
								$_SESSION["worklocation"] = $empAuthnt->list_worklocationee[$i];
								$_SESSION["timeeditable"] = $empAuthnt->list_timeeditableee[$i];
								$_SESSION["timeeditablevalue"] = $empAuthnt->list_timeeditablevalueee[$i];
								$_SESSION["prioritydtr"] = $empAuthnt->list_prioritydtree[$i];
								$_SESSION["allowedot"] = $empAuthnt->list_allowedotee[$i];
								$_SESSION["typeemployeeabrv"] = $empAuthnt->list_typeemployeeabrvee[$i];
								$_SESSION["gender"] = $empAuthnt->list_genderee[$i];
								$_SESSION["birthday"] = $empAuthnt->list_birthdayee[$i];
								$_SESSION["empage"] = $empAuthnt->list_empageee[$i];
								$_SESSION["officeid"] = $empAuthnt->list_officeidee[$i];
								$_SESSION["officecode"] = $empAuthnt->list_officecodeee[$i];
								$_SESSION["officename"] = $empAuthnt->list_officenameee[$i];
								$_SESSION["officetitle"] = $empAuthnt->list_officetitleee[$i];
								$_SESSION["officeabrv"] = $empAuthnt->list_officeabrvee[$i];
								$_SESSION["oldofficeabrv"] = $empAuthnt->list_oldofficeabrvee[$i];
								$_SESSION["officegpslocation"] = $empAuthnt->list_officegpslocationee[$i];
								$_SESSION["headofficer"] = $empAuthnt->list_headofficeree[$i];
								$_SESSION["headtitle"] = $empAuthnt->list_headtitleee[$i];
								$_SESSION["authhead"] = $empAuthnt->list_authheadee[$i];
								$_SESSION["authtitle"] = $empAuthnt->list_authtitleee[$i];
								$_SESSION["authdescription"] = $empAuthnt->list_authdescriptionee[$i];
								$_SESSION["yearemployed"] = $empAuthnt->list_yearemployedee[$i];
								$_SESSION["yearcalc"] = $empAuthnt->list_yearcalcee[$i];
								$_SESSION["typeemployeeno"] = $empAuthnt->list_typeemployeenoee[$i];
								$_SESSION["typeemployee"] = $empAuthnt->list_typeemployeeee[$i];
								$_SESSION["position"] = $empAuthnt->list_positionee[$i];
								$_SESSION["designation"] = $empAuthnt->list_designationee[$i];
								$_SESSION["mphone"] = $empAuthnt->list_mphoneee[$i];
								$_SESSION["empemail"] = $empAuthnt->list_empemailee[$i];
								$_SESSION["designationat"] = $empAuthnt->list_designationatee[$i];

								echo "<script>window.open('attendance', '_self');</script>";
							}
						}
					} else {
						echo '<div class="alert alert-danger alert-dismissible fade show">';
							echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
							echo "Invalid Employee ID or PIN";
						echo '</div>';
					}
				}
			}
		} else {
			echo "<p class='text-center text-info'>Login to your account.</p>";
		}
	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p class='text-danger'>Error: No Database</p>";
		echo "<p class='text-danger'>{$err_msg}</p>";
		die;
	}

	// Input Pattern
	// https://www.w3schools.com/tags/att_input_pattern.asp

?>
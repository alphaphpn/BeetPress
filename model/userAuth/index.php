<?php 

	try {

		if (file_exists("model/userAcct/index.php")) {
			require_once "model/userAcct/index.php";
		} elseif (file_exists("../../model/userAcct/index.php")) {
			require_once "../../model/userAcct/index.php";
		}

		$authAcctx = new authAcct();

		if ( isset($_POST["btnUserLogin"]) ) {
			$username = isset($_POST["username"]) ? $_POST["username"] : null;
			$password = isset($_POST["password"]) ? $_POST["password"] : null;
			$gpsinlocationgg = isset($_POST["gpsInput"]) ? $_POST["gpsInput"] : null;

			if ( empty(trim($username)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Invalid Username!';
				echo '</div>';
			} elseif ( empty(trim($password)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Invalid Password!';
				echo '</div>';
			} else {

				if ( $authAcctx->authenticate_userAcct($username,$password) ) {

					$authAcctx->authenticate_userAcct($username,$password);

					for ($i = 0; $i < count($authAcctx->list_unamegg); $i++) {
						$auth_authid = $authAcctx->list_authidgg[$i];
						$auth_agency_code = $authAcctx->list_agencycodegg[$i];
						$auth_agency_name = $authAcctx->list_agencynamegg[$i];
						$auth_uid = $authAcctx->list_uidgg[$i];
						$auth_profileid = $authAcctx->list_profileidgg[$i];
						$auth_uname = $authAcctx->list_unamegg[$i];
						$auth_nickname = $authAcctx->list_nicknamegg[$i];
						$auth_pword = $authAcctx->list_pwordgg[$i];
						$auth_country = $authAcctx->list_countrygg[$i];
						$auth_countrycode = $authAcctx->list_countrycodegg[$i];
						$auth_zipcode = $authAcctx->list_zipcodegg[$i];
						$auth_phone = $authAcctx->list_phonegg[$i];
						$auth_email = $authAcctx->list_emailgg[$i];
						$auth_birthdate = $authAcctx->list_birthdategg[$i];
						$auth_verified = $authAcctx->list_verifiedgg[$i];
						$auth_ustat = $authAcctx->list_ustatgg[$i];
						$auth_ulevel = $authAcctx->list_ulevelgg[$i];
						$auth_uposition = $authAcctx->list_upositiongg[$i];
						$auth_onoffline = $authAcctx->list_onofflinegg[$i];
						$auth_secure_question = $authAcctx->list_securequestiongg[$i];
						$auth_secure_answer = $authAcctx->list_secureanswergg[$i];
						$auth_officeid = $authAcctx->list_officeidgg[$i];
						$auth_officeabrv = $authAcctx->list_officeabrvgg[$i];
						$auth_officecode = $authAcctx->list_officecodegg[$i];
						$auth_xdel = $authAcctx->list_xdelgg[$i];
						$auth_createdby = $authAcctx->list_createdbygg[$i];
						$auth_modifiedby = $authAcctx->list_modifiedbygg[$i];
						$auth_modified_at = $authAcctx->list_modifiedatgg[$i];
						$auth_created_at = $authAcctx->list_createdatgg[$i];

						$_SESSION['d2s8wu_authid'] = trim($auth_authid);
						$_SESSION['d2s8wu_agencycode'] = trim($auth_agency_code);
						$_SESSION['d2s8wu_agencyname'] = trim($auth_agency_name);
						$_SESSION['d2s8wu_uid'] = trim($auth_uid);
						$_SESSION['d2s8wu_profileid'] = trim($auth_profileid);
						$_SESSION['d2s8wu_uname'] = trim($auth_uname);
						$_SESSION['d2s8wu_nickname'] = trim($auth_nickname);
						$_SESSION['d2s8wu_pword'] = trim($auth_pword);
						$_SESSION['d2s8wu_country'] = trim($auth_country);
						$_SESSION['d2s8wu_countrycode'] = trim($auth_countrycode);
						$_SESSION['d2s8wu_zipcode'] = trim($auth_zipcode);
						$_SESSION['d2s8wu_phone'] = trim($auth_phone);
						$_SESSION['d2s8wu_email'] = trim($auth_email);
						$_SESSION['d2s8wu_birthdate'] = trim($auth_birthdate);
						$_SESSION['d2s8wu_verified'] = trim($auth_verified);
						$_SESSION['d2s8wu_ustat'] = trim($auth_ustat);
						$_SESSION['d2s8wu_ulevel'] = trim($auth_ulevel);
						$_SESSION['d2s8wu_uposition'] = trim($auth_uposition);
						$_SESSION['d2s8wu_onoffline'] = trim($auth_onoffline);
						$_SESSION['d2s8wu_securequestion'] = trim($auth_secure_question);
						$_SESSION['d2s8wu_secureanswer'] = trim($auth_secure_answer);
						$_SESSION['d2s8wu_officeid'] = trim($auth_officeid);
						$_SESSION['d2s8wu_officeabrv'] = trim($auth_officeabrv);
						$_SESSION['d2s8wu_officecode'] = trim($auth_officecode);
						$_SESSION['d2s8wu_xdel'] = trim($auth_xdel);
						$_SESSION['d2s8wu_createdby'] = trim($auth_createdby);
						$_SESSION['d2s8wu_modifiedby'] = trim($auth_modifiedby);
						$_SESSION['d2s8wu_modifiedat'] = trim($auth_modified_at);
						$_SESSION['d2s8wu_createdat'] = trim($auth_created_at);
					}

					// Auth Employee if Exist
					if (file_exists("model/employee/index.php")) {
						require_once "model/employee/index.php";
					} elseif (file_exists("../../model/employee/index.php")) {
						require_once "../../model/employee/index.php";
					}
					$emmplAcctInfo = new employeeAcct();
					if ( $emmplAcctInfo->authEmployee_usingUserID($_SESSION['d2s8wu_uid']) ) {
						$emmplAcctInfo->authEmployee_usingUserID($_SESSION['d2s8wu_uid']);

						for($i = 0; $i < count($emmplAcctInfo->list_empautoidee); $i++) {
							if ( $emmplAcctInfo->list_activatedee[$i] == 0 ) {
								echo '<div class="alert alert-danger alert-dismissible fade show">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'Your Account is Disabled!';
								echo '</div>';
							} elseif ( $emmplAcctInfo->list_verifiedee[$i] == 0 ) {
								$_SESSION["gpsinlocation"] = $gpsinlocationgg;

								$_SESSION["agencycode"] = $emmplAcctInfo->list_agencycodeee[$i];
								$_SESSION["agencyname"] = $emmplAcctInfo->list_agencynameee[$i];
								$_SESSION["profileid"] = $emmplAcctInfo->list_profileidee[$i];
								$_SESSION["uid"] = $emmplAcctInfo->list_uidee[$i];
								$_SESSION["empidcode"] = $emmplAcctInfo->list_empidcodeee[$i];
								$_SESSION["hrempid"] = $emmplAcctInfo->list_hrempidee[$i];
								$_SESSION["biolocation"] = $emmplAcctInfo->list_biolocationee[$i];
								$_SESSION["biono"] = $emmplAcctInfo->list_bionoee[$i];
								$_SESSION["empname"] = $emmplAcctInfo->list_empnameee[$i];
								$_SESSION["verified"] = $emmplAcctInfo->list_verifiedee[$i];
								$_SESSION["shiftstatus"] = $emmplAcctInfo->list_shiftstatusee[$i];
								$_SESSION["employeeactivated"] = $emmplAcctInfo->list_activatedee[$i];
								$_SESSION["worklocation"] = $emmplAcctInfo->list_worklocationee[$i];
								$_SESSION["timeeditable"] = $emmplAcctInfo->list_timeeditableee[$i];
								$_SESSION["timeeditablevalue"] = $emmplAcctInfo->list_timeeditablevalueee[$i];
								$_SESSION["prioritydtr"] = $emmplAcctInfo->list_prioritydtree[$i];
								$_SESSION["allowedot"] = $emmplAcctInfo->list_allowedotee[$i];
								$_SESSION["typeemployeeabrv"] = $emmplAcctInfo->list_typeemployeeabrvee[$i];
								$_SESSION["gender"] = $emmplAcctInfo->list_genderee[$i];
								$_SESSION["birthday"] = $emmplAcctInfo->list_birthdayee[$i];
								$_SESSION["empage"] = $emmplAcctInfo->list_empageee[$i];
								$_SESSION["officeid"] = $emmplAcctInfo->list_officeidee[$i];
								$_SESSION["officecode"] = $emmplAcctInfo->list_officecodeee[$i];
								$_SESSION["officename"] = $emmplAcctInfo->list_officenameee[$i];
								$_SESSION["officetitle"] = $emmplAcctInfo->list_officetitleee[$i];
								$_SESSION["officeabrv"] = $emmplAcctInfo->list_officeabrvee[$i];
								$_SESSION["oldofficeabrv"] = $emmplAcctInfo->list_oldofficeabrvee[$i];
								$_SESSION["officegpslocation"] = $emmplAcctInfo->list_officegpslocationee[$i];
								$_SESSION["headofficer"] = $emmplAcctInfo->list_headofficeree[$i];
								$_SESSION["headtitle"] = $emmplAcctInfo->list_headtitleee[$i];
								$_SESSION["authhead"] = $emmplAcctInfo->list_authheadee[$i];
								$_SESSION["authtitle"] = $emmplAcctInfo->list_authtitleee[$i];
								$_SESSION["authdescription"] = $emmplAcctInfo->list_authdescriptionee[$i];
								$_SESSION["yearemployed"] = $emmplAcctInfo->list_yearemployedee[$i];
								$_SESSION["yearcalc"] = $emmplAcctInfo->list_yearcalcee[$i];
								$_SESSION["typeemployeeno"] = $emmplAcctInfo->list_typeemployeenoee[$i];
								$_SESSION["typeemployee"] = $emmplAcctInfo->list_typeemployeeee[$i];
								$_SESSION["position"] = $emmplAcctInfo->list_positionee[$i];
								$_SESSION["designation"] = $emmplAcctInfo->list_designationee[$i];
								$_SESSION["mphone"] = $emmplAcctInfo->list_mphoneee[$i];
								$_SESSION["empemail"] = $emmplAcctInfo->list_empemailee[$i];
								$_SESSION["designationat"] = $emmplAcctInfo->list_designationatee[$i];

								echo '<div class="alert alert-warning alert-dismissible fade show">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'Your Account needs to be Verified!';
								echo '</div>';
								echo '<div><span>Go to My <a href="attendance" class="text-decoration-none">Attendance</a></span></div>';
							} else {
								$_SESSION["gpsinlocation"] = $gpsinlocation;

								$_SESSION["agencycode"] = $emmplAcctInfo->list_agencycodeee[$i];
								$_SESSION["agencyname"] = $emmplAcctInfo->list_agencynameee[$i];
								$_SESSION["profileid"] = $emmplAcctInfo->list_profileidee[$i];
								$_SESSION["uid"] = $emmplAcctInfo->list_uidee[$i];
								$_SESSION["empidcode"] = $emmplAcctInfo->list_empidcodeee[$i];
								$_SESSION["hrempid"] = $emmplAcctInfo->list_hrempidee[$i];
								$_SESSION["biolocation"] = $emmplAcctInfo->list_biolocationee[$i];
								$_SESSION["biono"] = $emmplAcctInfo->list_bionoee[$i];
								$_SESSION["empname"] = $emmplAcctInfo->list_empnameee[$i];
								$_SESSION["verified"] = $emmplAcctInfo->list_verifiedee[$i];
								$_SESSION["shiftstatus"] = $emmplAcctInfo->list_shiftstatusee[$i];
								$_SESSION["employeeactivated"] = $emmplAcctInfo->list_activatedee[$i];
								$_SESSION["worklocation"] = $emmplAcctInfo->list_worklocationee[$i];
								$_SESSION["timeeditable"] = $emmplAcctInfo->list_timeeditableee[$i];
								$_SESSION["timeeditablevalue"] = $emmplAcctInfo->list_timeeditablevalueee[$i];
								$_SESSION["prioritydtr"] = $emmplAcctInfo->list_prioritydtree[$i];
								$_SESSION["allowedot"] = $emmplAcctInfo->list_allowedotee[$i];
								$_SESSION["typeemployeeabrv"] = $emmplAcctInfo->list_typeemployeeabrvee[$i];
								$_SESSION["gender"] = $emmplAcctInfo->list_genderee[$i];
								$_SESSION["birthday"] = $emmplAcctInfo->list_birthdayee[$i];
								$_SESSION["empage"] = $emmplAcctInfo->list_empageee[$i];
								$_SESSION["officeid"] = $emmplAcctInfo->list_officeidee[$i];
								$_SESSION["officecode"] = $emmplAcctInfo->list_officecodeee[$i];
								$_SESSION["officename"] = $emmplAcctInfo->list_officenameee[$i];
								$_SESSION["officetitle"] = $emmplAcctInfo->list_officetitleee[$i];
								$_SESSION["officeabrv"] = $emmplAcctInfo->list_officeabrvee[$i];
								$_SESSION["oldofficeabrv"] = $emmplAcctInfo->list_oldofficeabrvee[$i];
								$_SESSION["officegpslocation"] = $emmplAcctInfo->list_officegpslocationee[$i];
								$_SESSION["headofficer"] = $emmplAcctInfo->list_headofficeree[$i];
								$_SESSION["headtitle"] = $emmplAcctInfo->list_headtitleee[$i];
								$_SESSION["authhead"] = $emmplAcctInfo->list_authheadee[$i];
								$_SESSION["authtitle"] = $emmplAcctInfo->list_authtitleee[$i];
								$_SESSION["authdescription"] = $emmplAcctInfo->list_authdescriptionee[$i];
								$_SESSION["yearemployed"] = $emmplAcctInfo->list_yearemployedee[$i];
								$_SESSION["yearcalc"] = $emmplAcctInfo->list_yearcalcee[$i];
								$_SESSION["typeemployeeno"] = $emmplAcctInfo->list_typeemployeenoee[$i];
								$_SESSION["typeemployee"] = $emmplAcctInfo->list_typeemployeeee[$i];
								$_SESSION["position"] = $emmplAcctInfo->list_positionee[$i];
								$_SESSION["designation"] = $emmplAcctInfo->list_designationee[$i];
								$_SESSION["mphone"] = $emmplAcctInfo->list_mphoneee[$i];
								$_SESSION["empemail"] = $emmplAcctInfo->list_empemailee[$i];
								$_SESSION["designationat"] = $emmplAcctInfo->list_designationatee[$i];

								echo '<div class="alert alert-info alert-dismissible fade show m-1">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'Access Granted! Employee Account is Logged-in as well.';
								echo '</div>';
								echo '<div><span>Go to My <a href="attendance" class="text-decoration-none">Attendance</a></span></div>';
							}
						}
					}

					if ( $_SESSION['d2s8wu_ulevel'] == 1 || $_SESSION['d2s8wu_ulevel'] == 2 || $_SESSION['d2s8wu_ulevel'] == 15 || $_SESSION['d2s8wu_ulevel'] == 16 || $_SESSION['d2s8wu_ulevel'] == 17 ) {
						echo '<div class="alert alert-info alert-dismissible fade show m-1">';
							echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
							echo 'Access Granted! You can now <a href="bp-mngr">Manage</a> the <span class="text-danger">System</span>.';
						echo '</div>';
						exit();
					} else {
						echo '<div class="alert alert-info alert-dismissible fade show m-1">';
							echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
							echo 'Access Granted! You can now <a href="employee-info">Manage</a> your <span class="text-primary">Account</span>.';
						echo '</div>';
						exit();
					}

				} else {
					echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'Access denied! Invalid Username or Password. <br>';
						echo 'Try again? <a href="login" class="text-decoration-none">Yes</a> | <a href="home" class="text-decoration-none">No</a>';
					echo '</div>';
					exit();
				}
			}
		}

	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error @ Sign-up: {$err_msg}</p>";
		die;
	}

?>
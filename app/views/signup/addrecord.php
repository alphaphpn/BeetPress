<?php 

	try {
		include_once "model/userAcct/index.php";
		include_once "model/profile/index.php";
		include_once "model/employee/index.php";
		$authAcctx = new authAcct();
		$profileAcctx = new clssProfile();
		$emplyAcctx = new employeeAcct();

		$pattern_phone = '/^[789][0-9]{9}$/';

		$the_agency_code = trim("PLGU-ZSP");
		$the_agency_name = trim("Provincial Government of Zamboanga Sibugay");

		$the_country = trim("Philippines");
		$the_country_code = 63;
		$the_country_id = trim("C1063");

		$the_ulevel = 12;
		$the_uposition = trim("User");

		$the_ulevel_empl = 14;
		$the_uposition_empl = trim("Employee");
		
		$the_province = trim("Zamboanga Sibugay");
		$the_province_code = trim("C1063030906");
		$the_region = trim("Zamboanga Peninsula");
		$the_region_code = trim("C10630309");
		$the_region_no = 9;
		$the_region_sign = trim("IX");

		$the_nationality = trim("Filipino");

		$createdby = trim("sadmin");
		$modifiedby = trim("sadmin");

		if ( isset($_POST["btnSubmit"]) ) {
			$reg_imgpic = isset($_POST["imgdata"]) ? $_POST["imgdata"] : null;
			$reg_zipcode = isset($_POST["zipcode"]) ? $_POST["zipcode"] : null;
			$reg_nickname = isset($_POST["nickname"]) ? $_POST["nickname"] : null;
			$reg_ntitle = isset($_POST["ptitle"]) ? $_POST["ptitle"] : null;
			$reg_fname = isset($_POST["fname"]) ? $_POST["fname"] : null;
			$reg_mname = isset($_POST["mname"]) ? $_POST["mname"] : null;
			$reg_lname = isset($_POST["lname"]) ? $_POST["lname"] : null;
			$reg_suffix = isset($_POST["nsuffix"]) ? $_POST["nsuffix"] : null;
			$reg_profession = isset($_POST["nprofession"]) ? $_POST["nprofession"] : null;
			$reg_gender = isset($_POST["genderOptions"]) ? $_POST["genderOptions"] : null;
			$reg_birthyear = isset($_POST["birth-year"]) ? $_POST["birth-year"] : null;
			$reg_birthmonth = isset($_POST["birth-month"]) ? $_POST["birth-month"] : null;
			$reg_birthday = isset($_POST["birth-day"]) ? $_POST["birth-day"] : null;
			$reg_plbirth = isset($_POST["pbirth"]) ? $_POST["pbirth"] : null;
			$reg_phone = isset($_POST["phone"]) ? $_POST["phone"] : null;
			$reg_phone2 = isset($_POST["phone2"]) ? $_POST["phone2"] : null;
			$reg_email = isset($_POST["email"]) ? $_POST["email"] : null;
			$reg_fbid = isset($_POST["fbid"]) ? $_POST["fbid"] : null;
			$reg_username = isset($_POST["nameuser"]) ? $_POST["nameuser"] : null;
			$reg_password = isset($_POST["password"]) ? $_POST["password"] : null;
			$reg_password2 = isset($_POST["password2"]) ? $_POST["password2"] : null;

			$reg_town = isset($_POST["town"]) ? $_POST["town"] : null;
			$reg_typeemployee = isset($_POST["type-employee"]) ? $_POST["type-employee"] : null;
			$reg_typeemployeeabrv = isset($_POST["type-employee-abrv"]) ? $_POST["type-employee-abrv"] : null;
			$reg_typeemployeelabel = isset($_POST["type-employee-label"]) ? $_POST["type-employee-label"] : null;
			$reg_designation = isset($_POST["designation"]) ? $_POST["designation"] : null;

			$reg_bioloclabel = isset($_POST["bioloclabel"]) ? $_POST["bioloclabel"] : null;

			$fullname = null;
			if ( empty(trim($reg_ntitle)) && empty(trim($reg_mname)) && empty(trim($reg_suffix)) && empty(trim($reg_profession)) ) {
				$fullname = trim(strtoupper($reg_fname))." ".trim(strtoupper($reg_lname));
			} elseif ( empty(trim($reg_ntitle)) && empty(trim($reg_suffix)) && empty(trim($reg_profession)) ) {
				$fullname = trim(strtoupper($reg_fname))." ".trim(strtoupper($reg_mname))." ".trim(strtoupper($reg_lname));
				$fullname_mi = trim(strtoupper($reg_fname))." ".trim(substr(strtoupper($reg_mname),0,1)).". ".trim(strtoupper($reg_lname));
			} elseif ( empty(trim($reg_ntitle)) && empty(trim($reg_profession)) ) {
				$fullname = trim(strtoupper($reg_fname))." ".trim(strtoupper($reg_mname))." ".trim(strtoupper($reg_lname)).", ".trim(strtoupper($reg_suffix));
				$fullname_mi = trim(strtoupper($reg_fname))." ".trim(substr(strtoupper($reg_mname),0,1)).". ".trim(strtoupper($reg_lname)).", ".trim(strtoupper($reg_suffix));
			} elseif ( empty(trim($reg_profession)) ) {
				$fullname = trim(strtoupper($reg_ntitle))." ".trim(strtoupper($reg_fname))." ".trim(strtoupper($reg_mname))." ".trim(strtoupper($reg_lname)).", ".trim(strtoupper($reg_suffix));
				$fullname_mi = trim(strtoupper($reg_ntitle))." ".trim(strtoupper($reg_fname))." ".trim(substr(strtoupper($reg_mname),0,1)).". ".trim(strtoupper($reg_lname)).", ".trim(strtoupper($reg_suffix));
			} else {
				$fullname = trim(strtoupper($reg_ntitle))." ".trim(strtoupper($reg_fname))." ".trim(strtoupper($reg_mname))." ".trim(strtoupper($reg_lname)).", ".trim(strtoupper($reg_suffix)).", ".trim(strtoupper($reg_profession));
				$fullname_mi = trim(strtoupper($reg_ntitle))." ".trim(strtoupper($reg_fname))." ".trim(substr(strtoupper($reg_mname),0,1)).". ".trim(strtoupper($reg_lname)).", ".trim(strtoupper($reg_suffix)).", ".trim(strtoupper($reg_profession));
			}

			if ( empty(trim($reg_imgpic)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please Take a Picture for your ID';
				echo '</div>';
			} elseif ( empty(trim($reg_zipcode)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'What Municipality are you Registered as Voter?';
				echo '</div>';
			} elseif ( empty(trim($reg_nickname)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Nickname / Alias.';
				echo '</div>';
			} elseif ( empty(trim($reg_fname)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Firstname.';
				echo '</div>';
			} elseif ( empty(trim($reg_lname)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Lastname.';
				echo '</div>';
			} elseif ( empty(trim($reg_birthyear)) ) {
				$reg_birthyear = trim(date('Y') - 18);
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Valid Birth Year.';
				echo '</div>';
			} elseif ( empty(trim($reg_birthmonth)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Birth Month.';
				echo '</div>';
			} elseif ( empty(trim($reg_birthday)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Birth Day.';
				echo '</div>';
			} elseif ( empty(trim($reg_plbirth)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Birth Place.';
				echo '</div>';
			} elseif ( empty(trim($reg_gender)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please select Gender.';
				echo '</div>';
			} elseif ( empty(trim($reg_username)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please Enter Username.';
				echo '</div>';
			} elseif ( empty(trim($reg_password)) || empty(trim($reg_password2)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please Enter Password and Re-Type Password.';
				echo '</div>';
			} elseif ( trim($reg_password) !== trim($reg_password2) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Password Mismatched!';
				echo '</div>';
			} else {
				// Generate User ID
				$usserIDme = trim(randomNumbr11());
				// Generate Profile ID
				$profilleIDme = trim(randomNumbr11());

				$birthdayme = strtotime(trim($reg_birthyear)."-".trim($reg_birthmonth)."-".trim($reg_birthday));

				if ( $profileAcctx->Search_clssProfile($profilleIDme) ) {
					echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'Profile ID already exist!';
					echo '</div>';
				} elseif ( $profileAcctx->duplicateExistProfile($reg_fname,$reg_lname,$reg_gender,$birthdayme,$reg_plbirth) ) {
					echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'Profile already exist!';
					echo '</div>';
				} else {
					if ( $reg_typeemployee || $reg_office || $reg_biolocation || $reg_bionumber || $reg_employeeid || $reg_pincode || $reg_pincode2 ) {
						if ( empty($reg_typeemployee) ) {
							echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
								echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
								echo 'Type of Empoyee is Required!.';
							echo '</div>';
						} elseif ( empty($reg_office) ) {
							echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
								echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
								echo 'Office is Required!.';
							echo '</div>';
						} elseif ( empty($reg_biolocation) ) {
							echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
								echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
								echo 'Biometric Location is Required!.';
							echo '</div>';
						} elseif ( empty($reg_bionumber) ) {
							echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
								echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
								echo 'Biometric Number is Required!.';
							echo '</div>';
						} elseif ( empty($reg_employeeid) ) {
							echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
								echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
								echo 'Employee ID Number is Required!.';
							echo '</div>';
						} elseif ( empty($reg_pincode) ) {
							echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
								echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
								echo 'PIN Number is Required!.';
							echo '</div>';
						} elseif ( empty($reg_pincode2) ) {
							echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
								echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
								echo 'Re-type PIN Number!.';
							echo '</div>';
						} elseif ( trim($reg_pincode2) !== trim($reg_pincode) ) {
							echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
								echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
								echo 'Mismatched PIN Number!.';
							echo '</div>';
						} else {
							// Search for Duplicate Employee Name, Employee ID Number and Biomatric Number

							// Search for Duplicate Employee Data
							if ( $emplyAcctx->Search_employeeAcct_EmployeeName($fullname) ) {
								echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'Employee Name already exist!';
								echo '</div>';
							} elseif ( $emplyAcctx->Search_employeeAcct_ID($reg_employeeid) ) {
								echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'Employee ID Number already exist!';
								echo '</div>';
							} elseif ( $emplyAcctx->Search_employeeAcct_BioNumber($biolocbrrr,$bionumbrrr) ) {
								echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'Biometric Number in Locatio ['.$biolocbrrr.'] already exist!';
								echo '</div>';
							} else {
								// Proceed
								// Add User, Profile and Employee

								if ( $reg_phone ) {
									if ( !preg_match($pattern_phone, $reg_phone) ) {
										echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
											echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
											echo 'Invalid phone number format. Please use XXX-XXX-XXXX.';
										echo '</div>';
									} else {
										if ( $authAcctx->Search_userAcct_phone($reg_phone) ) {
											echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
												echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
												echo 'Primary Phone already exist!';
											echo '</div>';
										} else {
											// Proceed
											$msg = "Phone Valid";
											$authAcctx->insert_userAcct($the_agency_code,$the_agency_name,$usserIDme,$profilleIDme,$reg_username,$reg_nickname,$reg_password2,$the_country,$the_country_code,$reg_zipcode,$reg_phone,$reg_email,0,0,$the_ulevel_empl,$the_uposition_empl,$reg_imgpic,$createdby,$modifiedby,$msg);
											$profileAcctx->insert_clssProfile($profilleIDme,$reg_nickname,$reg_ntitle,$reg_fname,$reg_mname,$reg_lname,$reg_suffix,$reg_gender,$birthdayme,$reg_plbirth,$the_nationality,'','',$reg_email,$reg_imgpic,$reg_phone,$reg_phone2,$reg_fbid,'','','','','','',$reg_town,$reg_zipcode,'','',$the_province_code,$the_province,$the_region_code,$the_region_no,$the_region_sign,$the_region,$the_country_id,$the_country_code,$the_country,$reg_zipcode,'',$createdby,$modifiedby);

										}
									}
								} elseif ( $reg_phone2 ) {
									if ( !preg_match($pattern_phone, $reg_phone2) ) {
										echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
											echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
											echo 'Invalid phone number format. Please use XXX-XXX-XXXX.';
										echo '</div>';
									} else {
										// Proceed
										$msg = "2nd Phone Valid";
										$authAcctx->insert_userAcct($the_agency_code,$the_agency_name,$usserIDme,$profilleIDme,$reg_username,$reg_nickname,$reg_password2,$the_country,$the_country_code,$reg_zipcode,$reg_phone,$reg_email,0,0,$the_ulevel_empl,$the_uposition_empl,$reg_imgpic,$createdby,$modifiedby,$msg);
										$profileAcctx->insert_clssProfile($profilleIDme,$reg_nickname,$reg_ntitle,$reg_fname,$reg_mname,$reg_lname,$reg_suffix,$reg_gender,$birthdayme,$reg_plbirth,$the_nationality,'','',$reg_email,$reg_imgpic,$reg_phone,$reg_phone2,$reg_fbid,'','','','','','',$reg_town,$reg_zipcode,'','',$the_province_code,$the_province,$the_region_code,$the_region_no,$the_region_sign,$the_region,$the_country_id,$the_country_code,$the_country,$reg_zipcode,'',$createdby,$modifiedby);
									}
								} elseif ( $reg_email ) {
									if ( filter_var($reg_email, FILTER_VALIDATE_EMAIL) ) {
										if ( $authAcctx->Search_userAcct_uemail($reg_email) ) {
											echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
												echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
												echo 'E-Mail already exist!';
											echo '</div>';
										} else {
											// Proceed
											$msg = "Email Valid";
											$authAcctx->insert_userAcct($the_agency_code,$the_agency_name,$usserIDme,$profilleIDme,$reg_username,$reg_nickname,$reg_password2,$the_country,$the_country_code,$reg_zipcode,$reg_phone,$reg_email,0,0,$the_ulevel_empl,$the_uposition_empl,$reg_imgpic,$createdby,$modifiedby,$msg);
											$profileAcctx->insert_clssProfile($profilleIDme,$reg_nickname,$reg_ntitle,$reg_fname,$reg_mname,$reg_lname,$reg_suffix,$reg_gender,$birthdayme,$reg_plbirth,$the_nationality,'','',$reg_email,$reg_imgpic,$reg_phone,$reg_phone2,$reg_fbid,'','','','','','',$reg_town,$reg_zipcode,'','',$the_province_code,$the_province,$the_region_code,$the_region_no,$the_region_sign,$the_region,$the_country_id,$the_country_code,$the_country,$reg_zipcode,'',$createdby,$modifiedby);
										}
									} else {
										echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
											echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
											echo 'Invalid E-Mail!';
										echo '</div>';
									}
								} elseif ( $authAcctx->Search_userAcct_username($reg_username) ) {
									echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
										echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
										echo 'Username already exist!';
									echo '</div>';
								} else {
									// Proceed
									$msg = "All Valid";
									$authAcctx->insert_userAcct($the_agency_code,$the_agency_name,$usserIDme,$profilleIDme,$reg_username,$reg_nickname,$reg_password2,$the_country,$the_country_code,$reg_zipcode,$reg_phone,$reg_email,0,0,$the_ulevel_empl,$the_uposition_empl,$reg_imgpic,$createdby,$modifiedby,$msg);
									$profileAcctx->insert_clssProfile($profilleIDme,$reg_nickname,$reg_ntitle,$reg_fname,$reg_mname,$reg_lname,$reg_suffix,$reg_gender,$birthdayme,$reg_plbirth,$the_nationality,'','',$reg_email,$reg_imgpic,$reg_phone,$reg_phone2,$reg_fbid,'','','','','','',$reg_town,$reg_zipcode,'','',$the_province_code,$the_province,$the_region_code,$the_region_no,$the_region_sign,$the_region,$the_country_id,$the_country_code,$the_country,$reg_zipcode,'',$createdby,$modifiedby);
									$emplyAcctx->insert_Employee($the_agency_code,$the_agency_name,$reg_nickname,$fullname_mi,$officenameforid,$reg_designation,$profilleIDme,$usserIDme,$reg_employeeid,$pinword,$reg_employeeid,$reg_biolocation,$reg_bionumber,$fullname,$reg_gender,$birthdayme,$officeid,$officecode,$officename,$officetitle,$officeabrv,$oldofficeabrv,$officegpslocation,$headofficer,$headtitle,$authhead,$authtitle,$authdescription,$yearemployed,$reg_typeemployee,$reg_typeemployeeabrv,$reg_typeemployeelabel,0,1,$worklocation,0,0,0,3,0,$reg_designation,$reg_designation,$reg_phone,$reg_email,$designationat,$createdby,$modifiedby);
								}
							}
						}
					} else {
						// Proceed
						// Add User and Profile only

						if ( $reg_phone ) {
							if ( !preg_match($pattern_phone, $reg_phone) ) {
								echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'Invalid phone number format. Please use XXX-XXX-XXXX.';
								echo '</div>';
							} else {
								if ( $authAcctx->Search_userAcct_phone($reg_phone) ) {
									echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
										echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
										echo 'Primary Phone already exist!';
									echo '</div>';
								} else {
									// Proceed
									$msg = "Phone Valid";
									$authAcctx->insert_userAcct($the_agency_code,$the_agency_name,$usserIDme,$profilleIDme,$reg_username,$reg_nickname,$reg_password2,$the_country,$the_country_code,$reg_zipcode,$reg_phone,$reg_email,0,0,$the_ulevel,$the_uposition,$reg_imgpic,$createdby,$modifiedby,$msg);
									$profileAcctx->insert_clssProfile($profilleIDme,$reg_nickname,$reg_ntitle,$reg_fname,$reg_mname,$reg_lname,$reg_suffix,$reg_gender,$birthdayme,$reg_plbirth,$the_nationality,'','',$reg_email,$reg_imgpic,$reg_phone,$reg_phone2,$reg_fbid,'','','','','','',$reg_town,$reg_zipcode,'','',$the_province_code,$the_province,$the_region_code,$the_region_no,$the_region_sign,$the_region,$the_country_id,$the_country_code,$the_country,$reg_zipcode,'',$createdby,$modifiedby);

								}
							}
						} elseif ( $reg_phone2 ) {
							if ( !preg_match($pattern_phone, $reg_phone2) ) {
								echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'Invalid phone number format. Please use XXX-XXX-XXXX.';
								echo '</div>';
							} else {
								// Proceed
								$msg = "2nd Phone Valid";
								$authAcctx->insert_userAcct($the_agency_code,$the_agency_name,$usserIDme,$profilleIDme,$reg_username,$reg_nickname,$reg_password2,$the_country,$the_country_code,$reg_zipcode,$reg_phone,$reg_email,0,0,$the_ulevel,$the_uposition,$reg_imgpic,$createdby,$modifiedby,$msg);
								$profileAcctx->insert_clssProfile($profilleIDme,$reg_nickname,$reg_ntitle,$reg_fname,$reg_mname,$reg_lname,$reg_suffix,$reg_gender,$birthdayme,$reg_plbirth,$the_nationality,'','',$reg_email,$reg_imgpic,$reg_phone,$reg_phone2,$reg_fbid,'','','','','','',$reg_town,$reg_zipcode,'','',$the_province_code,$the_province,$the_region_code,$the_region_no,$the_region_sign,$the_region,$the_country_id,$the_country_code,$the_country,$reg_zipcode,'',$createdby,$modifiedby);
							}
						} elseif ( $reg_email ) {
							if ( filter_var($reg_email, FILTER_VALIDATE_EMAIL) ) {
								if ( $authAcctx->Search_userAcct_uemail($reg_email) ) {
									echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
										echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
										echo 'E-Mail already exist!';
									echo '</div>';
								} else {
									// Proceed
									$msg = "Email Valid";
									$authAcctx->insert_userAcct($the_agency_code,$the_agency_name,$usserIDme,$profilleIDme,$reg_username,$reg_nickname,$reg_password2,$the_country,$the_country_code,$reg_zipcode,$reg_phone,$reg_email,0,0,$the_ulevel,$the_uposition,$reg_imgpic,$createdby,$modifiedby,$msg);
									$profileAcctx->insert_clssProfile($profilleIDme,$reg_nickname,$reg_ntitle,$reg_fname,$reg_mname,$reg_lname,$reg_suffix,$reg_gender,$birthdayme,$reg_plbirth,$the_nationality,'','',$reg_email,$reg_imgpic,$reg_phone,$reg_phone2,$reg_fbid,'','','','','','',$reg_town,$reg_zipcode,'','',$the_province_code,$the_province,$the_region_code,$the_region_no,$the_region_sign,$the_region,$the_country_id,$the_country_code,$the_country,$reg_zipcode,'',$createdby,$modifiedby);
								}
							} else {
								echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'Invalid E-Mail!';
								echo '</div>';
							}
						} elseif ( $authAcctx->Search_userAcct_username($reg_username) ) {
							echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
								echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
								echo 'Username already exist!';
							echo '</div>';
						} else {
							// Proceed
							$msg = "All Valid";
							$authAcctx->insert_userAcct($the_agency_code,$the_agency_name,$usserIDme,$profilleIDme,$reg_username,$reg_nickname,$reg_password2,$the_country,$the_country_code,$reg_zipcode,$reg_phone,$reg_email,0,0,$the_ulevel,$the_uposition,$reg_imgpic,$createdby,$modifiedby,$msg);
							$profileAcctx->insert_clssProfile($profilleIDme,$reg_nickname,$reg_ntitle,$reg_fname,$reg_mname,$reg_lname,$reg_suffix,$reg_gender,$birthdayme,$reg_plbirth,$the_nationality,'','',$reg_email,$reg_imgpic,$reg_phone,$reg_phone2,$reg_fbid,'','','','','','',$reg_town,$reg_zipcode,'','',$the_province_code,$the_province,$the_region_code,$the_region_no,$the_region_sign,$the_region,$the_country_id,$the_country_code,$the_country,$reg_zipcode,'',$createdby,$modifiedby);
						}
					}
				}
			}
		}
	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error @ Sign-up: {$err_msg}</p>";
		die;
	}

?>
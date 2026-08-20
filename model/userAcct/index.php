<?php 

	try {

		if (file_exists("lib/cnn.php")) {
			require_once "lib/cnn.php";
		} elseif (file_exists("../../lib/cnn.php")) {
			require_once "../../lib/cnn.php";
		}

		class authAcct extends myDatabase {

			Public $authidgg,
				$agencycodegg,
				$agencynamegg,
				$uidgg,
				$profileidgg,
				$unamegg,
				$nicknamegg,
				$pwordgg,
				$countrygg,
				$countrycodegg,
				$zipcodegg,
				$phonegg,
				$emailgg,
				$birthdategg,
				$verifiedgg,
				$ustatgg,
				$ulevelgg,
				$upositiongg,
				$onofflinegg,
				$securequestiongg,
				$secureanswergg,
				$officeidgg,
				$officeabrvgg,
				$officecodegg,
				$xdelgg,
				$createdbygg,
				$modifiedbygg,
				$modifiedatgg,
				$createdatgg;

			Public $list_authidgg,
				$list_agencycodegg,
				$list_agencynamegg,
				$list_uidgg,
				$list_profileidgg,
				$list_unamegg,
				$list_nicknamegg,
				$list_pwordgg,
				$list_countrygg,
				$list_countrycodegg,
				$list_zipcodegg,
				$list_phonegg,
				$list_emailgg,
				$list_birthdategg,
				$list_verifiedgg,
				$list_ustatgg,
				$list_ulevelgg,
				$list_upositiongg,
				$list_onofflinegg,
				$list_securequestiongg,
				$list_secureanswergg,
				$list_officeidgg,
				$list_officeabrvgg,
				$list_officecodegg,
				$list_xdelgg,
				$list_createdbygg,
				$list_modifiedbygg,
				$list_modifiedatgg,
				$list_createdatgg;

			public function __construct() {
				$this->list_authidgg = array();
				$this->list_agencycodegg = array();
				$this->list_agencynamegg = array();
				$this->list_uidgg = array();
				$this->list_profileidgg = array();
				$this->list_unamegg = array();
				$this->list_nicknamegg = array();
				$this->list_pwordgg = array();
				$this->list_countrygg = array();
				$this->list_countrycodegg = array();
				$this->list_zipcodegg = array();
				$this->list_phonegg = array();
				$this->list_emailgg = array();
				$this->list_birthdategg = array();
				$this->list_verifiedgg = array();
				$this->list_ustatgg = array();
				$this->list_ulevelgg = array();
				$this->list_upositiongg = array();
				$this->list_onofflinegg = array();
				$this->list_securequestiongg = array();
				$this->list_secureanswergg = array();
				$this->list_officeidgg = array();
				$this->list_officeabrvgg = array();
				$this->list_officecodegg = array();
				$this->list_xdelgg = array();
				$this->list_createdbygg = array();
				$this->list_modifiedbygg = array();
				$this->list_modifiedatgg = array();
				$this->list_createdatgg = array();
			}

			public function clearlist_userAcct() {
				$this->list_authidgg = array();
				$this->list_agencycodegg = array();
				$this->list_agencynamegg = array();
				$this->list_uidgg = array();
				$this->list_profileidgg = array();
				$this->list_unamegg = array();
				$this->list_nicknamegg = array();
				$this->list_pwordgg = array();
				$this->list_countrygg = array();
				$this->list_countrycodegg = array();
				$this->list_zipcodegg = array();
				$this->list_phonegg = array();
				$this->list_emailgg = array();
				$this->list_birthdategg = array();
				$this->list_verifiedgg = array();
				$this->list_ustatgg = array();
				$this->list_ulevelgg = array();
				$this->list_upositiongg = array();
				$this->list_onofflinegg = array();
				$this->list_securequestiongg = array();
				$this->list_secureanswergg = array();
				$this->list_officeidgg = array();
				$this->list_officeabrvgg = array();
				$this->list_officecodegg = array();
				$this->list_xdelgg = array();
				$this->list_createdbygg = array();
				$this->list_modifiedbygg = array();
				$this->list_modifiedatgg = array();
				$this->list_createdatgg = array();
			}

			// Array of data values Memory single variable base on Database Table Fieldnames
			public function loadrecord_userAcct() {
				$this->clearlist_userAcct();
				$this->getConnection();
				$selectQuery = "SELECT * FROM user_tbl";
				$stmt = $this->cnn->prepare($selectQuery);

				$stmt->execute();

				$data = array();

				for ($i=0; $row = $stmt->fetch(); $i++) {
					$data = $row;
				}

				return $data;
			}

			// Return Count Record base on Database Table Fieldnames
			public function count_userAcct() {
				$this->clearlist_userAcct();
				$this->getConnection();
				$selectQuery = "SELECT * FROM user_tbl";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();
				$cntRcrd = $stmt->rowCount();

				return $cntRcrd;
			}

			// Authenticate User Account
			public function authenticate_userAcct($userid,$userpw) {
				if ( empty(trim($userid)) ) {
					echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'User ID is required!';
					echo '</div>';
				} elseif ( empty(trim($userpw)) ) {
					echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'Password is required!';
					echo '</div>';
				} else {
					$this->clearlist_userAcct();
					$this->getConnection();
					$auth_userid = trim($userid);
					$auth_userpw = trim(md5(trim($userpw)));

					$selectQuery = "SELECT * FROM user_tbl WHERE TRIM(uname)=:authuserid AND TRIM(pword)=:authuserpw LIMIT 1";
					$stmt = $this->cnn->prepare($selectQuery);
					$stmt->bindParam(':authuserid', $auth_userid);
					$stmt->bindParam(':authuserpw', $auth_userpw);
					$stmt->execute();

					$cntRcrd = $stmt->rowCount();

					if ($cntRcrd > 0) {
						// Record Found
						foreach ($stmt as $rwRcrd) {
							$this->list_authidgg[] = $rwRcrd['authid'];
							$this->list_agencycodegg[] = $rwRcrd['agency_code'];
							$this->list_agencynamegg[] = $rwRcrd['agency_name'];
							$this->list_uidgg[] = $rwRcrd['uid'];
							$this->list_profileidgg[] = $rwRcrd['profileid'];
							$this->list_unamegg[] = $rwRcrd['uname'];
							$this->list_nicknamegg[] = $rwRcrd['nickname'];
							$this->list_pwordgg[] = $rwRcrd['pword'];
							$this->list_countrygg[] = $rwRcrd['country'];
							$this->list_countrycodegg[] = $rwRcrd['countrycode'];
							$this->list_zipcodegg[] = $rwRcrd['zipcode'];
							$this->list_phonegg[] = $rwRcrd['phone'];
							$this->list_emailgg[] = $rwRcrd['email'];
							$this->list_birthdategg[] = $rwRcrd['birthdate'];
							$this->list_verifiedgg[] = $rwRcrd['verified'];
							$this->list_ustatgg[] = $rwRcrd['ustat'];
							$this->list_ulevelgg[] = $rwRcrd['ulevel'];
							$this->list_upositiongg[] = $rwRcrd['uposition'];
							$this->list_onofflinegg[] = $rwRcrd['onoffline'];
							$this->list_securequestiongg[] = $rwRcrd['secure_question'];
							$this->list_secureanswergg[] = $rwRcrd['secure_answer'];
							$this->list_officeidgg[] = $rwRcrd['officeid'];
							$this->list_officeabrvgg[] = $rwRcrd['officeabrv'];
							$this->list_officecodegg[] = $rwRcrd['officecode'];
							$this->list_xdelgg[] = $rwRcrd['xdel'];
							$this->list_createdbygg[] = $rwRcrd['createdby'];
							$this->list_modifiedbygg[] = $rwRcrd['modifiedby'];
							$this->list_modifiedatgg[] = $rwRcrd['modified_at'];
							$this->list_createdatgg[] = $rwRcrd['created_at'];
						}

						return true;
					} else {
						// Record Not Found
						return false;
					}
				}
			}

			// Searching for Duplicate UserID
			public function Search_userAcct_userid($userid) {
				$this->clearlist_userAcct();
				$this->getConnection();
				$userid = trim($userid);

				$selectQuery = "SELECT * FROM user_tbl WHERE uid=:userid";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':userid', $userid);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					return true;
					// Record Found
				} else {
					return false;
					// Record Not Found
				}
			}

			// Searching for Duplicate Username
			public function Search_userAcct_username($username) {
				$this->clearlist_userAcct();
				$this->getConnection();
				$username = trim($username);

				$selectQuery = "SELECT * FROM user_tbl WHERE uname=:username";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':username', $username);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					return true;
					// Record Found
				} else {
					return false;
					// Record Not Found
				}
			}

			// Searching for Duplicate Phone
			public function Search_userAcct_phone($uphone) {
				$this->clearlist_userAcct();
				$this->getConnection();
				$uphone = trim($uphone);

				$selectQuery = "SELECT * FROM user_tbl WHERE phone=:uphone";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':uphone', $uphone);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					return true;
					// Record Found
				} else {
					return false;
					// Record Not Found
				}
			}

			// Searching for Duplicate Email
			public function Search_userAcct_uemail($uemail) {
				$this->clearlist_userAcct();
				$this->getConnection();
				$uemail = trim($uemail);

				$selectQuery = "SELECT * FROM user_tbl WHERE email=:uemail LIMIT 1";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':uemail', $uemail);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					return true;
					// Record Found
				} else {
					return false;
					// Record Not Found
				}
			}

			// Searching for Duplicate UserID and ProfileID
			public function Search_userAcct_UserIDProfileID($userid,$profileid) {
				$this->clearlist_userAcct();
				$this->getConnection();
				$userid = trim($userid);
				$profileid = trim($profileid);

				$selectQuery = "SELECT * FROM user_tbl WHERE uid=:userid AND profileid=:profileid";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':userid', $userid);
				$stmt->bindParam(':profileid', $profileid);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					return true;
					// Record Found
				} else {
					return false;
					// Record Not Found
				}
			}

			// Searching for Duplicate Username and ProfileID
			public function Search_userAcct_UserNameProfileID($uname,$profileid) {
				$this->clearlist_userAcct();
				$this->getConnection();
				$uname = trim($uname);
				$profileid = trim($profileid);

				$selectQuery = "SELECT * FROM user_tbl WHERE uid=:uname AND profileid=:profileid";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':uname', $uname);
				$stmt->bindParam(':profileid', $profileid);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					return true;
					// Record Found
				} else {
					return false;
					// Record Not Found
				}
			}

			// Searching for Duplicate UserID and Username
			public function Search_userAcct_UserIDUserName($unameid,$uname) {
				$this->clearlist_userAcct();
				$this->getConnection();
				$unameid = trim($unameid);
				$uname = trim($uname);

				$selectQuery = "SELECT * FROM user_tbl WHERE uid=:unameid AND uname=:uname";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':unameid', $unameid);
				$stmt->bindParam(':uname', $uname);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					return true;
					// Record Found
				} else {
					return false;
					// Record Not Found
				}
			}

			// Searching for Duplicate UserID and Username and ProfileID
			public function Search_userAcct_UIDNameProfID($unameid,$uname,$profileid) {
				$this->clearlist_userAcct();
				$this->getConnection();
				$unameid = trim($unameid);
				$uname = trim($uname);
				$profileid = trim($profileid);

				$selectQuery = "SELECT * FROM user_tbl WHERE uid=:unameid AND uname=:uname AND profileid=:profileid";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':unameid', $unameid);
				$stmt->bindParam(':uname', $uname);
				$stmt->bindParam(':profileid', $profileid);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					return true;
					// Record Found
				} else {
					return false;
					// Record Not Found
				}
			}

			// Create or insert new data on the Database Table
			public function insert_userAcct($agencycodegg,$agencynamegg,$uidgg,$profileidgg,$unamegg,$nicknamegg,$pwordgg,$countrygg,$countrycodegg,$zipcodegg,$phonegg,$emailgg,$verifiedgg,$ustatgg,$ulevelgg,$upositiongg,$imgdata,$createdby,$modifiedby,$msg,$birthdategg) {
				$this->clearlist_userAcct();
				$this->getConnection();

				$agencycodegg = trim($agencycodegg);
				$agencynamegg = trim($agencynamegg);
				$uidgg = trim($uidgg);
				$profileidgg = trim($profileidgg);
				$unamegg = trim($unamegg);
				$nicknamegg = trim($nicknamegg);
				$pwordgg = trim(md5(trim($pwordgg)));
				$countrygg = trim($countrygg);
				$countrycodegg = trim($countrycodegg);
				$zipcodegg = trim($zipcodegg);
				$phonegg = trim($phonegg);
				$emailgg = trim($emailgg);
				$birthdategg = date('Y-m-d', $birthdategg);
				$verifiedgg = trim($verifiedgg);
				$ustatgg = trim($ustatgg);
				$ulevelgg = trim($ulevelgg);
				$upositiongg = trim($upositiongg);
				$imgdatagg = trim($imgdata);
				$createdby = trim($createdby);
				$modifiedby = trim($modifiedby);

				if ( empty(trim($uidgg)) ) {
					echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'User ID is required!';
					echo '</div>';
				} elseif ( empty(trim($profileidgg)) ) {
					echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'Profile ID is required!';
					echo '</div>';
				} elseif ( empty(trim($unamegg)) ) {
					echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'Username is required!';
					echo '</div>';
				} else {
					$finddupQry_uid = "SELECT * FROM user_tbl WHERE uid=:uid LIMIT 1";
					$stmt_finddupQry_uid = $this->cnn->prepare($finddupQry_uid);
					$stmt_finddupQry_uid->bindParam(':uid', $uidgg);
					$stmt_finddupQry_uid->execute();

					$cntRcrd_finddupQry_uid = $stmt_finddupQry_uid->rowCount();
					if ($cntRcrd_finddupQry_uid > 0) {
						echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
							echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
							echo 'User ID already exist!';
						echo '</div>';
					} else {
						$finddupQry_usern = "SELECT * FROM user_tbl WHERE uname=:uname LIMIT 1";
						$stmt_finddupQry_usern = $this->cnn->prepare($finddupQry_usern);
						$stmt_finddupQry_usern->bindParam(':uname', $unamegg);
						$stmt_finddupQry_usern->execute();

						$cntRcrd_finddupQry_usern = $stmt_finddupQry_usern->rowCount();
						if ($cntRcrd_finddupQry_usern > 0) {
							echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
								echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
								echo 'Username already exist!';
							echo '</div>';
						} else {
							if ( $phonegg ) {
								$pattern_phone = '/^[789][0-9]{9}$/';
								if ( !preg_match($pattern_phone, $phonegg) ) {
									echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
										echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
										echo 'Invalid phone number format. Please use XXX-XXX-XXXX.';
									echo '</div>';
								} else {
									$finddupQry_phone = "SELECT * FROM user_tbl WHERE phone=:phone LIMIT 1";
									$stmt_finddupQry_phone = $this->cnn->prepare($finddupQry_phone);
									$stmt_finddupQry_phone->bindParam(':phone', $phonegg);
									$stmt_finddupQry_phone->execute();
									$cntRcrd_finddupQry_phone = $stmt_finddupQry_phone->rowCount();
									if ($cntRcrd_finddupQry_phone > 0) {
										echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
											echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
											echo 'Phone already exist!';
										echo '</div>';
									} else {
										// Add Record
										$insertQuery = "INSERT INTO user_tbl SET 
											agency_code=:agencycode, 
											agency_name=:agencyname, 
											uid=:uid, 
											profileid=:profileid, 
											uname=:uname, 
											nickname=:nickname, 
											pword=:pword, 
											country=:country, 
											countrycode=:countrycode, 
											zipcode=:zipcode, 
											phone=:phone, 
											email=:email, 
											birthdate=:birthdate, 
											verified=:verified, 
											ustat=:ustat, 
											ulevel=:ulevel, 
											uposition=:uposition,
											createdby=:createdby,
											modifiedby=:modifiedby
											";
										$stmt = $this->cnn->prepare($insertQuery);
										$stmt->bindParam(':agencycode', $agencycodegg);
										$stmt->bindParam(':agencyname', $agencynamegg);
										$stmt->bindParam(':uid', $uidgg);
										$stmt->bindParam(':profileid', $profileidgg);
										$stmt->bindParam(':uname', $unamegg);
										$stmt->bindParam(':nickname', $nicknamegg);
										$stmt->bindParam(':pword', $pwordgg);
										$stmt->bindParam(':country', $countrygg);
										$stmt->bindParam(':countrycode', $countrycodegg);
										$stmt->bindParam(':zipcode', $zipcodegg);
										$stmt->bindParam(':phone', $phonegg);
										$stmt->bindParam(':email', $emailgg);
										$stmt->bindParam(':birthdate', $birthdategg);
										$stmt->bindParam(':verified', $verifiedgg);
										$stmt->bindParam(':ustat', $ustatgg);
										$stmt->bindParam(':ulevel', $ulevelgg);
										$stmt->bindParam(':uposition', $upositiongg);
										$stmt->bindParam(':createdby', $createdby);
										$stmt->bindParam(':modifiedby', $modifiedby);
										$stmt->execute();

										echo '<div class="alert alert-info alert-dismissible fade show m-1">';
											echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
											echo 'User Successfully Registered. ['.$msg.'] You may <a href="login">Login</a> Now!';
										echo '</div>';

										$useridfinale = trim($uidgg);
										$imgdata = trim($imgdatagg);
										if (file_exists("lib/user-img-saved.php")) {
											require_once "lib/user-img-saved.php";
										} elseif (file_exists("../../lib/user-img-saved.php")) {
											require_once "../../lib/user-img-saved.php";
										}
									}
								}
							} elseif ( $emailgg ) {
								if (filter_var($emailgg, FILTER_VALIDATE_EMAIL)) {
									// Valid Email
									// Search for Duplicate Email
									$finddupQry_email = "SELECT * FROM user_tbl WHERE email=:email LIMIT 1";
									$stmt_finddupQry_email = $this->cnn->prepare($finddupQry_email);
									$stmt_finddupQry_email->bindParam(':email', $emailgg);
									$stmt_finddupQry_email->execute();
									$cntRcrd_finddupQry_email = $stmt_finddupQry_email->rowCount();
									if ($cntRcrd_finddupQry_email > 0) {
										echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
											echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
											echo 'Email already exist!';
										echo '</div>';
									} else {
										// Add Record
										$insertQuery = "INSERT INTO user_tbl SET 
											agency_code=:agencycode, 
											agency_name=:agencyname, 
											uid=:uid, 
											profileid=:profileid, 
											uname=:uname, 
											nickname=:nickname, 
											pword=:pword, 
											country=:country, 
											countrycode=:countrycode, 
											zipcode=:zipcode, 
											phone=:phone, 
											email=:email, 
											birthdate=:birthdate, 
											verified=:verified, 
											ustat=:ustat, 
											ulevel=:ulevel, 
											uposition=:uposition,
											createdby=:createdby,
											modifiedby=:modifiedby
											";
										$stmt = $this->cnn->prepare($insertQuery);
										$stmt->bindParam(':agencycode', $agencycodegg);
										$stmt->bindParam(':agencyname', $agencynamegg);
										$stmt->bindParam(':uid', $uidgg);
										$stmt->bindParam(':profileid', $profileidgg);
										$stmt->bindParam(':uname', $unamegg);
										$stmt->bindParam(':nickname', $nicknamegg);
										$stmt->bindParam(':pword', $pwordgg);
										$stmt->bindParam(':country', $countrygg);
										$stmt->bindParam(':countrycode', $countrycodegg);
										$stmt->bindParam(':zipcode', $zipcodegg);
										$stmt->bindParam(':phone', $phonegg);
										$stmt->bindParam(':email', $emailgg);
										$stmt->bindParam(':birthdate', $birthdategg);
										$stmt->bindParam(':verified', $verifiedgg);
										$stmt->bindParam(':ustat', $ustatgg);
										$stmt->bindParam(':ulevel', $ulevelgg);
										$stmt->bindParam(':uposition', $upositiongg);
										$stmt->bindParam(':createdby', $createdby);
										$stmt->bindParam(':modifiedby', $modifiedby);
										$stmt->execute();

										echo '<div class="alert alert-info alert-dismissible fade show m-1">';
											echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
											echo 'User Successfully Registered. ['.$msg.'] You may <a href="login">Login</a> Now!';
										echo '</div>';

										$useridfinale = trim($uidgg);
										$imgdata = trim($imgdatagg);
										if (file_exists("lib/user-img-saved.php")) {
											require_once "lib/user-img-saved.php";
										} elseif (file_exists("../../lib/user-img-saved.php")) {
											require_once "../../lib/user-img-saved.php";
										}
									}
								} else {
									// InValid Email
									echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
										echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
										echo 'InValid Emailt!';
									echo '</div>';
								}
							} else {
								// Add Record
								$insertQuery = "INSERT INTO user_tbl SET 
									agency_code=:agencycode, 
									agency_name=:agencyname, 
									uid=:uid, 
									profileid=:profileid, 
									uname=:uname, 
									nickname=:nickname, 
									pword=:pword, 
									country=:country, 
									countrycode=:countrycode, 
									zipcode=:zipcode, 
									phone=:phone, 
									email=:email, 
									birthdate=:birthdate, 
									verified=:verified, 
									ustat=:ustat, 
									ulevel=:ulevel, 
									uposition=:uposition,
									createdby=:createdby,
									modifiedby=:modifiedby
									";
								$stmt = $this->cnn->prepare($insertQuery);
								$stmt->bindParam(':agencycode', $agencycodegg);
								$stmt->bindParam(':agencyname', $agencynamegg);
								$stmt->bindParam(':uid', $uidgg);
								$stmt->bindParam(':profileid', $profileidgg);
								$stmt->bindParam(':uname', $unamegg);
								$stmt->bindParam(':nickname', $nicknamegg);
								$stmt->bindParam(':pword', $pwordgg);
								$stmt->bindParam(':country', $countrygg);
								$stmt->bindParam(':countrycode', $countrycodegg);
								$stmt->bindParam(':zipcode', $zipcodegg);
								$stmt->bindParam(':phone', $phonegg);
								$stmt->bindParam(':email', $emailgg);
								$stmt->bindParam(':birthdate', $birthdategg);
								$stmt->bindParam(':verified', $verifiedgg);
								$stmt->bindParam(':ustat', $ustatgg);
								$stmt->bindParam(':ulevel', $ulevelgg);
								$stmt->bindParam(':uposition', $upositiongg);
								$stmt->bindParam(':createdby', $createdby);
								$stmt->bindParam(':modifiedby', $modifiedby);
								$stmt->execute();

								echo '<div class="alert alert-info alert-dismissible fade show m-1">';
									echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
									echo 'User Successfully Registered. ['.$msg.'] You may <a href="login">Login</a> Now!';
								echo '</div>';

								$useridfinale = trim($uidgg);
								$imgdata = trim($imgdatagg);
								if (file_exists("lib/user-img-saved.php")) {
									require_once "lib/user-img-saved.php";
								} elseif (file_exists("../../lib/user-img-saved.php")) {
									require_once "../../lib/user-img-saved.php";
								}
							}
						}
					}
				}
			}

			// List of User and their Names
			public function list_userAccts() {
				$this->clearlist_userAcct();
				$this->getConnection();
				$selectQuery = "SELECT `user_tbl`.`authid` AS `authid`,
						`user_tbl`.`uid` AS `uid`,
						`user_tbl`.`profileid` AS `profileid`,
						`user_tbl`.`uname` AS `uname`,
						`user_tbl`.`pword` AS `pword`,
						`user_tbl`.`verified` AS `verified`,
						`user_tbl`.`ustat` AS `ustat`,
						`user_tbl`.`ulevel` AS `ulevel`,
						`user_tbl`.`uposition` AS `uposition`,
						`user_tbl`.`officeabrv` AS `officeabrv`,
						`profile_tbl`.`first_name` AS `first_name`,
						`profile_tbl`.`middle_name` AS `middle_name`,
						`profile_tbl`.`last_name` AS `last_name`,
						`profile_tbl`.`suffix` AS `suffix` 
					FROM `user_tbl` 
					JOIN `profile_tbl` ON `user_tbl`.`profileid` = `profile_tbl`.`profileid`";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();
				$cntRcrd = $stmt->rowCount();

				return $cntRcrd;
			}
			
		}
	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error @ UserAcct: {$err_msg}</p>";
		die;
	}

?>

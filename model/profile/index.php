<?php 

	try {

		if (file_exists("lib/cnn.php")) {
			require_once "lib/cnn.php";
		} elseif (file_exists("../../lib/cnn.php")) {
			require_once "../../lib/cnn.php";
		}

		class clssProfile extends myDatabase {
			// Memory single variable base on Database Table Fieldnames
			Public $profileautoidii,
				$profileidii,
				$useridii,
				$ulevelii,
				$upositionii,
				$nicknameii,
				$ptitleii,
				$firstnameii,
				$middlenameii,
				$lastnameii,
				$suffixii,
				$fullnameii,
				$genderii,
				$birthdateii,
				$birthplaceii,
				$nationalityii,
				$civilstatusii,
				$bloodtypeii,
				$emailii,
				$photoii,
				$mobileii,
				$mobile2ii,
				$fbidii,
				$addressii,
				$addressline2ii,
				$streetii,
				$barangaycodeii,
				$barangayii,
				$municipalitycodeii,
				$municipalityii,
				$zipcodeii,
				$districtnoii,
				$districtsignii,
				$provincecodeii,
				$provinceii,
				$regioncodeii,
				$regionnoii,
				$regionsignii,
				$regionii,
				$countryidii,
				$countrycodeii,
				$countryii,
				$postalii,
				$foreignaddressii,
				$xdelii,
				$createdbyii,
				$modifiedbyii,
				$modifiedatii,
				$createdatii;

			// Memory list variable base on Database Table Fieldnames
			Public $list_profileautoidii,
				$list_profileidii,
				$list_useridii,
				$list_ulevelii,
				$list_upositionii,
				$list_nicknameii,
				$list_ptitleii,
				$list_firstnameii,
				$list_middlenameii,
				$list_lastnameii,
				$list_suffixii,
				$list_fullnameii,
				$list_genderii,
				$list_birthdateii,
				$list_birthplaceii,
				$list_nationalityii,
				$list_civilstatusii,
				$list_bloodtypeii,
				$list_emailii,
				$list_photoii,
				$list_mobileii,
				$list_mobile2ii,
				$list_fbidii,
				$list_addressii,
				$list_addressline2ii,
				$list_streetii,
				$list_barangaycodeii,
				$list_barangayii,
				$list_municipalitycodeii,
				$list_municipalityii,
				$list_zipcodeii,
				$list_districtnoii,
				$list_districtsignii,
				$list_provincecodeii,
				$list_provinceii,
				$list_regioncodeii,
				$list_regionnoii,
				$list_regionsignii,
				$list_regionii,
				$list_countryidii,
				$list_countrycodeii,
				$list_countryii,
				$list_postalii,
				$list_foreignaddressii,
				$list_xdelii,
				$list_createdbyii,
				$list_modifiedbyii,
				$list_modifiedatii,
				$list_createdatii;

			// Constructer Memory list variable base on Database Table Fieldnames
			public function __construct() {
				$this->list_profileautoidii = array();
				$this->list_profileidii = array();
				$this->list_useridii = array();
				$this->list_ulevelii = array();
				$this->list_upositionii = array();
				$this->list_nicknameii = array();
				$this->list_ptitleii = array();
				$this->list_firstnameii = array();
				$this->list_middlenameii = array();
				$this->list_lastnameii = array();
				$this->list_suffixii = array();
				$this->list_fullnameii = array();
				$this->list_genderii = array();
				$this->list_birthdateii = array();
				$this->list_birthplaceii = array();
				$this->list_nationalityii = array();
				$this->list_civilstatusii = array();
				$this->list_bloodtypeii = array();
				$this->list_emailii = array();
				$this->list_photoii = array();
				$this->list_mobileii = array();
				$this->list_mobile2ii = array();
				$this->list_fbidii = array();
				$this->list_addressii = array();
				$this->list_addressline2ii = array();
				$this->list_streetii = array();
				$this->list_barangaycodeii = array();
				$this->list_barangayii = array();
				$this->list_municipalitycodeii = array();
				$this->list_municipalityii = array();
				$this->list_zipcodeii = array();
				$this->list_districtnoii = array();
				$this->list_districtsignii = array();
				$this->list_provincecodeii = array();
				$this->list_provinceii = array();
				$this->list_regioncodeii = array();
				$this->list_regionnoii = array();
				$this->list_regionsignii = array();
				$this->list_regionii = array();
				$this->list_countryidii = array();
				$this->list_countrycodeii = array();
				$this->list_countryii = array();
				$this->list_postalii = array();
				$this->list_foreignaddressii = array();
				$this->list_xdelii = array();
				$this->list_createdbyii = array();
				$this->list_modifiedbyii = array();
				$this->list_modifiedatii = array();
				$this->list_createdatii = array();
			}

			// Clearing of data values Memory list variable base on Database Table Fieldnames
			public function clearlist_clssProfile() {
				$this->list_profileautoidii = array();
				$this->list_profileidii = array();
				$this->list_useridii = array();
				$this->list_ulevelii = array();
				$this->list_upositionii = array();
				$this->list_nicknameii = array();
				$this->list_ptitleii = array();
				$this->list_firstnameii = array();
				$this->list_middlenameii = array();
				$this->list_lastnameii = array();
				$this->list_suffixii = array();
				$this->list_fullnameii = array();
				$this->list_genderii = array();
				$this->list_birthdateii = array();
				$this->list_birthplaceii = array();
				$this->list_nationalityii = array();
				$this->list_civilstatusii = array();
				$this->list_bloodtypeii = array();
				$this->list_emailii = array();
				$this->list_photoii = array();
				$this->list_mobileii = array();
				$this->list_mobile2ii = array();
				$this->list_fbidii = array();
				$this->list_addressii = array();
				$this->list_addressline2ii = array();
				$this->list_streetii = array();
				$this->list_barangaycodeii = array();
				$this->list_barangayii = array();
				$this->list_municipalitycodeii = array();
				$this->list_municipalityii = array();
				$this->list_zipcodeii = array();
				$this->list_districtnoii = array();
				$this->list_districtsignii = array();
				$this->list_provincecodeii = array();
				$this->list_provinceii = array();
				$this->list_regioncodeii = array();
				$this->list_regionnoii = array();
				$this->list_regionsignii = array();
				$this->list_regionii = array();
				$this->list_countryidii = array();
				$this->list_countrycodeii = array();
				$this->list_countryii = array();
				$this->list_postalii = array();
				$this->list_foreignaddressii = array();
				$this->list_xdelii = array();
				$this->list_createdbyii = array();
				$this->list_modifiedbyii = array();
				$this->list_modifiedatii = array();
				$this->list_createdatii = array();
			}


			// Array of data values Memory single variable base on Database Table Fieldnames
			public function loadrecord_clssProfile() {
				$this->clearlist_clssProfile();
				$this->getConnection();
				$selectQuery = "SELECT * FROM profile_tbl";
				$stmt = $this->cnn->prepare($selectQuery);

				$stmt->execute();

				$data = array();

				for ($i=0; $row = $stmt->fetch(); $i++) {
					$data[] = $row;
				}

				return $data;
			}

			// Reading of data values Memory list variable base on Database Table Fieldnames
			// For Employee Registration
			public function list_forEmployeeReg() {
				$this->clearlist_clssProfile();
				$this->getConnection();

				$selectQuery = "SELECT 
						u.uid AS uid, 
						u.ulevel AS ulevel, 
						u.uposition AS uposition, 
						p.profile_autoid AS profileautoid, 
						p.profileid AS profileid, 
						p.nickname AS nickname, 
						p.ptitle AS ptitle, 
						p.first_name AS firstname, 
						p.middle_name AS middlename, 
						p.last_name AS lastname, 
						p.suffix AS suffix, 
						p.gender AS gender, 
						p.birth_date AS birthdate, 
						p.email AS email, 
						p.mobile AS mobile, 
						p.photo AS photo 
					FROM 
						user_tbl AS u 
					INNER JOIN 
						profile_tbl AS p ON u.profileid = p.profileid 
					WHERE 
						u.ulevel = 12
						";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_useridii[] = $rwRcrd['uid'];
						$this->list_ulevelii[] = $rwRcrd['ulevel'];
						$this->list_upositionii[] = $rwRcrd['uposition'];
						$this->list_profileautoidii[] = $rwRcrd['profileautoid'];
						$this->list_profileidii[] = $rwRcrd['profileid'];
						$this->list_nicknameii[] = $rwRcrd['nickname'];
						$this->list_genderii[] = $rwRcrd['gender'];
						$this->list_birthdateii[] = $rwRcrd['birthdate'];
						$this->list_emailii[] = $rwRcrd['email'];
						$this->list_photoii[] = $rwRcrd['photo'];
						$this->list_mobileii[] = $rwRcrd['mobile'];

						// create fullname
						$fullname_mi = null;
						if ( empty(trim($rwRcrd['ptitle'])) && empty(trim($rwRcrd['middlename'])) && empty(trim($rwRcrd['suffix'])) ) {
							$fullname_mi = trim(strtoupper($rwRcrd['firstname']))." ".trim(strtoupper($rwRcrd['lastname']));
						} elseif ( empty(trim($rwRcrd['ptitle'])) && empty(trim($rwRcrd['suffix'])) ) {
							$fullname_mi = trim(strtoupper($rwRcrd['firstname']))." ".trim(substr(strtoupper($rwRcrd['middlename']),0,1)).". ".trim(strtoupper($rwRcrd['lastname']));
						} elseif ( empty(trim($rwRcrd['ptitle'])) ) {
							$fullname_mi = trim(strtoupper($rwRcrd['firstname']))." ".trim(substr(strtoupper($rwRcrd['middlename']),0,1)).". ".trim(strtoupper($rwRcrd['lastname'])).", ".trim(strtoupper($rwRcrd['suffix']));
						} else {
							$fullname_mi = trim(strtoupper($rwRcrd['ptitle']))." ".trim(strtoupper($rwRcrd['firstname']))." ".trim(substr(strtoupper($rwRcrd['middlename']),0,1)).". ".trim(strtoupper($rwRcrd['lastname'])).", ".trim(strtoupper($rwRcrd['suffix']));
						}

						$this->list_fullnameii[] = $fullname_mi;
					}

					return true;
				} else {
					return false;
				}
			}

			// Create or insert new data on the Database Table
			public function insert_clssProfile($profileidii,$nicknameii,$ptitleii,$firstnameii,$middlenameii,$lastnameii,$suffixii,$genderii,$birthdateii,$birthplaceii,$nationalityii,$civilstatusii,$bloodtypeii,$emailii,$photoii,$mobileii,$mobile2ii,$fbidii,$addressii,$addressline2ii,$streetii,$barangaycodeii,$barangayii,$municipalitycodeii,$municipalityii,$zipcodeii,$districtnoii,$districtsignii,$provincecodeii,$provinceii,$regioncodeii,$regionnoii,$regionsignii,$regionii,$countryidii,$countrycodeii,$countryii,$postalii,$foreignaddressii,$createdbyii,$modifiedbyii,$msg) {
				$this->clearlist_clssProfile();
				$this->getConnection();

				$profileid = htmlspecialchars(trim($profileidii));
				$nickname = htmlspecialchars(trim($nicknameii));
				$ptitle = htmlspecialchars(trim($ptitleii));
				$firstname = htmlspecialchars(trim($firstnameii));
				$middlename = htmlspecialchars(trim($middlenameii));
				$lastname = htmlspecialchars(trim($lastnameii));
				$suffix = htmlspecialchars(trim($suffixii));
				$gender = htmlspecialchars(trim($genderii));
				$birthdate = date('Y-m-d', $birthdateii);
				$birthplace = htmlspecialchars(trim($birthplaceii));
				$nationality = htmlspecialchars(trim($nationalityii));
				$civilstatus = htmlspecialchars(trim($civilstatusii));
				$bloodtype = htmlspecialchars(trim($bloodtypeii));
				$email = htmlspecialchars(trim($emailii));
				$photo = htmlspecialchars(trim($photoii));
				$mobile = htmlspecialchars(trim($mobileii));
				$mobile2 = htmlspecialchars(trim($mobile2ii));
				$fbid = htmlspecialchars(trim($fbidii));
				$address = htmlspecialchars(trim($addressii));
				$addressline2 = htmlspecialchars(trim($addressline2ii));
				$street = htmlspecialchars(trim($streetii));
				$barangaycode = htmlspecialchars(trim($barangaycodeii));
				$barangay = htmlspecialchars(trim($barangayii));
				$municipalitycode = htmlspecialchars(trim($municipalitycodeii));
				$municipality = htmlspecialchars(trim($municipalityii));
				$zipcode = htmlspecialchars(trim($zipcodeii));
				$districtno = htmlspecialchars(trim($districtnoii));
				$districtsign = htmlspecialchars(trim($districtsignii));
				$provincecode = htmlspecialchars(trim($provincecodeii));
				$province = htmlspecialchars(trim($provinceii));
				$regioncode = htmlspecialchars(trim($regioncodeii));
				$regionno = htmlspecialchars(trim($regionnoii));
				$regionsign = htmlspecialchars(trim($regionsignii));
				$region = htmlspecialchars(trim($regionii));
				$countryid = htmlspecialchars(trim($countryidii));
				$countrycode = htmlspecialchars(trim($countrycodeii));
				$country = htmlspecialchars(trim($countryii));
				$postal = htmlspecialchars(trim($postalii));
				$foreignaddress = htmlspecialchars(trim($foreignaddressii));
				$createdby = htmlspecialchars(trim($createdbyii));
				$modifiedby = htmlspecialchars(trim($modifiedbyii));

				$insertQuery = "INSERT INTO profile_tbl SET 
					profileid=:profileid, 
					nickname=:nickname, 
					ptitle=:ptitle, 
					first_name=:firstname, 
					middle_name=:middlename, 
					last_name=:lastname, 
					suffix=:suffix, 
					gender=:gender, 
					birth_date=:birthdate, 
					birth_place=:birthplace, 
					nationality=:nationality, 
					civil_status=:civilstatus, 
					bloodtype=:bloodtype, 
					email=:email, 
					photo=:photo, 
					mobile=:mobile, 
					mobile2=:mobile2, 
					fbid=:fbid, 
					address=:address, 
					address_line_2=:addressline2, 
					street=:street, 
					barangay_code=:barangaycode, 
					barangay=:barangay, 
					municipality_code=:municipalitycode, 
					municipality=:municipality, 
					zipcode=:zipcode, 
					district_no=:districtno, 
					district_sign=:districtsign, 
					province_code=:provincecode, 
					province=:province, 
					region_code=:regioncode, 
					region_no=:regionno, 
					region_sign=:regionsign, 
					region=:region, 
					country_id=:countryid, 
					country_code=:countrycode, 
					country=:country, 
					postal=:postal, 
					foreign_address=:foreignaddress, 
					xdel=0, 
					createdby=:createdby, 
					modifiedby=:modifiedby
					";
				$stmt = $this->cnn->prepare($insertQuery);
				$stmt->bindParam(':profileid', $profileid);
				$stmt->bindParam(':nickname', $nickname);
				$stmt->bindParam(':ptitle', $ptitle);
				$stmt->bindParam(':firstname', $firstname);
				$stmt->bindParam(':middlename', $middlename);
				$stmt->bindParam(':lastname', $lastname);
				$stmt->bindParam(':suffix', $suffix);
				$stmt->bindParam(':gender', $gender);
				$stmt->bindParam(':birthdate', $birthdate);
				$stmt->bindParam(':birthplace', $birthplace);
				$stmt->bindParam(':nationality', $nationality);
				$stmt->bindParam(':civilstatus', $civilstatus);
				$stmt->bindParam(':bloodtype', $bloodtype);
				$stmt->bindParam(':email', $email);
				$stmt->bindParam(':photo', $photo);
				$stmt->bindParam(':mobile', $mobile);
				$stmt->bindParam(':mobile2', $mobile2);
				$stmt->bindParam(':fbid', $fbid);
				$stmt->bindParam(':address', $address);
				$stmt->bindParam(':addressline2', $addressline2);
				$stmt->bindParam(':street', $street);
				$stmt->bindParam(':barangaycode', $barangaycode);
				$stmt->bindParam(':barangay', $barangay);
				$stmt->bindParam(':municipalitycode', $municipalitycode);
				$stmt->bindParam(':municipality', $municipality);
				$stmt->bindParam(':zipcode', $zipcode);
				$stmt->bindParam(':districtno', $districtno);
				$stmt->bindParam(':districtsign', $districtsign);
				$stmt->bindParam(':provincecode', $provincecode);
				$stmt->bindParam(':province', $province);
				$stmt->bindParam(':regioncode', $regioncode);
				$stmt->bindParam(':regionno', $regionno);
				$stmt->bindParam(':regionsign', $regionsign);
				$stmt->bindParam(':region', $region);
				$stmt->bindParam(':countryid', $countryid);
				$stmt->bindParam(':countrycode', $countrycode);
				$stmt->bindParam(':country', $country);
				$stmt->bindParam(':postal', $postal);
				$stmt->bindParam(':foreignaddress', $foreignaddress);
				$stmt->bindParam(':createdby', $createdby);
				$stmt->bindParam(':modifiedby', $modifiedby);
				$stmt->execute();

				echo '<div class="alert alert-info alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Profile Successfully Registered. ['.$msg.'] You may <a href="login">Login</a> Now!';
				echo '</div>';

				$profileidfinale = trim($profileid);
				$imgdata = trim(htmlspecialchars(trim($photo)));
				if (file_exists("lib/profile-img-saved.php")) {
					require_once "lib/profile-img-saved.php";
				} elseif (file_exists("../../lib/profile-img-saved.php")) {
					require_once "../../lib/profile-img-saved.php";
				}
			}

			// Reading of data values Memory list variable base on Database Table Fieldnames
			public function list_clssProfile() {
				$this->clearlist_clssProfile();
				$this->getConnection();

				$selectQuery = "SELECT * FROM profile_tbl WHERE xdel=0";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_profileautoidii[] = $rwRcrd['profile_autoid'];
						$this->list_profileidii[] = $rwRcrd['profileid'];
						$this->list_nicknameii[] = $rwRcrd['nickname'];
						$this->list_ptitleii[] = $rwRcrd['ptitle'];
						$this->list_firstnameii[] = $rwRcrd['first_name'];
						$this->list_middlenameii[] = $rwRcrd['middle_name'];
						$this->list_lastnameii[] = $rwRcrd['last_name'];
						$this->list_suffixii[] = $rwRcrd['suffix'];
						$this->list_genderii[] = $rwRcrd['gender'];
						$this->list_birthdateii[] = $rwRcrd['birth_date'];
						$this->list_birthplaceii[] = $rwRcrd['birth_place'];
						$this->list_nationalityii[] = $rwRcrd['nationality'];
						$this->list_civilstatusii[] = $rwRcrd['civil_status'];
						$this->list_bloodtypeii[] = $rwRcrd['bloodtype'];
						$this->list_emailii[] = $rwRcrd['email'];
						$this->list_photoii[] = $rwRcrd['photo'];
						$this->list_mobileii[] = $rwRcrd['mobile'];
						$this->list_mobile2ii[] = $rwRcrd['mobile2'];
						$this->list_fbidii[] = $rwRcrd['fbid'];
						$this->list_addressii[] = $rwRcrd['address'];
						$this->list_addressline2ii[] = $rwRcrd['address_line_2'];
						$this->list_streetii[] = $rwRcrd['street'];
						$this->list_barangaycodeii[] = $rwRcrd['barangay_code'];
						$this->list_barangayii[] = $rwRcrd['barangay'];
						$this->list_municipalitycodeii[] = $rwRcrd['municipality_code'];
						$this->list_municipalityii[] = $rwRcrd['municipality'];
						$this->list_zipcodeii[] = $rwRcrd['zipcode'];
						$this->list_districtnoii[] = $rwRcrd['district_no'];
						$this->list_districtsignii[] = $rwRcrd['district_sign'];
						$this->list_provincecodeii[] = $rwRcrd['province_code'];
						$this->list_provinceii[] = $rwRcrd['province'];
						$this->list_regioncodeii[] = $rwRcrd['region_code'];
						$this->list_regionnoii[] = $rwRcrd['region_no'];
						$this->list_regionsignii[] = $rwRcrd['region_sign'];
						$this->list_regionii[] = $rwRcrd['region'];
						$this->list_countryidii[] = $rwRcrd['country_id'];
						$this->list_countrycodeii[] = $rwRcrd['country_code'];
						$this->list_countryii[] = $rwRcrd['country'];
						$this->list_postalii[] = $rwRcrd['postal'];
						$this->list_foreignaddressii[] = $rwRcrd['foreign_address'];
						$this->list_xdelii[] = $rwRcrd['xdel'];
						$this->list_createdbyii[] = $rwRcrd['createdby'];
						$this->list_modifiedbyii[] = $rwRcrd['modifiedby'];
						$this->list_modifiedatii[] = $rwRcrd['modified_at'];
						$this->list_createdatii[] = $rwRcrd['created_at'];
					}
					return true;
				} else {
					return false;
				}
			}

			// Searching of data values Memory list variable base on Database Table Fieldnames
			// Search by ProfileID
			public function Search_clssProfile($profileid) {
				$this->clearlist_clssProfile();
				$this->getConnection();

				$profileid = htmlspecialchars(trim($profileid));

				$selectQuery = "SELECT * FROM profile_tbl WHERE profileid=:profileid";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':profileid', $profileid);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_profileautoidii[] = $rwRcrd['profile_autoid'];
						$this->list_profileidii[] = $rwRcrd['profileid'];
						$this->list_nicknameii[] = $rwRcrd['nickname'];
						$this->list_ptitleii[] = $rwRcrd['ptitle'];
						$this->list_firstnameii[] = $rwRcrd['first_name'];
						$this->list_middlenameii[] = $rwRcrd['middle_name'];
						$this->list_lastnameii[] = $rwRcrd['last_name'];
						$this->list_suffixii[] = $rwRcrd['suffix'];
						$this->list_genderii[] = $rwRcrd['gender'];
						$this->list_birthdateii[] = $rwRcrd['birth_date'];
						$this->list_birthplaceii[] = $rwRcrd['birth_place'];
						$this->list_nationalityii[] = $rwRcrd['nationality'];
						$this->list_civilstatusii[] = $rwRcrd['civil_status'];
						$this->list_bloodtypeii[] = $rwRcrd['bloodtype'];
						$this->list_emailii[] = $rwRcrd['email'];
						$this->list_photoii[] = $rwRcrd['photo'];
						$this->list_mobileii[] = $rwRcrd['mobile'];
						$this->list_mobile2ii[] = $rwRcrd['mobile2'];
						$this->list_fbidii[] = $rwRcrd['fbid'];
						$this->list_addressii[] = $rwRcrd['address'];
						$this->list_addressline2ii[] = $rwRcrd['address_line_2'];
						$this->list_streetii[] = $rwRcrd['street'];
						$this->list_barangaycodeii[] = $rwRcrd['barangay_code'];
						$this->list_barangayii[] = $rwRcrd['barangay'];
						$this->list_municipalitycodeii[] = $rwRcrd['municipality_code'];
						$this->list_municipalityii[] = $rwRcrd['municipality'];
						$this->list_zipcodeii[] = $rwRcrd['zipcode'];
						$this->list_districtnoii[] = $rwRcrd['district_no'];
						$this->list_districtsignii[] = $rwRcrd['district_sign'];
						$this->list_provincecodeii[] = $rwRcrd['province_code'];
						$this->list_provinceii[] = $rwRcrd['province'];
						$this->list_regioncodeii[] = $rwRcrd['region_code'];
						$this->list_regionnoii[] = $rwRcrd['region_no'];
						$this->list_regionsignii[] = $rwRcrd['region_sign'];
						$this->list_regionii[] = $rwRcrd['region'];
						$this->list_countryidii[] = $rwRcrd['country_id'];
						$this->list_countrycodeii[] = $rwRcrd['country_code'];
						$this->list_countryii[] = $rwRcrd['country'];
						$this->list_postalii[] = $rwRcrd['postal'];
						$this->list_foreignaddressii[] = $rwRcrd['foreign_address'];
						$this->list_xdelii[] = $rwRcrd['xdel'];
						$this->list_createdbyii[] = $rwRcrd['createdby'];
						$this->list_modifiedbyii[] = $rwRcrd['modifiedby'];
						$this->list_modifiedatii[] = $rwRcrd['modified_at'];
						$this->list_createdatii[] = $rwRcrd['created_at'];
					}
					return true;
				} else {
					return false;
				}
			}

			// Search Duplicate Profile
			public function duplicateExistProfile($firstname,$lastname,$gender,$birthday,$birthplace) {
				$this->clearlist_clssProfile();
				$this->getConnection();

				$firstnameii = htmlspecialchars(trim($firstname));
				$lastnameii = htmlspecialchars(trim($lastname));
				$genderii = htmlspecialchars(trim($gender));
				$birthdateii = date('Y-m-d', $birthday);
				$birthplaceii = htmlspecialchars(trim($birthplace));

				$selectQuery = "SELECT * FROM profile_tbl WHERE 
					first_name=:firstname AND 
					last_name=:lastname AND 
					gender=:gender AND 
					birth_place=:birthplace AND 
					DATE_FORMAT(birth_date, '%Y-%m-%d')=DATE_FORMAT(:birthdate, '%Y-%m-%d')";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':firstname', $firstnameii);
				$stmt->bindParam(':lastname', $lastnameii);
				$stmt->bindParam(':gender', $genderii);
				$stmt->bindParam(':birthdate', $birthdateii);
				$stmt->bindParam(':birthplace', $birthplaceii);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					// Exist
					return true;
				} else {
					// Not
					return false;
				}
			}

			// Filter of data values Memory list variable base on Database Table Fieldnames
			// Firstname and Lastname
			public function Filter_clssProfileFnameLname($firstnameii,$lastnameii) {
				$this->clearlist_clssProfile();
				$this->getConnection();

				$firstname = '%'.htmlspecialchars(trim($firstnameii)).'%';
				$lastname = '%'.htmlspecialchars(trim($lastnameii)).'%';

				$selectQuery = "SELECT * FROM profile_tbl WHERE first_name LIKE :firstname AND last_name LIKE :lastname";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':firstname', $firstname);
				$stmt->bindParam(':lastname', $lastname);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_profileautoidii[] = $rwRcrd['profile_autoid'];
						$this->list_profileidii[] = $rwRcrd['profileid'];
						$this->list_nicknameii[] = $rwRcrd['nickname'];
						$this->list_ptitleii[] = $rwRcrd['ptitle'];
						$this->list_firstnameii[] = $rwRcrd['first_name'];
						$this->list_middlenameii[] = $rwRcrd['middle_name'];
						$this->list_lastnameii[] = $rwRcrd['last_name'];
						$this->list_suffixii[] = $rwRcrd['suffix'];
						$this->list_genderii[] = $rwRcrd['gender'];
						$this->list_birthdateii[] = $rwRcrd['birth_date'];
						$this->list_birthplaceii[] = $rwRcrd['birth_place'];
						$this->list_nationalityii[] = $rwRcrd['nationality'];
						$this->list_civilstatusii[] = $rwRcrd['civil_status'];
						$this->list_bloodtypeii[] = $rwRcrd['bloodtype'];
						$this->list_emailii[] = $rwRcrd['email'];
						$this->list_photoii[] = $rwRcrd['photo'];
						$this->list_mobileii[] = $rwRcrd['mobile'];
						$this->list_mobile2ii[] = $rwRcrd['mobile2'];
						$this->list_fbidii[] = $rwRcrd['fbid'];
						$this->list_addressii[] = $rwRcrd['address'];
						$this->list_addressline2ii[] = $rwRcrd['address_line_2'];
						$this->list_streetii[] = $rwRcrd['street'];
						$this->list_barangaycodeii[] = $rwRcrd['barangay_code'];
						$this->list_barangayii[] = $rwRcrd['barangay'];
						$this->list_municipalitycodeii[] = $rwRcrd['municipality_code'];
						$this->list_municipalityii[] = $rwRcrd['municipality'];
						$this->list_zipcodeii[] = $rwRcrd['zipcode'];
						$this->list_districtnoii[] = $rwRcrd['district_no'];
						$this->list_districtsignii[] = $rwRcrd['district_sign'];
						$this->list_provincecodeii[] = $rwRcrd['province_code'];
						$this->list_provinceii[] = $rwRcrd['province'];
						$this->list_regioncodeii[] = $rwRcrd['region_code'];
						$this->list_regionnoii[] = $rwRcrd['region_no'];
						$this->list_regionsignii[] = $rwRcrd['region_sign'];
						$this->list_regionii[] = $rwRcrd['region'];
						$this->list_countryidii[] = $rwRcrd['country_id'];
						$this->list_countrycodeii[] = $rwRcrd['country_code'];
						$this->list_countryii[] = $rwRcrd['country'];
						$this->list_postalii[] = $rwRcrd['postal'];
						$this->list_foreignaddressii[] = $rwRcrd['foreign_address'];
						$this->list_xdelii[] = $rwRcrd['xdel'];
						$this->list_createdbyii[] = $rwRcrd['createdby'];
						$this->list_modifiedbyii[] = $rwRcrd['modifiedby'];
						$this->list_modifiedatii[] = $rwRcrd['modified_at'];
						$this->list_createdatii[] = $rwRcrd['created_at'];
					}
					return true;
				} else {
					return false;
				}
			}

			// Update new data on the Database Table
			public function Update_clssProfile($profileautoid,$nicknameii,$ptitleii) {
				$this->clearlist_clssProfile();
				$this->getConnection();

				$nickname = htmlspecialchars(trim($nicknameii));
				$ptitle = htmlspecialchars(trim($ptitleii));
				$firstname = htmlspecialchars(trim($firstnameii));
				$middlename = htmlspecialchars(trim($middlenameii));
				$lastname = htmlspecialchars(trim($lastnameii));
				$suffix = htmlspecialchars(trim($suffixii));
				$gender = htmlspecialchars(trim($genderii));
				$birthdate = htmlspecialchars(trim($birthdateii));
				$birthplace = htmlspecialchars(trim($birthplaceii));
				$nationality = htmlspecialchars(trim($nationalityii));
				$civilstatus = htmlspecialchars(trim($civilstatusii));
				$bloodtype = htmlspecialchars(trim($bloodtypeii));
				$email = htmlspecialchars(trim($emailii));
				$photo = htmlspecialchars(trim($photoii));
				$mobile = htmlspecialchars(trim($mobileii));
				$mobile2 = htmlspecialchars(trim($mobile2ii));
				$fbid = htmlspecialchars(trim($fbidii));
				$address = htmlspecialchars(trim($addressii));
				$addressline2 = htmlspecialchars(trim($addressline2ii));
				$street = htmlspecialchars(trim($streetii));
				$barangaycode = htmlspecialchars(trim($barangaycodeii));
				$barangay = htmlspecialchars(trim($barangayii));
				$municipalitycode = htmlspecialchars(trim($municipalitycodeii));
				$municipality = htmlspecialchars(trim($municipalityii));
				$zipcode = htmlspecialchars(trim($zipcodeii));
				$districtno = htmlspecialchars(trim($districtnoii));
				$districtsign = htmlspecialchars(trim($districtsignii));
				$provincecode = htmlspecialchars(trim($provincecodeii));
				$province = htmlspecialchars(trim($provinceii));
				$regioncode = htmlspecialchars(trim($regioncodeii));
				$regionno = htmlspecialchars(trim($regionnoii));
				$regionsign = htmlspecialchars(trim($regionsignii));
				$region = htmlspecialchars(trim($regionii));
				$countryid = htmlspecialchars(trim($countryidii));
				$countrycode = htmlspecialchars(trim($countrycodeii));
				$country = htmlspecialchars(trim($countryii));
				$postal = htmlspecialchars(trim($postalii));
				$foreignaddress = htmlspecialchars(trim($foreignaddressii));
				$modifiedby = htmlspecialchars(trim($modifiedbyii));

				$updateQuery = "UPDATE profile_tbl SET 
					nickname=:nickname, 
					ptitle=:ptitle, 
					first_name=:firstname, 
					middle_name=:middlename, 
					last_name=:lastname, 
					suffix=:suffix, 
					gender=:gender, 
					birth_date=:birthdate, 
					birth_place=:birthplace, 
					nationality=:nationality, 
					civil_status=:civilstatus, 
					bloodtype=:bloodtype, 
					email=:email, 
					photo=:photo, 
					mobile=:mobile, 
					mobile2=:mobile2, 
					fbid=:fbid, 
					address=:address, 
					address_line_2=:addressline2, 
					street=:street, 
					barangay_code=:barangaycode, 
					barangay=:barangay, 
					municipality_code=:municipalitycode, 
					municipality=:municipality, 
					zipcode=:zipcode, 
					district_no=:districtno, 
					district_sign=:districtsign, 
					province_code=:provincecode, 
					province=:province, 
					region_code=:regioncode, 
					region_no=:regionno, 
					region_sign=:regionsign, 
					region=:region, 
					country_id=:countryid, 
					country_code=:countrycode, 
					country=:country, 
					postal=:postal, 
					foreign_address=:foreignaddress, 
					modifiedby=:modifiedby 
					WHERE 
					profile_autoid=:profileautoid";
				$stmt = $this->cnn->prepare($updateQuery);
				$stmt->bindParam(':profileautoid', $profileautoid);
				$stmt->bindParam(':nickname', $nickname);
				$stmt->bindParam(':ptitle', $ptitle);
				$stmt->bindParam(':firstname', $firstname);
				$stmt->bindParam(':middlename', $middlename);
				$stmt->bindParam(':lastname', $lastname);
				$stmt->bindParam(':suffix', $suffix);
				$stmt->bindParam(':gender', $gender);
				$stmt->bindParam(':birthdate', $birthdate);
				$stmt->bindParam(':birthplace', $birthplace);
				$stmt->bindParam(':nationality', $nationality);
				$stmt->bindParam(':civilstatus', $civilstatus);
				$stmt->bindParam(':bloodtype', $bloodtype);
				$stmt->bindParam(':email', $email);
				$stmt->bindParam(':photo', $photo);
				$stmt->bindParam(':mobile', $mobile);
				$stmt->bindParam(':mobile2', $mobile2);
				$stmt->bindParam(':fbid', $fbid);
				$stmt->bindParam(':address', $address);
				$stmt->bindParam(':addressline2', $addressline2);
				$stmt->bindParam(':street', $street);
				$stmt->bindParam(':barangaycode', $barangaycode);
				$stmt->bindParam(':barangay', $barangay);
				$stmt->bindParam(':municipalitycode', $municipalitycode);
				$stmt->bindParam(':municipality', $municipality);
				$stmt->bindParam(':zipcode', $zipcode);
				$stmt->bindParam(':districtno', $districtno);
				$stmt->bindParam(':districtsign', $districtsign);
				$stmt->bindParam(':provincecode', $provincecode);
				$stmt->bindParam(':province', $province);
				$stmt->bindParam(':regioncode', $regioncode);
				$stmt->bindParam(':regionno', $regionno);
				$stmt->bindParam(':regionsign', $regionsign);
				$stmt->bindParam(':region', $region);
				$stmt->bindParam(':countryid', $countryid);
				$stmt->bindParam(':countrycode', $countrycode);
				$stmt->bindParam(':country', $country);
				$stmt->bindParam(':postal', $postal);
				$stmt->bindParam(':foreignaddress', $foreignaddress);
				$stmt->bindParam(':xdel', $xdel);
				$stmt->bindParam(':createdby', $createdby);
				$stmt->bindParam(':modifiedby', $modifiedby);
				$stmt->execute();
			}

			// Delete of data values Memory list variable base on Database Table Fieldnames
			public function Delete_clssProfile($delete,$modifiedbyii) {
				$this->clearlist_clssProfile();
				$this->getConnection();

				$delete = htmlspecialchars(trim($delete));
				$modifiedby = htmlspecialchars(trim($modifiedbyii));

				$selectQuery = "SELECT * FROM profile_tbl WHERE profile_autoid=:profileautoid";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':profileautoid', $delete);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					$deleteQuery = "UPDATE profile_tbl SET xdel=1,modifiedby=:modifiedby WHERE profile_autoid=:profileautoid";
					$stmtDelete = $this->cnn->prepare($deleteQuery);
					$stmtDelete->bindParam(':profileautoid', $delete);
					$stmtDelete->bindParam(':modifiedby', $modifiedby);
					$stmtDelete->execute();
					return true;
				} else {
					return false;
				}
			}

			// Restore of data values Memory list variable base on Database Table Fieldnames
			public function Restore_clssProfile($delete,$modifiedbyii) {
				$this->clearlist_clssProfile();
				$this->getConnection();

				$delete = htmlspecialchars(trim($delete));
				$modifiedby = htmlspecialchars(trim($modifiedbyii));

				$selectQuery = "SELECT * FROM profile_tbl WHERE profile_autoid=:profileautoid";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':profileautoid', $delete);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					$deleteQuery = "UPDATE profile_tbl SET xdel=0,modifiedby=:modifiedby WHERE profile_autoid=:profileautoid";
					$stmtDelete = $this->cnn->prepare($deleteQuery);
					$stmtDelete->bindParam(':profileautoid', $delete);
					$stmtDelete->bindParam(':modifiedby', $modifiedby);
					$stmtDelete->execute();
					return true;
				} else {
					return false;
				}
			}

			// Permanently Delete of data values Memory list variable base on Database Table Fieldnames
			public function PermanentDelete_clssProfile($delete) {
				$this->clearlist_clssProfile();
				$this->getConnection();

				$delete = htmlspecialchars(trim($delete));

				$selectQuery = "SELECT * FROM profile_tbl WHERE profile_autoid=:profileautoid";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':profileautoid', $delete);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					$deleteQuery = "DELETE FROM profile_tbl WHERE profile_autoid=:profileautoid";
					$stmtDelete = $this->cnn->prepare($deleteQuery);
					$stmtDelete->bindParam(':profileautoid', $delete);
					$stmtDelete->execute();
					return true;
				} else {
					return false;
				}
			}
		}

	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error @ ProfileAcct: {$err_msg}</p>";
		die;
	}
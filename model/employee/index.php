<?php 

	try {

		if (file_exists("lib/cnn.php")) {
			require_once "lib/cnn.php";
		} elseif (file_exists("../../lib/cnn.php")) {
			require_once "../../lib/cnn.php";
		}

		class employeeAcct extends myDatabase {

			// Memory single variable base on Database Table Fieldnames
			Public $empautoidee,
				$agencycodeee,
				$agencynameee,
				$nicknameee,
				$empnameforidee,
				$officenameforidee,
				$designationforidee,
				$profileidee,
				$uidee,
				$empidcodeee,
				$pinwordee,
				$hrempidee,
				$bioloclabelee,
				$biolocationee,
				$bionoee,
				$empnameee,
				$genderee,
				$birthdayee,
				$addressee,
				$empageee,
				$officeidee,
				$officecodeee,
				$officenameee,
				$officetitleee,
				$officeabrvee,
				$oldofficeabrvee,
				$officegpslocationee,
				$headofficeree,
				$headtitleee,
				$authheadee,
				$authtitleee,
				$authdescriptionee,
				$yearemployedee,
				$yearcalcee,
				$typeemployeenoee,
				$typeemployeeabrvee,
				$typeemployeeee,
				$verifiedee,
				$activatedee,
				$worklocationee,
				$shiftstatusee,
				$timeeditableee,
				$prioritydtree,
				$timeeditablevalueee,
				$allowedotee,
				$plantillanoee,
				$positionee,
				$designationee,
				$positionclassee,
				$salaryamountee,
				$salaryperiodee,
				$salaryamountperperiodee,
				$authannualsalaryee,
				$actualannualsalaryee,
				$salarygradeee,
				$salarystepsee,
				$empareacodeee,
				$empareatypeee,
				$emplevelee,
				$philhealthcontributionee,
				$pagibibcontributionee,
				$ssscontributionee,
				$gsiscontributionee,
				$employeecontributionee,
				$payrollbanknameee,
				$payrollbanknumberee,
				$philhealthnoee,
				$pagibibnoee,
				$sssnoee,
				$gsisnoee,
				$taxidee,
				$lastdateemploymentee,
				$dateissuedee,
				$monthsvalidityee,
				$employmentstatusnoee,
				$employmentstatusee,
				$employmentstatusabvree,
				$validuntilee,
				$mphoneee,
				$empemailee,
				$designationatee,
				$incaseofemergencynameee,
				$incaseofemergencycontactee,
				$incaseofemergencyelationee,
				$xdelee,
				$slingid,
				$pocketid,
				$createdbyee,
				$modifiedbyee,
				$modifiedatee,
				$createdatee;

			// Memory list variable base on Database Table Fieldnames
			Public $list_empautoidee,
				$list_agencycodeee,
				$list_agencynameee,
				$list_nicknameee,
				$list_empnameforidee,
				$list_officenameforidee,
				$list_designationforidee,
				$list_profileidee,
				$list_uidee,
				$list_empidcodeee,
				$list_pinwordee,
				$list_hrempidee,
				$list_biolocationee,
				$list_bioloclabelee,
				$list_bionoee,
				$list_empnameee,
				$list_genderee,
				$list_birthdayee,
				$list_addressee,
				$list_empageee,
				$list_officeidee,
				$list_officecodeee,
				$list_officenameee,
				$list_officetitleee,
				$list_officeabrvee,
				$list_oldofficeabrvee,
				$list_officegpslocationee,
				$list_headofficeree,
				$list_headtitleee,
				$list_authheadee,
				$list_authtitleee,
				$list_authdescriptionee,
				$list_yearemployedee,
				$list_yearcalcee,
				$list_typeemployeenoee,
				$list_typeemployeeabrvee,
				$list_typeemployeeee,
				$list_verifiedee,
				$list_activatedee,
				$list_worklocationee,
				$list_shiftstatusee,
				$list_timeeditableee,
				$list_prioritydtree,
				$list_timeeditablevalueee,
				$list_allowedotee,
				$list_plantillanoee,
				$list_positionee,
				$list_designationee,
				$list_positionclassee,
				$list_salaryamountee,
				$list_salaryperiodee,
				$list_salaryamountperperiodee,
				$list_authannualsalaryee,
				$list_actualannualsalaryee,
				$list_salarygradeee,
				$list_salarystepsee,
				$list_empareacodeee,
				$list_empareatypeee,
				$list_emplevelee,
				$list_philhealthcontributionee,
				$list_pagibibcontributionee,
				$list_ssscontributionee,
				$list_gsiscontributionee,
				$list_employeecontributionee,
				$list_payrollbanknameee,
				$list_payrollbanknumberee,
				$list_philhealthnoee,
				$list_pagibibnoee,
				$list_sssnoee,
				$list_gsisnoee,
				$list_taxidee,
				$list_lastdateemploymentee,
				$list_dateissuedee,
				$list_monthsvalidityee,
				$list_employmentstatusnoee,
				$list_employmentstatusee,
				$list_employmentstatusabvree,
				$list_validuntilee,
				$list_mphoneee,
				$list_empemailee,
				$list_designationatee,
				$list_incaseofemergencynameee,
				$list_incaseofemergencycontactee,
				$list_incaseofemergencyelationee,
				$list_xdelee,
				$list_slingidee,
				$list_pocketidee,
				$list_createdbyee,
				$list_modifiedbyee,
				$list_modifiedatee,
				$list_createdatee;

			// Constructer Memory list variable base on Database Table Fieldnames
			public function __construct() {
				$this->list_empautoidee = array();
				$this->list_agencycodeee = array();
				$this->list_agencynameee = array();
				$this->list_nicknameee = array();
				$this->list_empnameforidee = array();
				$this->list_officenameforidee = array();
				$this->list_designationforidee = array();
				$this->list_profileidee = array();
				$this->list_uidee = array();
				$this->list_empidcodeee = array();
				$this->list_pinwordee = array();
				$this->list_hrempidee = array();
				$this->list_biolocationee = array();
				$this->list_bioloclabelee = array();
				$this->list_bionoee = array();
				$this->list_empnameee = array();
				$this->list_genderee = array();
				$this->list_birthdayee = array();
				$this->list_addressee = array();
				$this->list_empageee = array();
				$this->list_officeidee = array();
				$this->list_officecodeee = array();
				$this->list_officenameee = array();
				$this->list_officetitleee = array();
				$this->list_officeabrvee = array();
				$this->list_oldofficeabrvee = array();
				$this->list_officegpslocationee = array();
				$this->list_headofficeree = array();
				$this->list_headtitleee = array();
				$this->list_authheadee = array();
				$this->list_authtitleee = array();
				$this->list_authdescriptionee = array();
				$this->list_yearemployedee = array();
				$this->list_yearcalcee = array();
				$this->list_typeemployeenoee = array();
				$this->list_typeemployeeabrvee = array();
				$this->list_typeemployeeee = array();
				$this->list_verifiedee = array();
				$this->list_activatedee = array();
				$this->list_worklocationee = array();
				$this->list_shiftstatusee = array();
				$this->list_timeeditableee = array();
				$this->list_prioritydtree = array();
				$this->list_timeeditablevalueee = array();
				$this->list_allowedotee = array();
				$this->list_plantillanoee = array();
				$this->list_positionee = array();
				$this->list_designationee = array();
				$this->list_positionclassee = array();
				$this->list_salaryamountee = array();
				$this->list_salaryperiodee = array();
				$this->list_salaryamountperperiodee = array();
				$this->list_authannualsalaryee = array();
				$this->list_actualannualsalaryee = array();
				$this->list_salarygradeee = array();
				$this->list_salarystepsee = array();
				$this->list_empareacodeee = array();
				$this->list_empareatypeee = array();
				$this->list_emplevelee = array();
				$this->list_philhealthcontributionee = array();
				$this->list_pagibibcontributionee = array();
				$this->list_ssscontributionee = array();
				$this->list_gsiscontributionee = array();
				$this->list_employeecontributionee = array();
				$this->list_payrollbanknameee = array();
				$this->list_payrollbanknumberee = array();
				$this->list_philhealthnoee = array();
				$this->list_pagibibnoee = array();
				$this->list_sssnoee = array();
				$this->list_gsisnoee = array();
				$this->list_taxidee = array();
				$this->list_lastdateemploymentee = array();
				$this->list_dateissuedee = array();
				$this->list_monthsvalidityee = array();
				$this->list_employmentstatusnoee = array();
				$this->list_employmentstatusee = array();
				$this->list_employmentstatusabvree = array();
				$this->list_validuntilee = array();
				$this->list_mphoneee = array();
				$this->list_empemailee = array();
				$this->list_designationatee = array();
				$this->list_incaseofemergencynameee = array();
				$this->list_incaseofemergencycontactee = array();
				$this->list_incaseofemergencyelationee = array();
				$this->list_xdelee = array();
				$this->list_slingidee = array();
				$this->list_pocketidee = array();
				$this->list_createdbyee = array();
				$this->list_modifiedbyee = array();
				$this->list_modifiedatee = array();
				$this->list_createdatee = array();
			}

			// Clearing of data values Memory list variable base on Database Table Fieldnames
			public function clearlist_employeeAcct() {
				$this->list_empautoidee = array();
				$this->list_agencycodeee = array();
				$this->list_agencynameee = array();
				$this->list_nicknameee = array();
				$this->list_empnameforidee = array();
				$this->list_officenameforidee = array();
				$this->list_designationforidee = array();
				$this->list_profileidee = array();
				$this->list_uidee = array();
				$this->list_empidcodeee = array();
				$this->list_pinwordee = array();
				$this->list_hrempidee = array();
				$this->list_biolocationee = array();
				$this->list_bioloclabelee = array();
				$this->list_bionoee = array();
				$this->list_empnameee = array();
				$this->list_genderee = array();
				$this->list_birthdayee = array();
				$this->list_addressee = array();
				$this->list_empageee = array();
				$this->list_officeidee = array();
				$this->list_officecodeee = array();
				$this->list_officenameee = array();
				$this->list_officetitleee = array();
				$this->list_officeabrvee = array();
				$this->list_oldofficeabrvee = array();
				$this->list_officegpslocationee = array();
				$this->list_headofficeree = array();
				$this->list_headtitleee = array();
				$this->list_authheadee = array();
				$this->list_authtitleee = array();
				$this->list_authdescriptionee = array();
				$this->list_yearemployedee = array();
				$this->list_yearcalcee = array();
				$this->list_typeemployeenoee = array();
				$this->list_typeemployeeabrvee = array();
				$this->list_typeemployeeee = array();
				$this->list_verifiedee = array();
				$this->list_activatedee = array();
				$this->list_worklocationee = array();
				$this->list_shiftstatusee = array();
				$this->list_timeeditableee = array();
				$this->list_prioritydtree = array();
				$this->list_timeeditablevalueee = array();
				$this->list_allowedotee = array();
				$this->list_plantillanoee = array();
				$this->list_positionee = array();
				$this->list_designationee = array();
				$this->list_positionclassee = array();
				$this->list_salaryamountee = array();
				$this->list_salaryperiodee = array();
				$this->list_salaryamountperperiodee = array();
				$this->list_authannualsalaryee = array();
				$this->list_actualannualsalaryee = array();
				$this->list_salarygradeee = array();
				$this->list_salarystepsee = array();
				$this->list_empareacodeee = array();
				$this->list_empareatypeee = array();
				$this->list_emplevelee = array();
				$this->list_philhealthcontributionee = array();
				$this->list_pagibibcontributionee = array();
				$this->list_ssscontributionee = array();
				$this->list_gsiscontributionee = array();
				$this->list_employeecontributionee = array();
				$this->list_payrollbanknameee = array();
				$this->list_payrollbanknumberee = array();
				$this->list_philhealthnoee = array();
				$this->list_pagibibnoee = array();
				$this->list_sssnoee = array();
				$this->list_gsisnoee = array();
				$this->list_taxidee = array();
				$this->list_lastdateemploymentee = array();
				$this->list_dateissuedee = array();
				$this->list_monthsvalidityee = array();
				$this->list_employmentstatusnoee = array();
				$this->list_employmentstatusee = array();
				$this->list_employmentstatusabvree = array();
				$this->list_validuntilee = array();
				$this->list_mphoneee = array();
				$this->list_empemailee = array();
				$this->list_designationatee = array();
				$this->list_incaseofemergencynameee = array();
				$this->list_incaseofemergencycontactee = array();
				$this->list_incaseofemergencyelationee = array();
				$this->list_xdelee = array();
				$this->list_slingidee = array();
				$this->list_pocketidee = array();
				$this->list_createdbyee = array();
				$this->list_modifiedbyee = array();
				$this->list_modifiedatee = array();
				$this->list_createdatee = array();
			}

			// List of Employee
			public function fn_ListEmployee($officeid) {
				$this->clearlist_employeeAcct();
				$this->getConnection();

				$officeidme = $officeid;
				if ( empty($officeidme) || $officeidme == 0 || $officeidme = null ) {
					$selectQuery = "SELECT * FROM employee_tbl WHERE xdel=0 ORDER BY created_at DESC";
					$stmt = $this->cnn->prepare($selectQuery);
				} else {
					$selectQuery = "SELECT * FROM employee_tbl WHERE officeid=:officeidme AND xdel=0 ORDER BY created_at DESC";
					$stmt = $this->cnn->prepare($selectQuery);
					$stmt->bindParam(':officeidme', $officeidme);
				}
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_empautoidee[] = $rwRcrd['emp_autoid'];
						$this->list_profileidee[] = $rwRcrd['profileid'];
						$this->list_uidee[] = $rwRcrd['uid'];
						$this->list_empidcodeee[] = $rwRcrd['emp_idcode'];
						$this->list_pinwordee[] = $rwRcrd['pinword'];
						$this->list_hrempidee[] = $rwRcrd['hr_emp_id'];
						$this->list_bionoee[] = $rwRcrd['bio_no'];
						$this->list_nicknameee[] = $rwRcrd['nickname'];
						$this->list_empnameforidee[] = $rwRcrd['emp_name_forid'];
						$this->list_officenameforidee[] = $rwRcrd['officename_forid'];
						$this->list_designationforidee[] = $rwRcrd['designationforid'];
						$this->list_birthdayee[] = $rwRcrd['birthday'];
						$this->list_addressee = $rwRcrd['address'];
						$this->list_empageee[] = $rwRcrd['emp_age'];
						$this->list_officeidee[] = $rwRcrd['officeid'];
						$this->list_officegpslocationee[] = $rwRcrd['office_gps_location'];
						$this->list_yearemployedee[] = $rwRcrd['year_employed'];
						$this->list_yearcalcee[] = $rwRcrd['year_calc'];
						$this->list_typeemployeenoee[] = $rwRcrd['type_employee_no'];
						$this->list_verifiedee[] = $rwRcrd['verified'];
						$this->list_activatedee[] = $rwRcrd['activated'];
						$this->list_shiftstatusee[] = $rwRcrd['shift_status'];
						$this->list_timeeditableee[] = $rwRcrd['time_editable'];
						$this->list_prioritydtree[] = $rwRcrd['priority_dtr'];
						$this->list_timeeditablevalueee[] = $rwRcrd['time_editable_value'];
						$this->list_allowedotee[] = $rwRcrd['allowed_ot'];
						$this->list_plantillanoee[] = $rwRcrd['plantilla_no'];
						$this->list_positionclassee[] = $rwRcrd['position_class'];
						$this->list_salaryamountee[] = $rwRcrd['salary_amount'];
						$this->list_salaryperiodee[] = $rwRcrd['salary_period'];
						$this->list_salaryamountperperiodee[] = $rwRcrd['salary_amount_per_period'];
						$this->list_authannualsalaryee[] = $rwRcrd['auth_annual_salary'];
						$this->list_actualannualsalaryee[] = $rwRcrd['actual_annual_salary'];
						$this->list_salarygradeee[] = $rwRcrd['salary_grade'];
						$this->list_salarystepsee[] = $rwRcrd['salary_steps'];
						$this->list_empareacodeee[] = $rwRcrd['emp_area_code'];
						$this->list_empareatypeee[] = $rwRcrd['emp_area_type'];
						$this->list_emplevelee[] = $rwRcrd['emp_level'];
						$this->list_philhealthcontributionee[] = $rwRcrd['philhealth_contribution'];
						$this->list_pagibibcontributionee[] = $rwRcrd['pagibib_contribution'];
						$this->list_ssscontributionee[] = $rwRcrd['sss_contribution'];
						$this->list_gsiscontributionee[] = $rwRcrd['gsis_contribution'];
						$this->list_employeecontributionee[] = $rwRcrd['employee_contribution'];
						$this->list_payrollbanknameee[] = $rwRcrd['payroll_bank_name'];
						$this->list_payrollbanknumberee[] = $rwRcrd['payroll_bank_number'];
						$this->list_philhealthnoee[] = $rwRcrd['philhealth_no'];
						$this->list_pagibibnoee[] = $rwRcrd['pagibib_no'];
						$this->list_sssnoee[] = $rwRcrd['sss_no'];
						$this->list_gsisnoee[] = $rwRcrd['gsis_no'];
						$this->list_taxidee[] = $rwRcrd['tax_id'];
						$this->list_lastdateemploymentee[] = $rwRcrd['last_date_employment'];
						$this->list_dateissuedee[] = $rwRcrd['date_issued'];
						$this->list_monthsvalidityee[] = $rwRcrd['months_validity'];
						$this->list_employmentstatusnoee[] = $rwRcrd['employment_status_no'];
						$this->list_employmentstatusee[] = $rwRcrd['employment_status'];
						$this->list_validuntilee[] = $rwRcrd['valid_until'];
						$this->list_mphoneee[] = $rwRcrd['mphone'];
						$this->list_empemailee[] = $rwRcrd['empemail'];
						$this->list_incaseofemergencynameee[] = $rwRcrd['incaseofemergency_name'];
						$this->list_incaseofemergencycontactee[] = $rwRcrd['incaseofemergency_contact'];
						$this->list_incaseofemergencyelationee[] = $rwRcrd['incaseofemergency_relation'];
						$this->list_xdelee[] = $rwRcrd['xdel'];
						$this->list_slingidee[] = $rwRcrd['slingid'];
						$this->list_pocketidee[] = $rwRcrd['pocketid'];
						$this->list_createdbyee[] = $rwRcrd['createdby'];
						$this->list_modifiedbyee[] = $rwRcrd['modifiedby'];
						$this->list_modifiedatee[] = $rwRcrd['modified_at'];
						$this->list_createdatee[] = $rwRcrd['created_at'];
						$this->list_typeemployeeabrvee[] = $rwRcrd['type_employee_abrv'];
						$this->list_typeemployeeee[] = $rwRcrd['type_employee'];
						$this->list_officenameee[] = $rwRcrd['officename'];
					}
					return true;
				} else {
					return true;
				}
			}

			// Create or insert new data on the Database Table
			public function insert_Employee($agencycode,$agencyname,$nickname,$empnameforid,$officenameforid,$designationforid,$profileid,$uid,$empidcode,$pinword,$hrempid,$biolocation,$biono,$empname,$gender,$birthday,$officeid,$officecode,$officename,$officetitle,$officeabrv,$oldofficeabrv,$officegpslocation,$headofficer,$headtitle,$authhead,$authtitle,$authdescription,$yearemployed,$typeemployeeno,$typeemployeeabrv,$typeemployee,$verified,$activated,$worklocation,$shiftstatus,$timeeditable,$prioritydtr,$timeeditablevalue,$allowedot,$position,$designation,$mphone,$empemail,$designationat,$slingid,
				$pocketid,$createdby,$modifiedby,$bioloclabel,$imgdataempl,$incaseofemergencyname,$incaseofemergencycontact,$incaseofemergencyrelation,$address) {
				$this->clearlist_employeeAcct();
				$this->getConnection();

				$agencycode = trim($agencycode);
				$agencyname = trim($agencyname);
				$nickname = trim($nickname);
				$empnameforid = trim($empnameforid);
				$officenameforid = trim($officenameforid);
				$designationforid = trim($designationforid);
				$profileid = trim($profileid);
				$uid = trim($uid);
				$empidcode = trim($empidcode);
				$pinword = trim(md5(trim($pinword)));
				$hrempid = trim($hrempid);
				$bioloclabel = trim($bioloclabel);
				$biolocation = trim($biolocation);
				$biono = trim($biono);
				$empname = trim($empname);
				$gender = trim($gender);
				$birthday = date('Y-m-d', $birthday);
				$address = trim($address);
				$officeid = trim($officeid);
				$officecode = trim($officecode);
				$officename = trim($officename);
				$officetitle = trim($officetitle);
				$officeabrv = trim($officeabrv);
				$oldofficeabrv = trim($oldofficeabrv);
				$officegpslocation = trim($officegpslocation);
				$headofficer = trim($headofficer);
				$headtitle = trim($headtitle);
				$authhead = trim($authhead);
				$authtitle = trim($authtitle);
				$authdescription = trim($authdescription);
				$yearemployed = trim($yearemployed);
				$yearcalc = date("Y") - $yearemployed;
				$typeemployeeno = trim($typeemployeeno);
				$typeemployeeabrv = trim($typeemployeeabrv);
				$typeemployee = trim($typeemployee);
				$verified = trim($verified);
				$activated = trim($activated);
				$worklocation = trim($worklocation);
				$shiftstatus = trim($shiftstatus);
				$timeeditable = trim($timeeditable);
				$prioritydtr = trim($prioritydtr);
				$timeeditablevalue = trim($timeeditablevalue);
				$allowedot = trim($allowedot);
				$position = trim($position);
				$designation = trim($designation);
				$mphone = trim($mphone);
				$empemail = trim($empemail);
				$designationat = trim($designationat);
				$slingid = trim($slingid);
				$pocketid = trim($pocketid);
				$createdby = trim($createdby);
				$modifiedby = trim($modifiedby);
				$incaseofemergencyname = trim($incaseofemergencyname);
				$incaseofemergencycontact = trim($incaseofemergencycontact);
				$incaseofemergencyrelation = trim($incaseofemergencyrelation);

				$imgdataemplx = trim($imgdataempl);

				$insertQuery = "INSERT INTO employee_tbl SET 
					agency_code=:xagencycode, 
					agency_name=:xagencyname, 
					nickname=:xnickname, 
					emp_name_forid=:xempnameforid, 
					officename_forid=:xofficenameforid, 
					designationforid=:xdesignationforid, 
					profileid=:xprofileid, 
					uid=:xuid, 
					emp_idcode=:xempidcode, 
					pinword=:xpinword, 
					hr_emp_id=:xhrempid, 
					bio_loc_label=:xbioloclabel, 
					bio_location=:xbiolocation, 
					bio_no=:xbiono, 
					emp_name=:xempname, 
					gender=:xgender, 
					birthday=:xbirthday, 
					address=:xaddress, 
					officeid=:xofficeid, 
					officecode=:xofficecode, 
					officename=:xofficename, 
					officetitle=:xofficetitle, 
					officeabrv=:xofficeabrv, 
					oldofficeabrv=:xoldofficeabrv, 
					office_gps_location=:xofficegpslocation, 
					headofficer=:xheadofficer, 
					headtitle=:xheadtitle, 
					auth_head=:xauthhead, 
					auth_title=:xauthtitle, 
					auth_description=:xauthdescription, 
					year_employed=:xyearemployed, 
					year_calc=:xyearcalc, 
					type_employee_no=:xtypeemployeeno, 
					type_employee_abrv=:xtypeemployeeabrv, 
					type_employee=:xtypeemployee, 
					verified=:xverified, 
					activated=:xactivated, 
					work_location=:xworklocation, 
					shift_status=:xshiftstatus, 
					time_editable=:xtimeeditable, 
					priority_dtr=:xprioritydtr, 
					time_editable_value=:xtimeeditablevalue, 
					allowed_ot=:xallowedot, 
					position=:xposition, 
					designation=:xdesignation, 
					mphone=:xmphone, 
					empemail=:xempemail, 
					designation_at=:xdesignationat, 
					xdel=0, 
					slingid=:xslingid, 
					pocketid=:xpocketid, 
					createdby=:xcreatedby, 
					modifiedby=:xmodifiedby, 
					incaseofemergency_name=:xincaseofemergencyname, 
					incaseofemergency_contact=:xincaseofemergencycontact, 
					incaseofemergency_relation=:xincaseofemergencyrelation
					";
				$stmt = $this->cnn->prepare($insertQuery);
				$stmt->bindParam(':xagencycode', $agencycode);
				$stmt->bindParam(':xagencyname', $agencyname);
				$stmt->bindParam(':xnickname', $nickname);
				$stmt->bindParam(':xempnameforid', $empnameforid);
				$stmt->bindParam(':xofficenameforid', $officenameforid);
				$stmt->bindParam(':xdesignationforid', $designationforid);
				$stmt->bindParam(':xprofileid', $profileid);
				$stmt->bindParam(':xuid', $uid);
				$stmt->bindParam(':xempidcode', $empidcode);
				$stmt->bindParam(':xpinword', $pinword);
				$stmt->bindParam(':xhrempid', $hrempid);
				$stmt->bindParam(':xbioloclabel', $bioloclabel);
				$stmt->bindParam(':xbiolocation', $biolocation);
				$stmt->bindParam(':xbiono', $biono);
				$stmt->bindParam(':xempname', $empname);
				$stmt->bindParam(':xgender', $gender);
				$stmt->bindParam(':xbirthday', $birthday);
				$stmt->bindParam(':xaddress', $address);
				$stmt->bindParam(':xofficeid', $officeid);
				$stmt->bindParam(':xofficecode', $officecode);
				$stmt->bindParam(':xofficename', $officename);
				$stmt->bindParam(':xofficetitle', $officetitle);
				$stmt->bindParam(':xofficeabrv', $officeabrv);
				$stmt->bindParam(':xoldofficeabrv', $oldofficeabrv);
				$stmt->bindParam(':xofficegpslocation', $officegpslocation);
				$stmt->bindParam(':xheadofficer', $headofficer);
				$stmt->bindParam(':xheadtitle', $headtitle);
				$stmt->bindParam(':xauthhead', $authhead);
				$stmt->bindParam(':xauthtitle', $authtitle);
				$stmt->bindParam(':xauthdescription', $authdescription);
				$stmt->bindParam(':xyearemployed', $yearemployed);
				$stmt->bindParam(':xyearcalc', $yearcalc);
				$stmt->bindParam(':xtypeemployeeno', $typeemployeeno);
				$stmt->bindParam(':xtypeemployeeabrv', $typeemployeeabrv);
				$stmt->bindParam(':xtypeemployee', $typeemployee);
				$stmt->bindParam(':xverified', $verified);
				$stmt->bindParam(':xactivated', $designation);
				$stmt->bindParam(':xworklocation', $worklocation);
				$stmt->bindParam(':xshiftstatus', $shiftstatus);
				$stmt->bindParam(':xtimeeditable', $timeeditable);
				$stmt->bindParam(':xprioritydtr', $mphone);
				$stmt->bindParam(':xtimeeditablevalue', $empemail);
				$stmt->bindParam(':xallowedot', $designationat);
				$stmt->bindParam(':xposition', $createdby);
				$stmt->bindParam(':xdesignation', $modifiedby);
				$stmt->bindParam(':xmphone', $mphone);
				$stmt->bindParam(':xempemail', $empemail);
				$stmt->bindParam(':xdesignationat', $designationat);
				$stmt->bindParam(':xslingid', $slingid);
				$stmt->bindParam(':xpocketid', $pocketid);
				$stmt->bindParam(':xcreatedby', $createdby);
				$stmt->bindParam(':xmodifiedby', $modifiedby);
				$stmt->bindParam(':xincaseofemergencyname', $incaseofemergencyname);
				$stmt->bindParam(':xincaseofemergencycontact', $incaseofemergencycontact);
				$stmt->bindParam(':xincaseofemergencyrelation', $incaseofemergencyrelation);
				$stmt->execute();

				echo '<div class="alert alert-info alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Employee Successfully Registered. You may try <a href="login">Login</a> Now!';
				echo '</div>';

				$employeeidfinale = trim($empidcode);
				$imgdata = trim($imgdataemplx);
				if (file_exists("lib/employee-img-saved.php")) {
					require_once "lib/employee-img-saved.php";
				} elseif (file_exists("../../lib/employee-img-saved.php")) {
					require_once "../../lib/employee-img-saved.php";
				}
			}

			// List of Designation
			public function fn_ListDesignation() {
				$this->clearlist_employeeAcct();
				$this->getConnection();

				$selectQuery = "SELECT * FROM employee_tbl GROUP BY designationforid ORDER BY designationforid ASC";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_designationforidee[] = $rwRcrd['designationforid'];
					}
				}
			}

			// Search Employee using Employee ID
			public function fn_SearchEmployee($empidcodez) {
				$this->clearlist_employeeAcct();
				$this->getConnection();

				$selectQuery = "SELECT * FROM employee_tbl WHERE emp_idcode=:empidcode LIMIT 1";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':empidcode', $empidcodez);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_empautoidee[] = $rwRcrd['emp_autoid'];
						$this->list_profileidee[] = $rwRcrd['profileid'];
						$this->list_uidee[] = $rwRcrd['uid'];
						$this->list_empidcodeee[] = $rwRcrd['emp_idcode'];
						$this->list_pinwordee[] = $rwRcrd['pinword'];
						$this->list_hrempidee[] = $rwRcrd['hr_emp_id'];
						$this->list_bionoee[] = $rwRcrd['bio_no'];
						$this->list_birthdayee[] = $rwRcrd['birthday'];
						$this->list_empageee[] = $rwRcrd['emp_age'];
						$this->list_officeidee[] = $rwRcrd['officeid'];
						$this->list_officegpslocationee[] = $rwRcrd['office_gps_location'];
						$this->list_yearemployedee[] = $rwRcrd['year_employed'];
						$this->list_yearcalcee[] = $rwRcrd['year_calc'];
						$this->list_typeemployeenoee[] = $rwRcrd['type_employee_no'];
						$this->list_verifiedee[] = $rwRcrd['verified'];
						$this->list_activatedee[] = $rwRcrd['activated'];
						$this->list_shiftstatusee[] = $rwRcrd['shift_status'];
						$this->list_timeeditableee[] = $rwRcrd['time_editable'];
						$this->list_prioritydtree[] = $rwRcrd['priority_dtr'];
						$this->list_timeeditablevalueee[] = $rwRcrd['time_editable_value'];
						$this->list_allowedotee[] = $rwRcrd['allowed_ot'];
						$this->list_plantillanoee[] = $rwRcrd['plantilla_no'];
						$this->list_positionclassee[] = $rwRcrd['position_class'];
						$this->list_salaryamountee[] = $rwRcrd['salary_amount'];
						$this->list_salaryperiodee[] = $rwRcrd['salary_period'];
						$this->list_salaryamountperperiodee[] = $rwRcrd['salary_amount_per_period'];
						$this->list_authannualsalaryee[] = $rwRcrd['auth_annual_salary'];
						$this->list_actualannualsalaryee[] = $rwRcrd['actual_annual_salary'];
						$this->list_salarygradeee[] = $rwRcrd['salary_grade'];
						$this->list_salarystepsee[] = $rwRcrd['salary_steps'];
						$this->list_empareacodeee[] = $rwRcrd['emp_area_code'];
						$this->list_empareatypeee[] = $rwRcrd['emp_area_type'];
						$this->list_emplevelee[] = $rwRcrd['emp_level'];
						$this->list_philhealthcontributionee[] = $rwRcrd['philhealth_contribution'];
						$this->list_pagibibcontributionee[] = $rwRcrd['pagibib_contribution'];
						$this->list_ssscontributionee[] = $rwRcrd['sss_contribution'];
						$this->list_gsiscontributionee[] = $rwRcrd['gsis_contribution'];
						$this->list_employeecontributionee[] = $rwRcrd['employee_contribution'];
						$this->list_payrollbanknameee[] = $rwRcrd['payroll_bank_name'];
						$this->list_payrollbanknumberee[] = $rwRcrd['payroll_bank_number'];
						$this->list_philhealthnoee[] = $rwRcrd['philhealth_no'];
						$this->list_pagibibnoee[] = $rwRcrd['pagibib_no'];
						$this->list_sssnoee[] = $rwRcrd['sss_no'];
						$this->list_gsisnoee[] = $rwRcrd['gsis_no'];
						$this->list_taxidee[] = $rwRcrd['tax_id'];
						$this->list_lastdateemploymentee[] = $rwRcrd['last_date_employment'];
						$this->list_dateissuedee[] = $rwRcrd['date_issued'];
						$this->list_monthsvalidityee[] = $rwRcrd['months_validity'];
						$this->list_employmentstatusnoee[] = $rwRcrd['employment_status_no'];
						$this->list_employmentstatusee[] = $rwRcrd['employment_status'];
						$this->list_validuntilee[] = $rwRcrd['valid_until'];
						$this->list_mphoneee[] = $rwRcrd['mphone'];
						$this->list_empemailee[] = $rwRcrd['empemail'];
						$this->list_xdelee[] = $rwRcrd['xdel'];
						$this->list_slingidee[] = $rwRcrd['slingid'];
						$this->list_pocketidee[] = $rwRcrd['pocketid'];
						$this->list_createdbyee[] = $rwRcrd['createdby'];
						$this->list_modifiedbyee[] = $rwRcrd['modifiedby'];
						$this->list_modifiedatee[] = $rwRcrd['modified_at'];
						$this->list_createdatee[] = $rwRcrd['created_at'];
						$this->list_incaseofemergencycontactee[] = $rwRcrd['incaseofemergency_contact'];
						$this->list_incaseofemergencyelationee[] = $rwRcrd['incaseofemergency_relation'];


						include "lib/onoffline.php";
						if ( $onlineornot == 1 ) {
							$this->list_agencycodeee[] = utf8_encode($rwRcrd['agency_code']);
							$this->list_agencynameee[] = utf8_encode($rwRcrd['agency_name']);
							$this->list_nicknameee[] = utf8_encode($rwRcrd['nickname']);
							$this->list_empnameforidee[] = utf8_encode($rwRcrd['emp_name_forid']);
							$this->list_officenameforidee[] = utf8_encode($rwRcrd['officename_forid']);
							$this->list_designationforidee[] = utf8_encode($rwRcrd['designationforid']);
							$this->list_biolocationee[] = utf8_encode($rwRcrd['bio_location']);
							$this->list_empnameee[] = utf8_encode($rwRcrd['emp_name']);
							$this->list_genderee[] = utf8_encode($rwRcrd['gender']);
							$this->list_addressee[] = utf8_encode($rwRcrd['address']);
							$this->list_officecodeee[] = utf8_encode($rwRcrd['officecode']);
							$this->list_officenameee[] = utf8_encode($rwRcrd['officename']);
							$this->list_officetitleee[] = utf8_encode($rwRcrd['officetitle']);
							$this->list_officeabrvee[] = utf8_encode($rwRcrd['officeabrv']);
							$this->list_oldofficeabrvee[] = utf8_encode($rwRcrd['oldofficeabrv']);
							$this->list_headofficeree[] = utf8_encode($rwRcrd['headofficer']);
							$this->list_headtitleee[] = utf8_encode($rwRcrd['headtitle']);
							$this->list_authheadee[] = utf8_encode($rwRcrd['auth_head']);
							$this->list_authtitleee[] = utf8_encode($rwRcrd['auth_title']);
							$this->list_authdescriptionee[] = utf8_encode($rwRcrd['auth_description']);
							$this->list_typeemployeeabrvee[] = utf8_encode($rwRcrd['type_employee_abrv']);
							$this->list_typeemployeeee[] = utf8_encode($rwRcrd['type_employee']);
							$this->list_worklocationee[] = utf8_encode($rwRcrd['work_location']);
							$this->list_positionee[] = utf8_encode($rwRcrd['position']);
							$this->list_designationee[] = utf8_encode($rwRcrd['designation']);
							$this->list_employmentstatusabvree[] = utf8_encode($rwRcrd['employment_status_abvr']);
							$this->list_designationatee[] = utf8_encode($rwRcrd['designation_at']);
							$this->list_incaseofemergencynameee[] = utf8_encode($rwRcrd['incaseofemergency_name']);
						} else {
							$this->list_agencycodeee[] = $rwRcrd['agency_code'];
							$this->list_agencynameee[] = $rwRcrd['agency_name'];
							$this->list_nicknameee[] = $rwRcrd['nickname'];
							$this->list_empnameforidee[] = $rwRcrd['emp_name_forid'];
							$this->list_officenameforidee[] = $rwRcrd['officename_forid'];
							$this->list_designationforidee[] = $rwRcrd['designationforid'];
							$this->list_biolocationee[] = $rwRcrd['bio_location'];
							$this->list_empnameee[] = $rwRcrd['emp_name'];
							$this->list_genderee[] = $rwRcrd['gender'];
							$this->list_addressee[] = $rwRcrd['address'];
							$this->list_officecodeee[] = $rwRcrd['officecode'];
							$this->list_officenameee[] = $rwRcrd['officename'];
							$this->list_officetitleee[] = $rwRcrd['officetitle'];
							$this->list_officeabrvee[] = $rwRcrd['officeabrv'];
							$this->list_oldofficeabrvee[] = $rwRcrd['oldofficeabrv'];
							$this->list_headofficeree[] = $rwRcrd['headofficer'];
							$this->list_headtitleee[] = $rwRcrd['headtitle'];
							$this->list_authheadee[] = $rwRcrd['auth_head'];
							$this->list_authtitleee[] = $rwRcrd['auth_title'];
							$this->list_authdescriptionee[] = $rwRcrd['auth_description'];
							$this->list_typeemployeeabrvee[] = $rwRcrd['type_employee_abrv'];
							$this->list_typeemployeeee[] = $rwRcrd['type_employee'];
							$this->list_worklocationee[] = $rwRcrd['work_location'];
							$this->list_positionee[] = $rwRcrd['position'];
							$this->list_designationee[] = $rwRcrd['designation'];
							$this->list_employmentstatusabvree[] = $rwRcrd['employment_status_abvr'];
							$this->list_designationatee[] = $rwRcrd['designation_at'];
							$this->list_incaseofemergencynameee[] = $rwRcrd['incaseofemergency_name'];
						}
					}

					return true;
				} else {
					return false;
				}
			}

			// Employee Authentication
			public function fn_employeeAuth($empidcodez,$pinwordz) {
				$this->clearlist_employeeAcct();
				$this->getConnection();

				$empidcode = trim($empidcodez);
				$pinword = md5(trim($pinwordz));

				$selectQuery = "SELECT * FROM employee_tbl WHERE emp_idcode=:empidcode AND pinword=:pinword LIMIT 1";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':empidcode', $empidcode);
				$stmt->bindParam(':pinword', $pinword);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_empautoidee[] = $rwRcrd['emp_autoid'];
						$this->list_profileidee[] = $rwRcrd['profileid'];
						$this->list_uidee[] = $rwRcrd['uid'];
						$this->list_empidcodeee[] = $rwRcrd['emp_idcode'];
						$this->list_pinwordee[] = $rwRcrd['pinword'];
						$this->list_hrempidee[] = $rwRcrd['hr_emp_id'];
						$this->list_bionoee[] = $rwRcrd['bio_no'];
						$this->list_birthdayee[] = $rwRcrd['birthday'];
						$this->list_empageee[] = $rwRcrd['emp_age'];
						$this->list_officeidee[] = $rwRcrd['officeid'];
						$this->list_officegpslocationee[] = $rwRcrd['office_gps_location'];
						$this->list_yearemployedee[] = $rwRcrd['year_employed'];
						$this->list_yearcalcee[] = $rwRcrd['year_calc'];
						$this->list_typeemployeenoee[] = $rwRcrd['type_employee_no'];
						$this->list_verifiedee[] = $rwRcrd['verified'];
						$this->list_activatedee[] = $rwRcrd['activated'];
						$this->list_shiftstatusee[] = $rwRcrd['shift_status'];
						$this->list_timeeditableee[] = $rwRcrd['time_editable'];
						$this->list_prioritydtree[] = $rwRcrd['priority_dtr'];
						$this->list_timeeditablevalueee[] = $rwRcrd['time_editable_value'];
						$this->list_allowedotee[] = $rwRcrd['allowed_ot'];
						$this->list_plantillanoee[] = $rwRcrd['plantilla_no'];
						$this->list_positionclassee[] = $rwRcrd['position_class'];
						$this->list_salaryamountee[] = $rwRcrd['salary_amount'];
						$this->list_salaryperiodee[] = $rwRcrd['salary_period'];
						$this->list_salaryamountperperiodee[] = $rwRcrd['salary_amount_per_period'];
						$this->list_authannualsalaryee[] = $rwRcrd['auth_annual_salary'];
						$this->list_actualannualsalaryee[] = $rwRcrd['actual_annual_salary'];
						$this->list_salarygradeee[] = $rwRcrd['salary_grade'];
						$this->list_salarystepsee[] = $rwRcrd['salary_steps'];
						$this->list_empareacodeee[] = $rwRcrd['emp_area_code'];
						$this->list_empareatypeee[] = $rwRcrd['emp_area_type'];
						$this->list_emplevelee[] = $rwRcrd['emp_level'];
						$this->list_philhealthcontributionee[] = $rwRcrd['philhealth_contribution'];
						$this->list_pagibibcontributionee[] = $rwRcrd['pagibib_contribution'];
						$this->list_ssscontributionee[] = $rwRcrd['sss_contribution'];
						$this->list_gsiscontributionee[] = $rwRcrd['gsis_contribution'];
						$this->list_employeecontributionee[] = $rwRcrd['employee_contribution'];
						$this->list_payrollbanknameee[] = $rwRcrd['payroll_bank_name'];
						$this->list_payrollbanknumberee[] = $rwRcrd['payroll_bank_number'];
						$this->list_philhealthnoee[] = $rwRcrd['philhealth_no'];
						$this->list_pagibibnoee[] = $rwRcrd['pagibib_no'];
						$this->list_sssnoee[] = $rwRcrd['sss_no'];
						$this->list_gsisnoee[] = $rwRcrd['gsis_no'];
						$this->list_taxidee[] = $rwRcrd['tax_id'];
						$this->list_lastdateemploymentee[] = $rwRcrd['last_date_employment'];
						$this->list_dateissuedee[] = $rwRcrd['date_issued'];
						$this->list_monthsvalidityee[] = $rwRcrd['months_validity'];
						$this->list_employmentstatusnoee[] = $rwRcrd['employment_status_no'];
						$this->list_employmentstatusee[] = $rwRcrd['employment_status'];
						$this->list_validuntilee[] = $rwRcrd['valid_until'];
						$this->list_mphoneee[] = $rwRcrd['mphone'];
						$this->list_empemailee[] = $rwRcrd['empemail'];
						$this->list_slingidee[] = $rwRcrd['slingid'];
						$this->list_pocketidee[] = $rwRcrd['pocketid'];
						$this->list_xdelee[] = $rwRcrd['xdel'];
						$this->list_createdbyee[] = $rwRcrd['createdby'];
						$this->list_modifiedbyee[] = $rwRcrd['modifiedby'];
						$this->list_modifiedatee[] = $rwRcrd['modified_at'];
						$this->list_createdatee[] = $rwRcrd['created_at'];
						$this->list_incaseofemergencycontactee[] = $rwRcrd['incaseofemergency_contact'];
						$this->list_incaseofemergencyelationee[] = $rwRcrd['incaseofemergency_relation'];

						include "lib/onoffline.php";
						if ( $onlineornot == 1 ) {
							$this->list_agencycodeee[] = utf8_encode($rwRcrd['agency_code']);
							$this->list_agencynameee[] = utf8_encode($rwRcrd['agency_name']);
							$this->list_nicknameee[] = utf8_encode($rwRcrd['nickname']);
							$this->list_empnameforidee[] = utf8_encode($rwRcrd['emp_name_forid']);
							$this->list_officenameforidee[] = utf8_encode($rwRcrd['officename_forid']);
							$this->list_designationforidee[] = utf8_encode($rwRcrd['designationforid']);
							$this->list_biolocationee[] = utf8_encode($rwRcrd['bio_location']);
							$this->list_empnameee[] = utf8_encode($rwRcrd['emp_name']);
							$this->list_genderee[] = utf8_encode($rwRcrd['gender']);
							$this->list_addressee[] = utf8_encode($rwRcrd['address']);
							$this->list_officecodeee[] = utf8_encode($rwRcrd['officecode']);
							$this->list_officenameee[] = utf8_encode($rwRcrd['officename']);
							$this->list_officetitleee[] = utf8_encode($rwRcrd['officetitle']);
							$this->list_officeabrvee[] = utf8_encode($rwRcrd['officeabrv']);
							$this->list_oldofficeabrvee[] = utf8_encode($rwRcrd['oldofficeabrv']);
							$this->list_headofficeree[] = utf8_encode($rwRcrd['headofficer']);
							$this->list_headtitleee[] = utf8_encode($rwRcrd['headtitle']);
							$this->list_authheadee[] = utf8_encode($rwRcrd['auth_head']);
							$this->list_authtitleee[] = utf8_encode($rwRcrd['auth_title']);
							$this->list_authdescriptionee[] = utf8_encode($rwRcrd['auth_description']);
							$this->list_typeemployeeabrvee[] = utf8_encode($rwRcrd['type_employee_abrv']);
							$this->list_typeemployeeee[] = utf8_encode($rwRcrd['type_employee']);
							$this->list_worklocationee[] = utf8_encode($rwRcrd['work_location']);
							$this->list_positionee[] = utf8_encode($rwRcrd['position']);
							$this->list_designationee[] = utf8_encode($rwRcrd['designation']);
							$this->list_employmentstatusabvree[] = utf8_encode($rwRcrd['employment_status_abvr']);
							$this->list_designationatee[] = utf8_encode($rwRcrd['designation_at']);
							$this->list_incaseofemergencynameee[] = utf8_encode($rwRcrd['incaseofemergency_name']);
						} else {
							$this->list_agencycodeee[] = $rwRcrd['agency_code'];
							$this->list_agencynameee[] = $rwRcrd['agency_name'];
							$this->list_nicknameee[] = $rwRcrd['nickname'];
							$this->list_empnameforidee[] = $rwRcrd['emp_name_forid'];
							$this->list_officenameforidee[] = $rwRcrd['officename_forid'];
							$this->list_designationforidee[] = $rwRcrd['designationforid'];
							$this->list_biolocationee[] = $rwRcrd['bio_location'];
							$this->list_empnameee[] = $rwRcrd['emp_name'];
							$this->list_genderee[] = $rwRcrd['gender'];
							$this->list_addressee[] = $rwRcrd['address'];
							$this->list_officecodeee[] = $rwRcrd['officecode'];
							$this->list_officenameee[] = $rwRcrd['officename'];
							$this->list_officetitleee[] = $rwRcrd['officetitle'];
							$this->list_officeabrvee[] = $rwRcrd['officeabrv'];
							$this->list_oldofficeabrvee[] = $rwRcrd['oldofficeabrv'];
							$this->list_headofficeree[] = $rwRcrd['headofficer'];
							$this->list_headtitleee[] = $rwRcrd['headtitle'];
							$this->list_authheadee[] = $rwRcrd['auth_head'];
							$this->list_authtitleee[] = $rwRcrd['auth_title'];
							$this->list_authdescriptionee[] = $rwRcrd['auth_description'];
							$this->list_typeemployeeabrvee[] = $rwRcrd['type_employee_abrv'];
							$this->list_typeemployeeee[] = $rwRcrd['type_employee'];
							$this->list_worklocationee[] = $rwRcrd['work_location'];
							$this->list_positionee[] = $rwRcrd['position'];
							$this->list_designationee[] = $rwRcrd['designation'];
							$this->list_employmentstatusabvree[] = $rwRcrd['employment_status_abvr'];
							$this->list_designationatee[] = $rwRcrd['designation_at'];
							$this->list_incaseofemergencynameee[] = $rwRcrd['incaseofemergency_name'];
						}
					}

					return true;
				} else {
					return false;
				}
			}

			// Searching for Duplicate Biometric Number True/False
			public function Search_employeeAcct_BioNmr($bioempidnmbr) {
				$this->clearlist_employeeAcct();
				$this->getConnection();
				$bioempidnmbr = trim(htmlspecialchars($bioempidnmbr));

				$selectQuery = "SELECT * FROM employee_tbl WHERE bio_no=:bioempidnmbr";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':bioempidnmbr', $bioempidnmbr);
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

			// Searching for Duplicate Employee ID True/False
			public function Search_employeeAcct_ID($emploid) {
				$this->clearlist_employeeAcct();
				$this->getConnection();
				$emploidg = trim(htmlspecialchars($emploid));

				$selectQuery = "SELECT * FROM employee_tbl WHERE emp_idcode=:emploidg";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':emploidg', $emploidg);
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

			// Searching for Duplicate Biometric Location and Bio Number True/False
			public function Search_employeeAcct_BioNumber($biolocbrrr,$bionumbrrr) {
				$this->clearlist_employeeAcct();
				$this->getConnection();
				$bionumbrrrg = trim(htmlspecialchars($bionumbrrr));
				$biolocbrrr = trim(htmlspecialchars($biolocbrrr));

				$selectQuery = "SELECT * FROM employee_tbl WHERE bio_loc_no=:biolocno AND bio_no=:biono";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':biono', $bionumbrrrg);
				$stmt->bindParam(':biolocno', $biolocbrrr);
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

			// Searching for Duplicate Employee Name True/False
			public function Search_employeeAcct_EmployeeName($employeename) {
				$this->clearlist_employeeAcct();
				$this->getConnection();
				$xemployeeName = trim($employeename);

				$selectQuery = "SELECT * FROM employee_tbl WHERE emp_name=:xemployeeName OR emp_name_forid=:xemployeeName LIMIT 1";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':xemployeeName', $xemployeeName);
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

			// Auth Employee using User ID
			public function authEmployee_usingUserID($userid) {
				$this->clearlist_employeeAcct();
				$this->getConnection();
				$authxuserid = trim(htmlspecialchars($userid));

				$selectQuery = "SELECT * FROM employee_tbl WHERE uid=:authxuserid LIMIT 1";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':authxuserid', $authxuserid);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_empautoidee[] = $rwRcrd['emp_autoid'];
						$this->list_profileidee[] = $rwRcrd['profileid'];
						$this->list_uidee[] = $rwRcrd['uid'];
						$this->list_empidcodeee[] = $rwRcrd['emp_idcode'];
						$this->list_pinwordee[] = $rwRcrd['pinword'];
						$this->list_hrempidee[] = $rwRcrd['hr_emp_id'];
						$this->list_bionoee[] = $rwRcrd['bio_no'];
						$this->list_birthdayee[] = $rwRcrd['birthday'];
						$this->list_empageee[] = $rwRcrd['emp_age'];
						$this->list_officeidee[] = $rwRcrd['officeid'];
						$this->list_officegpslocationee[] = $rwRcrd['office_gps_location'];
						$this->list_yearemployedee[] = $rwRcrd['year_employed'];
						$this->list_yearcalcee[] = $rwRcrd['year_calc'];
						$this->list_typeemployeenoee[] = $rwRcrd['type_employee_no'];
						$this->list_verifiedee[] = $rwRcrd['verified'];
						$this->list_activatedee[] = $rwRcrd['activated'];
						$this->list_shiftstatusee[] = $rwRcrd['shift_status'];
						$this->list_timeeditableee[] = $rwRcrd['time_editable'];
						$this->list_prioritydtree[] = $rwRcrd['priority_dtr'];
						$this->list_timeeditablevalueee[] = $rwRcrd['time_editable_value'];
						$this->list_allowedotee[] = $rwRcrd['allowed_ot'];
						$this->list_plantillanoee[] = $rwRcrd['plantilla_no'];
						$this->list_positionclassee[] = $rwRcrd['position_class'];
						$this->list_salaryamountee[] = $rwRcrd['salary_amount'];
						$this->list_salaryperiodee[] = $rwRcrd['salary_period'];
						$this->list_salaryamountperperiodee[] = $rwRcrd['salary_amount_per_period'];
						$this->list_authannualsalaryee[] = $rwRcrd['auth_annual_salary'];
						$this->list_actualannualsalaryee[] = $rwRcrd['actual_annual_salary'];
						$this->list_salarygradeee[] = $rwRcrd['salary_grade'];
						$this->list_salarystepsee[] = $rwRcrd['salary_steps'];
						$this->list_empareacodeee[] = $rwRcrd['emp_area_code'];
						$this->list_empareatypeee[] = $rwRcrd['emp_area_type'];
						$this->list_emplevelee[] = $rwRcrd['emp_level'];
						$this->list_philhealthcontributionee[] = $rwRcrd['philhealth_contribution'];
						$this->list_pagibibcontributionee[] = $rwRcrd['pagibib_contribution'];
						$this->list_ssscontributionee[] = $rwRcrd['sss_contribution'];
						$this->list_gsiscontributionee[] = $rwRcrd['gsis_contribution'];
						$this->list_employeecontributionee[] = $rwRcrd['employee_contribution'];
						$this->list_payrollbanknameee[] = $rwRcrd['payroll_bank_name'];
						$this->list_payrollbanknumberee[] = $rwRcrd['payroll_bank_number'];
						$this->list_philhealthnoee[] = $rwRcrd['philhealth_no'];
						$this->list_pagibibnoee[] = $rwRcrd['pagibib_no'];
						$this->list_sssnoee[] = $rwRcrd['sss_no'];
						$this->list_gsisnoee[] = $rwRcrd['gsis_no'];
						$this->list_taxidee[] = $rwRcrd['tax_id'];
						$this->list_lastdateemploymentee[] = $rwRcrd['last_date_employment'];
						$this->list_dateissuedee[] = $rwRcrd['date_issued'];
						$this->list_monthsvalidityee[] = $rwRcrd['months_validity'];
						$this->list_employmentstatusnoee[] = $rwRcrd['employment_status_no'];
						$this->list_employmentstatusee[] = $rwRcrd['employment_status'];
						$this->list_validuntilee[] = $rwRcrd['valid_until'];
						$this->list_mphoneee[] = $rwRcrd['mphone'];
						$this->list_empemailee[] = $rwRcrd['empemail'];
						$this->list_slingidee[] = $rwRcrd['slingid'];
						$this->list_pocketidee[] = $rwRcrd['pocketid'];
						$this->list_incaseofemergencycontactee[] = $rwRcrd['incaseofemergency_contact'];
						$this->list_incaseofemergencyelationee[] = $rwRcrd['incaseofemergency_relation'];
						$this->list_xdelee[] = $rwRcrd['xdel'];
						$this->list_createdbyee[] = $rwRcrd['createdby'];
						$this->list_modifiedbyee[] = $rwRcrd['modifiedby'];
						$this->list_modifiedatee[] = $rwRcrd['modified_at'];
						$this->list_createdatee[] = $rwRcrd['created_at'];

						include "lib/onoffline.php";
						if ( $onlineornot == 1 ) {
							$this->list_agencycodeee[] = utf8_encode($rwRcrd['agency_code']);
							$this->list_agencynameee[] = utf8_encode($rwRcrd['agency_name']);
							$this->list_nicknameee[] = utf8_encode($rwRcrd['nickname']);
							$this->list_empnameforidee[] = utf8_encode($rwRcrd['emp_name_forid']);
							$this->list_officenameforidee[] = utf8_encode($rwRcrd['officename_forid']);
							$this->list_designationforidee[] = utf8_encode($rwRcrd['designationforid']);
							$this->list_biolocationee[] = utf8_encode($rwRcrd['bio_location']);
							$this->list_empnameee[] = utf8_encode($rwRcrd['emp_name']);
							$this->list_genderee[] = utf8_encode($rwRcrd['gender']);
							$this->list_addressee[] = utf8_encode($rwRcrd['address']);
							$this->list_officecodeee[] = utf8_encode($rwRcrd['officecode']);
							$this->list_officenameee[] = utf8_encode($rwRcrd['officename']);
							$this->list_officetitleee[] = utf8_encode($rwRcrd['officetitle']);
							$this->list_officeabrvee[] = utf8_encode($rwRcrd['officeabrv']);
							$this->list_oldofficeabrvee[] = utf8_encode($rwRcrd['oldofficeabrv']);
							$this->list_headofficeree[] = utf8_encode($rwRcrd['headofficer']);
							$this->list_headtitleee[] = utf8_encode($rwRcrd['headtitle']);
							$this->list_authheadee[] = utf8_encode($rwRcrd['auth_head']);
							$this->list_authtitleee[] = utf8_encode($rwRcrd['auth_title']);
							$this->list_authdescriptionee[] = utf8_encode($rwRcrd['auth_description']);
							$this->list_typeemployeeabrvee[] = utf8_encode($rwRcrd['type_employee_abrv']);
							$this->list_typeemployeeee[] = utf8_encode($rwRcrd['type_employee']);
							$this->list_worklocationee[] = utf8_encode($rwRcrd['work_location']);
							$this->list_positionee[] = utf8_encode($rwRcrd['position']);
							$this->list_designationee[] = utf8_encode($rwRcrd['designation']);
							$this->list_employmentstatusabvree[] = utf8_encode($rwRcrd['employment_status_abvr']);
							$this->list_designationatee[] = utf8_encode($rwRcrd['designation_at']);
							$this->list_incaseofemergencynameee[] = utf8_encode($rwRcrd['incaseofemergency_name']);
						} else {
							$this->list_agencycodeee[] = $rwRcrd['agency_code'];
							$this->list_agencynameee[] = $rwRcrd['agency_name'];
							$this->list_nicknameee[] = $rwRcrd['nickname'];
							$this->list_empnameforidee[] = $rwRcrd['emp_name_forid'];
							$this->list_officenameforidee[] = $rwRcrd['officename_forid'];
							$this->list_designationforidee[] = $rwRcrd['designationforid'];
							$this->list_biolocationee[] = $rwRcrd['bio_location'];
							$this->list_empnameee[] = $rwRcrd['emp_name'];
							$this->list_genderee[] = $rwRcrd['gender'];
							$this->list_addressee[] = $rwRcrd['address'];
							$this->list_officecodeee[] = $rwRcrd['officecode'];
							$this->list_officenameee[] = $rwRcrd['officename'];
							$this->list_officetitleee[] = $rwRcrd['officetitle'];
							$this->list_officeabrvee[] = $rwRcrd['officeabrv'];
							$this->list_oldofficeabrvee[] = $rwRcrd['oldofficeabrv'];
							$this->list_headofficeree[] = $rwRcrd['headofficer'];
							$this->list_headtitleee[] = $rwRcrd['headtitle'];
							$this->list_authheadee[] = $rwRcrd['auth_head'];
							$this->list_authtitleee[] = $rwRcrd['auth_title'];
							$this->list_authdescriptionee[] = $rwRcrd['auth_description'];
							$this->list_typeemployeeabrvee[] = $rwRcrd['type_employee_abrv'];
							$this->list_typeemployeeee[] = $rwRcrd['type_employee'];
							$this->list_worklocationee[] = $rwRcrd['work_location'];
							$this->list_positionee[] = $rwRcrd['position'];
							$this->list_designationee[] = $rwRcrd['designation'];
							$this->list_employmentstatusabvree[] = $rwRcrd['employment_status_abvr'];
							$this->list_designationatee[] = $rwRcrd['designation_at'];
							$this->list_incaseofemergencynameee[] = $rwRcrd['incaseofemergency_name'];
						}
					}

					return true;
				} else {
					return false;
				}
			}
		}

	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error @ EmployeeAcct: {$err_msg}</p>";
		die;
	}

?>
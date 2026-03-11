<?php 
	
	//  Get the data of current employee
	require_once "model/employee/index.php";
	$emmplAcctInfo = new employeeAcct();
	if ( $emmplAcctInfo->fn_SearchEmployee($empidcodeNow) ) {

		$emmplAcctInfo->fn_SearchEmployee($empidcodeNow);

		for ($i = 0; $i < count($emmplAcctInfo->list_empautoidee); $i++) {
			$empautoidcc = $emmplAcctInfo->list_empautoidee[$i];
			$agencycodecc = $emmplAcctInfo->list_agencycodeee[$i];
			$agencynamecc = $emmplAcctInfo->list_agencynameee[$i];

			$nicknamecc = $emmplAcctInfo->list_nicknameee[$i];
			$empnameforidcc = $emmplAcctInfo->list_empnameforidee[$i];
			$officenameforidcc = $emmplAcctInfo->list_officenameforidee[$i];
			$designationforidcc = $emmplAcctInfo->list_designationforidee[$i];

			$profileidcc = $emmplAcctInfo->list_profileidee[$i];
			$uidcc = $emmplAcctInfo->list_uidee[$i];
			$empidcodecc = $emmplAcctInfo->list_empidcodeee[$i];
			$pinwordcc = $emmplAcctInfo->list_pinwordee[$i];
			$hrempidcc = $emmplAcctInfo->list_hrempidee[$i];
			$biolocationcc = $emmplAcctInfo->list_biolocationee[$i];
			$bionocc = $emmplAcctInfo->list_bionoee[$i];
			$empnamecc = $emmplAcctInfo->list_empnameee[$i];
			$gendercc = $emmplAcctInfo->list_genderee[$i];
			$birthdaycc = $emmplAcctInfo->list_birthdayee[$i];
			$empagecc = $emmplAcctInfo->list_empageee[$i];
			$officeidcc = $emmplAcctInfo->list_officeidee[$i];
			$officecodecc = $emmplAcctInfo->list_officecodeee[$i];
			$officenamecc = $emmplAcctInfo->list_officenameee[$i];
			$officetitlecc = $emmplAcctInfo->list_officetitleee[$i];
			$officeabrvcc = $emmplAcctInfo->list_officeabrvee[$i];
			$oldofficeabrvcc = $emmplAcctInfo->list_oldofficeabrvee[$i];
			$officegpslocationcc = $emmplAcctInfo->list_officegpslocationee[$i];
			$headofficercc = $emmplAcctInfo->list_headofficeree[$i];
			$headtitlecc = $emmplAcctInfo->list_headtitleee[$i];
			$authheadcc = $emmplAcctInfo->list_authheadee[$i];
			$authtitlecc = $emmplAcctInfo->list_authtitleee[$i];
			$authdescriptioncc = $emmplAcctInfo->list_authdescriptionee[$i];
			$yearemployedcc = $emmplAcctInfo->list_yearemployedee[$i];
			$yearcalccc = $emmplAcctInfo->list_yearcalcee[$i];
			$typeemployeenocc = $emmplAcctInfo->list_typeemployeenoee[$i];
			$typeemployeeabrvcc = $emmplAcctInfo->list_typeemployeeabrvee[$i];
			$typeemployeecc = $emmplAcctInfo->list_typeemployeeee[$i];
			$verifiedcc = $emmplAcctInfo->list_verifiedee[$i];
			$activatedcc = $emmplAcctInfo->list_activatedee[$i];
			$worklocationcc = $emmplAcctInfo->list_worklocationee[$i];
			$shiftstatuscc = $emmplAcctInfo->list_shiftstatusee[$i];
			$timeeditablecc = $emmplAcctInfo->list_timeeditableee[$i];
			$prioritydtrcc = $emmplAcctInfo->list_prioritydtree[$i];
			$timeeditablevaluecc = $emmplAcctInfo->list_timeeditablevalueee[$i];
			$allowedotcc = $emmplAcctInfo->list_allowedotee[$i];
			$plantillanocc = $emmplAcctInfo->list_plantillanoee[$i];
			$positioncc = $emmplAcctInfo->list_positionee[$i];
			$designationcc = $emmplAcctInfo->list_designationee[$i];
			$positionclasscc = $emmplAcctInfo->list_positionclassee[$i];
			$salaryamountcc = $emmplAcctInfo->list_salaryamountee[$i];
			$salaryperiodcc = $emmplAcctInfo->list_salaryperiodee[$i];
			$salaryamountperperiodcc = $emmplAcctInfo->list_salaryamountperperiodee[$i];
			$authannualsalarycc = $emmplAcctInfo->list_authannualsalaryee[$i];
			$actualannualsalarycc = $emmplAcctInfo->list_actualannualsalaryee[$i];
			$salarygradecc = $emmplAcctInfo->list_salarygradeee[$i];
			$salarystepscc = $emmplAcctInfo->list_salarystepsee[$i];
			$empareacodecc = $emmplAcctInfo->list_empareacodeee[$i];
			$empareatypecc = $emmplAcctInfo->list_empareatypeee[$i];
			$emplevelcc = $emmplAcctInfo->list_emplevelee[$i];
			$philhealthcontributioncc = $emmplAcctInfo->list_philhealthcontributionee[$i];
			$pagibibcontributioncc = $emmplAcctInfo->list_pagibibcontributionee[$i];
			$ssscontributioncc = $emmplAcctInfo->list_ssscontributionee[$i];
			$gsiscontributioncc = $emmplAcctInfo->list_gsiscontributionee[$i];
			$employeecontributioncc = $emmplAcctInfo->list_employeecontributionee[$i];
			$payrollbanknamecc = $emmplAcctInfo->list_payrollbanknameee[$i];
			$payrollbanknumbercc = $emmplAcctInfo->list_payrollbanknumberee[$i];
			$philhealthnocc = $emmplAcctInfo->list_philhealthnoee[$i];
			$pagibibnocc = $emmplAcctInfo->list_pagibibnoee[$i];
			$sssnocc = $emmplAcctInfo->list_sssnoee[$i];
			$gsisnocc = $emmplAcctInfo->list_gsisnoee[$i];
			$taxidcc = $emmplAcctInfo->list_taxidee[$i];
			$lastdateemploymentcc = $emmplAcctInfo->list_lastdateemploymentee[$i];
			$dateissuedcc = $emmplAcctInfo->list_dateissuedee[$i];
			$monthsvaliditycc = $emmplAcctInfo->list_monthsvalidityee[$i];
			$employmentstatusnocc = $emmplAcctInfo->list_employmentstatusnoee[$i];
			$employmentstatuscc = $emmplAcctInfo->list_employmentstatusee[$i];
			$employmentstatusabvrcc = $emmplAcctInfo->list_employmentstatusabvree[$i];
			$validuntilcc = $emmplAcctInfo->list_validuntilee[$i];
			$mphonecc = $emmplAcctInfo->list_mphoneee[$i];
			$empemailcc = $emmplAcctInfo->list_empemailee[$i];
			$designationatcc = $emmplAcctInfo->list_designationatee[$i];
			$xdelcc = $emmplAcctInfo->list_xdelee[$i];
			$createdbycc = $emmplAcctInfo->list_createdbyee[$i];
			$modifiedbycc = $emmplAcctInfo->list_modifiedbyee[$i];
			$modifiedatcc = $emmplAcctInfo->list_modifiedatee[$i];
			$createdatcc = $emmplAcctInfo->list_createdatee[$i];

			$incaseofemergencynamecc = $emmplAcctInfo->list_incaseofemergencynameee[$i];
			$incaseofemergencycontactcc = $emmplAcctInfo->list_incaseofemergencycontactee[$i];
			$incaseofemergencyelationcc = $emmplAcctInfo->list_incaseofemergencyelationee[$i];

			$addresscc = $emmplAcctInfo->list_addressee[$i];
		}
	}

?>
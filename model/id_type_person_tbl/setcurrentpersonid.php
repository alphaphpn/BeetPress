<?php 
	
	//  Get the data of current Type of ID
	require_once "model/id_type_person_tbl/index.php";
	$personIDInfo = new clssPersonsIDies();
	if ( $personIDInfo->Search_clssPersonsIDies($profileidNowX,$empidcodeNowX) ) {

		$personIDInfo->Search_clssPersonsIDies($profileidNowX,$empidcodeNowX);

		for ($i = 0; $i < count($personIDInfo->list_autoidtypekk); $i++) {
			$autoidtypeoo = $personIDInfo->list_autoidtypekk[$i];
			$profileidoo = $personIDInfo->list_profileidkk[$i];
			$empidcodeoo = $personIDInfo->list_empidcodekk[$i];
			$idtypeoo = $personIDInfo->list_idtypekk[$i];
			$idnumberoo = $personIDInfo->list_idnumberkk[$i];
		}
	}

?>
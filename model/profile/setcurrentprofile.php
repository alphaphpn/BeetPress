<?php 
	
	//  Get the data of current profile
	require_once "model/profile/index.php";
	$profileInfo = new clssProfile();
	if ( $profileInfo->Search_clssProfile($profileidNow) ) {

		$profileInfo->Search_clssProfile($profileidNow);

		for ($i = 0; $i < count($profileInfo->list_profileautoidii); $i++) {
			$profileautoidbb = $profileInfo->list_profileautoidii[$i];
			$firstnamebb = $profileInfo->list_firstnameii[$i];
			$middlenamebb = $profileInfo->list_middlenameii[$i];
			$lastnamegg = $profileInfo->list_lastnameii[$i];
			$suffixgg = $profileInfo->list_suffixii[$i];
		}
	}

?>
<?php

	// Get the SubDTR Time of Employee base on the current date
	require_once "model/employee_dtr_sub/index.php";
	$yrdtrNow = trim(date("Y"));
	$monthdtrNow = trim(date("m"));
	$daynumberdtrNow = trim(number_format(date("d")));
	$empDTRSub = new employeeDTRSub();
	if ( $empDTRSub->Search_employeeDTRSub($empidcodeNow,$yrdtrNow,$monthdtrNow,$daynumberdtrNow) ) {
		$empDTRSub->Search_employeeDTRSub($empidcodeNow,$yrdtrNow,$monthdtrNow,$daynumberdtrNow);
		for($i = 0; $i < count($empDTRSub->list_empdtrsubautoiddd); $i++) {
			$amtimeinNow = $empDTRSub->list_amtimeindd[$i];
			$amtimeoutNow = $empDTRSub->list_amtimeoutdd[$i];
			$pmtimeinNow =$empDTRSub->list_pmtimeindd[$i];
			$pmtimeoutNow = $empDTRSub->list_pmtimeoutdd[$i];
		}
	} else {
		$amtimeinNow = null;
		$amtimeoutNow = null;
		$pmtimeinNow = null;
		$pmtimeoutNow = null;
	}

?>
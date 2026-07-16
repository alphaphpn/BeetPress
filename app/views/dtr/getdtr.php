<?php

	// Get the SubDTR Time of Employee base on the current date
	
	require_once "model/employee_dtr_sub/index.php";
	$empDTRSub = new employeeDTRSub();

	require_once "model/holidays/index.php";
	$getholidatz = new holidayz();

	require_once "model/halfday/index.php";
	$gethalfdayz = new halfdayz();

	if ( $empDTRSub->lstdaytimem_employeeDTRSub($dtrcodetu) ) {
		// Removed redundant function call here
		for($i = 0; $i < count($empDTRSub->list_empdtrsubautoiddd); $i++) {
			$daynameNow = trim($empDTRSub->list_namedaydd[$i]);
			$monthnoNow = trim($empDTRSub->list_monthnodd[$i]);
			$yearnoNow = trim($empDTRSub->list_yearnodd[$i]);
			$daynoNow = trim($empDTRSub->list_daynodd[$i]);
			$amtimeinNow = trim($empDTRSub->list_amtimeindd[$i]);
			$amtimeoutNow = trim($empDTRSub->list_amtimeoutdd[$i]);
			$pmtimeinNow = trim($empDTRSub->list_pmtimeindd[$i]);
			$pmtimeoutNow = trim($empDTRSub->list_pmtimeoutdd[$i]);

			$lateutimehourNow = trim($empDTRSub->list_lateutimehourdd[$i]) ? trim($empDTRSub->list_lateutimehourdd[$i]) : null;
			$lateutimeminNow = trim($empDTRSub->list_lateutimemindd[$i]) ? trim($empDTRSub->list_lateutimemindd[$i]) : null;
			$overtimehourNow = trim($empDTRSub->list_overtimehourdd[$i]) ? trim($empDTRSub->list_overtimehourdd[$i]) : null;
			$overtimeminNow = trim($empDTRSub->list_overtimemindd[$i]) ? trim($empDTRSub->list_overtimemindd[$i]) : null;

			if ( $daynameNow == "Sat" ) {
				if ( empty($amtimeinNow) && empty($amtimeoutNow) && empty($pmtimeinNow) && empty($pmtimeoutNow) ) {
					// Check for Holiday
					if ( $getholidatz->Search_holidayz($yearnoNow,$monthnoNow,$daynoNow) ) {
						for($h = 0; $h < count($getholidatz->list_holidaysautoid); $h++) {
							$holidaymyname = trim($getholidatz->list_holidayname[$h]);

							echo '<tr align="center">';
								echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynameNow.'</td>';
								echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynoNow.'</td>';
								echo '<td colspan="4" class="p-0 font-size-8 align-middle font-color-dark-blue border-end"><b>'.$holidaymyname.'</b></td>';
								echo '<td class="p-0 font-size-8 border-end"></td>';
								echo '<td class="p-0 font-size-8 border-end"></td>';
								echo '<td class="p-0 font-size-8 border-end"></td>';
								echo '<td class="p-0 font-size-8"></td>';
							echo '</tr>';
						}
					} else {
						echo '<tr align="center">';
							echo '<td class="p-0 font-size-8 align-middle border-end"></td>';
							echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynoNow.'</td>';
							echo '<td colspan="8" class="p-0 font-size-8 align-middle"><b>Saturday</b></td>';
						echo '</tr>';
					}
				} else {
					echo '<tr align="center">';
						echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynameNow.'</td>';
						echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynoNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$amtimeinNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$amtimeoutNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$pmtimeinNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$pmtimeoutNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$lateutimehourNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$lateutimeminNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$overtimehourNow.'</td>';
						echo '<td class="p-0 font-size-8">'.$overtimeminNow.'</td>';
					echo '</tr>';
				}
			} elseif ( $daynameNow == "Sun" ) {
				if ( empty($amtimeinNow) && empty($amtimeoutNow) && empty($pmtimeinNow) && empty($pmtimeoutNow) ) {
					// Check for Holiday
					if ( $getholidatz->Search_holidayz($yearnoNow,$monthnoNow,$daynoNow) ) {
						for($h = 0; $h < count($getholidatz->list_holidaysautoid); $h++) {
							$holidaymyname = trim($getholidatz->list_holidayname[$h]);

							echo '<tr align="center">';
								echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynameNow.'</td>';
								echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynoNow.'</td>';
								echo '<td colspan="4" class="p-0 font-size-8 align-middle font-color-dark-blue border-end"><b>'.$holidaymyname.'</b></td>';
								echo '<td class="p-0 font-size-8 border-end"></td>';
								echo '<td class="p-0 font-size-8 border-end"></td>';
								echo '<td class="p-0 font-size-8 border-end"></td>';
								echo '<td class="p-0 font-size-8"></td>';
							echo '</tr>';
						}
					} else {
						echo '<tr align="center">';
							echo '<td class="p-0 font-size-8 align-middle border-end"></td>';
							echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynoNow.'</td>';
							echo '<td colspan="8" class="p-0 font-size-8 align-middle"><b>Sunday</b></td>';
						echo '</tr>';
					}
				} else {
					echo '<tr align="center">';
						echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynameNow.'</td>';
						echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynoNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$amtimeinNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$amtimeoutNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$pmtimeinNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$pmtimeoutNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$lateutimehourNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$lateutimeminNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$overtimehourNow.'</td>';
						echo '<td class="p-0 font-size-8">'.$overtimeminNow.'</td>';
					echo '</tr>';
				}
			} else {
				if ( empty($amtimeinNow) && empty($amtimeoutNow) && empty($pmtimeinNow) && empty($pmtimeoutNow) ) {
					// Check for Holiday
					if ( $getholidatz->Search_holidayz($yearnoNow,$monthnoNow,$daynoNow) ) {
						for($h = 0; $h < count($getholidatz->list_holidaysautoid); $h++) {
							$holidaymyname = trim($getholidatz->list_holidayname[$h]);

							echo '<tr align="center">';
								echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynameNow.'</td>';
								echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynoNow.'</td>';
								echo '<td colspan="4" class="p-0 font-size-8 align-middle font-color-dark-blue border-end"><b>'.$holidaymyname.'</b></td>';
								echo '<td class="p-0 font-size-8 border-end"></td>';
								echo '<td class="p-0 font-size-8 border-end"></td>';
								echo '<td class="p-0 font-size-8 border-end"></td>';
								echo '<td class="p-0 font-size-8"></td>';
							echo '</tr>';
						}
					} else {
						if ( $typeemployeenotu == 1 || $typeemployeenotu == 2 || $typeemployeenotu == 5 || $typeemployeenotu == 6 ) {
							echo '<tr align="center">';
								echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynameNow.'</td>';
								echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynoNow.'</td>';
								if ( $daynoNow < date("j") ) {
									echo '<td colspan="4" class="p-0 font-size-8 align-middle font-color-dark-blue border-end"><b>LEAVE</b></td>';
								} else {
									echo '<td colspan="4" class="p-0 font-size-8 align-middle font-color-dark-blue border-end"></td>';
								}
								echo '<td class="p-0 font-size-8 border-end">'.$lateutimehourNow.'</td>';
								echo '<td class="p-0 font-size-8 border-end">'.$lateutimeminNow.'</td>';
								echo '<td class="p-0 font-size-8 border-end">'.$overtimehourNow.'</td>';
								echo '<td class="p-0 font-size-8">'.$overtimeminNow.'</td>';
							echo '</tr>';
						} elseif ( $typeemployeenotu == 3 || $typeemployeenotu == 4 ) {
							echo '<tr align="center">';
								echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynameNow.'</td>';
								echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynoNow.'</td>';
								if ( $daynoNow < date("j") ) {
									echo '<td colspan="4" class="p-0 font-size-8 align-middle font-color-dark-blue border-end"><b>ABSENT</b></td>';
								} else {
									echo '<td colspan="4" class="p-0 font-size-8 align-middle font-color-dark-blue border-end"></td>';
								}
								echo '<td class="p-0 font-size-8 border-end">'.$lateutimehourNow.'</td>';
								echo '<td class="p-0 font-size-8 border-end">'.$lateutimeminNow.'</td>';
								echo '<td class="p-0 font-size-8 border-end">'.$overtimehourNow.'</td>';
								echo '<td class="p-0 font-size-8">'.$overtimeminNow.'</td>';
							echo '</tr>';
						}
					}
				} else {
					echo '<tr align="center">';
						echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynameNow.'</td>';
						echo '<td class="p-0 font-size-8 align-middle border-end">'.$daynoNow.'</td>';
						
						// Check for Halfday
						$isHalfdayFound = false;
						if ( $gethalfdayz->fnFind_halfdayz($yearnoNow,$monthnoNow,$daynoNow) ) {
							for ($hd = 0; $hd < count($gethalfdayz->list_halfdayautoid); $hd++) {
								$isHalfdayFound = true;
								$halfdayname = trim($gethalfdayz->list_halfdayname[$hd]);
								$halfdaymeridiem = trim($gethalfdayz->list_halfdaymeridiem[$hd]);

								if ( $halfdaymeridiem === 'AM' ) {
									echo '<td colspan="2" class="p-0 font-size-8 align-middle font-color-dark-blue border-end"><b>'.$halfdayname.'</b></td>';
									echo '<td class="p-0 font-size-8 border-end">'.$pmtimeinNow.'</td>';
									echo '<td class="p-0 font-size-8 border-end">'.$pmtimeoutNow.'</td>';
								} elseif ( $halfdaymeridiem === 'PM' ) {
									echo '<td class="p-0 font-size-8 border-end">'.$amtimeinNow.'</td>';
									echo '<td class="p-0 font-size-8 border-end">'.$amtimeoutNow.'</td>';
									echo '<td colspan="2" class="p-0 font-size-8 align-middle font-color-dark-blue border-end"><b>'.$halfdayname.'</b></td>';
								} else {
									echo '<td class="p-0 font-size-8 border-end">'.$amtimeinNow.'</td>';
									echo '<td class="p-0 font-size-8 border-end">'.$amtimeoutNow.'</td>';
									echo '<td class="p-0 font-size-8 border-end">'.$pmtimeinNow.'</td>';
									echo '<td class="p-0 font-size-8 border-end">'.$pmtimeoutNow.'</td>';
								}
							}
						}

						if (!$isHalfdayFound) {
							echo '<td class="p-0 font-size-8 border-end">'.$amtimeinNow.'</td>';
							echo '<td class="p-0 font-size-8 border-end">'.$amtimeoutNow.'</td>';
							echo '<td class="p-0 font-size-8 border-end">'.$pmtimeinNow.'</td>';
							echo '<td class="p-0 font-size-8 border-end">'.$pmtimeoutNow.'</td>';
						}

						echo '<td class="p-0 font-size-8 border-end">'.$lateutimehourNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$lateutimeminNow.'</td>';
						echo '<td class="p-0 font-size-8 border-end">'.$overtimehourNow.'</td>';
						echo '<td class="p-0 font-size-8">'.$overtimeminNow.'</td>';
					echo '</tr>';
				}
			}
		}
	} else {
		echo '<tr align="center">';
			echo '<td colspan="10" class="p-0 font-size-8 align-middle">No Timelogs</td>';
		echo '</tr>';
	}

?>
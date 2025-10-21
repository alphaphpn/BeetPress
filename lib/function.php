<?php

	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$records_per_page = 15;
	$from_record_num = ($records_per_page * $page) - $records_per_page;
	$action = isset($_GET['action']) ? $_GET['action'] : "";

	// set_time_limit(0);
	
	// ini_set('default_charset', 'utf-8');

	$url = $_SERVER['REQUEST_URI'];
	$url_path = parse_url($url, PHP_URL_PATH);
	$basename = pathinfo($url_path, PATHINFO_BASENAME);

	function isValidDate($date, $format = 'Y-m-d') {
		$dateTime = DateTime::createFromFormat($format, $date);
		return $dateTime && $dateTime->format($format) === $date;
	}

	function subscrtptnmbr($valnmbr) {
		if ($valnmbr == 1) {
			return $valnmbr . "st";
		} elseif ($valnmbr == 2) {
			return $valnmbr . "nd";
		} elseif ($valnmbr == 3) {
			return $valnmbr . "rd"; 
		} else {
			return $valnmbr . "th";
		}
	}

	// Time Check Verifier - Start
	$time_pattern = '/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/';
	$word_pattern = '/^[A-Za-z]+$/';

	function checkIsTime($timeformat) {
		global $time_pattern;
		$timeformat = trim($timeformat); // Trim any leading or trailing whitespace

		if (preg_match($time_pattern, $timeformat)) {
			$time = DateTime::createFromFormat('H:i', $timeformat);
			$time_errors = DateTime::getLastErrors();

			// Check if $time_errors is an array before accessing its elements
			if (is_array($time_errors) && $time_errors['warning_count'] + $time_errors['error_count'] == 0) {
				return true;
			}
		}
		return false;
	}
	// Time Check Verifier - End

	// Alphanumeric Special Character Verifier - Start
	function specialChars($str) {
		return !ctype_alnum($str);
	}
	// Alphanumeric Special Character Verifier - End

	// Validate Value: DTR - Start
	function checkIsValStat($valstat) {
		if ($valstat=="LEAVE") {
			return TRUE;
		} elseif ($valstat=="MEMO") {
			return TRUE;
		} elseif ($valstat=="PASS-SLIP") {
			return TRUE;
		} elseif ($valstat=="OB") {
			return TRUE;
		} elseif (strpos($valstat, 'OB') !== false) {
			return TRUE;
		} elseif ($valstat=="OT") {
			return TRUE;
		} elseif (strpos($valstat, 'OT') !== false) {
			return TRUE;
		} elseif ($valstat=="DAY OFF") {
			return TRUE;
		} elseif ($valstat=="SWAP") {
			return TRUE;
		} elseif ($valstat=="OFFSET") {
			return TRUE;
		} elseif ($valstat=="MEETING") {
			return TRUE;
		} elseif ($valstat=="BAC Meeting") {
			return TRUE;
		} elseif ($valstat=="ABSENT") {
			return TRUE;
		} elseif ($valstat=="NOT APPLICABLE") {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	// Validate Value: DTR - End

	function fn_amtimein() {
		$permittedAMHR = '67';
		$permittedAMMinA = '012345';
		$permittedAMMinB = '0123456789';
		$the_am_in = substr(str_shuffle($permittedAMHR), 0, 1).":".substr(str_shuffle($permittedAMMinA), 0, 1).substr(str_shuffle($permittedAMMinB), 0, 1);

		return $the_am_in;
	}

	function fn_amtimeout() {
		$permittedAMHR = '67';
		$permittedAMMinA = '012';
		$permittedAMMinB = '0123456789';
		$the_am_in = "12:".substr(str_shuffle($permittedAMMinA), 0, 1).substr(str_shuffle($permittedAMMinB), 0, 1);

		return $the_am_in;
	}

	function fn_pmtimein() {
		$permittedAMMinA = '345';
		$permittedAMMinB = '0123456789';
		$the_am_in = "12:".substr(str_shuffle($permittedAMMinA), 0, 1).substr(str_shuffle($permittedAMMinB), 0, 1);

		return $the_am_in;
	}

	function fn_pmtimeout() {
		$permittedAMHR = '56';
		$permittedAMMinA = '5';
		$permittedAMMinB = '0123456789';
		$the_am_in = substr(str_shuffle($permittedAMHR), 0, 1).":".substr(str_shuffle($permittedAMMinA), 0, 1).substr(str_shuffle($permittedAMMinB), 0, 1);

		return $the_am_in;
	}

	// Password Encryptor - Start
	function pwordEnryptor($pwordata) {
		$md5_given = $pwordata;
		$pw_md5 = md5($md5_given);

		return $pw_md5;
	}
	// Password Encryptor - End

	function suggestedPassWord() {
		$encryp_symbol = substr(str_shuffle('~!@^*#</>'), 0, 1);
		$encryp_capital = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1);
		$encryp_small = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5);
		$encryp_symbol2 = substr(str_shuffle('~!@^*#</>'), 0, 1);
		$encryp_numbr = substr(str_shuffle('0123456789'), 0, 4);
		$encryp_symbol3 = substr(str_shuffle('~!@^*#</>'), 0, 1);

		$encryptforpw = $encryp_symbol.$encryp_capital.$encryp_small.$encryp_symbol2.$encryp_numbr.$encryp_symbol3;
		
		return $encryptforpw;
	}

	function randomCodeyuv() {
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle($permitted_chars), 0, 11);
	}

	function randomCodexz() {
		$permitted_chars2 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		return substr(str_shuffle($permitted_chars2), 0, 11);
	}

	function randomNumbr6() {
		$permitted_chars1 = '123456789';
		$permitted_chars2 = '0123456789';
		$combine = trim(substr(str_shuffle($permitted_chars1), 0, 1)).trim(substr(str_shuffle($permitted_chars2), 0, 6));
		return $combine;
	}

	function randomNumbr8() {
		$permitted_chars1 = '123456789';
		$permitted_chars2 = '0123456789';
		$combine = trim(substr(str_shuffle($permitted_chars1), 0, 1)).trim(substr(str_shuffle($permitted_chars2), 0, 7));
		return $combine;
	}

	function randomNumbr11() {
		$permitted_chars1 = '123456789';
		$permitted_chars2 = '0123456789';
		$combine = trim(substr(str_shuffle($permitted_chars1), 0, 1)).trim(substr(str_shuffle($permitted_chars2), 0, 10));
		return $combine;
	}

	function fn_WifiPW() {
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle($permitted_chars), 0, 8);
	}

	function remLeaveNmbrOnly($dvalue) {
		$characters_to_remove = array("-", ":", " ");
		$new_string = str_replace($characters_to_remove, "", trim($dvalue));
		return $new_string;
	}

	function genEmpQrCodeZ($employeeidg,$nickname,$fullname,$birthday,$position,$office,$validfrom) {
		$xdata = array("employee_id"=>$employeeidg, 
			"nickname"=>$nickname, 
			"fullname"=>$fullname, 
			"birthday"=>$birthday, 
			"position"=>$position, 
			"office"=>$office, 
			"valid_from"=>$validfrom);
		return json_encode($xdata);
	}

?>
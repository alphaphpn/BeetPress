<?php
	if (isset($_POST['profileidx']) && isset($_POST['employeeidx']) && isset($_POST['idtypex']) && isset($_POST['idnumberx'])) {
		$profileidx = $_POST['profileidx'];
		$employeeidx = $_POST['employeeidx'];
		$idtypex = $_POST['idtypex'];
		$idnumberx = $_POST['idnumberx'];

		// Adjust the path to your index.php depending on where lib/get-employee-ids.php is located
		require_once "index.php";
		$personIDInfo1 = new clssPersonsIDies();

		// Execute the insert once and check its return value directly to prevent double-insertions
		if ( $personIDInfo1->insert_clssPersonsIDies($profileidx,$employeeidx,$idtypex,$idnumberx) ) {
			echo 'Success';
			return true;
		} else {
			echo 'Already Exist!';
			return false;
		}
	}
?>
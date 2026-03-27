<?php
	if (isset($_POST['detetedid'])) {
		$detetedid = $_POST['detetedid'];

		// Adjust the path to your index.php depending on where lib/get-employee-ids.php is located
		require_once "index.php"; 
		$personIDInfo1 = new clssPersonsIDies();
		
		if ( $personIDInfo1->Delete_clssPersonsIDies($detetedid) ) {
			$personIDInfo1->Delete_clssPersonsIDies($detetedid);
		} else {
			echo '<tr><td colspan="3" class="text-center">No existing IDs found.</td></tr>';
		}
	}
?>
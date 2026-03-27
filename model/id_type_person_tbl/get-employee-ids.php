<?php
// Make sure to include any necessary database connections or session starts here if required by your model

if (isset($_POST['profileid']) && isset($_POST['employeeid'])) {
	$profileautoid_oo = $_POST['profileid'];
	$employeeid_oo = $_POST['employeeid'];

	// Adjust the path to your index.php depending on where lib/get-employee-ids.php is located
	require_once "index.php"; 
	$personIDInfo1 = new clssPersonsIDies();
	
	if ( $personIDInfo1->Search_clssPersonsIDies($profileautoid_oo, $employeeid_oo) ) {
		$personIDInfo1->Search_clssPersonsIDies($profileautoid_oo, $employeeid_oo);

		for ($i = 0; $i < count($personIDInfo1->list_autoidtypekk); $i++) {
			$autoidtypeoo1 = $personIDInfo1->list_autoidtypekk[$i];
			$idtypeoo1 = $personIDInfo1->list_idtypekk[$i];
			$idnumberoo1 = $personIDInfo1->list_idnumberkk[$i];

			echo '<tr>';
				echo '<td>'.trim($idtypeoo1).'</td>';
				echo '<td>'.trim($idnumberoo1).'</td>';
				echo '<td class="text-center align-middle"><button type="button" class="btn btn-sm text-danger p-0 border-0" style="font-size: 1.2rem; line-height: 1;" onclick="deleteRow(this,'.$autoidtypeoo1.')">×</button></td>';
			echo '</tr>';
		}
	} else {
		echo '<tr><td colspan="3" class="text-center">No existing IDs found.</td></tr>';
	}
}
?>
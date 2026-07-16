<?php 

	try {

		require_once "lib/cnn.php";

		class employeeMainDTR extends myDatabase {
			// Memory single variable base on Database Table Fieldnames
			Public $empdtrautoid, $agencycode, $agencyname, $empidcode, $dtrcode, $yearno, $monthno, $monthname, $profileid, $biolocation, $biono, $empname, $officeid, $officecode, $officename, $officetitle, $officeabrv, $officegpslocation, $typeemployeeno, $typeemployeeabrv, $headofficer, $headtitle, $authhead, $authtitle, $authdescription, $utlatehr, $utlatemin, $othr, $otmin, $shiftstatus, $timeeditable, $prioritydtr, $timeeditablevalue, $allowedot, $xdel, $createdby, $modifiedby, $modifiedat, $createdat;;

			// Memory list variable base on Database Table Fieldnames
			Public $list_empdtrautoid, $list_agencycode, $list_agencyname, $list_empidcode, $list_dtrcode, $list_yearno, $list_monthno, $list_monthname, $list_profileid, $list_biolocation, $list_biono, $list_empname, $list_officeid, $list_officecode, $list_officename, $list_officetitle, $list_officeabrv, $list_officegpslocation, $list_typeemployeeno, $list_typeemployeeabrv, $list_headofficer, $list_headtitle, $list_authhead, $list_authtitle, $list_authdescription, $list_utlatehr, $list_utlatemin, $list_othr, $list_otmin, $list_shiftstatus, $list_timeeditable, $list_prioritydtr, $list_timeeditablevalue, $list_allowedot, $list_xdel, $list_createdby, $list_modifiedby, $list_modifiedat, $list_createdat;;

			// Constructer Memory list variable base on Database Table Fieldnames
			public function __construct() {
				$this->list_empdtrautoid = array();
				$this->list_agencycode = array();
				$this->list_agencyname = array();
				$this->list_empidcode = array();
				$this->list_dtrcode = array();
				$this->list_yearno = array();
				$this->list_monthno = array();
				$this->list_monthname = array();
				$this->list_profileid = array();
				$this->list_biolocation = array();
				$this->list_biono = array();
				$this->list_empname = array();
				$this->list_officeid = array();
				$this->list_officecode = array();
				$this->list_officename = array();
				$this->list_officetitle = array();
				$this->list_officeabrv = array();
				$this->list_officegpslocation = array();
				$this->list_typeemployeeno = array();
				$this->list_typeemployeeabrv = array();
				$this->list_headofficer = array();
				$this->list_headtitle = array();
				$this->list_authhead = array();
				$this->list_authtitle = array();
				$this->list_authdescription = array();
				$this->list_utlatehr = array();
				$this->list_utlatemin = array();
				$this->list_othr = array();
				$this->list_otmin = array();
				$this->list_shiftstatus = array();
				$this->list_timeeditable = array();
				$this->list_prioritydtr = array();
				$this->list_timeeditablevalue = array();
				$this->list_allowedot = array();
				$this->list_xdel = array();
				$this->list_createdby = array();
				$this->list_modifiedby = array();
				$this->list_modifiedat = array();
				$this->list_createdat = array();
			}

			// Clearing of data values Memory list variable base on Database Table Fieldnames
			public function clearlist_employeeMainDTR() {
				$this->list_empdtrautoid = array();
				$this->list_agencycode = array();
				$this->list_agencyname = array();
				$this->list_empidcode = array();
				$this->list_dtrcode = array();
				$this->list_yearno = array();
				$this->list_monthno = array();
				$this->list_monthname = array();
				$this->list_profileid = array();
				$this->list_biolocation = array();
				$this->list_biono = array();
				$this->list_empname = array();
				$this->list_officeid = array();
				$this->list_officecode = array();
				$this->list_officename = array();
				$this->list_officetitle = array();
				$this->list_officeabrv = array();
				$this->list_officegpslocation = array();
				$this->list_typeemployeeno = array();
				$this->list_typeemployeeabrv = array();
				$this->list_headofficer = array();
				$this->list_headtitle = array();
				$this->list_authhead = array();
				$this->list_authtitle = array();
				$this->list_authdescription = array();
				$this->list_utlatehr = array();
				$this->list_utlatemin = array();
				$this->list_othr = array();
				$this->list_otmin = array();
				$this->list_shiftstatus = array();
				$this->list_timeeditable = array();
				$this->list_prioritydtr = array();
				$this->list_timeeditablevalue = array();
				$this->list_allowedot = array();
				$this->list_xdel = array();
				$this->list_createdby = array();
				$this->list_modifiedby = array();
				$this->list_modifiedat = array();
				$this->list_createdat = array();
			}


			// Array of data values Memory single variable base on Database Table Fieldnames
			public function loadrecord_employeeMainDTR() {
				$this->clearlist_employeeMainDTR();
				$this->getConnection();
				$selectQuery = "SELECT * FROM employee_dtr_tbl";
				$stmt = $this->cnn->prepare($selectQuery);

				$stmt->execute();

				$data = array();

				for ($i=0; $row = $stmt->fetch(); $i++) {
					$data[] = $row;
				}

				return $data;
			}

			// Return Count Record base on Database Table Fieldnames
			public function count_employeeMainDTR() {
				$this->clearlist_employeeMainDTR();
				$this->getConnection();
				$selectQuery = "SELECT * FROM employee_dtr_tbl";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();
				$cntRcrd = $stmt->rowCount();

				return $cntRcrd;
			}

			// Create or insert new data on the Database Table
			public function insert_employeeMainDTR($xxxx,$xxxx2) {
				$this->clearlist_employeeMainDTR();
				$this->getConnection();

				$xxxx = htmlspecialchars(trim($xxxx));
				$xxxx2 = htmlspecialchars(trim($xxxx2));

				$insertQuery = "INSERT INTO employee_dtr_tbl SET 
					xxxx=:xxxx, 
					xxxx2=:xxxx2
					";
				$stmt = $this->cnn->prepare($insertQuery);
				$stmt->bindParam(':xxxx', $xxxx);
				$stmt->bindParam(':xxxx2', $xxxx2);
				$stmt->execute();
			}

			// Reading of data values Memory list variable base on Database Table Fieldnames
			public function list_employeeMainDTR() {
				$this->clearlist_employeeMainDTR();
				$this->getConnection();

				$selectQuery = "SELECT * FROM employee_dtr_tbl";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_xxxx[] = $rwRcrd['xxxx'];
						$this->list_xxxx2[] = $rwRcrd['xxxx2'];
					}
				} else {
					echo "Record not found.";
				}
			}

			// Searching of data values Memory list variable base on Database Table Fieldnames
			public function Search_employeeMainDTR($search) {
				$this->clearlist_employeeMainDTR();
				$this->getConnection();

				$selectQuery = "SELECT * FROM employee_dtr_tbl WHERE xxxx=:xxxx";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':xxxx', $search);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_xxxx[] = $rwRcrd['xxxx'];
						$this->list_xxxx2[] = $rwRcrd['xxxx2'];
					}
				} else {
					echo "Record not found.";
				}
			}

			// Filter of data values Memory list variable base on Database Table Fieldnames
			public function Filter_employeeMainDTR($filter) {
				$this->clearlist_employeeMainDTR();
				$this->getConnection();

				$xxxx = '%'.$filter.'%';

				$selectQuery = "SELECT * FROM employee_dtr_tbl WHERE xxxx LIKE :xxxx";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':xxxx', $xxxx);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_xxxx[] = $rwRcrd['xxxx'];
						$this->list_xxxx2[] = $rwRcrd['xxxx2'];
					}
				} else {
					echo "Record not found.";
				}
			}

			// Update new data on the Database Table
			public function update_employeeMainDTR($id,$xxxx,$xxxx2) {
				$this->clearlist_employeeMainDTR();
				$this->getConnection();

				$xxxx = htmlspecialchars(trim($xxxx));
				$xxxx2 = htmlspecialchars(trim($xxxx2));

				$updateQuery = "UPDATE employee_dtr_tbl SET 
					xxxx=:xxxx, 
					xxxx2=:xxxx2 
					WHERE 
					id=:id
					";
				$stmt = $this->cnn->prepare($updateQuery);
				$stmt->bindParam(':id', $id);
				$stmt->bindParam(':xxxx', $xxxx);
				$stmt->bindParam(':xxxx2', $xxxx2);
				$stmt->execute();
			}

			// Delete of data values Memory list variable base on Database Table Fieldnames
			public function Delete_employeeMainDTR($delete) {
				$this->clearlist_employeeMainDTR();
				$this->getConnection();

				$selectQuery = "SELECT * FROM employee_dtr_tbl WHERE xxxx=:xxxx";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':xxxx', $delete);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					$deleteQuery = "DELETE FROM employee_dtr_tbl WHERE xxxx=:xxxx";
					$stmtDelete = $this->cnn->prepare($deleteQuery);
					$stmtDelete->bindParam(':xxxx', $delete);
					$stmtDelete->execute();
				} else {
					echo "No such record to be deleted.";
				}
			}
		}

	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error: {$err_msg}</p>";
		die;
	}
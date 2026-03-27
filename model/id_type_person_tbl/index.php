<?php 

	try {

		if (file_exists("lib/cnn.php")) {
			require_once "lib/cnn.php";
		} elseif (file_exists("../../lib/cnn.php")) {
			require_once "../../lib/cnn.php";
		}

		class clssPersonsIDies extends myDatabase {
			// Memory single variable base on Database Table Fieldnames
			Public $autoidtypekk,$profileidkk,$empidcodekk,$idtypekk,$idnumberkk;

			// Memory list variable base on Database Table Fieldnames
			Public $list_autoidtypekk,$list_profileidkk,$list_empidcodekk,$list_idtypekk,$list_idnumberkk;

			// Constructer Memory list variable base on Database Table Fieldnames
			public function __construct() {
				$this->list_autoidtypekk = array();
				$this->list_profileidkk = array();
				$this->list_empidcodekk = array();
				$this->list_idtypekk = array();
				$this->list_idnumberkk = array();
			}

			// Clearing of data values Memory list variable base on Database Table Fieldnames
			public function clearlist_clssPersonsIDies() {
				$this->list_autoidtypekk = array();
				$this->list_profileidkk = array();
				$this->list_empidcodekk = array();
				$this->list_idtypekk = array();
				$this->list_idnumberkk = array();
			}

			// Array of data values Memory single variable base on Database Table Fieldnames
			public function loadrecord_clssPersonsIDies() {
				$this->clearlist_clssPersonsIDies();
				$this->getConnection();
				$selectQuery = "SELECT * FROM id_type_person_tbl";
				$stmt = $this->cnn->prepare($selectQuery);

				$stmt->execute();

				$data = array();

				for ($i=0; $row = $stmt->fetch(); $i++) {
					$data[] = $row;
				}

				return $data;
			}

			// Searching of data values Memory list variable base on Database Table Fieldnames
			// Search by PersonsID
			public function Search_clssPersonsIDies($profileid,$empidcode) {
				$this->clearlist_clssPersonsIDies();
				$this->getConnection();

				$profileiduu = trim($profileid);
				$empidcodeuu = trim($empidcode);

				$selectQuery = "SELECT * FROM id_type_person_tbl WHERE profileid=:profileiduu OR emp_idcode=:empidcodeuu";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':profileiduu', $profileiduu);
				$stmt->bindParam(':empidcodeuu', $empidcodeuu);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_autoidtypekk[] = $rwRcrd['autoidtype'];
						$this->list_profileidkk[] = $rwRcrd['profileid'];
						$this->list_empidcodekk[] = $rwRcrd['emp_idcode'];
						$this->list_idtypekk[] = $rwRcrd['idtype'];
						$this->list_idnumberkk[] = $rwRcrd['idnumber'];
					}
					return true;
				} else {
					return false;
				}
			}

			// Delete of data values Memory list variable base on Database Table Fieldnames
			public function Delete_clssPersonsIDies($delete) {
				$this->clearlist_clssPersonsIDies();
				$this->getConnection();

				$selectQuery = "SELECT * FROM id_type_person_tbl WHERE autoidtype=:xxxx";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':xxxx', $delete);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					$deleteQuery = "DELETE FROM id_type_person_tbl WHERE autoidtype=:xxxx";
					$stmtDelete = $this->cnn->prepare($deleteQuery);
					$stmtDelete->bindParam(':xxxx', $delete);
					$stmtDelete->execute();

					return true;
				} else {
					echo "No such record to be deleted.";
					return false;
				}
			}

			// Create or insert new data on the Database Table
			public function insert_clssPersonsIDies($profileidx,$employeeidx,$idtypex,$idnumberx) {
				$this->clearlist_clssPersonsIDies();
				$this->getConnection();

				$profileidx = trim($profileidx);
				$employeeidx = trim($employeeidx);
				$idtypex = trim($idtypex);
				$idnumberx = trim($idnumberx);

				$selectQuery_dup = "SELECT * FROM id_type_person_tbl WHERE profileid=:profileidx AND emp_idcode=:employeeidx AND idtype=:idtypex";
				$stmt_dup = $this->cnn->prepare($selectQuery_dup);
				$stmt_dup->bindParam(':profileidx', $profileidx);
				$stmt_dup->bindParam(':employeeidx', $employeeidx);
				$stmt_dup->bindParam(':idtypex', $idtypex);
				// Removed the extra bindParam for idnumberx here to fix the PDO parameter mismatch error
				$stmt_dup->execute();

				$cntRcrd_dup = $stmt_dup->rowCount();

				if ($cntRcrd_dup > 0) {
					// Changed to return false so add-employee-ids.php knows it failed
					return false;
				} else {
					$insertQuery = "INSERT INTO id_type_person_tbl SET 
						profileid=:profileidx, 
						emp_idcode=:employeeidx, 
						idtype=:idtypex, 
						idnumber=:idnumberx";
					$stmt = $this->cnn->prepare($insertQuery);
					$stmt->bindParam(':profileidx', $profileidx);
					$stmt->bindParam(':employeeidx', $employeeidx);
					$stmt->bindParam(':idtypex', $idtypex);
					$stmt->bindParam(':idnumberx', $idnumberx);
					$stmt->execute();

					// Changed to return true so add-employee-ids.php knows it succeeded
					return true;
				}
			}
		}

	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error @ Persons IDies: {$err_msg}</p>";
		die;
	}

?>
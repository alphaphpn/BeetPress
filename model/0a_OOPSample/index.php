<?php 

	try {

		require_once "lib/cnn.php";

		class myCRUD extends myDatabase {
			// Memory single variable base on Database Table Fieldnames
			Public $xxxx, $xxxx2;

			// Memory list variable base on Database Table Fieldnames
			Public $list_xxxx, $list_xxxx2;

			// Constructer Memory list variable base on Database Table Fieldnames
			public function __construct() {
				$this->list_xxxx = array();
				$this->list_xxxx2 = array();
			}

			// Clearing of data values Memory list variable base on Database Table Fieldnames
			public function clearlist_myCRUD() {
				$this->list_xxxx = array();
				$this->list_xxxx2 = array();
			}


			// Array of data values Memory single variable base on Database Table Fieldnames
			public function loadrecord_myCRUD() {
				$this->clearlist_myCRUD();
				$this->getConnection();
				$selectQuery = "SELECT * FROM xxxx_tbl";
				$stmt = $this->cnn->prepare($selectQuery);

				$stmt->execute();

				$data = array();

				for ($i=0; $row = $stmt->fetch(); $i++) {
					$data[] = $row;
				}

				return $data;
			}

			// Create or insert new data on the Database Table
			public function insert_myCRUD($xxxx,$xxxx2) {
				$this->clearlist_myCRUD();
				$this->getConnection();

				$xxxx = htmlspecialchars(trim($xxxx));
				$xxxx2 = htmlspecialchars(trim($xxxx2));

				$insertQuery = "INSERT INTO xxxx_tbl SET 
					xxxx=:xxxx, 
					xxxx2=:xxxx2
					";
				$stmt = $this->cnn->prepare($insertQuery);
				$stmt->bindParam(':xxxx', $xxxx);
				$stmt->bindParam(':xxxx2', $xxxx2);
				$stmt->execute();
			}

			// Reading of data values Memory list variable base on Database Table Fieldnames
			public function list_myCRUD() {
				$this->clearlist_myCRUD();
				$this->getConnection();

				$selectQuery = "SELECT * FROM xxxx_tbl";
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
			public function Search_myCRUD($search) {
				$this->clearlist_myCRUD();
				$this->getConnection();

				$selectQuery = "SELECT * FROM xxxx_tbl WHERE xxxx=:xxxx";
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
			public function Filter_myCRUD($filter) {
				$this->clearlist_myCRUD();
				$this->getConnection();

				$xxxx = '%'.$filter.'%';

				$selectQuery = "SELECT * FROM xxxx_tbl WHERE xxxx LIKE :xxxx";
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
			public function insert_myCRUD($id,$xxxx,$xxxx2) {
				$this->clearlist_myCRUD();
				$this->getConnection();

				$xxxx = htmlspecialchars(trim($xxxx));
				$xxxx2 = htmlspecialchars(trim($xxxx2));

				$updateQuery = "UPDATE xxxx_tbl SET 
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
			public function Delete_myCRUD($delete) {
				$this->clearlist_myCRUD();
				$this->getConnection();

				$selectQuery = "SELECT * FROM xxxx_tbl WHERE xxxx=:xxxx";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':xxxx', $delete);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					$deleteQuery = "DELETE FROM xxxx_tbl WHERE xxxx=:xxxx";
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
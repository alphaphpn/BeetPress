<?php 

	try {

		if (file_exists("lib/cnn.php")) {
			require_once "lib/cnn.php";
		} elseif (file_exists("../../lib/cnn.php")) {
			require_once "../../lib/cnn.php";
		}

		class employeeAcct_Backup extends myDatabase {

			public function fn_emplBckpAll() {
				$this->getConnection();

				$filename = "employee_tbl.txt";
				$delimiter = "\t"; // Use "\t" for tab, "," for comma, etc.

				$selectQuery = "SELECT * FROM employee_tbl GROUP BY designationforid ORDER BY designationforid ASC";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				// Write header row
				if ($cntRcrd > 0) {
					$header = [];
					foreach ($stmt as $rwRcrd) {
						$this->list_designationforidee[] = $rwRcrd['designationforid'];
						$header[] = $field->name;
					}
				}





				$selectQuery = "SELECT * FROM xxxx_tbl";
				$stmt = $this->cnn->prepare($selectQuery);

				$stmt->execute();

				$data = array();

				for ($i=0; $row = $stmt->fetch(); $i++) {
					$data[] = $row;
				}

				return $data;





			}

		}

	} catch ( PDOException $error ) {
		$err_msg = $error->getMessage();
		echo "<p>Error: {$err_msg}</p>";
		die;
	}
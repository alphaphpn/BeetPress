<?php 

	try {

		require_once "lib/cnn.php";

		class holidayz extends myDatabase {
			// Memory single variable base on Database Table Fieldnames
			Public $holidaysautoid, $agencycode, $agencyname, $holidaysid, $holidayname, $holidaymonth, $holidaymthree, $holidaymno, $holidaymnumbr, $holidayday, $holidaydayno, $holidayyear, $xdel, $createdby, $modifiedby, $modifiedat, $createdat;

			// Memory list variable base on Database Table Fieldnames
			Public $list_holidaysautoid, $list_agencycode, $list_agencyname, $list_holidaysid, $list_holidayname, $list_holidaymonth, $list_holidaymthree, $list_holidaymno, $list_holidaymnumbr, $list_holidayday, $list_holidaydayno, $list_holidayyear, $list_xdel, $list_createdby, $list_modifiedby, $list_modifiedat, $list_createdat;

			// Constructer Memory list variable base on Database Table Fieldnames
			public function __construct() {
				$this->list_holidaysautoid = array();
				$this->list_agencycode = array();
				$this->list_agencyname = array();
				$this->list_holidaysid = array();
				$this->list_holidayname = array();
				$this->list_holidaymonth = array();
				$this->list_holidaymthree = array();
				$this->list_holidaymno = array();
				$this->list_holidaymnumbr = array();
				$this->list_holidayday = array();
				$this->list_holidaydayno = array();
				$this->list_holidayyear = array();
				$this->list_xdel = array();
				$this->list_createdby = array();
				$this->list_modifiedby = array();
				$this->list_modifiedat = array();
				$this->list_createdat = array();
			}

			// Clearing of data values Memory list variable base on Database Table Fieldnames
			public function clearlist_holidayz() {
				$this->list_holidaysautoid = array();
				$this->list_agencycode = array();
				$this->list_agencyname = array();
				$this->list_holidaysid = array();
				$this->list_holidayname = array();
				$this->list_holidaymonth = array();
				$this->list_holidaymthree = array();
				$this->list_holidaymno = array();
				$this->list_holidaymnumbr = array();
				$this->list_holidayday = array();
				$this->list_holidaydayno = array();
				$this->list_holidayyear = array();
				$this->list_xdel = array();
				$this->list_createdby = array();
				$this->list_modifiedby = array();
				$this->list_modifiedat = array();
				$this->list_createdat = array();
			}


			// Array of data values Memory single variable base on Database Table Fieldnames
			public function loadrecord_holidayz() {
				$this->clearlist_holidayz();
				$this->getConnection();
				$selectQuery = "SELECT * FROM holidays_tbl";
				$stmt = $this->cnn->prepare($selectQuery);

				$stmt->execute();

				$data = array();

				for ($i=0; $row = $stmt->fetch(); $i++) {
					$data[] = $row;
				}

				return $data;
			}

			// Return Count Record base on Database Table Fieldnames
			public function count_holidayz() {
				$this->clearlist_holidayz();
				$this->getConnection();
				$selectQuery = "SELECT * FROM holidays_tbl";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();
				$cntRcrd = $stmt->rowCount();

				return $cntRcrd;
			}

			// Create or insert new data on the Database Table
			public function insert_holidayz($xxxx,$xxxx2) {
				$this->clearlist_holidayz();
				$this->getConnection();

				$xxxx = htmlspecialchars(trim($xxxx));
				$xxxx2 = htmlspecialchars(trim($xxxx2));

				$insertQuery = "INSERT INTO holidays_tbl SET 
					xxxx=:xxxx, 
					xxxx2=:xxxx2
					";
				$stmt = $this->cnn->prepare($insertQuery);
				$stmt->bindParam(':xxxx', $xxxx);
				$stmt->bindParam(':xxxx2', $xxxx2);
				$stmt->execute();
			}

			// Reading of data values Memory list variable base on Database Table Fieldnames
			public function list_holidayz() {
				$this->clearlist_holidayz();
				$this->getConnection();

				$selectQuery = "SELECT * FROM holidays_tbl";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_holidaysautoid[] = $rwRcrd['holidays_autoid'];
						$this->list_agencycode[] = $rwRcrd['agency_code'];
						$this->list_agencyname[] = $rwRcrd['agency_name'];
						$this->list_holidaysid[] = $rwRcrd['holidays_id'];
						$this->list_holidayname[] = $rwRcrd['holiday_name'];
						$this->list_holidaymonth[] = $rwRcrd['holiday_month'];
						$this->list_holidaymthree[] = $rwRcrd['holiday_mthree'];
						$this->list_holidaymno[] = $rwRcrd['holiday_mno'];
						$this->list_holidaymnumbr[] = $rwRcrd['holiday_mnumbr'];
						$this->list_holidayday[] = $rwRcrd['holiday_day'];
						$this->list_holidaydayno[] = $rwRcrd['holiday_dayno'];
						$this->list_holidayyear[] = $rwRcrd['holiday_year'];
						$this->list_xdel[] = $rwRcrd['xdel'];
						$this->list_createdby[] = $rwRcrd['createdby'];
						$this->list_modifiedby[] = $rwRcrd['modifiedby'];
						$this->list_modifiedat[] = $rwRcrd['modified_at'];
						$this->list_createdat[] = $rwRcrd['created_at'];
					}
				} else {
					echo "Record not found.";
				}
			}

			// Searching of data values Memory list variable base on Database Table Fieldnames
			public function Search_holidayz($yearme,$monthme,$dayme) {
				$this->clearlist_holidayz();
				$this->getConnection();

				$selectQuery = "SELECT * FROM holidays_tbl WHERE holiday_year=:yearme AND holiday_mno=:monthme AND holiday_day=:dayme";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':yearme', $yearme);
				$stmt->bindParam(':monthme', $monthme);
				$stmt->bindParam(':dayme', $dayme);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_holidaysautoid[] = $rwRcrd['holidays_autoid'];
						$this->list_agencycode[] = $rwRcrd['agency_code'];
						$this->list_agencyname[] = $rwRcrd['agency_name'];
						$this->list_holidaysid[] = $rwRcrd['holidays_id'];
						$this->list_holidayname[] = $rwRcrd['holiday_name'];
						$this->list_holidaymonth[] = $rwRcrd['holiday_month'];
						$this->list_holidaymthree[] = $rwRcrd['holiday_mthree'];
						$this->list_holidaymno[] = $rwRcrd['holiday_mno'];
						$this->list_holidaymnumbr[] = $rwRcrd['holiday_mnumbr'];
						$this->list_holidayday[] = $rwRcrd['holiday_day'];
						$this->list_holidaydayno[] = $rwRcrd['holiday_dayno'];
						$this->list_holidayyear[] = $rwRcrd['holiday_year'];
						$this->list_xdel[] = $rwRcrd['xdel'];
						$this->list_createdby[] = $rwRcrd['createdby'];
						$this->list_modifiedby[] = $rwRcrd['modifiedby'];
						$this->list_modifiedat[] = $rwRcrd['modified_at'];
						$this->list_createdat[] = $rwRcrd['created_at'];
					}

					return true;
				} else {
					return false;
				}
			}

			// Filter of data values Memory list variable base on Database Table Fieldnames
			public function Filter_holidayz($filter) {
				$this->clearlist_holidayz();
				$this->getConnection();

				$xxxx = '%'.$filter.'%';

				$selectQuery = "SELECT * FROM holidays_tbl WHERE xxxx LIKE :xxxx";
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
			public function update_holidayz($id,$xxxx,$xxxx2) {
				$this->clearlist_holidayz();
				$this->getConnection();

				$xxxx = htmlspecialchars(trim($xxxx));
				$xxxx2 = htmlspecialchars(trim($xxxx2));

				$updateQuery = "UPDATE holidays_tbl SET 
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
			public function Delete_holidayz($delete) {
				$this->clearlist_holidayz();
				$this->getConnection();

				$selectQuery = "SELECT * FROM holidays_tbl WHERE xxxx=:xxxx";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':xxxx', $delete);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					$deleteQuery = "DELETE FROM holidays_tbl WHERE xxxx=:xxxx";
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
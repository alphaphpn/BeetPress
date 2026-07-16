<?php 

	try {

		require_once "lib/cnn.php";

		class halfdayz extends myDatabase {
			// Memory single variable base on Database Table Fieldnames
			Public $halfdayautoid, $agencycode, $agencyname, $halfdayid, $halfdaymeridiem, $halfdayname, $halfdaymonth, $halfdaymthree, $halfdaymno, $halfdaymnumbr, $halfdayday, $halfdaydayno, $halfdayyear, $xdel, $createdby, $modifiedby, $modifiedat, $createdat;

			// Memory list variable base on Database Table Fieldnames
			Public $list_halfdayautoid, $list_agencycode, $list_agencyname, $list_halfdayid, $list_halfdaymeridiem, $list_halfdayname, $list_halfdaymonth, $list_halfdaymthree, $list_halfdaymno, $list_halfdaymnumbr, $list_halfdayday, $list_halfdaydayno, $list_halfdayyear, $list_xdel, $list_createdby, $list_modifiedby, $list_modifiedat, $list_createdat;

			// Constructer Memory list variable base on Database Table Fieldnames
			public function __construct() {
				$this->list_halfdayautoid = array();
				$this->list_agencycode = array();
				$this->list_agencyname = array();
				$this->list_halfdayid = array();
				$this->list_halfdaymeridiem = array();
				$this->list_halfdayname = array();
				$this->list_halfdaymonth = array();
				$this->list_halfdaymthree = array();
				$this->list_halfdaymno = array();
				$this->list_halfdaymnumbr = array();
				$this->list_halfdayday = array();
				$this->list_halfdaydayno = array();
				$this->list_halfdayyear = array();
				$this->list_xdel = array();
				$this->list_createdby = array();
				$this->list_modifiedby = array();
				$this->list_modifiedat = array();
				$this->list_createdat = array();
			}

			// Clearing of data values Memory list variable base on Database Table Fieldnames
			public function clearlist_halfdayz() {
				$this->list_halfdayautoid = array();
				$this->list_agencycode = array();
				$this->list_agencyname = array();
				$this->list_halfdayid = array();
				$this->list_halfdaymeridiem = array();
				$this->list_halfdayname = array();
				$this->list_halfdaymonth = array();
				$this->list_halfdaymthree = array();
				$this->list_halfdaymno = array();
				$this->list_halfdaymnumbr = array();
				$this->list_halfdayday = array();
				$this->list_halfdaydayno = array();
				$this->list_halfdayyear = array();
				$this->list_xdel = array();
				$this->list_createdby = array();
				$this->list_modifiedby = array();
				$this->list_modifiedat = array();
				$this->list_createdat = array();
			}


			// Array of data values Memory single variable base on Database Table Fieldnames
			public function loadrecord_halfdayz() {
				$this->clearlist_halfdayz();
				$this->getConnection();
				$selectQuery = "SELECT * FROM halfday_tbl";
				$stmt = $this->cnn->prepare($selectQuery);

				$stmt->execute();

				$data = array();

				for ($i=0; $row = $stmt->fetch(); $i++) {
					$data[] = $row;
				}

				return $data;
			}

			// Return Count Record base on Database Table Fieldnames
			public function count_halfdayz() {
				$this->clearlist_halfdayz();
				$this->getConnection();
				$selectQuery = "SELECT * FROM halfday_tbl";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();
				$cntRcrd = $stmt->rowCount();

				return $cntRcrd;
			}

			// Create or insert new data on the Database Table
			public function insert_halfdayz($xxxx,$xxxx2) {
				$this->clearlist_halfdayz();
				$this->getConnection();

				$xxxx = htmlspecialchars(trim($xxxx));
				$xxxx2 = htmlspecialchars(trim($xxxx2));

				$insertQuery = "INSERT INTO halfday_tbl SET 
					xxxx=:xxxx, 
					xxxx2=:xxxx2
					";
				$stmt = $this->cnn->prepare($insertQuery);
				$stmt->bindParam(':xxxx', $xxxx);
				$stmt->bindParam(':xxxx2', $xxxx2);
				$stmt->execute();
			}

			// Reading of data values Memory list variable base on Database Table Fieldnames
			public function list_halfdayz() {
				$this->clearlist_halfdayz();
				$this->getConnection();

				$selectQuery = "SELECT * FROM halfday_tbl";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_halfdayautoid[] = $rwRcrd['halfday_autoid'];
						$this->list_agencycode[] = $rwRcrd['agency_code'];
						$this->list_agencyname[] = $rwRcrd['agency_name'];
						$this->list_halfdayid[] = $rwRcrd['halfday_id'];
						$this->list_halfdaymeridiem[] = $rwRcrd['halfday_meridiem'];
						$this->list_halfdayname[] = $rwRcrd['halfday_name'];
						$this->list_halfdaymonth[] = $rwRcrd['halfday_month'];
						$this->list_halfdaymthree[] = $rwRcrd['halfday_mthree'];
						$this->list_halfdaymno[] = $rwRcrd['halfday_mno'];
						$this->list_halfdaymnumbr[] = $rwRcrd['halfday_mnumbr'];
						$this->list_halfdayday[] = $rwRcrd['halfday_day'];
						$this->list_halfdaydayno[] = $rwRcrd['halfday_dayno'];
						$this->list_halfdayyear[] = $rwRcrd['halfday_year'];
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
			public function Search_halfdayz($search) {
				$this->clearlist_halfdayz();
				$this->getConnection();

				$selectQuery = "SELECT * FROM halfday_tbl WHERE xxxx=:xxxx";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':xxxx', $search);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_halfdayautoid[] = $rwRcrd['halfday_autoid'];
						$this->list_agencycode[] = $rwRcrd['agency_code'];
						$this->list_agencyname[] = $rwRcrd['agency_name'];
						$this->list_halfdayid[] = $rwRcrd['halfday_id'];
						$this->list_halfdaymeridiem[] = $rwRcrd['halfday_meridiem'];
						$this->list_halfdayname[] = $rwRcrd['halfday_name'];
						$this->list_halfdaymonth[] = $rwRcrd['halfday_month'];
						$this->list_halfdaymthree[] = $rwRcrd['halfday_mthree'];
						$this->list_halfdaymno[] = $rwRcrd['halfday_mno'];
						$this->list_halfdaymnumbr[] = $rwRcrd['halfday_mnumbr'];
						$this->list_halfdayday[] = $rwRcrd['halfday_day'];
						$this->list_halfdaydayno[] = $rwRcrd['halfday_dayno'];
						$this->list_halfdayyear[] = $rwRcrd['halfday_year'];
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

			// Search for Year, Month, Day for Halfday
			public function fnFind_halfdayz($yearme,$monthme,$dayme) {
				$this->clearlist_halfdayz();
				$this->getConnection();

				$selectQuery = "SELECT * FROM halfday_tbl WHERE halfday_year=:yearme AND halfday_mno=:monthme AND halfday_day=:dayme LIMIT 1";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':yearme', $yearme);
				$stmt->bindParam(':monthme', $monthme);
				$stmt->bindParam(':dayme', $dayme);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_halfdayautoid[] = $rwRcrd['halfday_autoid'];
						$this->list_agencycode[] = $rwRcrd['agency_code'];
						$this->list_agencyname[] = $rwRcrd['agency_name'];
						$this->list_halfdayid[] = $rwRcrd['halfday_id'];
						$this->list_halfdaymeridiem[] = $rwRcrd['halfday_meridiem'];
						$this->list_halfdayname[] = $rwRcrd['halfday_name'];
						$this->list_halfdaymonth[] = $rwRcrd['halfday_month'];
						$this->list_halfdaymthree[] = $rwRcrd['halfday_mthree'];
						$this->list_halfdaymno[] = $rwRcrd['halfday_mno'];
						$this->list_halfdaymnumbr[] = $rwRcrd['halfday_mnumbr'];
						$this->list_halfdayday[] = $rwRcrd['halfday_day'];
						$this->list_halfdaydayno[] = $rwRcrd['halfday_dayno'];
						$this->list_halfdayyear[] = $rwRcrd['halfday_year'];
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
			public function Filter_halfdayz($filter) {
				$this->clearlist_halfdayz();
				$this->getConnection();

				$xxxx = '%'.$filter.'%';

				$selectQuery = "SELECT * FROM halfday_tbl WHERE xxxx LIKE :xxxx";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':xxxx', $xxxx);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_halfdayautoid[] = $rwRcrd['halfday_autoid'];
						$this->list_agencycode[] = $rwRcrd['agency_code'];
						$this->list_agencyname[] = $rwRcrd['agency_name'];
						$this->list_halfdayid[] = $rwRcrd['halfday_id'];
						$this->list_halfdaymeridiem[] = $rwRcrd['halfday_meridiem'];
						$this->list_halfdayname[] = $rwRcrd['halfday_name'];
						$this->list_halfdaymonth[] = $rwRcrd['halfday_month'];
						$this->list_halfdaymthree[] = $rwRcrd['halfday_mthree'];
						$this->list_halfdaymno[] = $rwRcrd['halfday_mno'];
						$this->list_halfdaymnumbr[] = $rwRcrd['halfday_mnumbr'];
						$this->list_halfdayday[] = $rwRcrd['halfday_day'];
						$this->list_halfdaydayno[] = $rwRcrd['halfday_dayno'];
						$this->list_halfdayyear[] = $rwRcrd['halfday_year'];
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

			// Update new data on the Database Table
			public function update_halfdayz($id,$xxxx,$xxxx2) {
				$this->clearlist_halfdayz();
				$this->getConnection();

				$xxxx = htmlspecialchars(trim($xxxx));
				$xxxx2 = htmlspecialchars(trim($xxxx2));

				$updateQuery = "UPDATE halfday_tbl SET 
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
			public function Delete_halfdayz($delete) {
				$this->clearlist_halfdayz();
				$this->getConnection();

				$selectQuery = "SELECT * FROM halfday_tbl WHERE xxxx=:xxxx";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->bindParam(':xxxx', $delete);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					$deleteQuery = "DELETE FROM halfday_tbl WHERE xxxx=:xxxx";
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
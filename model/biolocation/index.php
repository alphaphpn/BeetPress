<?php 

	try {

		require_once "lib/cnn.php";

		class clssBioLocation extends myDatabase {
			// Memory single variable base on Database Table Fieldnames
			Public $biolocationid, $biolocation, $timelogstype;

			// Memory list variable base on Database Table Fieldnames
			Public $list_biolocationid, $list_biolocation, $list_timelogstype;

			// Constructer Memory list variable base on Database Table Fieldnames
			public function __construct() {
				$this->list_biolocationid = array();
				$this->list_biolocation = array();
				$this->list_timelogstype = array();
			}

			// Clearing of data values Memory list variable base on Database Table Fieldnames
			public function clearlist_clssBioLocation() {
				$this->list_biolocationid = array();
				$this->list_biolocation = array();
				$this->list_timelogstype = array();
			}


			// Array of data values Memory single variable base on Database Table Fieldnames
			public function loadrecord_clssBioLocation() {
				$this->clearlist_clssBioLocation();
				$this->getConnection();
				$selectQuery = "SELECT * FROM bio_location_tbl";
				$stmt = $this->cnn->prepare($selectQuery);

				$stmt->execute();

				$data = array();

				for ($i=0; $row = $stmt->fetch(); $i++) {
					$data[] = $row;
				}

				return $data;
			}

			// Reading of data values Memory list variable base on Database Table Fieldnames
			public function list_clssBioLocation() {
				$this->clearlist_clssBioLocation();
				$this->getConnection();

				$selectQuery = "SELECT * FROM bio_location_tbl";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_biolocationid[] = $rwRcrd['bio_location_id'];
						$this->list_biolocation[] = $rwRcrd['bio_location'];
						$this->list_timelogstype[] = $rwRcrd['timelogs_type'];
					}
				} else {
					echo "Record not found.";
				}
			}
		}

	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error: {$err_msg}</p>";
		die;
	}
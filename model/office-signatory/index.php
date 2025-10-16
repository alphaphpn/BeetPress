<?php 

	try {

		require_once "lib/cnn.php";

		class officeSignatory extends myDatabase {
			// Memory single variable base on Database Table Fieldnames
			Public $officesignatoryautoidjj,
				$agencycodejj,
				$agencynamejj,
				$officeidjj,
				$officecodejj,
				$officenamejj,
				$officetitlejj,
				$officeabrvjj,
				$oldofficeabrvjj,
				$headofficerjj,
				$headtitlejj,
				$authheadjj,
				$authtitlejj,
				$authdescriptionjj,
				$authimagewsignjj,
				$authimagewoutsignjj,
				$effectivitydatejj,
				$signatorystatusjj,
				$officegpslocationjj,
				$xdeljj,
				$createdbyjj,
				$modifiedbyjj,
				$modifiedatjj,
				$createdatjj;

			// Memory list variable base on Database Table Fieldnames
			Public $list_officesignatoryautoidjj,
				$list_agencycodejj,
				$list_agencynamejj,
				$list_officeidjj,
				$list_officecodejj,
				$list_officenamejj,
				$list_officetitlejj,
				$list_officeabrvjj,
				$list_oldofficeabrvjj,
				$list_headofficerjj,
				$list_headtitlejj,
				$list_authheadjj,
				$list_authtitlejj,
				$list_authdescriptionjj,
				$list_authimagewsignjj,
				$list_authimagewoutsignjj,
				$list_effectivitydatejj,
				$list_signatorystatusjj,
				$list_officegpslocationjj,
				$list_xdeljj,
				$list_createdbyjj,
				$list_modifiedbyjj,
				$list_modifiedatjj,
				$list_createdatjj;

			// Constructer Memory list variable base on Database Table Fieldnames
			public function __construct() {
				$this->list_officesignatoryautoidjj = array();
				$this->list_agencycodejj = array();
				$this->list_agencynamejj = array();
				$this->list_officeidjj = array();
				$this->list_officecodejj = array();
				$this->list_officenamejj = array();
				$this->list_officetitlejj = array();
				$this->list_officeabrvjj = array();
				$this->list_oldofficeabrvjj = array();
				$this->list_headofficerjj = array();
				$this->list_headtitlejj = array();
				$this->list_authheadjj = array();
				$this->list_authtitlejj = array();
				$this->list_authdescriptionjj = array();
				$this->list_authimagewsignjj = array();
				$this->list_authimagewoutsignjj = array();
				$this->list_effectivitydatejj = array();
				$this->list_signatorystatusjj = array();
				$this->list_officegpslocationjj = array();
				$this->list_xdeljj = array();
				$this->list_createdbyjj = array();
				$this->list_modifiedbyjj = array();
				$this->list_modifiedatjj = array();
				$this->list_createdatjj = array();
			}

			// Clearing of data values Memory list variable base on Database Table Fieldnames
			public function clearlist_officeSignatory() {
				$this->list_officesignatoryautoidjj = array();
				$this->list_agencycodejj = array();
				$this->list_agencynamejj = array();
				$this->list_officeidjj = array();
				$this->list_officecodejj = array();
				$this->list_officenamejj = array();
				$this->list_officetitlejj = array();
				$this->list_officeabrvjj = array();
				$this->list_oldofficeabrvjj = array();
				$this->list_headofficerjj = array();
				$this->list_headtitlejj = array();
				$this->list_authheadjj = array();
				$this->list_authtitlejj = array();
				$this->list_authdescriptionjj = array();
				$this->list_authimagewsignjj = array();
				$this->list_authimagewoutsignjj = array();
				$this->list_effectivitydatejj = array();
				$this->list_signatorystatusjj = array();
				$this->list_officegpslocationjj = array();
				$this->list_xdeljj = array();
				$this->list_createdbyjj = array();
				$this->list_modifiedbyjj = array();
				$this->list_modifiedatjj = array();
				$this->list_createdatjj = array();
			}


			// Array of data values Memory single variable base on Database Table Fieldnames
			public function loadrecord_officeSignatory() {
				$this->clearlist_officeSignatory();
				$this->getConnection();
				$selectQuery = "SELECT * FROM office_signatory_tbl";
				$stmt = $this->cnn->prepare($selectQuery);

				$stmt->execute();

				$data = array();

				for ($i=0; $row = $stmt->fetch(); $i++) {
					$data[] = $row;
				}

				return $data;
			}

			// Reading of data values Memory list variable base on Database Table Fieldnames NOT DELETED
			public function vwofficeSignatoryZero() {
				$this->clearlist_officeSignatory();
				$this->getConnection();

				$selectQuery = "SELECT * FROM office_signatory_tbl WHERE xdel=0";
				$stmt = $this->cnn->prepare($selectQuery);
				$stmt->execute();

				$cntRcrd = $stmt->rowCount();

				if ($cntRcrd > 0) {
					foreach ($stmt as $rwRcrd) {
						$this->list_officesignatoryautoidjj[] = $rwRcrd['office_signatory_autoid'];
						$this->list_agencycodejj[] = $rwRcrd['agency_code'];
						$this->list_agencynamejj[] = $rwRcrd['agency_name'];
						$this->list_officeidjj[] = $rwRcrd['officeid'];
						$this->list_officecodejj[] = $rwRcrd['officecode'];
						$this->list_officenamejj[] = $rwRcrd['officename'];
						$this->list_officetitlejj[] = $rwRcrd['officetitle'];
						$this->list_officeabrvjj[] = $rwRcrd['officeabrv'];
						$this->list_oldofficeabrvjj[] = $rwRcrd['oldofficeabrv'];
						$this->list_headofficerjj[] = $rwRcrd['headofficer'];
						$this->list_headtitlejj[] = $rwRcrd['headtitle'];
						$this->list_authheadjj[] = $rwRcrd['auth_head'];
						$this->list_authtitlejj[] = $rwRcrd['auth_title'];
						$this->list_authdescriptionjj[] = $rwRcrd['auth_description'];
						$this->list_authimagewsignjj[] = $rwRcrd['auth_image_w_sign'];
						$this->list_authimagewoutsignjj[] = $rwRcrd['auth_image_wout_sign'];
						$this->list_effectivitydatejj[] = $rwRcrd['effectivity_date'];
						$this->list_signatorystatusjj[] = $rwRcrd['signatory_status'];
						$this->list_officegpslocationjj[] = $rwRcrd['office_gps_location'];
						$this->list_xdeljj[] = $rwRcrd['xdel'];
						$this->list_createdbyjj[] = $rwRcrd['createdby'];
						$this->list_modifiedbyjj[] = $rwRcrd['modifiedby'];
						$this->list_modifiedatjj[] = $rwRcrd['modified_at'];
						$this->list_createdatjj[] = $rwRcrd['created_at'];
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

?>
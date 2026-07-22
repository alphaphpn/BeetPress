<?php

	if (file_exists("../../lib/cnn.php")) {
		require_once "../../lib/cnn.php";
	} elseif (file_exists("../../../lib/cnn.php")) {
		require_once "../../../lib/cnn.php";
	}

	$autoid              = isset($_POST['autoid'])               ? trim($_POST['autoid'])               : null;
	$agency_code         = isset($_POST['agency_code'])          ? trim($_POST['agency_code'])          : '';
	$officeid            = isset($_POST['officeid'])             ? trim($_POST['officeid'])             : '';
	$officecode          = isset($_POST['officecode'])           ? trim($_POST['officecode'])           : '';
	$officename          = isset($_POST['officename'])           ? trim($_POST['officename'])           : '';
	$officetitle         = isset($_POST['officetitle'])          ? trim($_POST['officetitle'])          : '';
	$officeabrv          = isset($_POST['officeabrv'])           ? trim($_POST['officeabrv'])           : '';
	$oldofficeabrv       = isset($_POST['oldofficeabrv'])        ? trim($_POST['oldofficeabrv'])        : '';
	$headofficer         = isset($_POST['headofficer'])          ? trim($_POST['headofficer'])          : '';
	$headtitle           = isset($_POST['headtitle'])            ? trim($_POST['headtitle'])            : '';
	$auth_head           = isset($_POST['auth_head'])            ? trim($_POST['auth_head'])            : '';
	$auth_title          = isset($_POST['auth_title'])           ? trim($_POST['auth_title'])           : '';
	$auth_description    = isset($_POST['auth_description'])     ? trim($_POST['auth_description'])     : '';
	$effectivity_date    = isset($_POST['effectivity_date'])     ? trim($_POST['effectivity_date'])     : null;
	$signatory_status    = isset($_POST['signatory_status'])     ? intval($_POST['signatory_status'])   : 0;
	$office_gps_location = isset($_POST['office_gps_location'])  ? trim($_POST['office_gps_location'])  : '';
	$meter               = isset($_POST['meter'])                 ? trim($_POST['meter'])                 : null;

	try {
		$db  = new myDatabase();
		$cnn = $db->getConnection();

		$sql = "UPDATE office_signatory_tbl SET
					agency_code          = :agency_code,
					officeid             = :officeid,
					officecode           = :officecode,
					officename           = :officename,
					officetitle          = :officetitle,
					officeabrv           = :officeabrv,
					oldofficeabrv        = :oldofficeabrv,
					headofficer          = :headofficer,
					headtitle            = :headtitle,
					auth_head            = :auth_head,
					auth_title           = :auth_title,
					auth_description     = :auth_description,
					effectivity_date     = :effectivity_date,
					signatory_status     = :signatory_status,
					office_gps_location  = :office_gps_location,
					meter                = :meter
				WHERE office_signatory_autoid = :autoid";

		$stmt = $cnn->prepare($sql);
		$stmt->bindParam(':agency_code',         $agency_code);
		$stmt->bindParam(':officeid',            $officeid);
		$stmt->bindParam(':officecode',          $officecode);
		$stmt->bindParam(':officename',          $officename);
		$stmt->bindParam(':officetitle',         $officetitle);
		$stmt->bindParam(':officeabrv',          $officeabrv);
		$stmt->bindParam(':oldofficeabrv',       $oldofficeabrv);
		$stmt->bindParam(':headofficer',         $headofficer);
		$stmt->bindParam(':headtitle',           $headtitle);
		$stmt->bindParam(':auth_head',           $auth_head);
		$stmt->bindParam(':auth_title',          $auth_title);
		$stmt->bindParam(':auth_description',    $auth_description);
		$stmt->bindParam(':effectivity_date',    $effectivity_date);
		$stmt->bindParam(':signatory_status',    $signatory_status);
		$stmt->bindParam(':office_gps_location', $office_gps_location);
		$stmt->bindParam(':meter',               $meter);
		$stmt->bindParam(':autoid',              $autoid);
		$stmt->execute();

		echo json_encode(['status' => 'success']);

	} catch (PDOException $e) {
		echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
	}

?>

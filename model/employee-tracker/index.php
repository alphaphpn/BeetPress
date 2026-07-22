<?php

	try {

		if (file_exists("lib/cnn.php")) {
			require_once "lib/cnn.php";
		} elseif (file_exists("../../lib/cnn.php")) {
			require_once "../../lib/cnn.php";
		}

		class employeeTracker extends myDatabase {

			public $list_empidcode,
				$list_employee_name,
				$list_officetitle,
				$list_designation,
				$list_employee_role,
				$list_work_location,
				$list_office_landmark,
				$list_office_longitude,
				$list_office_latitude,
				$list_office_meter,
				$list_online_status,
				$list_device_id,
				$list_device_name,
				$list_duty_status;

			public function __construct() {
				$this->list_empidcode        = [];
				$this->list_employee_name    = [];
				$this->list_officetitle      = [];
				$this->list_designation      = [];
				$this->list_employee_role    = [];
				$this->list_work_location    = [];
				$this->list_office_landmark  = [];
				$this->list_office_longitude = [];
				$this->list_office_latitude  = [];
				$this->list_office_meter     = [];
				$this->list_online_status    = [];
				$this->list_device_id        = [];
				$this->list_device_name      = [];
				$this->list_duty_status      = [];
			}

			public function fn_ListEmployeeTracker($officeid) {
				$this->__construct();
				$this->getConnection();

				if (empty($officeid) || $officeid == 0 || $officeid == null) {
					$sql = "SELECT e.emp_idcode, e.emp_name_forid, e.officetitle, e.designationforid,
					               e.employee_role, e.work_location, e.office_landmark,
					               e.office_longitude, e.office_latitude,
					               e.office_meter,
					               e.online_status, e.device_id, e.device_name, e.duty_status
					        FROM employee_tbl e
					        WHERE e.xdel=0 ORDER BY e.created_at DESC";
					$stmt = $this->cnn->prepare($sql);
				} else {
					$sql = "SELECT e.emp_idcode, e.emp_name_forid, e.officetitle, e.designationforid,
					               e.employee_role, e.work_location, e.office_landmark,
					               e.office_longitude, e.office_latitude,
					               e.office_meter,
					               e.online_status, e.device_id, e.device_name, e.duty_status
					        FROM employee_tbl e
					        WHERE e.officeid=:officeid AND e.xdel=0 ORDER BY e.created_at DESC";
					$stmt = $this->cnn->prepare($sql);
					$stmt->bindParam(':officeid', $officeid);
				}
				$stmt->execute();

				if ($stmt->rowCount() > 0) {
					foreach ($stmt as $row) {
						$this->list_empidcode[]        = $row['emp_idcode'];
						$this->list_employee_name[]    = $row['emp_name_forid'];
						$this->list_officetitle[]      = $row['officetitle'];
						$this->list_designation[]      = $row['designationforid'];
						$this->list_employee_role[]    = $row['employee_role'];
						$this->list_work_location[]    = $row['work_location'];
						$this->list_office_landmark[]  = $row['office_landmark'];
						$this->list_office_longitude[] = $row['office_longitude'];
						$this->list_office_latitude[]  = $row['office_latitude'];
						$this->list_office_meter[]     = $row['office_meter'];
						$this->list_online_status[]    = $row['online_status'];
						$this->list_device_id[]        = $row['device_id'];
						$this->list_device_name[]      = $row['device_name'];
						$this->list_duty_status[]      = $row['duty_status'];
					}
					return true;
				}
				return false;
			}

			public static function roleLabel($val) {
				$map = [
					0 => 'Staff',
					1 => 'Official',
					2 => 'Executive',
					3 => 'Head Officer',
					4 => 'Assistant Head Officer',
					5 => 'Admin Officer',
				];
				return isset($map[$val]) ? $map[$val] : $val;
			}
		}

	} catch (PDOException $error) {
		echo "<p>Error @ employeeTracker: " . $error->getMessage() . "</p>";
		die;
	}

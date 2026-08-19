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
				$list_duty_status,
				$list_duty_tooltip,
				$list_duty_map_origin,
				$list_duty_map_destination;

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
				$this->list_duty_tooltip     = [];
				$this->list_duty_map_origin  = [];
				$this->list_duty_map_destination = [];
			}

			private function hasTimeLog($value) {
				return trim((string) $value) !== '';
			}

			private function gpsCoordinates($value) {
				preg_match_all('/-?\d+(?:\.\d+)?/', (string) $value, $matches);
				if (count($matches[0]) < 2) return '';
				$first = (float) $matches[0][0];
				$second = (float) $matches[0][1];
				if (abs($first) > 90 && abs($second) <= 90) {
					$temp = $first;
					$first = $second;
					$second = $temp;
				}
				if (abs($first) > 90 || abs($second) > 180) return '';
				return $first . ',' . $second;
			}

			public function fn_ListEmployeeTracker($officeid) {
				$this->__construct();
				$this->getConnection();
				$currentYear = (int) date('Y');
				$currentMonth = (int) date('n');
				$currentDay = (int) date('j');
				$currentPeriod = date('A') === 'AM' ? 'AM' : 'PM';

				// Duty status is calculated from today's DTR: clock-in without its matching clock-out means On-Duty.
				$dutyStatusSql = "CASE WHEN :duty_period = 'AM' THEN
					CASE WHEN NULLIF(TRIM(d.amtimein), '') IS NOT NULL AND NULLIF(TRIM(d.amtimeout), '') IS NULL THEN 1 ELSE 0 END
					ELSE CASE WHEN NULLIF(TRIM(d.pmtimein), '') IS NOT NULL AND NULLIF(TRIM(d.pmtimeout), '') IS NULL THEN 1 ELSE 0 END
					END AS duty_status";
				$dtrJoinSql = "LEFT JOIN (
					SELECT emp_idcode,
						MAX(amtimein) AS amtimein, MAX(amtimeout) AS amtimeout,
						MAX(pmtimein) AS pmtimein, MAX(pmtimeout) AS pmtimeout,
						MAX(attendance_gps_location_am_in) AS attendance_gps_location_am_in,
						MAX(attendance_gps_location_am_out) AS attendance_gps_location_am_out,
						MAX(attendance_gps_location_pm_in) AS attendance_gps_location_pm_in,
						MAX(attendance_gps_location_pm_out) AS attendance_gps_location_pm_out
					FROM employee_dtr_sub_tbl
					WHERE yearno = :dtr_year AND monthno = :dtr_month AND dayno = :dtr_day
					GROUP BY emp_idcode
				) d ON d.emp_idcode = e.emp_idcode";
				$params = array(':duty_period' => $currentPeriod, ':dtr_year' => $currentYear, ':dtr_month' => $currentMonth, ':dtr_day' => $currentDay);

				if (empty($officeid) || $officeid == 0 || $officeid == null) {
					$sql = "SELECT e.emp_idcode, e.emp_name_forid, e.officetitle, e.designationforid,
					               e.employee_role, e.work_location, e.office_landmark,
					               e.office_longitude, e.office_latitude,
					               e.office_meter,
					               e.online_status, e.device_id, e.device_name,
					               d.amtimein, d.amtimeout, d.pmtimein, d.pmtimeout,
					               d.attendance_gps_location_am_in, d.attendance_gps_location_am_out,
					               d.attendance_gps_location_pm_in, d.attendance_gps_location_pm_out,
					               {$dutyStatusSql}
					        FROM employee_tbl e {$dtrJoinSql}
					        WHERE e.xdel=0 ORDER BY e.created_at DESC";
					$stmt = $this->cnn->prepare($sql);
				} else {
					$sql = "SELECT e.emp_idcode, e.emp_name_forid, e.officetitle, e.designationforid,
					               e.employee_role, e.work_location, e.office_landmark,
					               e.office_longitude, e.office_latitude,
					               e.office_meter,
					               e.online_status, e.device_id, e.device_name,
					               d.amtimein, d.amtimeout, d.pmtimein, d.pmtimeout,
					               d.attendance_gps_location_am_in, d.attendance_gps_location_am_out,
					               d.attendance_gps_location_pm_in, d.attendance_gps_location_pm_out,
					               {$dutyStatusSql}
					        FROM employee_tbl e {$dtrJoinSql}
					        WHERE e.officeid=:officeid AND e.xdel=0 ORDER BY e.created_at DESC";
					$stmt = $this->cnn->prepare($sql);
					$params[':officeid'] = $officeid;
				}
				$stmt->execute($params);

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

						$timeLogDetails = array(
							'AM In: ' . ($this->hasTimeLog($row['amtimein']) ? $row['amtimein'] : 'Not logged'),
							'AM Out: ' . ($this->hasTimeLog($row['amtimeout']) ? $row['amtimeout'] : 'Not logged'),
							'PM In: ' . ($this->hasTimeLog($row['pmtimein']) ? $row['pmtimein'] : 'Not logged'),
							'PM Out: ' . ($this->hasTimeLog($row['pmtimeout']) ? $row['pmtimeout'] : 'Not logged')
						);
						$latestGps = '';
						if ($currentPeriod === 'AM') {
							$latestGps = $this->hasTimeLog($row['amtimeout']) ? $row['attendance_gps_location_am_out'] : $row['attendance_gps_location_am_in'];
						} else {
							if ($this->hasTimeLog($row['pmtimeout'])) $latestGps = $row['attendance_gps_location_pm_out'];
							elseif ($this->hasTimeLog($row['pmtimein'])) $latestGps = $row['attendance_gps_location_pm_in'];
							elseif ($this->hasTimeLog($row['amtimeout'])) $latestGps = $row['attendance_gps_location_am_out'];
							else $latestGps = $row['attendance_gps_location_am_in'];
						}
						$destination = $this->gpsCoordinates($latestGps);
						$origin = $this->gpsCoordinates($row['office_latitude'] . ',' . $row['office_longitude']);
						if ($origin === '') $origin = trim((string) $row['office_landmark']);
						if ($destination !== '') $timeLogDetails[] = 'Click to open directions to the logged GPS location.';
						else $timeLogDetails[] = 'No GPS location is available for the current time-log event.';
						$this->list_duty_tooltip[] = implode("\n", $timeLogDetails);
						$this->list_duty_map_origin[] = $origin;
						$this->list_duty_map_destination[] = $destination;
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

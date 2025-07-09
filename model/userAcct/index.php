<?php 

	require_once "../../lib/cnn.php";

	class authAcct extends myDatabase {

		Public $uAccT_authid, 
			$uAccT_uid, 
			$uAccT_profileid, 
			$uAccT_uname, 
			$uAccT_pword, 
			$uAccT_country, 
			$uAccT_countrycode, 
			$uAccT_phone, 
			$uAccT_email, 
			$uAccT_verified, 
			$uAccT_ustat, 
			$uAccT_ulevel, 
			$uAccT_uposition, 
			$uAccT_createddate, 
			$uAccT_datemodified, 
			$uAccT_onoffline, 
			$uAccT_secure_question, 
			$uAccT_secure_answer, 
			$uAccT_xdel, 
			$uAccT_officeid, 
			$uAccT_officeabvr, 
			$uAccT_officeabvr;

		Public $uLogz_user_logs_id, 
			$uLogz_user_logs_date, 
			$uLogz_user_logs_time, 
			$uLogz_uid, 
			$uLogz_profileid, 
			$uLogz_uname, 
			$uLogz_phone, 
			$uLogz_email, 
			$uLogz_ustat, 
			$uLogz_ulevel, 
			$uLogz_uposition, 
			$uLogz_officeid, 
			$uLogz_officeabvr, 
			$uLogz_onoffline, 
			$uLogz_onoffline;

		Public $uAccT_list_authid, 
			$uAccT_list_uid, 
			$uAccT_list_profileid, 
			$uAccT_list_uname, 
			$uAccT_list_pword, 
			$uAccT_list_country, 
			$uAccT_list_countrycode, 
			$uAccT_list_phone, 
			$uAccT_list_email, 
			$uAccT_list_verified, 
			$uAccT_list_ustat, 
			$uAccT_list_ulevel, 
			$uAccT_list_uposition, 
			$uAccT_list_createddate, 
			$uAccT_list_datemodified, 
			$uAccT_list_onoffline, 
			$uAccT_list_secure_question, 
			$uAccT_list_secure_answer, 
			$uAccT_list_xdel, 
			$uAccT_list_officeid, 
			$uAccT_list_officeabvr, 
			$uAccT_list_officeabvr;

		Public $uLogz_list_user_logs_id, 
			$uLogz_list_user_logs_date, 
			$uLogz_list_user_logs_time, 
			$uLogz_list_uid, 
			$uLogz_list_profileid, 
			$uLogz_list_uname, 
			$uLogz_list_phone, 
			$uLogz_list_email, 
			$uLogz_list_ustat, 
			$uLogz_list_ulevel, 
			$uLogz_list_uposition, 
			$uLogz_list_officeid, 
			$uLogz_list_officeabvr, 
			$uLogz_list_onoffline, 
			$uLogz_list_onoffline;

		public function __construct() {
			$this->uAccT_list_authid = array();
			$this->uAccT_list_uid = array();
			$this->uAccT_list_profileid = array();
			$this->uAccT_list_uname = array();
			$this->uAccT_list_pword = array();
			$this->uAccT_list_country = array();
			$this->uAccT_list_countrycode = array();
			$this->uAccT_list_phone = array();
			$this->uAccT_list_email = array();
			$this->uAccT_list_verified = array();
			$this->uAccT_list_ustat = array();
			$this->uAccT_list_ulevel = array();
			$this->uAccT_list_uposition = array();
			$this->uAccT_list_createddate = array();
			$this->uAccT_list_datemodified = array();
			$this->uAccT_list_onoffline = array();
			$this->uAccT_list_secure_question = array();
			$this->uAccT_list_secure_answer = array();
			$this->uAccT_list_xdel = array();
			$this->uAccT_list_officeid = array();
			$this->uAccT_list_officeabvr = array();
			$this->uAccT_list_officeabvr = array();

			$this->uLogz_list_user_logs_id = array();
			$this->uLogz_list_user_logs_date = array();
			$this->uLogz_list_user_logs_time = array();
			$this->uLogz_list_uid = array();
			$this->uLogz_list_profileid = array();
			$this->uLogz_list_uname = array();
			$this->uLogz_list_phone = array();
			$this->uLogz_list_email = array();
			$this->uLogz_list_ustat = array();
			$this->uLogz_list_ulevel = array();
			$this->uLogz_list_uposition = array();
			$this->uLogz_list_officeid = array();
			$this->uLogz_list_officeabvr = array();
			$this->uLogz_list_onoffline = array();
			$this->uLogz_list_onoffline = array();
		}

		public function clearlist_userAcct() {
			$this->uAccT_list_authid = array();
			$this->uAccT_list_uid = array();
			$this->uAccT_list_profileid = array();
			$this->uAccT_list_uname = array();
			$this->uAccT_list_pword = array();
			$this->uAccT_list_country = array();
			$this->uAccT_list_countrycode = array();
			$this->uAccT_list_phone = array();
			$this->uAccT_list_email = array();
			$this->uAccT_list_verified = array();
			$this->uAccT_list_ustat = array();
			$this->uAccT_list_ulevel = array();
			$this->uAccT_list_uposition = array();
			$this->uAccT_list_createddate = array();
			$this->uAccT_list_datemodified = array();
			$this->uAccT_list_onoffline = array();
			$this->uAccT_list_secure_question = array();
			$this->uAccT_list_secure_answer = array();
			$this->uAccT_list_xdel = array();
			$this->uAccT_list_officeid = array();
			$this->uAccT_list_officeabvr = array();
			$this->uAccT_list_officeabvr = array();
		}

		public function clearlist_userLogs() {
			$this->uLogz_list_user_logs_id = array();
			$this->uLogz_list_user_logs_date = array();
			$this->uLogz_list_user_logs_time = array();
			$this->uLogz_list_uid = array();
			$this->uLogz_list_profileid = array();
			$this->uLogz_list_uname = array();
			$this->uLogz_list_phone = array();
			$this->uLogz_list_email = array();
			$this->uLogz_list_ustat = array();
			$this->uLogz_list_ulevel = array();
			$this->uLogz_list_uposition = array();
			$this->uLogz_list_officeid = array();
			$this->uLogz_list_officeabvr = array();
			$this->uLogz_list_onoffline = array();
			$this->uLogz_list_onoffline = array();
		}

		public function loadrecord_userAcct() {
			$this->clearlist_userAcct();
			$this->getConnection();
			$selectQuery = "SELECT * FROM user_tbl";
			$stmt = $this->cnn->prepare($selectQuery);

			$stmt->execute();

			$data = array();

			for ($i=0; $row = $stmt->fetch(); $i++) {
				$data[] = $row;
			}

			return $data;
		}

		public function loadrecord_userLogs() {
			$this->clearlist_userLogs();
			$this->getConnection();
			$selectQuery = "SELECT * FROM user_logs";
			$stmt = $this->cnn->prepare($selectQuery);

			$stmt->execute();

			$data = array();

			for ($i=0; $row = $stmt->fetch(); $i++) {
				$data[] = $row;
			}

			return $data;
		}

	}

?>
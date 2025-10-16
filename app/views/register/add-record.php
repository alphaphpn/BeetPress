<?php 

	$reg_fbid = null;
	$reg_nickname = null;
	$reg_phone = null;
	$reg_phone2 = null;
	$reg_email = null;
	$reg_fname = null;
	$reg_mname = null;
	$reg_lname = null;
	$reg_suffix = null;
	$reg_ntitle = null;
	$reg_birthyear = trim(date('Y') - 18);
	$reg_birthmonth = null;
	$reg_birthday = null;
	$reg_gender = null;
	$reg_username = null;
	$reg_password = null;
	$reg_password2 = null;
	$reg_sysid = null;
	$reg_imgpic = null;
	$sagestidpw = suggestedPassWord();
	$reg_pincode = randomNumbr6();

	try {
		if ( isset($_POST["btnSubmit"]) ) {
			$reg_fbid = isset($_POST["fbid"]) ? $_POST["fbid"] : null;
			$reg_nickname = isset($_POST["nickname"]) ? $_POST["nickname"] : null;
			$reg_phone = isset($_POST["phone"]) ? $_POST["phone"] : null;
			$reg_phone2 = isset($_POST["phone2"]) ? $_POST["phone2"] : null;
			$reg_email = isset($_POST["email"]) ? $_POST["email"] : null;
			$reg_fname = isset($_POST["first-name"]) ? $_POST["first-name"] : null;
			$reg_mname = isset($_POST["middle-name"]) ? $_POST["middle-name"] : null;
			$reg_lname = isset($_POST["last-name"]) ? $_POST["last-name"] : null;
			$reg_suffix = isset($_POST["suffix"]) ? $_POST["suffix"] : null;
			$reg_ntitle = isset($_POST["name-title"]) ? $_POST["name-title"] : null;
			$reg_birthyear = isset($_POST["birth-year"]) ? $_POST["birth-year"] : null;
			$reg_birthmonth = isset($_POST["birth-month"]) ? $_POST["birth-month"] : null;
			$reg_birthday = isset($_POST["birth-day"]) ? $_POST["birth-day"] : null;
			$reg_gender = isset($_POST["gender"]) ? $_POST["gender"] : null;
			$reg_username = isset($_POST["nameuser"]) ? $_POST["nameuser"] : null;
			$reg_password = isset($_POST["password"]) ? $_POST["password"] : null;
			$reg_password2 = isset($_POST["password2"]) ? $_POST["password2"] : null;
			$reg_sysid = isset($_POST["sysid"]) ? $_POST["sysid"] : null;
			$reg_imgpic = isset($_POST["imgdata"]) ? $_POST["imgdata"] : null;

			if ( empty(trim($reg_nickname)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Nickname / Alias.';
				echo '</div>';
			} elseif ( empty(trim($reg_fname)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Firstname.';
				echo '</div>';
			} elseif ( empty(trim($reg_lname)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Lastname.';
				echo '</div>';
			} elseif ( empty(trim($reg_birthyear)) ) {
				$reg_birthyear = trim(date('Y') - 18);
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Valid Birth Year.';
				echo '</div>';
			} elseif ( empty(trim($reg_birthmonth)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Birth Month.';
				echo '</div>';
			} elseif ( empty(trim($reg_birthday)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter Birth Day.';
				echo '</div>';
			} elseif ( empty(trim($reg_gender)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please select Gender.';
				echo '</div>';
			} elseif ( empty(trim($reg_username)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please Enter Username.';
				echo '</div>';
			} elseif ( empty(trim($reg_password)) || empty(trim($reg_password2)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please Enter Password and Re-Type Password.';
				echo '</div>';
			} elseif ( empty(trim($reg_imgpic)) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please Take a Picture.';
				echo '</div>';
			} elseif ( trim($reg_password) !== trim($reg_password2) ) {
				echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Password Mismatched!';
				echo '</div>';
			} else {
				// If all fields are Valid.
				include_once "model/userAcct/index.php";
				$authAcctx = new authAcct();

				// Searching for Duplicate Username
				if ( $authAcctx->Search_userAcct_username($reg_username) ) {
					echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'Username already exist!';
					echo '</div>';
				} elseif ( $authAcctx->Search_userAcct_phone($reg_phone) ) {
					echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'Primary Phone already exist!';
					echo '</div>';
				} elseif ( $authAcctx->Search_userAcct_uemail($reg_email) ) {
					echo '<div class="alert alert-danger alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'E-Mail already exist!';
					echo '</div>';
				} else {
					echo '<div class="alert alert-info alert-dismissible fade show m-1">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'Successfully Registered. You may <a href="login?">Login</a> Now!';
					echo '</div>';
				}
			}

		}
	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error: {$err_msg}</p>";
		die;
	}

?>
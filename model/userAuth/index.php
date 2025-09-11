<?php 

	require_once "../../lib/cnn.php";

	class authUser extends myDatabase {

		public $xzipcode, $xusername, $xpword, $xulevel, $xuposition;

		public $xtown, $distno, $gcodezip;

		public function authUserTown($xusername,$xpword,$xzipcode) {
			$this->getConnection();

			if ($xusername !== null || $xpword !== null || $xzipcode !== null) {
				$pasCode = md5(trim(htmlspecialchars($xpword)));
				$sqlAuth = "SELECT * FROM user_tbl WHERE uname=:xusername AND pword=:authpw AND ulevel=99 OR uname=:xusername AND pword=:authpw AND ulevel=14";
				$stmtAuth = $this->cnn->prepare($sqlAuth);
				$stmtAuth->bindParam(':xusername', $xusername);
				$stmtAuth->bindParam(':authpw', $pasCode);
				$stmtAuth->execute();

				$countAuth = $stmtAuth->rowCount();

				if ($countAuth > 0) {
					foreach ($stmtAuth as $rowAuth) {
						$xusername = $rowAuth['uname'];
						$xulevel = $rowAuth['ulevel'];
						$xuposition = $rowAuth['uposition'];
					}

					session_start();

					$_SESSION["username"] = $xusername;
					$_SESSION["ulevel"] = $xulevel;
					$_SESSION["uposition"] = $xuposition;

					$sqlTownAuth = "SELECT * FROM municipal_tbl WHERE zipcode=:xzipcode";
					$stmtTownAuth = $this->cnn->prepare($sqlTownAuth);
					$stmtTownAuth->bindValue(':xzipcode', $xzipcode);
					$stmtTownAuth->execute();
					$countTownAuth = $stmtTownAuth->rowCount();

					if ($countTownAuth > 0) {
						foreach ($stmtTownAuth as $rowTownAuth) {
							$xtown = $rowTownAuth['municipal'];
							$distno = $rowTownAuth['district_no'];
							$gcodezip = $rowTownAuth['zipcode'];
							$cleader = $rowTownAuth['coordinator'];
						}

						$_SESSION["xtown"] = $xtown;
						$_SESSION["distno"] = $distno;
						$_SESSION["gcodezip"] = $gcodezip;
						$_SESSION["cleader"] = $cleader;

						echo '<div class="alert alert-primary alert-dismissible fade show">';
							echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
							echo 'Access Granted.';
						echo '</div>';

						echo "<script>window.location.href='../../';</script>";
					} else {
						echo '<div class="alert alert-warning alert-dismissible fade show">';
							echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
							echo 'Invalid ZIPCODE!';
						echo '</div>';
					}
				} else {
					echo '<div class="alert alert-warning alert-dismissible fade show">';
						echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
						echo 'Invalid User!';
					echo '</div>';
				}
			} else {
				echo '<div class="alert alert-denied alert-dismissible fade show">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Access Denied!';
				echo '</div>';
			}
		}

	}

?>
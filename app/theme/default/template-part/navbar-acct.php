<?php 

	$disp_usernamed = isset($_SESSION["d2s8wu_uname"]) ? trim($_SESSION["d2s8wu_uname"]) : null; 
	$disp_userid = isset($_SESSION["d2s8wu_uid"]) ? trim($_SESSION["d2s8wu_uid"]) : null; 

	$actv_emply_employeeactivated = isset($_SESSION["employeeactivated"]) ? trim($_SESSION["employeeactivated"]) : null; 
	$actv_emply_empidcode = isset($_SESSION["empidcode"]) ? trim($_SESSION["empidcode"]) : null; 
	$actv_emply_userid = isset($_SESSION["uid"]) ? trim($_SESSION["uid"]) : null; 

?>

	<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" title="<?php echo trim($disp_usernamed); ?>">Account</a>
		<ul class="dropdown-menu">
			<?php 
				if ( empty($actv_emply_employeeactivated) || empty($actv_emply_empidcode) ) {
					// echo "<script>alert('Please login as Employee!');</script>";
					include_once "navbar-acct-user-employee.php";
				} elseif ( empty($disp_usernamed) || empty($disp_userid) ) {
					// echo "<script>alert('You are not Login!');</script>";
				} elseif ( $actv_emply_employeeactivated && $actv_emply_empidcode ) {
					if ( $disp_userid == $actv_emply_userid ) {
						// echo "<script>alert('User ID and Employee UID Matched!')</script>";
						include_once "navbar-acct-user-employee.php";
						?>
							<li><hr class="dropdown-divider"></li>
						<?php
						include_once "navbar-employee-inner.php";
					} else {
						// echo "<script>alert('User ID and Employee UID Mismatched!')</script>";
						include_once "navbar-navbar-acct-user-only.php";
					}
				}
			?>
		</ul>
	</li>
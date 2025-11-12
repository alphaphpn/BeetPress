<?php 

	include_once "lib/env.php";
	if ( isset($_GET['logout']) ) {
		if ( $_GET['logout'] == 1 ) {
			session_destroy();
			echo '<script>window.open("attendance-auth","_self");</script>';
		}
	} elseif ( isset($_SESSION["empidcode"]) && isset($_SESSION["biono"]) && isset($_SESSION["empname"]) && isset($_SESSION["employeeactivated"]) ) {
		echo '<script>window.open("home","_self");</script>';
	} elseif ( isset($_GET['employeeID']) || isset($_GET['pinInput']) ) {
		$ehemploid = isset($_GET['employeeID']) ? $_GET['employeeID'] : null;
		$ehempincoder = isset($_GET['pinInput']) ? $_GET['pinInput'] : null;
	} else {
		$ehemploid = null;
		$ehempincoder = null;
	}

?>

	<section class="position-relative bg-light w-100 vh-100 pt-3 pb-5 clearfix">
		<div class="container">
			<div class="w-100 text-center d-flex justify-content-center">
				<div class="text-center mb-3" style="width: fit-content;">
					
					<h3 class="text-center mb-4 mobile-font-size-12">Attendance Login</h3>
					<hr class="y-axis-margin-0-nobile">
				</div>
			</div>

			<div class="w-100 text-center d-flex justify-content-center">
				<div class="text-center mb-3">
					<div class="row">
						<div class="col-lg-12">
							<div class="position-relative clearfix">
								<form id="empLogin" method="post" class="needs-validation" novalidate>
									<div class="mb-3">
										<label for="emailInput" class="form-label">Employee ID</label>
										<input type="number" value="<?php echo trim($ehemploid); ?>" min="10000000" max="99999999" class="form-control" id="employeeID" name="employeeID" onfocus="this.select();" aria-describedby="employeeHelp" required>
										<div class="valid-feedback">Valid.</div>
										<div class="invalid-feedback">Invalid Employee ID.</div>
									</div>

									<div class="mb-3">
										<label for="pinInput" class="form-label">PIN</label>
										<div class="input-group">
											<input type="password" value="<?php echo trim($ehempincoder); ?>" onfocus="this.select();" pattern="[0-9]{6,8}" class="form-control password" id="pinInput" name="pinInput" autocomplete="new-password" onpaste="return false;" required>
											<div class="input-group-prepend cursor-hand">
												<span id="show_hide_pin" class="input-group-text h-100 rounded-0 rounded-end">
													<i class="fa fa-eye-slash" aria-hidden="true" onclick="PinHideShow()"></i>
												</span>
											</div>
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Invalid PIN Code. Numbers only.</div>
										</div>
									</div>

									<div class="mb-3 form-check">
										<input type="checkbox" class="form-check-input" id="rememberMeCheck">
										<label class="form-check-label" for="rememberMeCheck">Remember me</label>
									</div>

									<div class="mb-3"><?php include_once "model/employeeAuth/index.php"; ?></div>

									<button type="submit" id="btnEmpLogin" name="btnEmpLogin" class="btn third-bg-color text-white mb-3">Time In/Out</button>

								<?php 
									if ( $onlineornot == 1 ) {
								?>
									<div class="mb-3">
										<script src="assets/js/getyourgps.js"></script>
										<div id="GpsErrMassage" class="text-primary cursor-pointer">Your Location</div>
										<input type="text" class="form-control w-100 d-none" id="gpsInput" name="gpsInput" required>
										<div class="invalid-feedback">Please turn ON your GPS and make sure to have an internet connection.</div>
									</div>
								<?php 	
									} else {
								?>
										<div class="text-info">Your on a Local Network System</div>
								<?php 
									}
								?>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


<?php 

	include_once "app/views/index/feat-img.php";
	include_once "app/views/index/footer-info.php";
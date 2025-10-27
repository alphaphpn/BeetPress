<?php 

	$username = null;
	$password = null;

?>

	<form id="user-login" method="post" class="needs-validation" novalidate>

		<div class="mb-3"><?php include_once "model/userAuth/index.php"; ?></div>

		<div class="mb-3">
			<label for="username" class="form-label">Username</label>
			<input type="text" value="<?php echo trim($username); ?>" class="form-control w-100" id="username" name="username" aria-describedby="usernameHelp" placeholder="Enter Username" required>
			<div class="valid-feedback">Valid.</div>
			<div class="invalid-feedback">Invalid Username</div>
		</div>

		<div class="mb-3">
			<label for="password" class="form-label">Password</label>
			<div class="input-group">
				<input id="password" type="password" value="<?php echo trim(htmlspecialchars($password)); ?>" onfocus="this.select();" class="form-control password" placeholder="Enter password" name="password" autocomplete="new-password" onpaste="return false;" required>
				<div class="input-group-prepend cursor-hand">
					<span id="show_hide_password" class="input-group-text h-100 rounded-0 rounded-end">
						<i class="fa fa-eye-slash" aria-hidden="true" onclick="PwHideShow()"></i>
					</span>
				</div>
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-feedback">Invalid password</div>
			</div>
		</div>

		<div class="mb-3 form-check d-flex justify-align-center gap-3">
			<input type="checkbox" class="form-check-input" id="rememberMeCheck">
			<label class="form-check-label" for="rememberMeCheck">Remember me</label>
		</div>

		<button type="submit" id="btnUserLogin" name="btnUserLogin" class="btn third-bg-color text-white mb-3">Login</button>

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
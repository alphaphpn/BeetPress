<?php 

	if ( isset($_SESSION["empidcode"]) ) {
		echo '<script>window.open("employee-info","_self");</script>';
	}

?>

	<style>
		#disp-vid { max-width: 290px; overflow: hidden; }
		#disp-vid video#video { max-height: 350px; object-fit: cover; margin-left: calc(100% / -2.5); }

		.vidframez {
			position: absolute;
			border-width: 16px 56px 74px 56px;
			border-style: solid;
			border-color: rgba(255, 255, 255, 0.3);
			box-sizing: border-box;
			inset: 0px;
		}

		/* The message box is shown when the user clicks on the password field */
		#message {
			display:none;
			background: #f1f1f1;
			color: #000;
			position: relative;
			padding: 20px;
			margin-top: 10px;
		}

		#message p {
			padding: 5px 35px;
			font-size: 18px;
		}

		/* Add a green text color and a checkmark when the requirements are right */
		.valid {
			color: green;
		}

		.valid:before {
			position: relative;
			left: -35px;
			content: "✔";
		}

		/* Add a red text color and an "x" when the requirements are wrong */
		.invalid {
			color: red;
		}

		.invalid:before {
			position: relative;
			left: -35px;
			content: "✖";
		}

		@media only screen and (max-width: 425px) {
			#disp-vid video#video { margin-left: auto; margin-right: auto; }
		}
	</style>

	<section id="sign-up" class="w-100 mh-100 py-5">
		<div class="container mh-100">
			<div class="card m-auto" style="max-width: 1024px;">
				<form id="empreg" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
					<div class="card-header">
						<div class="w-100 text-center">
							<h3 class="p-0 m-0">Sign-Up</h3>
							<label class="p-0 m-0">Exclusive only @ Zamboanga Sibugay</label>
						</div>
					</div>
					<div class="card-body">
						<div class="w-100 d-flex justify-content-end">
							<p><b class="text-danger">( * )</b> Required Fields</p>
						</div>

						<div class="w-100 d-flex flex-wrap justify-content-center">
							<?php include_once "add-record.php"; ?>
						</div>

						<div class="row mb-2">
							<a href="//facebook.com/profile" target="_blank">Click here to get your facebook ID</a>
							<p>Sample Facebook ID: https://www.facebook.com/<b class="text-danger">profile.name</b></p>
							<div class="col-lg-6">
								<input id="fbid" type="text" value="<?php echo htmlspecialchars(trim($reg_fbid)); ?>" onfocus="this.select();" class="form-control" placeholder="Enter facebook ID" name="fbid" autofocus>
							</div>

							<div class="col-lg-6">
								<input id="nickname" type="text" value="<?php echo htmlspecialchars(trim($reg_nickname)); ?>" onfocus="this.select();" class="form-control" placeholder="Nickname" name="nickname" required>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
						</div>

						<div class="row mb-2">
							<div class="col-lg-4">
								<input id="phone" type="tel" value="<?php echo htmlspecialchars(trim($reg_phone)); ?>" onfocus="this.select();" pattern="[789][0-9]{9}" class="form-control mb-2" placeholder="Enter Mobile# (9154826025)" name="phone">
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Invalid Phone Number</div>
							</div>
							<div class="col-lg-4">
								<input id="phone2" type="tel" value="<?php echo htmlspecialchars(trim($reg_phone2)); ?>" onfocus="this.select();" pattern="[789][0-9]{9}" class="form-control mb-2" placeholder="Enter Secondary Mobile#" name="phone2">
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Invalid Phone Number</div>
							</div>
							<div class="col-lg-4">
								<input id="email" type="email" value="<?php echo htmlspecialchars(trim($reg_email)); ?>" onfocus="this.select();" class="form-control" placeholder="Enter email" name="email">
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Invalid E-Mail</div>
							</div>
						</div>

						<div class="row mb-2">
							<div class="col-lg-6">
								<input id="first-name" type="text" value="<?php echo htmlspecialchars(trim($reg_fname)); ?>" onfocus="this.select();" class="form-control mb-2" placeholder="* First Name" name="first-name" required>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
							<div class="col-lg-6">
								<input id="middle-name" type="text" value="<?php echo htmlspecialchars(trim($reg_mname)); ?>" onfocus="this.select();" class="form-control" placeholder="Middle Name" name="middle-name">
							</div>
						</div>

						<div class="row mb-2">
							<div class="col-lg-6">
								<input id="last-name" type="text" value="<?php echo htmlspecialchars(trim($reg_lname)); ?>" onfocus="this.select();" class="form-control mb-2" placeholder="* Last Name" name="last-name" required>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
							<div class="col-lg-2">
								<input id="suffix" type="text" value="<?php echo htmlspecialchars(trim($reg_suffix)); ?>" onfocus="this.select();" class="form-control mb-2" placeholder="Suffix" name="suffix">
							</div>
							<div class="col-lg-4">
								<input id="name-title" type="text" value="<?php echo htmlspecialchars(trim($reg_ntitle)); ?>" onfocus="this.select();" class="form-control" placeholder="Title (Atty, Dr, Engr, etc...)" name="name-title">
							</div>
						</div>

						<div class="row mb-2 mx-2 rounded border bg-light">
							<label>* Birthday</label>
							<div class="col-lg-4 mb-2">
								<input id="year" type="number" value="<?php echo trim($reg_birthyear); ?>" onfocus="this.select();" min="1900" max="<?php echo trim(date('Y') - 18); ?>" class="form-control" placeholder="* Year" name="birth-year" required>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Invalid Year</div>
							</div>
							<div class="col-lg-5">
								<select id="month" class="form-select form-control mb-2" placeholder="* Month" name="birth-month" required>
									<option disabled value> -- select an option -- </option>
									<option value="01" data-value="January" <?php if ( trim($reg_birthmonth) == '01' ) { echo 'selected'; } ?>>January</option>
									<option value="02" data-value="February" <?php if ( empty(trim($reg_birthmonth)) ) { echo 'selected'; } elseif ( trim($reg_birthmonth) == '02' ) { echo 'selected'; } ?>>February</option>
									<option value="03" data-value="March" <?php if ( trim($reg_birthmonth) == '03' ) { echo 'selected'; } ?>>March</option>
									<option value="04" data-value="April" <?php if ( trim($reg_birthmonth) == '04' ) { echo 'selected'; } ?>>April</option>
									<option value="05" data-value="May" <?php if ( trim($reg_birthmonth) == '05' ) { echo 'selected'; } ?>>May</option>
									<option value="06" data-value="June" <?php if ( trim($reg_birthmonth) == '06' ) { echo 'selected'; } ?>>June</option>
									<option value="07" data-value="July" <?php if ( trim($reg_birthmonth) == '07' ) { echo 'selected'; } ?>>July</option>
									<option value="08" data-value="August" <?php if ( trim($reg_birthmonth) == '08' ) { echo 'selected'; } ?>>August</option>
									<option value="09" data-value="September" <?php if ( trim($reg_birthmonth) == '09' ) { echo 'selected'; } ?>>September</option>
									<option value="10" data-value="October" <?php if ( trim($reg_birthmonth) == '10' ) { echo 'selected'; } ?>>October</option>
									<option value="11" data-value="November" <?php if ( trim($reg_birthmonth) == '11' ) { echo 'selected'; } ?>>November</option>
									<option value="12" data-value="December" <?php if ( trim($reg_birthmonth) == '12' ) { echo 'selected'; } ?>>December</option>
								</select>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
							<div class="col-lg-3">
								<input id="days" type="number" value="<?php echo trim($reg_birthday); ?>" onfocus="this.select();" min="1" max="31" class="form-control" placeholder="* Day" name="birth-day" required>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Invalid Day</div>
								<div>Days in month: <span id="output">31</span></div>
							</div>
						</div>

						<div class="row mb-2 mx-2">
							<div class="col-md-2 rounded border bg-light">
								<label class="form-label">* Gender</label>
								<div class="form-check m-3">
									<input class="form-check-input cursor-hand" type="radio" name="gender" id="female" value="Female" required <?php if ( trim($reg_gender) == 'Female' ) { echo 'checked'; } elseif ( empty(trim($reg_gender)) ) { echo 'checked'; } ?>>
									<label class="form-check-label cursor-hand" for="female">Female</label>
								</div>
								<div class="form-check m-3">
									<input class="form-check-input cursor-hand" type="radio" name="gender" id="male" value="Male" required <?php if ( trim($reg_gender) == 'Male' ) { echo 'checked'; } ?>>
									<label class="form-check-label cursor-hand" for="male">Male</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>
							</div>

							<div class="col-md-10 rounded border bg-light">
								<label class="form-label">* User Account</label>
								<div class="form-input m-1">
									<input id="nameuser" type="text" onfocus="this.select();" value="<?php echo htmlspecialchars(trim($reg_username)); ?>" class="form-control mb-2" placeholder="Enter Username" name="nameuser" autocomplete="off" required>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid Username</div>
								</div>

								<div class="form-outline m-1">
									<div class="input-group" id="show_hide_password">
										<input type="password" onfocus="this.select();" class="form-control form-control-md password" id="password" placeholder="Enter your desired Password" value="<?php echo htmlspecialchars(trim($reg_password)); ?>" name="password" autocomplete="new-password" required>
										<div class="input-group-prepend cursor-hand">
											<span class="input-group-text h-100 rounded-0 rounded-end">
												<i class="fa fa-eye-slash" aria-hidden="true" onclick="PwHideShow()"></i>
											</span>
										</div>
										<div class="valid-feedback">Valid.</div>
										<div class="invalid-feedback">Invalid Password</div>
									</div>
								</div>

								<div class="form-outline m-1 mb-3">
									<div class="input-group" id="show_hide_password2">
										<input type="password" onfocus="this.select();" class="form-control form-control-md password" id="password2" placeholder="Re-type your Password" name="password2" value="<?php echo htmlspecialchars(trim($reg_password2)); ?>" autocomplete="new-password" required>
										<div class="input-group-prepend cursor-hand">
											<span class="input-group-text h-100 rounded-0 rounded-end">
												<i class="fa fa-eye-slash" aria-hidden="true" onclick="PwHideShow2()"></i>
											</span>
										</div>
										<div class="valid-feedback">Valid.</div>
										<div class="invalid-feedback">Invalid Password</div>
									</div>
								</div>

								<div class="form-outline m-1">
									<div id="message">
										<h6>Suggested Password: <?php echo trim($sagestidpw); ?></h6>
										<h5>Password must contain the following:</h5>
										<p id="letter" class="invalid">A <b>lowercase</b> letter</p>
										<p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
										<p id="number" class="invalid">A <b>number</b></p>
										<p id="length" class="invalid">Minimum <b>8 characters</b></p>
									</div>
								</div>
							</div>
						</div>

						<hr>

						<div class="row">
							<div class="col">
								<p class="text-center my-1">Please Look into the Camera when taking the Photo.</p>
								<p class="text-center my-1">Use solid backgroud (White, <span class="text-primary">Blue</span> or <span class="text-success">Green</span>).</p>
							</div>
						</div>

						<div class="row justify-content-center">
							<div id="disp-vid" class="col-md-4 mb-2 text-center mx-auto w-100 h-auto position-relative">
								<video id="video" title="Picture" class="border w-auto h-auto" autoplay></video>
								<div class="vidframez"></div>
							</div>

							<div id="disp-pix" class="col-md-4 mb-2 d-none">
								<canvas id="canvas" class="border w-100"></canvas>
							</div>

							<div class="col-md-4 mb-2 d-none">
							</div>
						</div>

						<div class="row  m-0">
							<div class="col m-0">
								<p class="text-center m-0">Put only the Face inside the Frame.</p>
							</div>
						</div>

						<div class="row mb-2">
							<div class="col mb-2 text-center">
								<button id="start-camera" type="button" class="btn btn-primary">Start Camera</button>
								<button id="click-photo" type="button" class="btn btn-success d-none">Click Photo</button>
								<button id="stop-camera" type="button" class="btn btn-secondary">Stop Camera</button>
								<button id="retake-photo" type="button" class="btn btn-warning d-none">Re-Take Photo</button>
							</div>
						</div>

						<div class="row mb-2 justify-content-center">
							<div class="col-md-4 mb-2"></div>
							<div class="col-md-4 mb-2">
								<textarea id="imgdata" class="w-100 d-none" name="imgdata" required><?php echo htmlspecialchars(trim($reg_imgpic)) ?></textarea>
								<div class="valid-feedback text-center">Valid.</div>
								<div class="invalid-feedback text-center">Please Take a Picture. It is required!</div>
							</div>
							<div class="col-md-4 mb-2"></div>
						</div>

						<div class="row mb-2">
							<div class="col d-flex justify-content-center">
								<div class="card">
									<div class="card-header text-center">Sample Photo</div>
									<div class="card-body text-center"><img src="public/profileID/sample.jpg" class="w-100" style="max-width: 200px;"></div>
									<div class="card-footer">
										<p>This photo will be use for your:</p>
										<ul>
											<li>Employee ID</li>
											<li>Sibugaynon ID</li>
											<li>Personal Data Sheet</li>
											<li>and other Government Official Documents</li>
										</ul>
									</div>
								</div>
							</div>
						</div>

						<?php 
							try {
								$empregistry = isset($_GET['regemp']) ? $_GET['regemp'] : null;

								if ( empty($empregistry) ) {

								} else {
						?>
									<div class="row mb2"></div>
						<?php 
								}
							} catch (Exception $e) {
								
							}
						?>
					</div>

					<div class="card-footer">
						<div class="w-100 d-flex flex-wrap justify-content-end">
							<a id="clearfields" href="" class="btn btn-primary m-2 d-none">Clear</a>
							<button id="btnSubmit" type="submit" class="btn btn-danger m-2" name="btnSubmit">Submit</button>
							<a href="<?php echo trim($domainhome); ?>" class="btn btn-success m-2">Back</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>

	<script>
		var myInput = document.getElementById("password");
		var letter = document.getElementById("letter");
		var capital = document.getElementById("capital");
		var number = document.getElementById("number");
		var length = document.getElementById("length");

		// When the user clicks on the password field, show the message box
		myInput.onfocus = function() {
			document.getElementById("message").style.display = "block";
		}

		// When the user clicks outside of the password field, hide the message box
		myInput.onblur = function() {
			document.getElementById("message").style.display = "none";
		}

		// When the user starts to type something inside the password field
		myInput.onkeyup = function() {
			// Validate lowercase letters
			var lowerCaseLetters = /[a-z]/g;
			if (myInput.value.match(lowerCaseLetters)) {
				letter.classList.remove("invalid");
				letter.classList.add("valid");
			} else {
				letter.classList.remove("valid");
				letter.classList.add("invalid");
			}

			// Validate capital letters
			var upperCaseLetters = /[A-Z]/g;
			if (myInput.value.match(upperCaseLetters)) {  
				capital.classList.remove("invalid");
				capital.classList.add("valid");
			} else {
				capital.classList.remove("valid");
				capital.classList.add("invalid");
			}

			// Validate numbers
			var numbers = /[0-9]/g;
			if (myInput.value.match(numbers)) {  
				number.classList.remove("invalid");
				number.classList.add("valid");
			} else {
				number.classList.remove("valid");
				number.classList.add("invalid");
			}

			// Validate length
			if (myInput.value.length >= 8) {
				length.classList.remove("invalid");
				length.classList.add("valid");
			} else {
				length.classList.remove("valid");
				length.classList.add("invalid");
			}
		}
	</script>

	<?php 

		if ( empty($reg_birthday) ) {
	?>

	<script>
		function daysInMonth (month, year) {
			return new Date(parseInt(year), parseInt(month), 0).getDate();
		};

		const byId = (id) => document.getElementById(id);
		const monthSelect = byId("month");
		const yearSelect = byId("year");
		const daysSelect = byId("days");

		const updateOutput = () => { 
			byId("output").innerText = daysInMonth(monthSelect.value, yearSelect.value);
			byId("days").max = daysInMonth(monthSelect.value, yearSelect.value);
			byId("days").value = daysInMonth(monthSelect.value, yearSelect.value);
		};
		updateOutput();

		[monthSelect, yearSelect].forEach((domNode) => { 
			domNode.addEventListener("change", updateOutput);
		});
	</script>

	<?php
		}

	?>

	<script>
		let videoStream;

		let camera_button = document.querySelector("#start-camera");
		let stopCameraBtn = document.querySelector("#stop-camera");
		let dispvid = document.querySelector("#disp-vid");
		let video = document.querySelector("#video");
		let retakephoto = document.querySelector("#retake-photo");
		let click_button = document.querySelector("#click-photo");
		let disppix = document.querySelector("#disp-pix");
		let canvas = document.querySelector("#canvas");
		let imgdata = document.querySelector("#imgdata");

		let videowidth = video.offsetWidth;
		let videoheight = video.offsetHeight;

		camera_button.addEventListener('click', async function() {
			try {
				// Request video stream and store it in the global variable
				videoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
				video.srcObject = videoStream;

				// Show/hide buttons and video elements
				dispvid.classList.remove('d-none');
				disppix.classList.add('d-none');

				camera_button.classList.add("d-none");
				click_button.classList.remove('d-none');
				stopCameraBtn.classList.remove('d-none');
				retakephoto.classList.add('d-none');
			} catch (err) {
				console.error("Error accessing camera: ", err);
				alert("Error: Could not access the camera. Please check your browser permissions.");
			}
		});

		stopCameraBtn.addEventListener('click', async function() {
			// Check if a stream exists
			if (videoStream) {
				// Get all video tracks from the stream
				const tracks = videoStream.getTracks();

				// Loop through each track and stop it
				tracks.forEach(track => track.stop());

				// Clear the video source
				video.srcObject = null;
				videoStream = null;

				// Update button visibility
				camera_button.classList.remove("d-none");
				click_button.classList.add('d-none');
				stopCameraBtn.classList.add('d-none');
				retakephoto.classList.add('d-none');
				dispvid.classList.add('d-none');
				disppix.classList.add('d-none');
			}
		});

		click_button.addEventListener('click', async function() {
			disppix.classList.remove('d-none');

			// Define standard passport photo dimensions in pixels (35mm x 45mm @ 300 DPI)
			const passportWidth = 413;
			const passportHeight = 531;

			// Set the canvas dimensions to the passport size
			canvas.width = passportWidth;
			canvas.height = passportHeight;

			// Calculate the aspect ratio of the video feed
			const videoAspectRatio = video.videoWidth / video.videoHeight;

			// Calculate the aspect ratio of the passport photo
			const passportAspectRatio = passportWidth / passportHeight;

			let sx, sy, sWidth, sHeight;

			// Determine how to crop the video to fit the passport aspect ratio
			if (videoAspectRatio > passportAspectRatio) {
				// Video is wider than the passport aspect ratio, so we need to crop the sides
				sHeight = video.videoHeight;
				sWidth = video.videoHeight * passportAspectRatio;
				sx = (video.videoWidth - sWidth) / 2; // Center the crop horizontally
				sy = 0;
			} else {
				// Video is taller than the passport aspect ratio, so we need to crop the top and bottom
				sWidth = video.videoWidth;
				sHeight = video.videoWidth / passportAspectRatio;
				sx = 0;
				sy = (video.videoHeight - sHeight) / 2; // Center the crop vertically
			}

			// Draw the cropped video frame onto the canvas
			canvas.getContext('2d').drawImage(video, sx, sy, sWidth, sHeight, 0, 0, passportWidth, passportHeight);

			// canvas.style.width = videowidth+'px';
			// canvas.style.height = video.offsetHeight+'px';
			let image_data_url = canvas.toDataURL('image/jpeg');

			// data url of the image
			// console.log(image_data_url);
			imgdata.value = image_data_url;

			dispvid.classList.add('d-none');
			retakephoto.classList.remove('d-none');
			click_button.classList.add('d-none');
		});

		retakephoto.addEventListener('click', async function() {
			disppix.classList.add('d-none');

			dispvid.classList.remove('d-none');
			camera_button.classList.add("d-none");
			retakephoto.classList.add('d-none');
			click_button.classList.remove('d-none');
		});
	</script>

<?php 

	include_once "app/views/index/feat-img.php";
	include_once "app/views/index/footer-info.php";

	// for Video frame when taking a pictrure ---> https://verify.philsys.gov.ph/

?>
<?php 

	if ( isset($_SESSION["empidcode"]) && isset($_SESSION["biono"]) && isset($_SESSION["empname"]) && isset($_SESSION["employeeactivated"]) ) {
		echo '<script>window.open("home","_self");</script>';
	}

?>
	<section id="sign-up" class="w-100 mh-100 py-5">
		<div class="container mh-100">
			<div class="card m-auto" style="max-width: 1024px;">
				<form id="empreg" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
					<div class="card-header">
						<div class="w-100 text-center">
							<h3 class="p-0 m-0">Sign-Up</h3>
							<label class="p-0 m-0">Exclusive only in PLGU-ZSP</label>
						</div>
					</div>
					<div class="card-body">
						<div class="w-100 d-flex justify-content-end">
							<p><b class="text-danger">( * )</b> Required Fields</p>
						</div>

						<div class="row mb-2">
							<div class="col">
								<a href="//facebook.com/profile" target="_blank">Click here to get your facebook ID</a>
								<p>Sample Facebook ID: https://www.facebook.com/<b class="text-danger">profile.name</b></p>
								<input id="fbid" type="text" class="form-control" placeholder="Enter facebook ID" name="fbid">
							</div>
						</div>

						<div class="row mb-2">
							<div class="col-lg-4">
								<input id="phone" type="tel" pattern="[789][0-9]{9}" class="form-control mb-2" placeholder="Enter Mobile# (9154826025)" name="phone">
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
							<div class="col-lg-4">
								<input id="phone2" type="tel" pattern="[789][0-9]{9}" class="form-control mb-2" placeholder="Enter Secondary Mobile#" name="phone2">
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
							<div class="col-lg-4">
								<input id="email" type="email" class="form-control" placeholder="Enter email" name="email">
							</div>
						</div>

						<div class="row mb-2">
							<div class="col-lg-6">
								<input id="first-name" type="text" class="form-control mb-2" placeholder="* First Name" name="first-name" required>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
							<div class="col-lg-6">
								<input id="middle-name" type="text" class="form-control" placeholder="Middle Name" name="middle-name">
							</div>
						</div>

						<div class="row mb-2">
							<div class="col-lg-6">
								<input id="last-name" type="text" class="form-control mb-2" placeholder="* Last Name" name="last-name" required>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
							<div class="col-lg-2">
								<input id="suffix" type="text" class="form-control mb-2" placeholder="Suffix" name="suffix">
							</div>
							<div class="col-lg-4">
								<input id="name-title" type="text" class="form-control" placeholder="Title (Atty, Dr, Engr, etc...)" name="name-title">
							</div>
						</div>

						<div class="row mb-2 mx-2 rounded border bg-light">
							<label>* Birthday</label>
							<div class="col-lg-4 mb-2">
								<input id="year" type="number" min="1900" max="<?php echo trim(date('Y')); ?>" class="form-control" placeholder="* Year" name="birth-year" value="<?php echo trim(date('Y') - 18); ?>" required>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
							<div class="col-lg-5">
								<select id="month" class="form-select form-control mb-2" placeholder="* Month" name="birth-month" required>
									<option disabled value> -- select an option -- </option>
									<option value="01" data-value="January">January</option>
									<option value="02" data-value="February" selected>February</option>
									<option value="03" data-value="March">March</option>
									<option value="04" data-value="April">April</option>
									<option value="05" data-value="May">May</option>
									<option value="06" data-value="June">June</option>
									<option value="07" data-value="July">July</option>
									<option value="08" data-value="August">August</option>
									<option value="09" data-value="September">September</option>
									<option value="10" data-value="October">October</option>
									<option value="11" data-value="November">November</option>
									<option value="12" data-value="December">December</option>
								</select>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
							<div class="col-lg-3">
								<input id="days" type="number" min="1" max="31" class="form-control" placeholder="* Day" name="birth-day" required>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
								<div>Days in month: <span id="output">31</span></div>
							</div>
						</div>

						<div class="row mb-2 mx-2 gap-2">
							<div class="col-md-3 rounded border bg-light">
								<label class="form-label">* Gender</label>
								<div class="form-check m-3">
									<input class="form-check-input" type="radio" name="gender" id="female" value="Female" checked required>
									<label class="form-check-label" for="female">Female</label>
								</div>
								<div class="form-check m-3">
									<input class="form-check-input" type="radio" name="gender" id="male" value="Male" required>
									<label class="form-check-label" for="male">Male</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>
							</div>

							<div class="col-md-8 rounded border bg-light">
								<label class="form-label">* User Account</label>
								<div class="form-input m-1">
									<input id="nameuser" type="text" value="" class="form-control mb-2" placeholder="Enter Username" name="nameuser" required>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>

								<div class="form-outline m-1">
									<div class="input-group" id="show_hide_password">
										<input type="password" class="form-control form-control-md password" id="password" placeholder="Enter your desired Password" name="password"  required>
										<div class="input-group-prepend cursor-hand">
											<span class="input-group-text h-100 rounded-0 rounded-end">
												<i class="fa fa-eye-slash" aria-hidden="true" onclick="PwHideShow()"></i>
											</span>
										</div>
										<div class="valid-feedback">Valid.</div>
										<div class="invalid-feedback">Please fill out this field.</div>
									</div>
								</div>

								<div class="form-outline m-1">
									<div class="input-group" id="show_hide_password2">
										<input type="password" class="form-control form-control-md password" id="password2" placeholder="Re-type your Password" name="password2"  required>
										<div class="input-group-prepend cursor-hand">
											<span class="input-group-text h-100 rounded-0 rounded-end">
												<i class="fa fa-eye-slash" aria-hidden="true" onclick="PwHideShow2()"></i>
											</span>
										</div>
										<div class="valid-feedback">Valid.</div>
										<div class="invalid-feedback">Please fill out this field.</div>
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

						<div class="row mb-2 justify-content-center">
							<div id="disp-vid" class="col-md-4 mb-2">
								<video id="video" title="Picture" class="border w-100 h-auto" autoplay></video>
							</div>

							<div id="disp-pix" class="col-md-4 mb-2 d-none">
								<canvas id="canvas" class="border w-100"></canvas>
							</div>

							<div class="col-md-4 mb-2 d-none">
								<textarea id="imgdata" class="w-100" name="imgdata"></textarea>
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

						<div class="row">
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
					</div>
					<div class="card-footer">
						<div class="w-100 d-flex flex-wrap justify-content-center">
							<?php // include_once "add-record.php"; ?>
						</div>
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

			canvas.getContext('2d').drawImage(video, 0, 0, videowidth, videoheight);
			canvas.style.width = videowidth+'px';
			canvas.style.height = video.offsetHeight+'px';
			let image_data_url = canvas.toDataURL('image/jpeg');

			// data url of the image
			// console.log(image_data_url);
			imgdata.value = image_data_url;

			retakephoto.classList.remove('d-none');
			click_button.classList.add('d-none');
		});

		retakephoto.addEventListener('click', async function() {
			disppix.classList.add('d-none');

			camera_button.classList.add("d-none");
			retakephoto.classList.add('d-none');
			click_button.classList.remove('d-none');
		});
	</script>

<?php 

	include_once "app/views/index/feat-img.php";
	include_once "app/views/index/footer-info.php";
<?php 

	require_once "lib/session-attendance.php";
	$empidcodeNow = isset($_SESSION["empidcode"]) ? $_SESSION["empidcode"] : null;

	require_once "model/employee/setcurrentemployee.php";

	if ( $shiftstatuscc == 1 ) {
		require_once "model/attendance/forautodtr.php";
	}

	$allowedotjk = isset($allowedotcc) ? $allowedotcc : null;

	require_once "model/employee_dtr_sub/getonedaytimedtr.php";

?>

	<section class="position-relative primary-bg-color-light w-100 h-100 pt-3 pb-5 clearfix">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 mb-2">
					<div class="position-sticky" style="top: 3rem;">
						<div class="card mb-2">
							<div class="card-body">
								<div id="accordion">
									<div class="card">
										<div class="card-header p-0">
											<a class="btn w-100" data-bs-toggle="collapse" href="#collapseOne">MENU</a>
										</div>

										<div id="collapseOne" class="collapse" data-bs-parent="#accordion">
											<div class="card-body p-1">
												<a class="btn third-bg-color text-white btn-sm w-100 mb-1" href="<?php echo trim($domainhome); ?>/employee-info">Employee Profile</a>
												<a class="btn third-bg-color text-white btn-sm w-100 mb-1" href="<?php echo trim($domainhome); ?>/attendance">Work Attendance</a>
												<a class="btn third-bg-color text-white btn-sm w-100 mb-1" href="<?php echo trim($domainhome); ?>/dtr">Daily Time Record</a>

												<a class="btn third-bg-color text-white btn-sm w-100 mb-1" href="<?php echo trim($domainhome); ?>/#">End of the Day (EOD) Report</a>
												<a class="btn third-bg-color text-white btn-sm w-100 mb-1" href="<?php echo trim($domainhome); ?>/#">Accomplishment Report</a>
												<a class="btn third-bg-color text-white btn-sm w-100 mb-1" href="<?php echo trim($domainhome); ?>/#">PDS - Personal Data Info.</a>
												<a class="btn third-bg-color text-white btn-sm w-100 mb-1" href="<?php echo trim($domainhome); ?>/#">Employee ID</a>
												<a class="btn third-bg-color text-white btn-sm w-100 mb-1" href="<?php echo trim($domainhome); ?>/#">Work History</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card mb-2">
							<div class="card-header text-center">
								<label><b>Employee Profile</b></label>
							</div>
							<div class="card-body">
								<div class="w-100 text-center">
									<div class="img-profile-pix bg-obj-cover-top mb-2 border border-4" style="background-image: url('<?php echo trim($domainhome).'/public/employeeID/'.trim($empidcodecc).'.jpeg'; ?>');"></div>
								</div>

								<div class="w-100 position-relative text-center mb-2">
									<script src="<?php echo trim($domainhome); ?>/assets/npm/jsbarcode@3.12.1/dist/barcodes/JsBarcode.all.min.js"></script>

									<svg id="barcode"></svg>

									<script>
										JsBarcode("#barcode", <?php echo '"'.trim($empidcodecc).'"'; ?>, {
											height: 20 // Set the desired height in pixels
										});
									</script>
								</div>

								<div class="w-100 position-relative mb-2">
									<p class="mb-0"><i class='fas fa-user-alt me-2'></i> <?php echo trim($empnamecc); ?></p>
									<p class="mb-0"><i class='fas fa-tasks me-2'></i> <?php if (empty($positioncc)) { echo trim('Position'); } else { echo trim($positioncc); } ?></p>
									<p class="mb-0"><i class='fas fa-building me-2'></i> <?php echo trim($headtitlecc); ?></p>
									<p class="mb-0"><i class='fas fa-phone me-2'></i> <?php echo trim($mphonecc); ?></p>
									<p class="mb-0"><i class='fas fa-envelope-square me-2'></i> <?php echo trim($empemailcc); ?></p>
									<hr>
									<p class="mb-0"><i class='fas fa-pen-square me-2'></i> <?php if (empty($designationcc)) { echo trim('** Designation **'); } else { echo trim($designationcc); } ?></p>
									<p class="mb-0"><i class='far fa-building me-2'></i> <?php if (empty($designationatcc)) { echo trim('** Designated @ **'); } else { echo trim($designationatcc); } ?></p>
									<div class="d-flex justify-content-center">
										<a href="employee-id" class="text-decoration-none">Employee ID</a>
									</div>
									<hr>
									<p class="mb-0"><i class='fas fa-user-friends me-2'></i> Gender: <?php if (empty($_SESSION['gender'])) { echo trim('Male | Female'); } else { echo trim($_SESSION['gender']); } ?></p>
									<p class="mb-0"><i class='fas fa-gift me-2'></i> Birthday: <?php echo trim($_SESSION['birthday']); ?></p>
									<p class="mb-0"><i class='far fa-grin-beam me-2'></i> <?php echo trim($_SESSION['empage']); ?> years of Age</p>
								</div>
							</div>
							<div class="card-footer"><a href="#" class="btn third-bg-color text-white w-100" disabled>Edit Your Information</a></div>
						</div>

						<div class="card mb-2">
							<div class="card-body">
								<?php 
									if ( $shiftstatuscc == null || $shiftstatuscc == 0 || $shiftstatuscc == 3 ) {
										echo "<p class='text-center mb-0 text-primary'>8hrs Regular Shift</p>";
									} elseif ( $shiftstatuscc == 1 ) {
										echo "<p class='text-center mb-0 text-info'>Exempted on Attendace Log</p>";
									} elseif ( $shiftstatuscc == 2 ) {
										echo "<p class='text-center mb-0 text-dark'>Field Worker</p>";
									} elseif ( $shiftstatuscc == 4 ) {
										echo "<p class='text-center mb-0 text-warning'>12hrs Shift AM-in & PM-out</p>";
									} elseif ( $shiftstatuscc == 4 ) {
										echo "<p class='text-center mb-0 text-warning'>12hrs Shift PM-in & AM-out</p>";
									}

									echo "<hr>";

									if ( $timeeditablevaluecc == 0 || $timeeditablevaluecc == null ) {
										echo "<p class='text-center mb-0 text-danger'>Insufficient access to update your timelogs on your DTR.</p>";
									} elseif ( $timeeditablevaluecc == 1 ) {
										echo "<p class='text-center mb-0 text-danger'>You only have <b>1 time</b> to update your timelogs on your DTR.</p>";
									} elseif ( $timeeditablevaluecc > 1 ) {
										echo "<p class='text-center mb-0 text-dark'>You have <b>{$timeeditablevaluecc}x</b> to update your timelogs on your DTR.</p>";
									}
								?>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-6 d-mobile-none mb-2">
				<?php 

					include "post.php";

				?>
				</div>

				<div class="col-lg-3 mb-2">
					<div class="position-sticky" style="top: 3rem;">
						<div class="card mb-2">
							<div class="card-header text-center">
								<label>Attendance</label>
							</div>
							<div class="card-body">
								<div>
									<form id="am-time-atttlog" name="am-time-atttlog" method="post" class="d-done">
										<fieldset class="border border-warning p-2">
											<legend class="float-none w-auto px-2 mb-0 text-center font-size-14"> AM </legend>
											<div class="row">
												<div class="col-sm-6">
													<?php 
														if ( empty($amtimeinNow) && empty($amtimeoutNow) ) { 
													?>
														<button type="submit" id="emp-time-am-in" name="emp-time-am-in" class="btn btn-sm third-bg-color text-white w-100 my-1">Time IN</button>
													<?php 
														} elseif ( empty($amtimeinNow) && $amtimeoutNow ) {

														}
													?>
												</div>

												<div class="col-sm-6">
													<?php 
														if ( empty($amtimeoutNow) ) { 
													?>
														<button type="submit" id="emp-time-am-out" name="emp-time-am-out" class="btn btn-sm third-bg-color text-white w-100 my-1">Time OUT</button>
													<?php 
														}
													?>

													<?php 

														if ( empty($allowedotjk) ) {

														} elseif ( $allowedotjk == 1 ) {
															if ( empty($amtimeinNow) && empty($amtimeoutNow) && empty($pmtimeinNow) && empty($pmtimeoutNow) ) {
															
															} elseif ( isset($amtimeinNow) && empty($amtimeoutNow) && empty($pmtimeinNow) && empty($pmtimeoutNow) ) {

															} elseif ( isset($amtimeinNow) && isset($amtimeoutNow) && empty($pmtimeinNow) && empty($pmtimeoutNow) ) {

																$timeallowed = trim(number_format(date("hi")));

																if ( $timeallowed >= 1205) {
													?>
																	<button type="submit" id="emp-time-pm-in" name="emp-time-pm-in" class="btn btn-sm third-bg-color text-white w-100 my-1">OT - IN (PM)</button>
													<?php 
																}
															}
														}
													?>
												</div>
											</div>

											<div class="row">
												<div class="col"><?php include_once "model/attendance/index.php"; ?></div>
											</div>
										</fieldset>
									</form>

									<form id="pm-time-atttlog" name="pm-time-atttlog" method="post" class="d-done">
										<fieldset class="border border-warning p-2">
											<legend class="float-none w-auto px-2 mb-0 text-center font-size-14"> PM </legend>
											<div class="row">
												<div class="col-sm-6">
													<?php 
														if ( empty($pmtimeinNow) && empty($pmtimeoutNow) ) { 
													?>
														<button type="submit" id="emp-time-pm-in" name="emp-time-pm-in" class="btn btn-sm third-bg-color text-white w-100 my-1">Time IN</button>
													<?php 
														} elseif ( empty($pmtimeinNow) && $pmtimeoutNow ) {

														}
													?>
												</div>

												<div class="col-sm-6">
													<?php 
														if ( empty($pmtimeoutNow) ) { 
													?>
														<button type="submit" id="emp-time-pm-out" name="emp-time-pm-out" class="btn btn-sm third-bg-color text-white w-100 my-1">Time OUT</button>
													<?php 
														}
													?>
												</div>
											</div>

											<div class="row">
												<div class="col"><?php include_once "model/attendance/index.php"; ?></div>
											</div>
										</fieldset>
									</form>
								</div>

								<div>
									<table class="table table-striped table-hover">
										<thead>
											<tr align="center" class="mx-0">
												<th colspan="2 mx-0" class="font-size-14">AM</th>
												<th colspan="2 mx-0" class="font-size-14">PM</th>
											</tr>

											<tr align="center">
												<th class="font-size-14">IN</th>
												<th class="font-size-14">OUT</th>
												<th class="font-size-14">IN</th>
												<th class="font-size-14">OUT</th>
											</tr>
										</thead>

										<tbody>
											<?php 
												if ( $empDTRSub->Search_employeeDTRSub($empidcodeNow,$yrdtrNow,$monthdtrNow,$daynumberdtrNow) ) {
											?>
												<form method="post">
													<tr align="center">
														<td>
															<button type="submit" id="delaminwon" name="delaminwon" class="btn btn-close-time">x</button>
															<?php 
																if ( empty($amtimeinNow) ) { 
																	echo trim('--:--'); 
																} else { 
																	echo '<p onclick="getData(this)" id="timeinamval" class="m-0 p-0" data-bs-toggle="modal" data-bs-target="#mdltimeupdate" data-fldname="amtimein" data-labelz="AM Time In">'.trim($amtimeinNow).'</p>'; 
																}
															?>
														</td>

														<td>
															<button type="submit" id="delamoutwon" name="delamoutwon" class="btn btn-close-time">x</button>
															<?php 
																if ( empty($amtimeoutNow) ) { 
																	echo trim('--:--'); 
																} else { 
																	echo '<p onclick="getData(this)" id="timeoutamval" class="m-0 p-0" data-bs-toggle="modal" data-bs-target="#mdltimeupdate" data-fldname="amtimeout" data-labelz="AM Time Out">'.trim($amtimeoutNow).'</p>'; 
																}
															?>
														</td>

														<td>
															<button type="submit" id="delpminwon" name="delpminwon" class="btn btn-close-time">x</button>
															<?php 
																if ( empty($pmtimeinNow) ) { 
																	echo trim('--:--'); 
																} else { 
																	echo '<p onclick="getData(this)" id="timeinpmval" class="m-0 p-0" data-bs-toggle="modal" data-bs-target="#mdltimeupdate" data-fldname="pmtimein" data-labelz="PM Time In">'.trim($pmtimeinNow).'</p>'; 
																}
															?>
														</td>

														<td>
															<button type="submit" id="delpmoutwon" name="delpmoutwon" class="btn btn-close-time">x</button>
															<?php 
																if ( empty($pmtimeoutNow) ) { 
																	echo trim('--:--'); 
																} else { 
																	echo '<p onclick="getData(this)" id="timeoutpmval" class="m-0 p-0" data-bs-toggle="modal" data-bs-target="#mdltimeupdate" data-fldname="pmtimeout" data-labelz="PM Time Out">'.trim($pmtimeoutNow).'</p>'; 
																}
															?>
														</td>
													</tr>

													<tr align="center">
														<td colspan="4">
															<?php include_once "model/attendance/deletetimelog.php"; ?>
														</td>
													</tr>
												</form>
											<?php 
												} else {
											?>
												<tr align="center">
													<td colspan="4">No time registered</td>
												</tr>
											<?php 
												}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="card mb-2">
							<div class="card-header text-center">
								<label>Upcoming Event's</label>
							</div>
							<div class="card-body">
								<p class="p-0 m-0">Civil Service Month</p>
								<p class="p-0 m-0">Monday, September 7, 2025</p>
							</div>
						</div>

						<div class="card mb-2">
							<div class="card-body">
								<link rel="stylesheet" href="<?php echo trim($domainhome); ?>/assets/css/zabuto_calendar.css">
								
								<div id="my-calendar"></div>

								<script src="<?php echo trim($domainhome); ?>/assets/js/joshuaedwardk.calendar.js"></script>
							</div>
						</div>

						<div class="card mb-2">
							<div class="card-header text-center">
								<label>Upcoming Birthday's</label>
							</div>
							<div class="card-body">
								<div>
									<button type="button" class="btn btn-sm third-bg-color text-white float-end font-size-11">Send Greeting's</button>
									<p class="p-0 m-0"><span class="fw-bold">John C. Doe</span> of OPAd</p>
									<p class="p-0 m-0">December 3, 2025 <br>Wednesday</p>
								</div>
								<hr>

								<div>
									<button type="button" class="btn btn-sm third-bg-color text-white float-end font-size-11">Send Greeting's</button>
									<p class="p-0 m-0"><span class="fw-bold">Jane Smith</span> of OPGov</p>
									<p class="p-0 m-0">December 10, 2025 <br>Wednesday</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-6 d-none display-on-mobile-col-6 mb-2">
				<?php 

					include "post.php"

				?>
				</div>
			</div>
		</div>
	</section>

	<!-- Modal: Edit and Update Time -->
	<div class="modal" id="mdltimeupdate">
		<div class="modal-dialog">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h3 class="modal-title">Edit and Update Time</h3>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<div class="input-group">
						<span id="mdl-labeld-input" class="input-group-text"></span>
						<input id="mdl-valued-input" type="text" class="form-control" placeholder="00:00" required>
					</div>
				</div>

				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="submit" id="updatedtrtimelogz" name="updatedtrtimelogz" class="btn btn-primary">Update</button>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
				</div>

			</div>
		</div>
	</div>

	<script>
		function getData(element) {
			const labelz = element.dataset.labelz;
			const fldname = element.dataset.fldname;
			let valtimed = element.innerHTML;

			document.getElementById("mdl-labeld-input").innerHTML = labelz;
			document.getElementById("mdl-valued-input").setAttribute("name", "fldname");
			document.getElementById("mdl-valued-input").value = valtimed;
		}

		function fnTimeDateEmp() {
			let currentTime = new Date();
			// let currentTime = currentTimeX.toLocaleString('en-US', { timeZone: 'Asia/Manila' });
			let currentTimeMillis = currentTime.getTime(); // milliseconds
			let currentUTCTime = currentTime.toUTCString();

			const xmonthz = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
			const xdayzname = ["Sunday","Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

			let currSeconds = currentTime.getSeconds();
			let currMin = currentTime.getMinutes();
			let currHour = currentTime.getHours(); // 24hour
			// let currAMPM = currentTime.();

			let currDay = xdayzname[currentTime.getDay()];
			let currDayNo = currentTime.getDate();
			let currMonth = currentTime.getMonth(); // Month Number
			let currMonthName = xmonthz[currentTime.getMonth()]; // Month Name
			let curYear = currentTime.getFullYear();

			const formattedTime = new Intl.DateTimeFormat('default', {
				hour: 'numeric',
				minute: 'numeric',
				second: 'numeric',
				hour12: true // Ensures 12-hour format with AM/PM
			}).format(currentTime);

			let dateBeginNow = currDay + " | " + currMonthName + " " + currDayNo + ", " + curYear + " | " + formattedTime;
			const currntDateX = currMonthName + " " + currDayNo + ", " + curYear;

			// document.getElementById("pdatereturn2").innerHTML = currntDateX;
			// document.getElementById("pdaynreturn2").innerHTML = currDay;
			// document.getElementById("ptimereturn2").innerHTML = formattedTime;
		}
		setInterval(fnTimeDateEmp, 1000); // Run updateTime() every second

		let currentTime2 = new Date();
		let currMin2 = currentTime2.getMinutes();
		let currHour2 = currentTime2.getHours(); // 24hour

		combiHrz = currHour2 + "" + currMin2.toString().padStart(2, '0');
		// console.log(combiHrz);
		
		if (combiHrz > 1230) {
			$('#pm-time-atttlog').removeClass( "d-none" );
			$('#pm-time-atttlog').addClass( "d-block" );

			$('#am-time-atttlog').removeClass( "d-block" );
			$('#am-time-atttlog').addClass( "d-none" );
		} else {
			$('#pm-time-atttlog').removeClass( "d-block" );
			$('#pm-time-atttlog').addClass( "d-none" );

			$('#am-time-atttlog').removeClass( "d-none" );
			$('#am-time-atttlog').addClass( "d-block" );
		}
	</script>

<?php 

	include_once "app/views/index/feat-img.php";
	include_once "app/views/index/footer-info.php";
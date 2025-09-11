<?php 

	require_once "lib/session-attendance.php";

	if ($_SESSION["shiftstatus"]==1) {
		require_once "model/attendance/forautodtr.php";
	}

	$allowedotjk = isset($_SESSION["allowedot"]) ? $_SESSION["allowedot"] : null;

	try {
		require_once "lib/env.php";

		$cnn = null;

		$cnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$empidcodeNow = trim($_SESSION["empidcode"]);
		$yrdtrNow = trim(date("Y"));
		$monthdtrNow = trim(date("m"));
		$daynumberdtrNow = trim(number_format(date("d")));

		$qryNowSubDTREmployee = "SELECT * FROM employee_dtr_sub_tbl WHERE 
			emp_idcode=:empidcodenow AND 
			yearno=:yrdtrnow AND 
			monthno=:monthdtrnow AND 
			dayno=:daynumberdtrnow 
			LIMIT 1
		";

		$stmtNowSubDTREmployee = $cnn->prepare($qryNowSubDTREmployee);
		$stmtNowSubDTREmployee->bindValue(':empidcodenow', $empidcodeNow);
		$stmtNowSubDTREmployee->bindValue(':yrdtrnow', $yrdtrNow);
		$stmtNowSubDTREmployee->bindValue(':monthdtrnow', $monthdtrNow);
		$stmtNowSubDTREmployee->bindValue(':daynumberdtrnow', $daynumberdtrNow);

		$stmtNowSubDTREmployee->execute();

		$countNowSubDTREmployee = $stmtNowSubDTREmployee->rowCount();

		if ($countNowSubDTREmployee > 0) {
			foreach ($stmtNowSubDTREmployee as $rowNowSubDTREmployee) {
				$amtimeinNow = $rowNowSubDTREmployee['amtimein'];
				$amtimeoutNow = $rowNowSubDTREmployee['amtimeout'];
				$pmtimeinNow = $rowNowSubDTREmployee['pmtimein'];
				$pmtimeoutNow = $rowNowSubDTREmployee['pmtimeout'];
			}
		}

		if ( isset($_POST['delpmoutwon']) ) {
			
		}

	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error: {$err_msg}</p>";
		die;
	}
?>

	<style>
		/**
		* Zabuto Calendar
		*/

		div.zabuto_calendar {
			margin: 0;
			padding: 0;
		}

		div.zabuto_calendar .table {
			width: 100%;
			margin: 0;
			padding: 0;
		}

		div.zabuto_calendar .table th,
		div.zabuto_calendar .table td {
			padding: 0px 0px;
			text-align: center;
		}

		div.zabuto_calendar .table tr th,
		div.zabuto_calendar .table tr td {
			background-color: #ffffff;
		}

		div.zabuto_calendar .table tr.calendar-month-header th {
			background-color: #fafafa;
		}

		div.zabuto_calendar .table tr.calendar-month-header th span {
			cursor: pointer;
			display: inline-block;
			padding-bottom: 10px;
		}

		div.zabuto_calendar .table tr.calendar-dow-header th {
			background-color: #f0f0f0;
		}

		div.zabuto_calendar .table tr:last-child {
			border-bottom: 1px solid #dddddd;
		}

		div.zabuto_calendar .table tr.calendar-month-header th {
			padding-top: 0px;
			padding-bottom: 0px;
		}

		div.zabuto_calendar .table-bordered tr.calendar-month-header th {
			border-left: 0;
			border-right: 0;
		}

		div.zabuto_calendar .table-bordered tr.calendar-month-header th:first-child {
			border-left: 1px solid #dddddd;
		}

		div.zabuto_calendar div.calendar-month-navigation {
			cursor: pointer;
			margin: 0;
			padding: 0;
			padding-top: 5px;
		}

		div.zabuto_calendar tr.calendar-dow-header th,
		div.zabuto_calendar tr.calendar-dow td {
			width: 14%;
		}

		div.zabuto_calendar .table tr td div.day {
			margin: 0;
			padding: 0;
		}

		/* actions and events */
		div.zabuto_calendar .table tr td.event div.day,
		div.zabuto_calendar ul.legend li.event {
			background-color: #fff0c3;
		}

		div.zabuto_calendar .table tr td.dow-clickable,
		div.zabuto_calendar .table tr td.event-clickable {
			cursor: pointer;
		}

		/* badge */
		div.zabuto_calendar .badge-today,
		div.zabuto_calendar div.legend span.badge-today {
			background-color: #357ebd;
			color: #ffffff;
			text-shadow: none;
		}

		div.zabuto_calendar .badge-event,
		div.zabuto_calendar div.legend span.badge-event {
			background-color: #ff9b08;
			color: #ffffff;
			text-shadow: none;
		}

		div.zabuto_calendar .badge-event {
			font-size: 0.95em;
			padding-left: 8px;
			padding-right: 8px;
			padding-bottom: 4px;
		}

		/* legend */
		div.zabuto_calendar div.legend {
			margin-top: 5px;
			text-align: right;
		}

		div.zabuto_calendar div.legend span {
			color: #999999;
			font-size: 10px;
			font-weight: normal;
		}

		div.zabuto_calendar div.legend span.legend-text:after,
		div.zabuto_calendar div.legend span.legend-block:after,
		div.zabuto_calendar div.legend span.legend-list:after,
		div.zabuto_calendar div.legend span.legend-spacer:after {
			content: ' ';
		}

		div.zabuto_calendar div.legend span.legend-spacer {
			padding-left: 25px;
		}

		div.zabuto_calendar ul.legend > span {
			padding-left: 2px;
		}

		div.zabuto_calendar ul.legend {
			display: inline-block;
			list-style: none outside none;
			margin: 0;
			padding: 0;
		}

		div.zabuto_calendar ul.legend li {
			display: inline-block;
			height: 11px;
			width: 11px;
			margin-left: 5px;
		}

		div.zabuto_calendar ul.legend
		div.zabuto_calendar ul.legend li:first-child {
			margin-left: 7px;
		}

		div.zabuto_calendar ul.legend li:last-child {
			margin-right: 5px;
		}

		div.zabuto_calendar div.legend span.badge {
			font-size: 0.9em;
			border-radius: 5px 5px 5px 5px;
			padding-left: 5px;
			padding-right: 5px;
			padding-top: 2px;
			padding-bottom: 3px;
		}

		/* responsive */
		@media (max-width: 979px) {
			div.zabuto_calendar .table th,
			div.zabuto_calendar .table td {
				padding: 0px 0px;
			}
		}
	</style>

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
									<div class="img-profile-pix bg-obj-cover mb-2 border border-4" style="background-image: url('<?php echo trim($domainhome); ?>/assets/media/avatar2.png');"></div>
								</div>

								<div class="w-100 position-relative text-center mb-2">
									<script src="<?php echo trim($domainhome); ?>/assets/npm/jsbarcode@3.12.1/dist/barcodes/JsBarcode.all.min.js"></script>

									<svg id="barcode"></svg>

									<script>
										JsBarcode("#barcode", <?php echo '"'.trim($_SESSION['empidcode']).'"'; ?>, {
											height: 20 // Set the desired height in pixels
										});
									</script>
								</div>

								<div class="w-100 position-relative mb-2">
									<p class="mb-0"><i class='fas fa-user-alt me-2'></i> <?php echo trim($_SESSION['empname']); ?></p>
									<p class="mb-0"><i class='fas fa-tasks me-2'></i> <?php if (empty($_SESSION['position'])) { echo trim('Position'); } else { echo trim($_SESSION['position']); } ?></p>
									<p class="mb-0"><i class='fas fa-building me-2'></i> <?php echo trim($_SESSION['officetitle']); ?></p>
									<p class="mb-0"><i class='fas fa-phone me-2'></i> <?php echo trim($_SESSION['mphone']); ?></p>
									<p class="mb-0"><i class='fas fa-envelope-square me-2'></i> <?php echo trim($_SESSION['empemail']); ?></p>
									<hr>
									<p class="mb-0"><i class='fas fa-pen-square me-2'></i> <?php if (empty($_SESSION['designation'])) { echo trim('** Designation **'); } else { echo trim($_SESSION['designation']); } ?></p>
									<p class="mb-0"><i class='far fa-building me-2'></i> <?php echo trim($_SESSION['designationat']); ?></p>
									<div class="d-flex justify-content-between">
										<a href="#" class="text-decoration-none">Sling ID</a>
										<a href="#" class="text-decoration-none">Pocket ID</a>
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
								<p class="text-center mb-0">
									<?php 
										if ( $_SESSION["shiftstatus"] == null || $_SESSION["shiftstatus"] == 0 || $_SESSION["shiftstatus"] == 3 ) {
											echo "8hrs Regular Shift";
										} elseif ( $_SESSION["shiftstatus"] == 1 ) {
											echo "Exempted on Attendace Log";
										} elseif ( $_SESSION["shiftstatus"] == 2 ) {
											echo "Field Worker";
										} elseif ( $_SESSION["shiftstatus"] == 4 ) {
											echo "12hrs Shift AM-in & PM-out";
										} elseif ( $_SESSION["shiftstatus"] == 4 ) {
											echo "12hrs Shift PM-in & AM-out";
										}
									?>
								</p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-6 d-mobile-none mb-2">
				<?php 

					include "post.php"

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
														<button type="submit" id="emp-time-am-out" name="emp-time-am-out" class="btn btn-lg third-bg-color text-white w-100 my-1">Time OUT</button>
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
																	<button type="submit" id="emp-time-pm-in" name="emp-time-pm-in" class="btn btn-lg third-bg-color text-white w-100 my-1">OT - IN (PM)</button>
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
												if ($countNowSubDTREmployee > 0) {
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
			let currentTimeX = new Date();
			let currentTime = currentTimeX.toLocaleString('en-US', { timeZone: 'Asia/Manila' });
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

			document.getElementById("pdatereturn2").innerHTML = currntDateX;
			document.getElementById("pdaynreturn2").innerHTML = currDay;
			document.getElementById("ptimereturn2").innerHTML = formattedTime;
		}
		setInterval(fnTimeDateEmp, 1000); // Run updateTime() every second

		let currentTime2 = new Date();
		let currMin2 = currentTime2.getMinutes();
		let currHour2 = currentTime2.getHours(); // 24hour

		combiHrz = currHour2 + "" + currMin2.toString().padStart(2, '0');
		// console.log(currHour2+" | "+currMin2.toString().padStart(2, '0'));
		
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
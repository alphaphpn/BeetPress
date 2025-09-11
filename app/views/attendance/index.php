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

	<section class="position-relative bg-light w-100 vh-86 pt-3 pb-5 clearfix">
		<div class="container">
			<div class="w-100 text-center d-flex justify-content-center">
				<div class="text-center mb-3" style="width: fit-content;">
					<h4 class="text-center mobile-font-size-12">Your Work Attendance</h4>
					<hr class="y-axis-margin-0-nobile">
				</div>
			</div>

			<div class="row">
				<div class="col-md-4"></div>

				<div class="col-md-4">
					<div class="position-relative clearfix">
						<form id="am-time-atttlog" name="am-time-atttlog" method="post" class="d-done">
							<fieldset class="border border-warning p-2">
								<legend class="float-none w-auto px-2 mb-0 text-center"> AM </legend>
								<div class="row">
									<div class="col-sm-6">
										<?php 
											if ( empty($amtimeinNow) && empty($amtimeoutNow) ) { 
										?>
											<button type="submit" id="emp-time-am-in" name="emp-time-am-in" class="btn btn-lg third-bg-color text-white w-100 my-1">Time IN</button>
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
								<legend class="float-none w-auto px-2 mb-0 text-center"> PM </legend>
								<div class="row">
									<div class="col-sm-6">
										<?php 
											if ( empty($pmtimeinNow) && empty($pmtimeoutNow) ) { 
										?>
											<button type="submit" id="emp-time-pm-in" name="emp-time-pm-in" class="btn btn-lg third-bg-color text-white w-100 my-1">Time IN</button>
										<?php 
											} elseif ( empty($pmtimeinNow) && $pmtimeoutNow ) {

											}
										?>
									</div>

									<div class="col-sm-6">
										<?php 
											if ( empty($pmtimeoutNow) ) { 
										?>
											<button type="submit" id="emp-time-pm-out" name="emp-time-pm-out" class="btn btn-lg third-bg-color text-white w-100 my-1">Time OUT</button>
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

						<div class="text-center">
							<p class="font-size-22 m-0"><span id="pdaynreturn2"></span>, <span id="pdatereturn2"></span> <br> <span id="ptimereturn2"></span></p>
						</div>
					</div>
					<hr>

					<div class="position-relative clearfix">
						<h6 class="text-center">Your Time Logs as of Today</h6>
						<hr>
						<table class="table table-striped table-hover">
							<thead>
								<tr align="center" class="mx-0">
									<th colspan="2 mx-0">AM</th>
									<th colspan="2 mx-0">PM</th>
								</tr>

								<tr align="center">
									<th>IN</th>
									<th>OUT</th>
									<th>IN</th>
									<th>OUT</th>
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
														echo '<p onclick="getData(this)" id="timeinamval" class="m-0 p-0" data-bs-toggle="modal" data-bs-target="#mdltimeupdate" data-fldname="amtimein" data-labelz="AM Time In">'.trim('--:--').'</p>';
													} else { 
														 echo trim($amtimeinNow);
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

							<tfoot>
								<tr align="center"><td colspan="4"><a href="//google.com/maps/dir/7.7881218435487245,122.57361312438182/<?php echo trim($_SESSION["gpsinlocation"]); ?>/@7.7881218435487245,122.57361312438182,19z/data=!4m4!4m3!1m0!1m1!4e1" target="_blank" class="text-decoration-none">Your Location</a></td></tr>
							</tfoot>
						</table>
					</div>
				</div>

				<div class="col-md-4"></div>
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

		combiHrz = currHour2 + "" + currMin2;
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
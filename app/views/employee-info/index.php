<?php 

	require_once "lib/session-attendance.php";
	$empidcodeNow = isset($_SESSION["empidcode"]) ? $_SESSION["empidcode"] : null;

	require_once "model/employee/setcurrentemployee.php";
	$datebd = new DateTime(trim($_SESSION['birthday']));
	$yearbd = $datebd->format('Y');
	$theaged = date("Y") - $yearbd;

	if ( $shiftstatuscc == 1 ) {
		require_once "model/attendance/forautodtr.php";
	}

	$allowedotjk = isset($allowedotcc) ? $allowedotcc : null;

	require_once "model/employee_dtr_sub/getonedaytimedtr.php";

?>

	<style>
		@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");

		/* Floating popup for adding ID */
		.add-id-popup {
			position: absolute;
			top: -1px;
			right: -280px;
			width: 270px;
			background: #ffffff;
			border: 1px solid #dee2e6;
			border-radius: 0.375rem;
			box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
			padding: 15px;
			display: none;
			z-index: 1060;
		}

		@media (max-width: 1100px) {
			.add-id-popup {
				right: 0;
				top: 50px;
				border: 2px solid #198754;
			}
		}

		/* Signature Studio */
		.ss-canvas-wrap {
			position: relative;
			background-color: #ffffff;
			background-image:
				linear-gradient(45deg, #f0f0f0 25%, transparent 25%),
				linear-gradient(-45deg, #f0f0f0 25%, transparent 25%),
				linear-gradient(45deg, transparent 75%, #f0f0f0 75%),
				linear-gradient(-45deg, transparent 75%, #f0f0f0 75%);
			background-size: 16px 16px;
			background-position: 0 0, 0 8px, 8px -8px, -8px 0px;
			border-radius: 0.375rem;
			overflow: hidden;
			touch-action: none;
			width: 100%;
			height: clamp(180px, 40vh, 320px);
		}
		.ss-canvas-wrap canvas {
			display: block;
			width: 100%;
			height: 100%;
			touch-action: none;
			-webkit-user-select: none;
			user-select: none;
			background: transparent;
		}
		.ss-placeholder {
			position: absolute; inset: 0;
			display: flex; align-items: center; justify-content: center;
			pointer-events: none; z-index: 1;
		}
		.ss-swatch {
			width: 28px; height: 28px;
			border-radius: 50%;
			border: 2px solid #dee2e6;
			cursor: pointer;
			transition: transform .15s, border-color .15s;
			padding: 0; min-width: 28px;
		}
		.ss-swatch.active { border-color: #0d6efd; transform: scale(1.18); }
		.ss-sig-label {
			font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
		}
		.nav-tabs .nav-link { color: #6c757d; font-size: 0.875rem; }
		.nav-tabs .nav-link.active { font-weight: 600; color: #212529; }
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
									<div class="img-profile-pix bg-obj-cover-top mb-2 border border-4" style="background-image: url('<?php echo trim($pixloc).'/public/employeeID/'.trim($empidcodecc).'.jpeg'; ?>');"></div>
								</div>

								<div class="w-100 position-relative text-center mb-2">
									<script src="<?php echo trim($domainhome); ?>/assets/npm/jsbarcode@3.12.1/dist/barcodes/JsBarcode.all.min.js"></script>
									<svg id="barcode"></svg>
									<script>
										JsBarcode("#barcode", <?php echo '"'.trim($empidcodecc).'"'; ?>, { height: 20 });
									</script>
								</div>

								<div class="w-100 position-relative mb-2">
									<p class="mb-0"><i class='fas fa-user-alt me-2'></i> <?php echo trim($empnamecc); ?></p>
									<p class="mb-0"><i class='fas fa-tasks me-2'></i> <?php if (empty($designationforidcc)) { echo trim('Position'); } else { echo trim($designationforidcc); } ?></p>
									<p class="mb-0"><i class='fas fa-building me-2'></i> <?php echo trim($officenameforidcc); ?></p>
									<p class="mb-0"><i class='fas fa-phone me-2'></i> 0<?php echo trim($mphonecc); ?></p>
									<p class="mb-0 text-nowrap"><i class='fas fa-envelope-square me-2'></i> <span class="text-wrap"><?php echo trim($empemailcc); ?></span></p>
									<hr>
									<p class="mb-0"><i class='fas fa-pen-square me-2'></i> <?php if (empty($designationcc)) { echo trim('** Designation **'); } else { echo trim($designationcc); } ?></p>
									<p class="mb-0"><i class='far fa-building me-2'></i> <?php if (empty($designationatcc)) { echo trim('** Designated @ **'); } else { echo trim($designationatcc); } ?></p>
									<div class="d-flex justify-content-center">
										<a href="employee-id" class="text-decoration-none" target="_blank">Employee ID</a>
									</div>
									<hr>
									<p class="mb-0"><i class='fas fa-user-friends me-2'></i> Gender: <?php if (empty($_SESSION['gender'])) { echo trim('Male | Female'); } else { echo trim($_SESSION['gender']); } ?></p>
									<p class="mb-0"><i class='fas fa-gift me-2'></i> Birthday: <?php echo date("F j, Y", strtotime(trim($_SESSION['birthday']))); ?></p>
									<p class="mb-0"><i class='far fa-grin-beam me-2'></i> <?php echo trim($theaged); ?> years of Age</p>
								</div>
							</div>
							<div class="card-footer">
								<button type="button" class="btn third-bg-color text-white w-100 my-1" onclick="openProfileModal('<?php echo trim($empidcodecc); ?>')">Edit Your Information</button>

								<!-- ↓↓ CHANGED: now calls fnDisplaySignature() and targets #myModalSign ↓↓ -->
								<button type="button" class="btn third-bg-color text-white w-100 my-1"
										data-bs-toggle="modal" data-bs-target="#myModalSign"
										onclick="fnDisplaySignature('<?php echo $signimgemplloc."public/employee_sign/".trim($empidcodecc).".png"; ?>', '<?php echo trim($empidcodecc); ?>')">
									Change your Signature
								</button>
							</div>
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

						<!-- ↓↓ CHANGED: added id="dashboardSignatureImg" so JS can refresh it after save ↓↓ -->
						<div class="card mb-2">
							<div class="card-body text-center">
								<img id="dashboardSignatureImg"
									 src="<?php echo $signimgemplloc."public/employee_sign/".trim($empidcodecc).".png?t=".time(); ?>"
									 class="w-100"
									 style="max-height:120px; object-fit:contain;"
									 onerror="this.style.opacity='0.3'">
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-6 d-mobile-none mb-2">
				<?php include "post.php"; ?>
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
														} elseif ( empty($amtimeinNow) && $amtimeoutNow ) {}
													?>
												</div>
												<div class="col-sm-6">
													<?php if ( empty($amtimeoutNow) ) { ?>
														<button type="submit" id="emp-time-am-out" name="emp-time-am-out" class="btn btn-sm third-bg-color text-white w-100 my-1">Time OUT</button>
													<?php } ?>

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
														} elseif ( empty($pmtimeinNow) && $pmtimeoutNow ) {}
													?>
												</div>
												<div class="col-sm-6">
													<?php if ( empty($pmtimeoutNow) ) { ?>
														<button type="submit" id="emp-time-pm-out" name="emp-time-pm-out" class="btn btn-sm third-bg-color text-white w-100 my-1">Time OUT</button>
													<?php } ?>
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
											<?php } ?>
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
				<?php include "post.php" ?>
				</div>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     PROFILE INFO MODAL (unchanged)
	============================================================ -->
	<div class="modal fade text-dark" id="myModalProfileInfo" aria-hidden="true">
		<div class="modal-dialog modal-lg position-relative">
			<div class="modal-content text-start">

				<div id="addIdPopup" class="add-id-popup border-success border-top-0 border-end-0 border-bottom-0 border-4">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h6 class="mb-0 fw-bold">Add ID</h6>
						<button type="button" class="btn-close" aria-label="Close" onclick="hideAddIdPopup();"></button>
					</div>
					<div class="mb-3">
						<label for="idTypeInput" class="form-label mb-1" style="font-size: 0.9rem;">ID Type</label>
						<select class="form-select form-select-sm" id="idTypeInput">
							<option value="" selected disabled>Select ID Type</option>
							<option value="National ID">National ID</option>
							<option value="PhilHealth">PhilHealth</option>
							<option value="Pag-Ibig">Pag-Ibig</option>
							<option value="SSS">SSS</option>
							<option value="GSIS">GSIS</option>
							<option value="UMID">UMID</option>
							<option value="TIN">TIN</option>
							<option value="Voter's ID">Voter's ID</option>
							<option value="Driver's License">Driver's License</option>
							<option value="Passport">Passport</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="idNumberInput" class="form-label mb-1" style="font-size: 0.9rem;">ID Number</label>
						<input type="text" class="form-control form-control-sm" id="idNumberInput" placeholder="Enter ID number">
					</div>
					<button type="button" class="btn btn-success btn-sm w-100" onclick="addId()">Add to Table</button>
				</div>

				<div class="modal-header">
					<h5 class="modal-title" id="profileModalLabel">Update Your Information</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<form id="profileForm" onsubmit="saveProfileDetails(event)">
					<div class="modal-body">
						<input type="hidden" id="currentEmpId" value="">

						<div class="d-flex justify-content-between align-items-center mb-3">
							<h6 class="mb-0">Identification Cards</h6>
							<button type="button" class="btn btn-sm btn-success" onclick="showAddIdPopup()">+ Add New ID</button>
						</div>

						<div class="border rounded p-2 mb-4">
							<table class="table table-bordered table-striped table-sm mb-0" id="idTable">
								<thead class="table-light">
									<tr>
										<th>ID Type</th>
										<th>ID Number</th>
										<th style="width: 50px;" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody id="idTableBody"></tbody>
							</table>
						</div>

						<div class="row g-2 mb-2">
							<div class="col-md-12">
								<label for="iceAddress" class="form-label">Address</label>
								<input type="text" class="form-control" id="iceAddress" placeholder="Address" value="">
							</div>
						</div>

						<hr class="my-4">

						<h6 class="mb-3">In Case of Emergency (ICE)</h6>
						
						<div class="row g-2 mb-2">
							<div class="col-md-4">
								<label for="iceName" class="form-label">Contact Name</label>
								<input type="text" class="form-control" id="iceName" placeholder="Full name" value="">
							</div>
							<div class="col-md-4">
								<label for="iceRelationship" class="form-label">Relationship</label>
								<input type="text" class="form-control" id="iceRelationship" placeholder="e.g. Parent, Spouse" value="">
							</div>
							<div class="col-md-4">
								<label for="iceNumber" class="form-label">Contact Number</label>
								<input type="tel" class="form-control" id="iceNumber" placeholder="Phone number" value="">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save Information</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- ============================================================
	     DTR TIME UPDATE MODAL (unchanged)
	============================================================ -->
	<div class="modal" id="mdltimeupdate">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">Update Information</h3>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<div class="input-group">
						<span id="mdl-labeld-input" class="input-group-text"></span>
						<input id="mdl-valued-input" type="text" class="form-control" placeholder="00:00" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="updatedtrtimelogz" name="updatedtrtimelogz" class="btn btn-primary">Update</button>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- ============================================================
	     SIGNATURE STUDIO MODAL  (replaces old #mdlsignupdate)
	============================================================ -->
	<div class="modal fade text-dark" id="myModalSign" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content text-start">

				<!-- Header -->
				<div class="modal-header border-bottom-0 pb-1">
					<div>
						<h5 class="modal-title mb-0">
							<i class="bi bi-vector-pen me-2 text-primary"></i>Signature Studio
						</h5>
						<p class="mb-0 text-muted" style="font-size:11px">
							Employee ID: <span id="ss_empid_label" class="fw-semibold text-dark"></span>
						</p>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<!-- Body -->
				<div class="modal-body px-3 px-sm-4 pt-2 pb-3">

					<!-- Current saved preview -->
					<div class="rounded border bg-white text-center mb-3 p-2" style="min-height:80px;">
						<p class="text-muted mb-1" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;">
							Current Saved Signature
						</p>
						<img id="ss_preview_img" src="" alt=""
							 style="max-height:90px;max-width:100%;object-fit:contain;display:block;margin:auto;">
						<p id="ss_no_sig" class="text-muted mb-0 small" style="display:none;">No signature on file yet</p>
					</div>

					<!-- Mode tabs -->
					<ul class="nav nav-tabs mb-3" id="ss_tabs">
						<li class="nav-item flex-fill text-center">
							<button class="nav-link active w-100 py-2" type="button" onclick="ssSetMode('draw')">
								<i class="bi bi-pencil me-1"></i><span class="d-none d-sm-inline">Draw</span>
							</button>
						</li>
						<li class="nav-item flex-fill text-center">
							<button class="nav-link w-100 py-2" type="button" onclick="ssSetMode('upload')">
								<i class="bi bi-upload me-1"></i><span class="d-none d-sm-inline">Upload</span>
							</button>
						</li>
						<li class="nav-item flex-fill text-center">
							<button class="nav-link w-100 py-2" type="button" onclick="ssSetMode('camera')">
								<i class="bi bi-camera me-1"></i><span class="d-none d-sm-inline">Camera</span>
							</button>
						</li>
					</ul>

					<!-- Draw controls -->
					<div id="ss_draw_controls" class="d-flex align-items-center flex-wrap gap-2 mb-3">
						<div class="d-flex gap-2 flex-wrap" id="ss_swatches"></div>
						<div class="d-flex align-items-center gap-2 ms-auto">
							<label class="small text-muted mb-0">Size</label>
							<input type="range" id="ss_pen_size" class="form-range" min="1" max="14" step="0.5" value="3.5"
								   style="width:70px"
								   oninput="document.getElementById('ss_size_val').textContent=this.value">
							<span class="small text-muted" id="ss_size_val" style="min-width:22px">3.5</span>
						</div>
					</div>

					<!-- Upload area -->
					<div id="ss_upload_area" class="mb-3 d-none">
						<div class="rounded p-4 text-center bg-white"
							 style="border:2px dashed #ced4da;cursor:pointer;"
							 onclick="document.getElementById('ss_file_input').click()">
							<input type="file" id="ss_file_input" accept="image/*" class="d-none" onchange="ssHandleUpload(event)">
							<i class="bi bi-image fs-2 text-secondary d-block mb-2"></i>
							<p class="mb-0 text-secondary small">Tap to choose an image</p>
							<p class="mb-0 text-muted" style="font-size:11px;">PNG, JPG, GIF, WebP</p>
						</div>
					</div>

					<!-- Camera area -->
					<div id="ss_camera_area" class="mb-3 d-none">
						<video id="ss_camera_video"
							   style="width:100%;max-height:200px;object-fit:cover;background:#000;border-radius:.375rem;"
							   autoplay playsinline muted></video>
						<div class="d-flex gap-2 justify-content-center flex-wrap mt-2">
							<button class="btn btn-dark btn-sm" type="button" id="ss_btn_start" onclick="ssStartCamera()">
								<i class="bi bi-play-fill me-1"></i>Start Camera
							</button>
							<button class="btn btn-warning btn-sm d-none" type="button" id="ss_btn_snap" onclick="ssSnapPhoto()">
								<i class="bi bi-camera-fill me-1"></i>Capture
							</button>
							<button class="btn btn-secondary btn-sm d-none" type="button" id="ss_btn_stop" onclick="ssStopCamera()">
								<i class="bi bi-stop-fill me-1"></i>Stop
							</button>
						</div>
						<p class="text-muted text-center mt-2 mb-0 small" id="ss_cam_status">Camera is off</p>
					</div>

					<!-- Image transform controls (upload / camera mode) -->
					<div id="ss_img_controls" class="card bg-light border mb-2 d-none">
						<div class="card-body py-2 px-3">
							<div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
								<small class="text-muted"><i class="bi bi-arrows-move me-1"></i>Drag canvas to reposition · pinch to zoom</small>
								<button class="btn btn-sm btn-outline-secondary py-0 px-2" type="button"
										onclick="ssResetTransform()" style="font-size:12px;">
									<i class="bi bi-arrow-counterclockwise"></i> Reset
								</button>
							</div>
							<div class="row g-2 small">
								<div class="col-6 col-sm-3">
									<label class="text-muted d-block mb-0">Zoom</label>
									<input type="range" class="form-range" id="ss_zoom" min="0.1" max="3" step="0.05" value="1" oninput="ssSlidersToTransform()">
								</div>
								<div class="col-6 col-sm-3">
									<label class="text-muted d-block mb-0">Left / Right</label>
									<input type="range" class="form-range" id="ss_x" min="-500" max="500" value="0" oninput="ssSlidersToTransform()">
								</div>
								<div class="col-6 col-sm-3">
									<label class="text-muted d-block mb-0">Up / Down</label>
									<input type="range" class="form-range" id="ss_y" min="-500" max="500" value="0" oninput="ssSlidersToTransform()">
								</div>
								<div class="col-6 col-sm-3">
									<label class="text-muted d-block mb-0">Rotate</label>
									<input type="range" class="form-range" id="ss_rot" min="-180" max="180" value="0" oninput="ssSlidersToTransform()">
								</div>
							</div>
						</div>
					</div>

					<!-- Background removal (upload / camera mode) -->
					<div id="ss_bg_card" class="card bg-light border mb-2 d-none">
						<div class="card-body py-2 px-3">
							<div class="d-flex align-items-center flex-wrap gap-2">
								<div class="form-check mb-0">
									<input class="form-check-input" type="checkbox" id="ss_auto_bg" onchange="ssRenderImage()">
									<label class="form-check-label small" for="ss_auto_bg">Auto-remove background</label>
								</div>
								<div class="d-flex align-items-center gap-2 ms-auto">
									<span class="small text-muted">Tolerance</span>
									<input type="range" class="form-range" id="ss_tolerance" min="10" max="120" value="40"
										   style="width:65px;"
										   oninput="document.getElementById('ss_tol_val').textContent=this.value;ssRenderImage()">
									<span class="small text-muted" id="ss_tol_val" style="min-width:22px;">40</span>
								</div>
							</div>
						</div>
					</div>

					<!-- Canvas -->
					<div class="ss-canvas-wrap border" id="ss_canvas_wrap">
						<div class="ss-placeholder" id="ss_placeholder">
							<span class="text-muted fst-italic small">Sign here…</span>
						</div>
						<canvas id="ss_canvas"></canvas>
					</div>
					<p class="text-warning border-top border-warning pt-1 mb-0 ss-sig-label">Authorized Signature</p>

				</div><!-- /modal-body -->

				<!-- Footer -->
				<div class="modal-footer bg-light py-2 gap-2 flex-nowrap">
					<div class="d-flex gap-2">
						<button class="btn btn-outline-secondary btn-sm" type="button" onclick="ssClearCanvas()">
							<i class="bi bi-trash"></i><span class="d-none d-sm-inline ms-1">Clear</span>
						</button>
						<button class="btn btn-outline-warning btn-sm" type="button" id="ss_btn_undo"
								onclick="ssUndoStroke()" disabled>
							<i class="bi bi-arrow-counterclockwise"></i><span class="d-none d-sm-inline ms-1">Undo</span>
						</button>
					</div>
					<div class="d-flex gap-2 ms-auto">
						<button class="btn btn-secondary btn-sm" type="button" data-bs-dismiss="modal">
							<i class="bi bi-x"></i><span class="d-none d-sm-inline ms-1">Cancel</span>
						</button>
						<button class="btn btn-primary btn-sm" type="button" id="ss_btn_save"
								disabled onclick="ssSaveSignature()">
							<i class="bi bi-floppy"></i><span class="ms-1">Save Signature</span>
						</button>
					</div>
				</div>

			</div>
		</div>
	</div>
	<!-- ============================================================
	     END SIGNATURE STUDIO MODAL
	============================================================ -->


	<script>
		// ==========================================
		// PROFILE & ICE LOGIC
		// ==========================================
		function openProfileModal(empId) {
			document.getElementById('currentEmpId').value = empId;
			hideAddIdPopup();
			fn_getdataides('', empId);
			fn_getProfileDetails('', empId);
			var profileModal = new bootstrap.Modal(document.getElementById('myModalProfileInfo'));
			profileModal.show();
		}

		function showAddIdPopup() {
			document.getElementById('addIdPopup').style.display = 'block';
			document.getElementById('idTypeInput').focus();
		}

		function hideAddIdPopup() {
			document.getElementById('addIdPopup').style.display = 'none';
			document.getElementById('idTypeInput').value = '';
			document.getElementById('idNumberInput').value = '';
		}

		function addId() {
			const empId    = document.getElementById('currentEmpId').value;
			const idType   = document.getElementById('idTypeInput').value;
			const idNumber = document.getElementById('idNumberInput').value;

			if (idType && idNumber) {
				const tbody = document.getElementById('idTableBody');
				const existingRows = tbody.getElementsByTagName('tr');
				for (let i = 0; i < existingRows.length; i++) {
					if (existingRows[i].cells && existingRows[i].cells[0].textContent === idType) {
						alert(`The ID type "${idType}" has already been added.`);
						return;
					}
				}

				const row   = tbody.insertRow();
				const cell1 = row.insertCell(0);
				const cell2 = row.insertCell(1);
				const cell3 = row.insertCell(2);
				cell1.textContent = idType;
				cell2.textContent = idNumber;
				cell3.className   = 'text-center align-middle';
				cell3.innerHTML   = '<button type="button" class="btn btn-sm text-danger p-0 border-0" style="font-size:1.2rem;line-height:1;" onclick="deleteRow(this,null)">&times;</button>';

				document.getElementById('idTypeInput').value  = '';
				document.getElementById('idNumberInput').value = '';
				document.getElementById('idTypeInput').focus();

				const fd = new FormData();
				fd.append('profileidx',  '');
				fd.append('employeeidx', empId);
				fd.append('idtypex',     idType);
				fd.append('idnumberx',   idNumber);

				fetch('model/id_type_person_tbl/add-employee-ids.php', { method: 'POST', body: fd })
					.then(r => r.text())
					.then(data => { console.log("Saved ID:", data); fn_getdataides('', empId); })
					.catch(err => { console.error("Error:", err); alert("Failed to save the ID."); });
			} else {
				alert('Please select an ID type and enter an ID number.');
			}
		}

		function deleteRow(buttonElement, detetedid) {
			buttonElement.closest('tr').remove();
			if (detetedid) {
				const fd = new FormData();
				fd.append('detetedid', detetedid);
				fetch('model/id_type_person_tbl/delete-employee-ids.php', { method: 'POST', body: fd })
					.then(r => r.text()).then(d => console.log("Deleted:", d))
					.catch(e => console.error("Error:", e));
			}
		}

		function fn_getdataides(profileid, employeeid) {
			const tbody = document.getElementById('idTableBody');
			tbody.innerHTML = '<tr><td colspan="3" class="text-center">Loading ID details...</td></tr>';
			const fd = new FormData();
			fd.append('profileid',  profileid);
			fd.append('employeeid', employeeid);
			fetch('model/id_type_person_tbl/get-employee-ids.php', { method: 'POST', body: fd })
				.then(r => r.text())
				.then(data => { tbody.innerHTML = data; })
				.catch(err => { console.error(err); tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error loading IDs.</td></tr>'; });
		}

		function fn_getProfileDetails(profileid, employeeid) {
			document.getElementById('iceName').value			= '';
			document.getElementById('iceAddress').value			= '';
			document.getElementById('iceRelationship').value	= '';
			document.getElementById('iceNumber').value			= '';
			const fd = new FormData();
			fd.append('profileid',  profileid);
			fd.append('employeeid', employeeid);
			fetch('model/employee/get-profile-details.php', { method: 'POST', body: fd })
				.then(r => r.json())
				.then(data => {
					if (data) {
						document.getElementById('iceName').value			= data.ice_name			|| '';
						
						// FIXED: Changed data.ice_address to data.address
						document.getElementById('iceAddress').value			= data.address			|| '';
						
						document.getElementById('iceRelationship').value	= data.ice_relationship	|| '';
						document.getElementById('iceNumber').value			= data.ice_number		|| '';
					}
				})
				.catch(err => console.error('Error fetching profile:', err));
		}

		function saveProfileDetails(event) {
			if (event) event.preventDefault();
			const empId				= document.getElementById('currentEmpId').value;
			const iceAddress		= document.getElementById('iceAddress').value;
			const iceName			= document.getElementById('iceName').value;
			const iceRelationship	= document.getElementById('iceRelationship').value;
			const iceNumber			= document.getElementById('iceNumber').value;
			const fd				= new FormData();
			fd.append('employeeid',       empId);
			fd.append('iceAddress',         iceAddress);
			fd.append('ice_name',         iceName);
			fd.append('ice_relationship', iceRelationship);
			fd.append('ice_number',       iceNumber);
			fetch('model/employee/update-profile-details-employee.php', { method: 'POST', body: fd })
				.then(r => r.text())
				.then(() => {
					alert('Information saved successfully!');
					const el = document.getElementById('myModalProfileInfo');
					const m  = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
					if (m) m.hide();
				})
				.catch(err => { console.error(err); alert('Failed to save the details.'); });
		}

		// ==========================================
		// DTR LOGIC
		// ==========================================
		function getData(element) {
			document.getElementById("mdl-labeld-input").innerHTML = element.dataset.labelz;
			document.getElementById("mdl-valued-input").setAttribute("name", "fldname");
			document.getElementById("mdl-valued-input").value = element.innerHTML;
		}

		function fnTimeDateEmp() {
			let currentTime      = new Date();
			const xmonthz        = ["January","February","March","April","May","June","July","August","September","October","November","December"];
			const xdayzname      = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
			const formattedTime  = new Intl.DateTimeFormat('default', { hour:'numeric', minute:'numeric', second:'numeric', hour12:true }).format(currentTime);
		}
		setInterval(fnTimeDateEmp, 1000);

		let currentTime2 = new Date();
		let combiHrz = currentTime2.getHours() + "" + currentTime2.getMinutes().toString().padStart(2, '0');

		if (combiHrz > 1230) {
			$('#pm-time-atttlog').removeClass("d-none").addClass("d-block");
			$('#am-time-atttlog').removeClass("d-block").addClass("d-none");
		} else {
			$('#pm-time-atttlog').removeClass("d-block").addClass("d-none");
			$('#am-time-atttlog').removeClass("d-none").addClass("d-block");
		}

		// ==========================================
		// SIGNATURE STUDIO
		// ==========================================
		(function () {

			/* ── State ── */
			let ssEmpId        = null;
			let ssMode         = 'draw';
			let ssIsEmpty      = true;
			let ssIsDrawing    = false;
			let ssPenColor     = '#000000';
			let ssAllStrokes   = [];
			let ssCurrentStroke = null;
			let ssLastPt       = null;

			let ssRawImg    = null;
			let ssTransform = { scale: 1, x: 0, y: 0, rotate: 0 };

			let ssDragging           = false;
			let ssDragStart          = { x: 0, y: 0 };
			let ssDragTransformStart = {};
			let ssPinchDist0         = 0;

			let ssCssW = 0, ssCssH = 0;
			let ssCameraStream = null;

			const COLORS = ['#000000','#1a1a2e','#198754','#0d6efd','#dc3545','#6f42c1'];

			const canvas = document.getElementById('ss_canvas');
			const ctx    = canvas.getContext('2d', { willReadFrequently: true });

			/* ── Public: called by the "Change your Signature" button ── */
			window.fnDisplaySignature = function (imgSrc, emplNo) {
				ssEmpId = emplNo;
				document.getElementById('ss_empid_label').textContent = emplNo;

				/* Refresh top preview */
				const freshSrc = 'public/employee_sign/' + emplNo + '.png?t=' + Date.now();
				const prevImg  = document.getElementById('ss_preview_img');
				const noSig    = document.getElementById('ss_no_sig');
				prevImg.style.display = 'block';
				noSig.style.display   = 'none';
				prevImg.src = freshSrc;
				prevImg.onerror = function () {
					this.style.display = 'none';
					noSig.style.display = 'block';
				};

				ssClearCanvas();
				ssSetMode('draw');
				window._ssPendingLoad = freshSrc;
			};

			/* ── Load existing sig onto canvas after modal opens ── */
			document.getElementById('myModalSign').addEventListener('shown.bs.modal', function () {
				ssInitCanvas();
				if (window._ssPendingLoad) {
					const path = window._ssPendingLoad;
					window._ssPendingLoad = null;
					const img = new Image();
					img.crossOrigin = 'anonymous';
					img.onload = () => {
						if (img.width > 10) { ssSetImage(img); ssSetMode('draw'); }
					};
					img.onerror = () => { /* blank canvas is fine */ };
					img.src = path;
				}
			});

			document.getElementById('myModalSign').addEventListener('hidden.bs.modal', function () {
				ssStopCamera();
			});

			window.addEventListener('resize', () => {
				if (document.getElementById('myModalSign').classList.contains('show')) {
					ssInitCanvas();
					if (ssMode === 'draw') ssRedraw();
					else if (ssRawImg) ssRenderImage();
				}
			});

			/* ── Canvas init (1 : 1 pixel = CSS pixel) ── */
			function ssInitCanvas() {
				const wrap = document.getElementById('ss_canvas_wrap');
				const w = wrap.clientWidth;
				const h = wrap.clientHeight;
				if (!w || !h) return;

				if (ssCssW && ssCssH && (ssCssW !== w || ssCssH !== h)) {
					const sx = w / ssCssW, sy = h / ssCssH;
					ssAllStrokes.forEach(s => {
						s.pts.forEach(p => { p.x *= sx; p.y *= sy; });
						s.size *= Math.min(sx, sy);
					});
					ssTransform.x *= sx;
					ssTransform.y *= sy;
					ssTransform.scale *= Math.min(sx, sy);
					ssSyncSliders();
				}

				ssCssW = w; ssCssH = h;
				canvas.width  = w;
				canvas.height = h;
				ctx.lineCap = 'round';
				ctx.lineJoin = 'round';
			}

			/* ── Colour swatches ── */
			const swatchWrap = document.getElementById('ss_swatches');
			COLORS.forEach(c => {
				const b = document.createElement('button');
				b.type = 'button';
				b.className = 'ss-swatch' + (c === ssPenColor ? ' active' : '');
				b.style.background = c;
				b.onclick = () => {
					ssPenColor = c;
					swatchWrap.querySelectorAll('.ss-swatch').forEach(s => s.classList.toggle('active', s.style.background === c));
				};
				swatchWrap.appendChild(b);
			});

			/* ── Pointer helpers ── */
			function ssGetPos(e) {
				const r  = canvas.getBoundingClientRect();
				const sx = canvas.width  / r.width;
				const sy = canvas.height / r.height;
				const src = e.touches ? e.touches[0] : e;
				return { x: (src.clientX - r.left) * sx, y: (src.clientY - r.top) * sy };
			}
			function ssPinchDistFn(e) {
				return Math.hypot(
					e.touches[0].clientX - e.touches[1].clientX,
					e.touches[0].clientY - e.touches[1].clientY
				);
			}

			/* ── Events ── */
			canvas.addEventListener('mousedown',   ssPointerDown);
			canvas.addEventListener('mousemove',   ssPointerMove);
			canvas.addEventListener('mouseup',     ssPointerUp);
			canvas.addEventListener('mouseleave',  ssPointerUp);
			canvas.addEventListener('touchstart',  ssPointerDown, { passive: false });
			canvas.addEventListener('touchmove',   ssPointerMove, { passive: false });
			canvas.addEventListener('touchend',    ssPointerUp);
			canvas.addEventListener('touchcancel', ssPointerUp);

			function ssPointerDown(e) {
				if (ssMode === 'draw') { ssStartDraw(e); return; }
				if (!ssRawImg) return;
				e.preventDefault();
				if (e.touches && e.touches.length === 2) {
					ssDragging = true; ssPinchDist0 = ssPinchDistFn(e);
					ssDragTransformStart = { ...ssTransform }; return;
				}
				const p = ssGetPos(e);
				ssDragging = true; ssDragStart = p;
				ssDragTransformStart = { ...ssTransform };
				canvas.style.cursor = 'grabbing';
			}

			function ssPointerMove(e) {
				if (ssMode === 'draw') { ssDraw(e); return; }
				if (!ssRawImg || !ssDragging) return;
				e.preventDefault();
				if (e.touches && e.touches.length === 2) {
					ssTransform.scale = ssDragTransformStart.scale * (ssPinchDistFn(e) / ssPinchDist0);
					ssSyncSliders(); ssRenderImage(); return;
				}
				const p = ssGetPos(e);
				ssTransform.x = ssDragTransformStart.x + (p.x - ssDragStart.x);
				ssTransform.y = ssDragTransformStart.y + (p.y - ssDragStart.y);
				ssSyncSliders(); ssRenderImage();
			}

			function ssPointerUp(e) {
				if (ssMode === 'draw') { ssStopDraw(e); return; }
				ssDragging = false;
				canvas.style.cursor = 'default';
			}

			/* ── Draw ── */
			function ssStartDraw(e) {
				e.preventDefault();
				ssIsDrawing = true;
				const p    = ssGetPos(e);
				const size = parseFloat(document.getElementById('ss_pen_size').value);
				ssCurrentStroke = { color: ssPenColor, size, pts: [p] };
				ssAllStrokes.push(ssCurrentStroke);
				ssLastPt = p;
				ctx.beginPath(); ctx.fillStyle = ssPenColor;
				ctx.arc(p.x, p.y, size / 2, 0, Math.PI * 2); ctx.fill();
				ssMarkUsed();
			}

			function ssDraw(e) {
				if (!ssIsDrawing) return;
				e.preventDefault();
				const p = ssGetPos(e);
				ssCurrentStroke.pts.push(p);
				ctx.beginPath();
				ctx.moveTo(ssLastPt.x, ssLastPt.y); ctx.lineTo(p.x, p.y);
				ctx.strokeStyle = ssCurrentStroke.color;
				ctx.lineWidth   = ssCurrentStroke.size;
				ctx.lineCap = 'round'; ctx.lineJoin = 'round';
				ctx.stroke();
				ssLastPt = p;
			}

			function ssStopDraw(e) {
				if (!ssIsDrawing) return;
				ssIsDrawing = false;
				document.getElementById('ss_btn_undo').disabled = (ssAllStrokes.length === 0);
			}

			window.ssUndoStroke = function () {
				if (!ssAllStrokes.length) return;
				ssAllStrokes.pop();
				ssAllStrokes.length === 0 ? ssClearCanvas() : ssRedraw();
				document.getElementById('ss_btn_undo').disabled = (ssAllStrokes.length === 0);
			};

			/* ── Redraw all strokes ── */
			function ssRedraw() {
				ctx.clearRect(0, 0, canvas.width, canvas.height);
				if (ssRawImg) ssRenderImage(false, false);
				ssAllStrokes.forEach(s => {
					if (!s.pts.length) return;
					ctx.beginPath();
					ctx.strokeStyle = s.color; ctx.lineWidth = s.size;
					ctx.lineCap = 'round'; ctx.lineJoin = 'round';
					if (s.pts.length === 1) {
						ctx.fillStyle = s.color;
						ctx.arc(s.pts[0].x, s.pts[0].y, s.size / 2, 0, Math.PI * 2);
						ctx.fill(); return;
					}
					ctx.moveTo(s.pts[0].x, s.pts[0].y);
					s.pts.slice(1).forEach(p => ctx.lineTo(p.x, p.y));
					ctx.stroke();
				});
			}

			/* ── Image render ── */
			function ssRenderImage(drawStrokes = true, applyBg = true) {
				if (!ssRawImg) return;
				ctx.clearRect(0, 0, canvas.width, canvas.height);
				ctx.save();
				ctx.translate(canvas.width / 2 + ssTransform.x, canvas.height / 2 + ssTransform.y);
				ctx.rotate(ssTransform.rotate * Math.PI / 180);
				ctx.scale(ssTransform.scale, ssTransform.scale);
				ctx.drawImage(ssRawImg, -ssRawImg.width / 2, -ssRawImg.height / 2);
				ctx.restore();
				if (applyBg && document.getElementById('ss_auto_bg').checked) ssApplyBgRemoval();
				if (drawStrokes) ssRedraw();
			}
			window.ssRenderImage = ssRenderImage;

			function ssApplyBgRemoval() {
				const tol = parseInt(document.getElementById('ss_tolerance').value);
				const id  = ctx.getImageData(0, 0, canvas.width, canvas.height);
				const d   = id.data;
				const sample = (i) => [d[i], d[i+1], d[i+2]];
				const c0 = sample(0);
				const c1 = sample((canvas.width - 1) * 4);
				const c2 = sample((canvas.height - 1) * canvas.width * 4);
				const c3 = sample((canvas.height * canvas.width - 1) * 4);
				const bgR = (c0[0]+c1[0]+c2[0]+c3[0]) / 4;
				const bgG = (c0[1]+c1[1]+c2[1]+c3[1]) / 4;
				const bgB = (c0[2]+c1[2]+c2[2]+c3[2]) / 4;
				for (let i = 0; i < d.length; i += 4) {
					if (d[i+3] === 0) continue;
					const dist = Math.sqrt((d[i]-bgR)**2 + (d[i+1]-bgG)**2 + (d[i+2]-bgB)**2);
					if (dist < tol) d[i+3] = Math.round(Math.min(1, dist / tol * 3) * 255);
				}
				ctx.putImageData(id, 0, 0);
			}

			function ssSetImage(img) {
				ssRawImg = img;
				ssResetTransform();
				const fit = Math.min(canvas.width / img.width, canvas.height / img.height) * 0.85;
				ssTransform.scale = fit;
				ssSyncSliders();
				document.getElementById('ss_img_controls').classList.remove('d-none');
				ssRenderImage();
				ssMarkUsed();
			}

			window.ssResetTransform = function () {
				ssTransform = { scale: 1, x: 0, y: 0, rotate: 0 };
				ssSyncSliders();
				if (ssRawImg) ssRenderImage();
			};

			function ssSyncSliders() {
				document.getElementById('ss_zoom').value = ssTransform.scale;
				document.getElementById('ss_x').value    = ssTransform.x;
				document.getElementById('ss_y').value    = ssTransform.y;
				document.getElementById('ss_rot').value  = ssTransform.rotate % 360;
			}

			window.ssSlidersToTransform = function () {
				ssTransform.scale  = parseFloat(document.getElementById('ss_zoom').value);
				ssTransform.x      = parseInt(document.getElementById('ss_x').value);
				ssTransform.y      = parseInt(document.getElementById('ss_y').value);
				ssTransform.rotate = parseInt(document.getElementById('ss_rot').value);
				ssRenderImage();
			};

			/* ── Mode switching ── */
			window.ssSetMode = function (m) {
				if (ssMode === 'camera' && m !== 'camera') ssStopCamera();
				ssMode = m;
				document.querySelectorAll('#ss_tabs .nav-link').forEach((btn, i) => {
					btn.classList.toggle('active', ['draw','upload','camera'][i] === m);
				});
				document.getElementById('ss_draw_controls').classList.toggle('d-none', m !== 'draw');
				document.getElementById('ss_upload_area').classList.toggle('d-none',   m !== 'upload');
				document.getElementById('ss_camera_area').classList.toggle('d-none',   m !== 'camera');
				document.getElementById('ss_bg_card').classList.toggle('d-none',       m === 'draw');
				document.getElementById('ss_img_controls').classList.toggle('d-none',  m === 'draw' || !ssRawImg);
				canvas.style.cursor = m === 'draw' ? 'crosshair' : 'default';
			};

			/* ── Clear ── */
			window.ssClearCanvas = function () {
				ctx.clearRect(0, 0, canvas.width, canvas.height);
				ssAllStrokes = []; ssRawImg = null; ssIsEmpty = true;
				ssTransform  = { scale: 1, x: 0, y: 0, rotate: 0 };
				document.getElementById('ss_placeholder').style.display    = 'flex';
				document.getElementById('ss_btn_save').disabled            = true;
				document.getElementById('ss_btn_undo').disabled            = true;
				document.getElementById('ss_img_controls').classList.add('d-none');
				document.getElementById('ss_auto_bg').checked              = false;
			};

			function ssMarkUsed() {
				ssIsEmpty = false;
				document.getElementById('ss_placeholder').style.display = 'none';
				document.getElementById('ss_btn_save').disabled         = false;
				document.getElementById('ss_btn_undo').disabled         = (ssAllStrokes.length === 0);
			}

			/* ── Upload ── */
			window.ssHandleUpload = function (e) {
				const file = e.target.files[0];
				if (!file) return;
				const reader = new FileReader();
				reader.onload = ev => {
					const img = new Image();
					img.onload = () => ssSetImage(img);
					img.src = ev.target.result;
				};
				reader.readAsDataURL(file);
				e.target.value = '';
			};

			/* ── Camera ── */
			window.ssStartCamera = async function () {
				try {
					document.getElementById('ss_btn_start').innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Starting…';
					ssCameraStream = await navigator.mediaDevices.getUserMedia({
						video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 } }, audio: false
					});
					if (ssMode !== 'camera') { ssCameraStream.getTracks().forEach(t => t.stop()); ssCameraStream = null; return; }
					document.getElementById('ss_camera_video').srcObject = ssCameraStream;
					document.getElementById('ss_btn_start').classList.add('d-none');
					document.getElementById('ss_btn_snap').classList.remove('d-none');
					document.getElementById('ss_btn_stop').classList.remove('d-none');
					document.getElementById('ss_cam_status').textContent = 'Camera active — tap Capture when ready';
				} catch (err) {
					document.getElementById('ss_cam_status').textContent = '⚠ ' + err.message;
					document.getElementById('ss_btn_start').innerHTML = '<i class="bi bi-play-fill me-1"></i>Start Camera';
				}
			};

			window.ssSnapPhoto = function () {
				const video = document.getElementById('ss_camera_video');
				const off   = document.createElement('canvas');
				off.width = video.videoWidth; off.height = video.videoHeight;
				off.getContext('2d').drawImage(video, 0, 0);
				const img = new Image();
				img.onload = () => { ssSetImage(img); ssStopCamera(); document.getElementById('ss_cam_status').textContent = '✓ Photo captured'; };
				img.src = off.toDataURL('image/png');
			};

			window.ssStopCamera = function () {
				if (ssCameraStream) { ssCameraStream.getTracks().forEach(t => t.stop()); ssCameraStream = null; }
				const v = document.getElementById('ss_camera_video');
				if (v) v.srcObject = null;
				['ss_btn_snap','ss_btn_stop'].forEach(id => document.getElementById(id).classList.add('d-none'));
				document.getElementById('ss_btn_start').classList.remove('d-none');
				document.getElementById('ss_btn_start').innerHTML = '<i class="bi bi-play-fill me-1"></i>Start Camera';
				const st = document.getElementById('ss_cam_status');
				if (st && st.textContent !== '✓ Photo captured') st.textContent = 'Camera is off';
			};

			/* ── Flatten to PNG for export ── */
			function ssGetExportDataURL() {
				const flat = document.createElement('canvas');
				flat.width  = canvas.width;
				flat.height = canvas.height;
				const fCtx  = flat.getContext('2d');

				if (ssRawImg) {
					fCtx.save();
					fCtx.translate(flat.width / 2 + ssTransform.x, flat.height / 2 + ssTransform.y);
					fCtx.rotate(ssTransform.rotate * Math.PI / 180);
					fCtx.scale(ssTransform.scale, ssTransform.scale);
					fCtx.drawImage(ssRawImg, -ssRawImg.width / 2, -ssRawImg.height / 2);
					fCtx.restore();
				}

				ssAllStrokes.forEach(s => {
					if (!s.pts.length) return;
					fCtx.beginPath();
					fCtx.strokeStyle = s.color; fCtx.lineWidth = s.size;
					fCtx.lineCap = 'round'; fCtx.lineJoin = 'round';
					if (s.pts.length === 1) {
						fCtx.fillStyle = s.color;
						fCtx.arc(s.pts[0].x, s.pts[0].y, s.size / 2, 0, Math.PI * 2);
						fCtx.fill(); return;
					}
					fCtx.moveTo(s.pts[0].x, s.pts[0].y);
					s.pts.slice(1).forEach(p => fCtx.lineTo(p.x, p.y));
					fCtx.stroke();
				});

				return flat.toDataURL('image/png');
			}

			/* ── Save ── */
			window.ssSaveSignature = function () {
				if (ssIsEmpty && !ssRawImg && ssAllStrokes.length === 0) {
					alert('Please provide a signature first.'); return;
				}

				const btn  = document.getElementById('ss_btn_save');
				const orig = btn.innerHTML;
				btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';
				btn.disabled  = true;

				const fd = new FormData();
				fd.append('employee_id', ssEmpId);
				fd.append('signature',   ssGetExportDataURL());

				fetch('lib/employee-sign-saved.php', { method: 'POST', body: fd })
					.then(r => r.json())
					.then(res => {
						if (res.status === 'success' || res.success) {
							const freshPath = 'public/employee_sign/' + ssEmpId + '.png?t=' + Date.now();

							/* 1. Top preview inside the modal */
							const prev  = document.getElementById('ss_preview_img');
							const noSig = document.getElementById('ss_no_sig');
							prev.src = freshPath;
							prev.style.display = 'block';
							noSig.style.display = 'none';
							prev.onerror = () => {};

							/* 2. Dashboard card */
							const dash = document.getElementById('dashboardSignatureImg');
							if (dash) dash.src = freshPath;

							/* 3. Any other img referencing this employee's signature */
							document.querySelectorAll('img[src*="employee_sign/<?php echo trim($empidcodecc); ?>"]').forEach(img => {
								img.src = freshPath;
							});

							alert('Signature saved successfully!');
						} else {
							alert('Error saving: ' + (res.message || res.error || 'Unknown error'));
						}
					})
					.catch(err => { console.error(err); alert('Failed to connect to server.'); })
					.finally(() => { btn.innerHTML = orig; btn.disabled = false; });
			};

		})(); /* end IIFE */
	</script>

<?php 

	include_once "app/views/index/feat-img.php";
	include_once "app/views/index/footer-info.php";
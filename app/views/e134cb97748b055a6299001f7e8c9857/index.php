<?php 

	// ?isemployee=fe86d6d4e69b5821c74e4816f981b4f8  - for DGTHMC
	// ?isemployee=535ad200e13bd0556a843d992efa9e00  - for Capitol

	// xxx

	$array_key = ["fe86d6d4e69b5821c74e4816f981b4f8", "535ad200e13bd0556a843d992efa9e00"];
	$emplyornot = isset($_GET['isemployee']) ? $_GET['isemployee'] : null;
	$settedsec = array_search($emplyornot, $array_key);

	$reg_imgpic = null;
	$reg_zipcode = null;
	$reg_nickname = null;
	$reg_ntitle = null;
	$reg_fname = null;
	$reg_mname = null;
	$reg_lname = null;
	$reg_suffix = null;
	$reg_profession = null;
	$reg_gender = null;
	$reg_birthyear = trim(date('Y') - 18);
	$reg_birthmonth = null;
	$reg_birthday = null;
	$reg_plbirth = null;
	$reg_phone = null;
	$reg_phone2 = null;
	$reg_email = null;
	$reg_fbid = null;
	$reg_username = null;
	$sagestidpw = suggestedPassWord();
	$reg_password = null;
	$reg_password2 = null;
	$reg_typeemployee = null;
	$reg_office = null;
	$reg_biolocation = null;
	$reg_bionumber = null;
	$reg_employeeid = null;
	$reg_pincode = null;
	$reg_pincode2 = $reg_pincode;

	$reg_town = null;
	$reg_typeemployee = null;
	$reg_typeemployeeabrv = null;
	$reg_typeemployeelabel = null;
	$reg_designation = null;

	$reg_bioloclabel = null;

	$reg_officeid = null;
	$reg_officecode = null;
	$reg_officename = null;
	$reg_officetitle = null;
	$reg_officeabrv = null;
	$reg_oldofficeabrv = null;
	$reg_headofficer = null;
	$reg_headtitle = null;
	$reg_authhead = null;
	$reg_authtitle = null;
	$reg_authdescription = null;
	$reg_officegpslocation = null;

	$reg_officenmbering = null;

?>

	<style>
		#disp-vid { max-width: 290px; height: 356px!important; overflow: hidden; }
		#disp-vid video#video { 
			max-height: 350px; 
			object-fit: cover; 
			margin-left: calc(100% / -2.5);
			transform: rotateY(180deg);
			-webkit-transform: rotateY(180deg);
			/* Safari and Chrome */
			-moz-transform: rotateY(180deg);
			/* Firefox */
		}

		#disp-pix #canvas {
			transform: rotateY(180deg);
			-webkit-transform: rotateY(180deg);
			/* Safari and Chrome */
			-moz-transform: rotateY(180deg);
			/* Firefox */
		}

		.vidframez {
			position: absolute;
			border-width: 12px 56px 88px 56px;
			border-style: solid;
			border-color: rgba(0, 0, 0, 0.2);
			box-sizing: border-box;
			inset: 0px;
			height: 350px;
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

	<section id="signup" class="w-100 mh-100 pt-3" style="padding-bottom: 62px;">
		<div class="container mh-100">
			<div class="card m-auto" style="max-width: 1024px;">
				<div class="card-header"><h5 class="text-center text-primary">Sign-Up Account</h5></div>

				<div class="card-body">
					
					<!-- Nav tabs -->
					<ul class="nav nav-tabs d-none">
						<li class="nav-item">
							<a id="tab-imagepicture" class="nav-link active" data-bs-toggle="tab" href="#imagepicture">ID Picture</a>
						</li>

						<li class="nav-item">
							<a id="tab-pinfo" class="nav-link" data-bs-toggle="tab" href="#pinfo">P. Info.</a>
						</li>

						<li class="nav-item">
							<a id="tab-contact" class="nav-link" data-bs-toggle="tab" href="#contact">Contact</a>
						</li>

						<li class="nav-item">
							<a id="tab-user" class="nav-link" data-bs-toggle="tab" href="#user">User</a>
						</li>

						<li class="nav-item">
							<a id="tab-employeeq" class="nav-link" data-bs-toggle="tab" href="#employeeq">Q. Employee</a>
						</li>

						<li class="nav-item">
							<a id="tab-employee" class="nav-link" data-bs-toggle="tab" href="#employee">Employee</a>
						</li>

						<li class="nav-item">
							<a id="tab-submit" class="nav-link" data-bs-toggle="tab" href="#usubmitx">Submit</a>
						</li>
					</ul>

					<!-- Tab panes -->
					<form id="empreg" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
						<div class="w-100 d-flex flex-wrap justify-content-center">
							<?php 

								include_once "addrecord.php"; 

								if ( empty($reg_bionumber) || $reg_bionumber == null ) {

								} else {
									echo '<div class="alert alert-success alert-dismissible fade show m-1">';
										echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
										echo '<p>You have successfully registered as an Employee. Do you want to print the Employee Information?</p>';
										echo '<a class="btn btn-light m-2" href="">Yes</a>';
										echo '<button class="btn btn-secondary m-2">No</button>';
									echo '</div>';
								}
							?>
						</div>
						<hr>

						<div class="tab-content">
							<div class="tab-pane container active" id="imagepicture">
								<h6 class="text-center text-primary">Profile Picture</h6>
								<div class="my-1 w-100 text-center">
									<button id="bttn-sampix" class="text-center m-auto btn btn-info btn-sm" type="button">Sample Picture</button>
								</div>

								<div id="samp-pix" class="row my-2 d-none">
									<div class="col-lg-6">
										<img src="<?php echo trim($domainhome); ?>/assets/media/passport-size-01.jpg" class="w-100">
									</div>
									<div class="col-lg-6">
										<img src="<?php echo trim($domainhome); ?>/assets/media/passport-size-02.jpg" class="w-100">
									</div>
								</div>

								<div class="my-1">
									<p class="text-center my-1">Please Look into the Camera when taking the Photo.</p>
									<p class="text-center my-1">Use solid backgroud (White, <span class="text-primary">Blue</span> or <span class="text-success">Green</span>).</p>
								</div>

								<div class="row justify-content-center">
									<div id="disp-vid" class="col-md-4 mb-2 text-center mx-auto w-100 h-auto position-relative">
										<video id="video" title="Picture" class="w-auto h-auto" autoplay></video>

										<div id="zoom-container"class="d-none">
											<label for="zoom-slider">Zoom:</label>
											<input type="range" id="zoom-slider" min="1" max="5" value="1" step="0.1">
											<span id="zoom-value">1.0x</span>
										</div>

										<div class="vidframez"></div>
									</div>

									<div id="disp-pix" class="col-md-4 mb-2 d-none">
										<canvas id="canvas" class="border w-100"></canvas>
									</div>

									<div class="col-md-4 mb-2 d-none">
									</div>
								</div>

								<div class="row m-0">
									<div class="col m-0">
										<p class="text-center m-0">Put only the Face inside the Frame.</p>
									</div>
								</div>

								<div class="my-2 text-center">
									<div id="select-container" class="d-none"> <label for="camera-select">Select Camera:</label>
										<select id="camera-select"></select>
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
										<textarea id="imgdata" class="w-100 d-none" name="imgdata" required><?php echo trim(htmlspecialchars($reg_imgpic)); ?></textarea>
										<div class="valid-feedback text-center">Valid.</div>
										<div class="invalid-feedback text-center">Please Take a Picture. It is required!</div>

										<div class="form-floating">
											<select id="zipcode" class="form-select form-control" placeholder="* Registered as Voter?" name="zipcode" required>
												<option value disabled <?php if ( empty(trim($reg_zipcode)) ) { echo "selected"; } ?>> -- select an option -- </option>
												<option value="7001" data-value="Ipil" <?php if ( trim($reg_zipcode) == 7001 ) { echo "selected"; } ?>>Ipil</option>
												<option value="7002" data-value="Roseller T. Lim" <?php if ( $reg_zipcode == 7002 ) { echo "selected"; } ?>>Roseller T. Lim</option>
												<option value="7003" data-value="Titay" <?php if ( $reg_zipcode == 7003 ) { echo "selected"; } ?>>Titay</option>
												<option value="7004" data-value="Naga" <?php if ( $reg_zipcode == 7004 ) { echo "selected"; } ?>>Naga</option>
												<option value="7005" data-value="Kabasalan" <?php if ( $reg_zipcode == 7005 ) { echo "selected"; } ?>>Kabasalan</option>
												<option value="7006" data-value="Siay" <?php if ( $reg_zipcode == 7006 ) { echo "selected"; } ?>>Siay</option>
												<option value="7007" data-value="Imelda" <?php if ( $reg_zipcode == 7007 ) { echo "selected"; } ?>>Imelda</option>
												<option value="7008" data-value="Payao" <?php if ( $reg_zipcode == 7008 ) { echo "selected"; } ?>>Payao</option>
												<option value="7009" data-value="Buug" <?php if ( $reg_zipcode == 7009 ) { echo "selected"; } ?>>Buug</option>
												<option value="7010" data-value="Mabuhay" <?php if ( $reg_zipcode == 7010 ) { echo "selected"; } ?>>Mabuhay</option>
												<option value="7012" data-value="Talusan" <?php if ( $reg_zipcode == 7012 ) { echo "selected"; } ?>>Talusan</option>
												<option value="7018" data-value="Tungawan" <?php if ( $reg_zipcode == 7018 ) { echo "selected"; } ?>>Tungawan</option>
												<option value="7038" data-value="Malangas" <?php if ( $reg_zipcode == 7038 ) { echo "selected"; } ?>>Malangas</option>
												<option value="7039" data-value="Diplahan" <?php if ( $reg_zipcode == 7039 ) { echo "selected"; } ?>>Diplahan</option>
												<option value="7040" data-value="Alicia" <?php if ( $reg_zipcode == 7040 ) { echo "selected"; } ?>>Alicia</option>
												<option value="7041" data-value="Olutanga" <?php if ( $reg_zipcode == 7041 ) { echo "selected"; } ?>>Olutanga</option>
											</select>
											<label for="month"><b class="text-danger">*&nbsp;</b> Registered as Voter</label>
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Invalid Town</div>
										</div>

										<input id="town" type="text" value="<?php echo trim(htmlspecialchars($reg_town)); ?>" name="town" class="d-none" readonly>
									</div>
									<div class="col-md-4 mb-2"></div>
								</div>

								<div class="w-100 d-flex flex-wrap justify-content-end">
									<div class="btn-group">
										<a href="<?php echo trim($domainhome); ?>" class="btn btn-outline-primary"><i class='fas fa-home'></i></a>
										<a href="" class="btn btn-outline-primary"><i class="fas fa-sync"></i></a>
										<button type="button" id="clck-to-pinfo2" class="btn btn-outline-primary">Next</button>
									</div>
								</div>
							</div>

							<div class="tab-pane container fade" id="pinfo">
								<h6 class="text-center text-primary">Persoanl Info.</h6>
								<div class="form-floating my-1">
									<input type="text" class="form-control" id="nickname" value="<?php echo trim(htmlspecialchars($reg_nickname)); ?>" onfocus="this.select();" placeholder="Enter Nickname" name="nickname" required autofocus>
									<label for="nickname"><b class="text-danger">*&nbsp;</b>Enter Nickname</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>

								<div class="form-floating my-1">
									<input type="text" class="form-control" id="ptitle" value="<?php echo trim(htmlspecialchars($reg_ntitle)); ?>" onfocus="this.select();" placeholder="Title" name="ptitle" list="list_ptitle">
									<datalist id="list_ptitle">
										<option value="Atty.">
										<option value="Engr.">
										<option value="Dr.">
										<option value="Rev.">
									</datalist>
									<label for="ptitle">Title</label>
								</div>

								<div class="form-floating my-1">
									<input type="text" class="form-control" id="fname" value="<?php echo trim(htmlspecialchars($reg_fname)); ?>" onfocus="this.select();" placeholder="Enter First Name" name="fname" required>
									<label for="fname"><b class="text-danger">*&nbsp;</b>First Name</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>

								<div class="form-floating my-1">
									<input type="text" class="form-control" id="mname" value="<?php echo trim(htmlspecialchars($reg_mname)); ?>" onfocus="this.select();" placeholder="Enter Middle Name" name="mname">
									<label for="mname">Middle Name</label>
								</div>

								<div class="form-floating my-1">
									<input type="text" class="form-control" id="lname" value="<?php echo trim(htmlspecialchars($reg_lname)); ?>" onfocus="this.select();" placeholder="Enter Last Name" name="lname" required>
									<label for="lname"><b class="text-danger">*&nbsp;</b>Last Name</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>

								<div class="form-floating my-1">
									<input type="text" class="form-control" id="nsuffix" value="<?php echo trim(htmlspecialchars($reg_suffix)); ?>" onfocus="this.select();" placeholder="Enter Suffix" name="nsuffix" list="list_nsuffix">
									<datalist id="list_nsuffix">
										<option value="Jr.">
										<option value="Sr.">
										<option value="II">
										<option value="III">
										<option value="IV">
										<option value="V">
									</datalist>
									<label for="nsuffix">Suffix</label>
								</div>

								<div class="form-floating my-1">
									<input type="text" class="form-control" id="nprofession" value="<?php echo trim(htmlspecialchars($reg_profession)); ?>" onfocus="this.select();" placeholder="Enter Profession" name="nprofession" list="list_nprofession">
									<datalist id="list_nprofession">
										<option value="MBA">
										<option value="CPA">
										<option value="Ph.D.">
										<option value="M.D.">
										<option value="Esq.">
										<option value="MSIT">
										<option value="MIT">
										<option value="MIS">
									</datalist>
									<label for="nprofession">Profession</label>
								</div>

								<hr>

								<div class="my-1">
									<label class="form-label float-start d-inline-block d-block-mobile-view me-3"><b class="text-danger">*&nbsp;</b>Gender:</label>
									<div class="form-check form-check-inline cursor-hand">
										<input class="form-check-input cursor-hand" type="radio" name="genderOptions" id="genderMale" value="Male" <?php if ( trim($reg_gender) == trim("Male") ) { echo "checked"; } ?> required>
										<label class="form-check-label cursor-hand" for="genderMale">Male</label>
										<div class="valid-feedback">Valid.</div>
										<div class="invalid-feedback">Please fill out this field.</div>
									</div>
									<div class="form-check form-check-inline cursor-hand">
										<input class="form-check-input cursor-hand" type="radio" name="genderOptions" id="genderFemale" value="Female" <?php if ( trim($reg_gender) == trim("Female") ) { echo "checked"; } ?> required>
										<label class="form-check-label cursor-hand" for="genderFemale">Female</label>
										<div class="valid-feedback">-</div>
										<div class="invalid-feedback">-</div>
									</div>
								</div>

								<hr>

								<div class="my-1">
									<label class="form-label float-start d-inline-block d-block-mobile-view me-3">Birthday:</label>

									<div class="form-floating d-inline-block d-block-mobile-view m-1">
										<input id="year" type="number" value="<?php echo trim(htmlspecialchars($reg_birthyear)); ?>" onfocus="this.select();" min="1900" max="<?php echo trim(date('Y') - 18); ?>" class="form-control w-100 d-inline-block d-block-mobile-view" placeholder="* Year" name="birth-year" required>
										<label for="year"><b class="text-danger">*&nbsp;</b>Year</label>
										<div class="valid-feedback">Valid.</div>
										<div class="invalid-feedback">Invalid Year</div>
										<div><b class="text-danger">*&nbsp;</b>Year of Birth</div>
									</div>

									<div class="form-floating d-inline-block d-block-mobile-view m-1">
										<select id="month" class="form-select form-control" placeholder="* Month" name="birth-month" required>
											<option value disabled <?php if ( empty(trim($reg_birthmonth)) ) { echo "selected"; } ?>> -- select an option -- </option>
											<option value="01" data-value="January" <?php if ( trim($reg_birthmonth) == "01" ) { echo "selected"; } ?>>January</option>
											<option value="02" data-value="February" <?php if ( $reg_birthmonth == "02" ) { echo "selected"; } ?>>February</option>
											<option value="03" data-value="March" <?php if ( $reg_birthmonth == "03" ) { echo "selected"; } ?>>March</option>
											<option value="04" data-value="April" <?php if ( $reg_birthmonth == "04" ) { echo "selected"; } ?>>April</option>
											<option value="05" data-value="May" <?php if ( $reg_birthmonth == "05" ) { echo "selected"; } ?>>May</option>
											<option value="06" data-value="June" <?php if ( $reg_birthmonth == "06" ) { echo "selected"; } ?>>June</option>
											<option value="07" data-value="July" <?php if ( $reg_birthmonth == "07" ) { echo "selected"; } ?>>July</option>
											<option value="08" data-value="August" <?php if ( $reg_birthmonth == "08" ) { echo "selected"; } ?>>August</option>
											<option value="09" data-value="September" <?php if ( $reg_birthmonth == "09" ) { echo "selected"; } ?>>September</option>
											<option value="10" data-value="October" <?php if ( $reg_birthmonth == "10" ) { echo "selected"; } ?>>October</option>
											<option value="11" data-value="November" <?php if ( $reg_birthmonth == "11" ) { echo "selected"; } ?>>November</option>
											<option value="12" data-value="December" <?php if ( $reg_birthmonth == "12" ) { echo "selected"; } ?>>December</option>
										</select>
										<label for="month"><b class="text-danger">*&nbsp;</b>Birth Month</label>
										<div class="valid-feedback">Valid.</div>
										<div class="invalid-feedback">Invalid Month</div>
										<div><b class="text-danger">*&nbsp;</b>Birth Month</div>
									</div>

									<div class="form-floating d-inline-block d-block-mobile-view m-1">
										<input id="days" type="number" value="<?php echo trim(htmlspecialchars($reg_birthday)); ?>" onfocus="this.select();" min="1" max="31" class="form-control" placeholder="* Day" name="birth-day" required>
										<label for="days"><b class="text-danger">*&nbsp;</b>Birth Day</label>
										<div class="valid-feedback">Valid.</div>
										<div class="invalid-feedback">Invalid Day</div>
										<div>Days in month: <span id="output">31</span></div>
									</div>
								</div>

								<div class="form-floating my-3">
									<input type="text" class="form-control" id="pbirth" value="<?php echo trim(htmlspecialchars($reg_plbirth)); ?>" onfocus="this.select();" placeholder="Enter Place of Birth" name="pbirth" required>
									<label for="pbirth"><b class="text-danger">*&nbsp;</b>Place of Birth</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>

								<div class="w-100 d-flex flex-wrap justify-content-center">
									<p class="m-0 p-0">[ Note: <b class="text-danger">*&nbsp;</b> Fields are Required! ]</p>
								</div>

								<div class="w-100 d-flex flex-wrap justify-content-end">
									<div class="btn-group">
										<button type="button" id="clck-to-imgpic" class="btn btn-outline-primary">Back</button>
										<a href="<?php echo trim($domainhome); ?>" class="btn btn-outline-primary"><i class='fas fa-home'></i></a>
										<a href="" class="btn btn-outline-primary"><i class="fas fa-sync"></i></a>
										<button type="button" id="clck-to-contak" class="btn btn-outline-primary">Next</button>
									</div>
								</div>
							</div>

							<div class="tab-pane container fade" id="contact">
								<h6 class="text-center text-primary">Contact Info.</h6>

								<div class="form-floating my-1">
									<input type="number" class="form-control" value="<?php echo trim(htmlspecialchars($reg_phone)); ?>" onfocus="this.select();" id="phone" placeholder="Enter Primary Phone" name="phone" min="1000000000" max="9999999999" pattern="[789][0-9]{9}">
									<label for="phone">Enter Primary Phone</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid Phone</div>
									<div class="text-primary">Sample Phone Number: <span class="text-danger">915 482 6025</span></div>
								</div>

								<div class="form-floating my-1">
									<input type="number" class="form-control" id="phone2" value="<?php echo trim(htmlspecialchars($reg_phone2)); ?>" placeholder="Enter Primary Phone" name="phone2" min="1000000000" max="9999999999" pattern="[789][0-9]{9}">
									<label for="phone2">Enter Secondary Phone</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid Phone</div>
								</div>

								<div class="form-floating my-1">
									<input id="email" type="email" value="<?php echo trim(htmlspecialchars($reg_email)); ?>" onfocus="this.select();" class="form-control" placeholder="Enter Email" name="email">
									<label for="email">Enter Email</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid E-Mail</div>
								</div>

								<hr>

								<div class="form-floating my-1">
									<input id="fbid" type="text" value="<?php echo trim(htmlspecialchars($reg_fbid)); ?>" onfocus="this.select();" class="form-control" placeholder="Facebook ID" name="fbid">
									<label for="email">Enter Facebook ID</label>
								</div>

								<div class="w-100 mt-0 mb-1">
									<a href="//facebook.com/profile" target="_blank">Click here to get your facebook ID</a>
									<p>Sample Facebook ID: https://www.facebook.com/<b class="text-danger">profile.name</b></p>
								</div>

								<div class="w-100 d-flex flex-wrap justify-content-end">
									<div class="btn-group">
										<button type="button" id="clck-to-pinfo" class="btn btn-outline-primary">Back</button>
										<a href="<?php echo trim($domainhome); ?>" class="btn btn-outline-primary"><i class='fas fa-home'></i></a>
										<a href="" class="btn btn-outline-primary"><i class="fas fa-sync"></i></a>
										<button type="button" id="clck-to-user" class="btn btn-outline-primary">Next</button>
									</div>
								</div>
							</div>

							<div class="tab-pane container fade" id="user">
								<h6 class="text-center text-primary">User Info.</h6>

								<div class="form-floating my-2">
									<input id="nameuser" type="text" value="<?php echo trim(htmlspecialchars($reg_username)); ?>" onfocus="this.select();" onkeyup="showUserExist(this.value);" class="form-control" placeholder="Enter Username" name="nameuser" autocomplete="off" required>
									<label for="nameuser"><b class="text-danger">*&nbsp;</b>Enter Username</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid Username</div>
									<div class="d-flex flex-row gap-2 m-1">
										<div id="user-result"><div class="text-danger"><i class='fas fa-ban'></i> Username NOT Available</div></div>
										<button type="button" class="btn btn-sm btn-success" onclick="suggestUsername();">Generate Username</button>
									</div>
								</div>

								<div class="input-group form-floating my-2">
									<input id="password" type="password" value="<?php echo trim(htmlspecialchars($reg_password)); ?>" onfocus="this.select();" class="form-control password" placeholder="Enter password" name="password" autocomplete="new-password" onpaste="return false;" required>
									<label for="password"><b class="text-danger">*&nbsp;</b>Enter Password</label>
									<div class="input-group-prepend cursor-hand">
										<span class="input-group-text h-100 rounded-0 rounded-end">
											<i class="fa fa-eye-slash" aria-hidden="true" onclick="PwHideShow()"></i>
										</span>
									</div>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid password</div>
								</div>

								<div class="my-2">
									<button type="button" class="btn btn-sm btn-dark" onclick="genetPassW();">Generate Password</button>
								</div>

								<div class="input-group form-floating my-2">
									<input id="password2" type="password" value="<?php echo trim(htmlspecialchars($reg_password2)); ?>" onfocus="this.select();" class="form-control password" placeholder="Re-type password" name="password2" autocomplete="new-password" onpaste="return false;" required>
									<label for="password2"><b class="text-danger">*&nbsp;</b>Re-Type Password</label>
									<div class="input-group-prepend cursor-hand">
										<span class="input-group-text h-100 rounded-0 rounded-end">
											<i class="fa fa-eye-slash" aria-hidden="true" onclick="PwHideShow2()"></i>
										</span>
									</div>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid password</div>
								</div>

								<div class="form-outline my-2">
									<div id="message">
										<h6>Suggested Password: <?php echo trim($sagestidpw); ?></h6>
										<h5>Password must contain the following:</h5>
										<p id="letter" class="invalid">A <b>lowercase</b> letter</p>
										<p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
										<p id="number" class="invalid">A <b>number</b></p>
										<p id="length" class="invalid">Minimum <b>8 characters</b></p>
									</div>
								</div>

								<div class="w-100 d-flex flex-wrap justify-content-end">
									<div class="btn-group">
										<button type="button" id="clck-to-contakb" class="btn btn-outline-primary">Back</button>
										<a href="<?php echo trim($domainhome); ?>" class="btn btn-outline-primary"><i class='fas fa-home'></i></a>
										<a href="" class="btn btn-outline-primary"><i class="fas fa-sync"></i></a>
										<button type="button" id="clck-to-employee" class="btn btn-outline-primary">Next</button>
									</div>
								</div>
							</div>

							<div class="tab-pane container fade" id="employeeq">
								<div class="position-relative">
									<div class="row vh-58">
										<h3 class="text-center mx-auto mt-auto">Are you an Employee?</h3>
										<div class="d-flex w-100 mx-auto mb-auto justify-content-center">
											<button type="button" id="empYes" name="empYes" class="btn btn-outline-primary m-1">Yes</button>
											<button type="button" id="empNo" name="empNo" class="btn btn-outline-primary m-1">No</button>
										</div>
									</div>
								</div>

								<div class="w-100 d-flex flex-wrap justify-content-end">
									<div class="btn-group">
										<button type="button" id="clck-to-employeeq" class="btn btn-outline-primary">Back</button>
										<a href="<?php echo trim($domainhome); ?>" class="btn btn-outline-primary"><i class='fas fa-home'></i></a>
										<a href="" class="btn btn-outline-primary"><i class="fas fa-sync"></i></a>
									</div>
								</div>
							</div>

							<div class="tab-pane container fade" id="employee">
								<h6 class="text-center text-primary">Employee Info.</h6>

								<div class="form-floating my-2">
									<select id="type-employee" class="form-select form-control" name="type-employee">
										<option value disabled <?php if ( empty(trim($reg_typeemployee)) ) { echo "selected"; } ?>> -- select an option -- </option>
										<option id="emptypex-1" value="1" data-value="REG" label="Regular/Permanent" <?php if ( trim($reg_typeemployee) == 1 ) { echo "selected"; } ?>></option>
										<option id="emptypex-2" value="2" data-value="TMP" label="Temporary" <?php if ( trim($reg_typeemployee) == 2 ) { echo "selected"; } ?>></option>
										<option id="emptypex-3" value="3" data-value="COS" label="Contractual" <?php if ( trim($reg_typeemployee) == 3 ) { echo "selected"; } ?>></option>
										<option id="emptypex-4" value="4" data-value="JO" label="Casual" <?php if ( trim($reg_typeemployee) == 4 ) { echo "selected"; } ?>></option>
										<option id="emptypex-5" value="5" data-value="PRB" label="Probationary" <?php if ( trim($reg_typeemployee) == 5 ) { echo "selected"; } ?>></option>
										<option id="emptypex-6" value="6" data-value="COT" label="Coterminous" <?php if ( trim($reg_typeemployee) == 6 ) { echo "selected"; } ?>></option>
									</select>
									<label for="type-employee">Enter Employee Status</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>

								<div class="my-2 d-none">
									<input id="type-employee-abrv" value="<?php echo trim(htmlspecialchars($reg_typeemployeeabrv)); ?>" type="text" name="type-employee-abrv" readonly>
									<input id="type-employee-label" value="<?php echo trim(htmlspecialchars($reg_typeemployeelabel)); ?>" type="text" name="type-employee-label" readonly>
								</div>

								<div class="form-floating my-2">
									<select id="office" class="form-select form-control" name="office" placeholder="Designated Office">
										<option value disabled <?php if ( empty(trim($reg_officenmbering)) ) { echo "selected"; } ?>> -- select an option -- </option>
									<?php 
										require_once "model/office-signatory/index.php";
										$officeSignatoryLst = new officeSignatory();
										$officeSignatoryLst->vwofficeSignatoryZero();
										for ($i = 0; $i < count($officeSignatoryLst->list_officenamejj); $i++) {
											$nokey = $i + 1;
											$officesignatoryautoidjj = $officeSignatoryLst->list_officesignatoryautoidjj[$i];
											$agencycodejj = $officeSignatoryLst->list_agencycodejj[$i];
											$agencynamejj = $officeSignatoryLst->list_agencynamejj[$i];
											$officeidjj = $officeSignatoryLst->list_officeidjj[$i];
											$officecodejj = $officeSignatoryLst->list_officecodejj[$i];
											$officenamejj = $officeSignatoryLst->list_officenamejj[$i];
											$officetitlejj = $officeSignatoryLst->list_officetitlejj[$i];
											$officeabrvjj = $officeSignatoryLst->list_officeabrvjj[$i];
											$oldofficeabrvjj = $officeSignatoryLst->list_oldofficeabrvjj[$i];
											$headofficerjj = $officeSignatoryLst->list_headofficerjj[$i];
											$headtitlejj = $officeSignatoryLst->list_headtitlejj[$i];
											$authheadjj = $officeSignatoryLst->list_authheadjj[$i];
											$authtitlejj = $officeSignatoryLst->list_authtitlejj[$i];
											$authdescriptionjj = $officeSignatoryLst->list_authdescriptionjj[$i];
											$authimagewsignjj = $officeSignatoryLst->list_authimagewsignjj[$i];
											$authimagewoutsignjj = $officeSignatoryLst->list_authimagewoutsignjj[$i];
											$effectivitydatejj = $officeSignatoryLst->list_effectivitydatejj[$i];
											$signatorystatusjj = $officeSignatoryLst->list_signatorystatusjj[$i];
											$officegpslocationjj = $officeSignatoryLst->list_officegpslocationjj[$i];
											$xdeljj = $officeSignatoryLst->list_xdeljj[$i];
											$createdbyjj = $officeSignatoryLst->list_createdbyjj[$i];
											$modifiedbyjj = $officeSignatoryLst->list_modifiedbyjj[$i];
											$modifiedatjj = $officeSignatoryLst->list_modifiedatjj[$i];
											$createdatjj = $officeSignatoryLst->list_createdatjj[$i];

											if ( trim($reg_officenmbering) == trim($officesignatoryautoidjj) ) {
												$chelected_office = "selected";
											}

											echo "<option id='officexid-".$officesignatoryautoidjj."' value='".$officesignatoryautoidjj."' label='".$officenamejj."' data-value='".$officesignatoryautoidjj."' data-code='".$officecodejj."' data-title='".$officetitlejj."' data-abvr='".$officeabrvjj."' data-head='".$headofficerjj."' data-htitle='".$headtitlejj."' data-authead='".$authheadjj."' data-autheadtitle='".$authtitlejj."' data-authdesc='".$authdescriptionjj."' data-oldabvr='".$oldofficeabrvjj."' data-offgpsloc='".$officegpslocationjj."' data-seqnmbr='".$nokey."' ".$chelected_office." data-officeid='".$officeidjj."'>".$officenamejj."</option>";
										}
									?>
									</select>
									<label for="office">Enter Designated Office</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>

								<div class="my-2 d-none">
									<input id="officeid" type="text" name="officeid" value="<?php echo trim(htmlspecialchars($reg_officeid)); ?>" readonly>
									<input id="officecode" type="text" name="officecode" value="<?php echo trim(htmlspecialchars($reg_officecode)); ?>" readonly>
									<input id="officename" type="text" name="officename" value="<?php echo trim(htmlspecialchars($reg_officename)); ?>" readonly>
									<input id="officetitle" type="text" name="officetitle" value="<?php echo trim(htmlspecialchars($reg_officetitle)); ?>" readonly>
									<input id="officeabrv" type="text" name="officeabrv" value="<?php echo trim(htmlspecialchars($reg_officeabrv)); ?>" readonly>
									<input id="oldofficeabrv" type="text" name="oldofficeabrv" value="<?php echo trim(htmlspecialchars($reg_oldofficeabrv)); ?>" readonly>
									<input id="headofficer" type="text" name="headofficer" value="<?php echo trim(htmlspecialchars($reg_headofficer)); ?>" readonly>
									<input id="headtitle" type="text" name="headtitle" value="<?php echo trim(htmlspecialchars($reg_headtitle)); ?>" readonly>
									<input id="authhead" type="text" name="authhead" value="<?php echo trim(htmlspecialchars($reg_authhead)); ?>" readonly>
									<input id="authtitle" type="text" name="authtitle" value="<?php echo trim(htmlspecialchars($reg_authtitle)); ?>" readonly>
									<input id="authdescription" type="text" name="authdescription" value="<?php echo trim(htmlspecialchars($reg_authdescription)); ?>" readonly>
									<input id="officegpslocation" type="text" name="officegpslocation" value="<?php echo trim(htmlspecialchars($reg_officegpslocation)); ?>" readonly>

									<input id="officenmbering" type="text" name="officenmbering" value="<?php echo trim(htmlspecialchars($reg_officenmbering)); ?>" readonly>
								</div>

								<div class="form-floating my-2">
									<select id="biolocation" class="form-select form-control" name="biolocation" placeholder="Biometric Location">
										<option value disabled <?php if ( empty(trim($reg_biolocation)) ) { echo "selected"; } ?>> -- select an option -- </option>
									<?php 
										require_once "model/biolocation/index.php";
										$biolocationLst = new clssBioLocation();
										$biolocationLst->list_clssBioLocation();
										for ($i = 0; $i < count($biolocationLst->list_biolocationid); $i++) {
											$biolocationidmm = $biolocationLst->list_biolocationid[$i];
											$biolocationmm = $biolocationLst->list_biolocation[$i];
											$timelogstypemm = $biolocationLst->list_timelogstype[$i];

											if ( trim($reg_biolocation) == trim($biolocationidmm) ) {
												$chelected_biolocation = "selected";
											}

											echo "<option id='bioloc-".$biolocationidmm."' value='".$biolocationidmm."' label='".$biolocationmm."' data-value='".$timelogstypemm."' ".$chelected_biolocation.">".$biolocationmm."</option>";
										}
									?>
									</select>
									<label for="biolocation">Enter Biometric Location</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid Biometric Location.</div>
								</div>

								<div class="my-2 d-none"><input id="bioloclabel" type="text" value="<?php echo trim(htmlspecialchars($reg_bioloclabel)); ?>" name="bioloclabel" readonly></div>

								<div class="form-floating my-2">
									<input id="bionumber" type="number" value="<?php echo trim(htmlspecialchars($reg_bionumber)); ?>" onfocus="this.select();" min="100000" max="99999999" class="form-control" placeholder="Enter Biometric Number" name="bionumber">
									<label for="bionumber">Enter Biometric Number</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid Biometric Number</div>

									<div class="d-flex flex-row gap-2 m-1">
										<div id="bionmbr-result"><div class="text-danger"><i class='fas fa-ban'></i> Biometric Number NOT Available</div></div>
										<button type="button" class="btn btn-sm btn-success" onclick="suggestBioNmbrz();">Generate ID#</button>
									</div>
								</div>

								<div class="form-floating my-2">
									<input id="employeeid" type="number" value="<?php echo trim(htmlspecialchars($reg_employeeid)); ?>" onfocus="this.select();" min="100000" max="99999999" class="form-control" placeholder="Enter Employee ID" name="employeeid">
									<label for="employeeid">Enter Employee ID</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid Employee ID</div>

									<div class="d-flex flex-row gap-2 m-1">
										<div id="emplidnmbr-result"><div class="text-danger"><i class='fas fa-ban'></i> Employee ID Number NOT Available</div></div>
									</div>
								</div>

								<div class="form-floating my-2">
									<input id="pincode" type="number" value="<?php echo trim(htmlspecialchars($reg_pincode)); ?>" onfocus="this.select();" min="100000" max="99999999" class="form-control" placeholder="Enter PIN Code" name="pincode" onpaste="return false;">
									<label for="pincode">Enter PIN Code</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid PIN Code. Up to 8 digit only.</div>
								</div>

								<div class="my-2">
									<button type="button" class="btn btn-sm btn-dark" onclick="genetPincodex();">Generate PIN Code</button>
								</div>

								<div class="form-floating my-2">
									<input id="pincode2" type="number" value="<?php echo trim(htmlspecialchars($reg_pincode2)); ?>" onfocus="this.select();" min="100000" max="99999999" class="form-control" placeholder="Re-Type PIN Code" name="pincode2" onpaste="return false;">
									<label for="pincode2">Re-Type PIN Code</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Invalid PIN Code. Up to 8 digit only.</div>
								</div>

								<div class="form-floating my-2">
									<input id="designation" type="text" value="<?php echo trim(htmlspecialchars($reg_designation)); ?>" onfocus="this.select();" class="form-control" placeholder="Enter your Designation" name="designation" list="list_designation">
									<label for="designation">Enter your Designation</label>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Your Designation is Required!</div>

									<datalist id="list_designation">
										<?php 
											require_once "model/employee/index.php";
											$designationLst = new employeeAcct();
											$designationLst->fn_ListDesignation();

											for ($i = 0; $i < count($designationLst->list_designationforidee); $i++) {
												$thedesignationhaha = $designationLst->list_designationforidee[$i];

												echo '<option value="'.$thedesignationhaha.'">'.$thedesignationhaha.'</option>';
											}
										?>
									</datalist>
								</div>

								<p class="w-100 text-center my-2 text-danger">Make sure all required fields are filled-up.</p>

								<div class="w-100 d-flex flex-wrap justify-content-end">
									<div class="btn-group">
										<button type="button" id="clck-to-employeeb" class="btn btn-outline-primary">Back</button>
										<a href="<?php echo trim($domainhome); ?>" class="btn btn-outline-primary"><i class='fas fa-home'></i></a>
										<a href="" class="btn btn-outline-primary"><i class="fas fa-sync"></i></a>
										<button id="with_employee" type="submit" class="btn btn-primary" name="btnSubmit">Submit</button>
									</div>
								</div>
							</div>

							<div class="tab-pane container fade" id="usubmitx">
								<div class="position-relative">
									<div class="row vh-58">
										<button id="no_employee" type="submit" class="btn btn-outline-primary text-center mt-auto mx-auto w-auto" name="btnSubmit">Submit</button>
										<p class="w-100 text-center mx-auto mb-auto text-danger">Make sure all required fields are filled-up.</p>
									</div>
								</div>

								<div class="w-100 d-flex flex-wrap justify-content-end">
									<div class="btn-group">
										<button type="button" id="clck-to-userb" class="btn btn-outline-primary">Back</button>
										<a href="<?php echo trim($domainhome); ?>" class="btn btn-outline-primary"><i class='fas fa-home'></i></a>
										<a href="" class="btn btn-outline-primary"><i class="fas fa-sync"></i></a>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
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

		const bttnsampix = document.getElementById("bttn-sampix");
		const samppix = document.getElementById("samp-pix");
		bttnsampix.addEventListener('click', function(event) {
			if ( $(samppix).hasClass("d-none") ) {
				$(samppix).removeClass("d-none");
			} else {
				$(samppix).addClass("d-none");
			}
		});

		const stopCameraBtn2 = document.getElementById("stop-camera");
		const clcktopinfo2 = document.getElementById("clck-to-pinfo2");
		const clcktoimgpic = document.getElementById("clck-to-imgpic");
		const clcktopinfo = document.getElementById("clck-to-pinfo");
		const clcktocontak = document.getElementById("clck-to-contak");
		const clcktocontakb = document.getElementById("clck-to-contakb");
		const clcktouser = document.getElementById("clck-to-user");
		const clcktoemployee = document.getElementById("clck-to-employee");
		const clcktoemployeeb = document.getElementById("clck-to-employeeb");
		const clcktoemployeeq = document.getElementById("clck-to-employeeq");
		const clcktouserb = document.getElementById("clck-to-userb");
		const empYesz = document.getElementById("empYes");
		const empNo = document.getElementById("empNo");
		const tabimagepicture = document.getElementById("tab-imagepicture");
		const tabpinfo = document.getElementById("tab-pinfo");
		const tabcontact = document.getElementById("tab-contact");
		const tabuser = document.getElementById("tab-user");
		const tabemployee = document.getElementById("tab-employee");
		const tabemployeeq = document.getElementById("tab-employeeq");
		const tabsubmit = document.getElementById("tab-submit");
		const imgdata2 = document.getElementById("imgdata");
		const zipcode = document.getElementById("zipcode");

		const knickname = document.getElementById("nickname");
		const kfname = document.getElementById("fname");
		const klname = document.getElementById("lname");
		const kyear = document.getElementById("year");
		const kmonth = document.getElementById("month");
		const kdays = document.getElementById("days");
		const pbirth = document.getElementById("pbirth");

		const knameuser = document.getElementById("nameuser");
		const kpassword = document.getElementById("password");
		const kpassword2 = document.getElementById("password2");

		const kpincode = document.getElementById("pincode");
		const kpincode2 = document.getElementById("pincode2");

		const userresult = document.getElementById("user-result");

		const town = document.getElementById("town");
		zipcode.addEventListener('change', async function() {
			var zipcodeval = zipcode.value;
			town.value = document.querySelector('option[value="' + zipcodeval + '"]').dataset.value;
		});

		const typeemployee = document.getElementById("type-employee");
		const typeemployeeabrv = document.getElementById("type-employee-abrv");
		const typeemployeelabel = document.getElementById("type-employee-label");
		typeemployee.addEventListener('change', async function() {
			var typeemployeeval = typeemployee.value;
			typeemployeeabrv.value = document.querySelector('option[id="emptypex-' + typeemployeeval + '"]').dataset.value;
			typeemployeelabel.value = document.querySelector('option[id="emptypex-' + typeemployeeval + '"]').label;
		});

		const office = document.getElementById("office");
		const officeid = document.getElementById("officeid");
		const officecode = document.getElementById("officecode");
		const officename = document.getElementById("officename");
		const officetitle = document.getElementById("officetitle");
		const officeabrv = document.getElementById("officeabrv");
		const oldofficeabrv = document.getElementById("oldofficeabrv");
		const headofficer = document.getElementById("headofficer");
		const headtitle = document.getElementById("headtitle");
		const authhead = document.getElementById("authhead");
		const authtitle = document.getElementById("authtitle");
		const authdescription = document.getElementById("authdescription");
		const officegpslocation = document.getElementById("officegpslocation");
		const officenmbering = document.getElementById("officenmbering");
		office.addEventListener('change', async function() {
			var officeval = office.value;
			officeid.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.officeid;
			officecode.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.code;
			officename.value = document.querySelector('option[id="officexid-' + officeval + '"]').label;

			officetitle.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.title;
			officeabrv.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.abvr;
			oldofficeabrv.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.oldabvr;
			headofficer.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.head;
			headtitle.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.htitle;
			authhead.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.authead;
			authtitle.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.autheadtitle;
			authdescription.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.authdesc;
			officegpslocation.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.offgpsloc;

			officenmbering.value = document.querySelector('option[id="officexid-' + officeval + '"]').dataset.seqnmbr;
		});

		const biolocation = document.getElementById("biolocation");
		const bioloclabel = document.getElementById("bioloclabel");
		biolocation.addEventListener('change', async function() {
			var biolocationval = biolocation.value;
			bioloclabel.value = document.querySelector('option[id="bioloc-' + biolocationval + '"]').label;
		});

		function showUserExist(maoniuser) {
			if (maoniuser.length == 0) {
				userresult.innerHTML = "<div class='text-danger'><i class='fas fa-ban'></i> Username NOT Available</div>";
				return;
			} else {
				const xmlhttptt = new XMLHttpRequest();
				xmlhttptt.onload = function() {
					userresult.innerHTML = this.responseText;
				}
				xmlhttptt.open("GET", "model/userAcct/ifuseexist.php?nameuser=" + maoniuser);
				xmlhttptt.send();
			}
		}

		function suggestUsername() {
			const ur_username = kfname.value.toLowerCase() + "." + klname.value.toLowerCase();
			knameuser.value = ur_username.replaceAll(' ', '');
			knameuser.focus();
		}

		knameuser.addEventListener('focus', function(event) {
			showUserExist(knameuser.value);
		});
		knameuser.addEventListener('input', function(event) {
			showUserExist(knameuser.value);
		});
		knameuser.addEventListener('blur', function(event) {
			showUserExist(knameuser.value);
		});

		function genetPassW() {
			kpassword.value = suggestPWord();
			kpassword2.value = kpassword.value;
			kpassword.focus();
		}

		function genetPincodex() {
			kpincode.value = suggestPincodexx();
			kpincode2.value = kpincode.value;
			kpincode.focus();
		}

		const bionmbrresult = document.getElementById("bionmbr-result");
		const emplidnmbrresult = document.getElementById("emplidnmbr-result");
		const bionumberget = document.getElementById("bionumber");
		const employeeidget = document.getElementById("employeeid");
		function suggestBioNmbrz() {
			if ( typeemployee.value === "" ) {
				alert("Please Select Employee Status!");
				typeemployee.focus();
			} else if ( office.value === "" ) {
				alert("Please Select Designated Office!");
				office.focus();
			} else {
				let theofficenmbering = String(officenmbering.value).padStart(2, '0');
				bionumberget.value = typeemployee.value + theofficenmbering + randNmbrfive();
				employeeidget.value = bionumberget.value;
				bionumberget.focus();
			}
		}

		function showBioIDNumbrzExist(bioempidnmbr) {
			if (bioempidnmbr.length == 0) {
				bionmbrresult.innerHTML = "<div class='text-danger'><i class='fas fa-ban'></i> Biometric Number NOT Available</div>";
				return;
			} else {
				const xmlhttptt = new XMLHttpRequest();
				xmlhttptt.onload = function() {
					bionmbrresult.innerHTML = this.responseText;
				}
				xmlhttptt.open("GET", "model/employee/ifbionmbrexist.php?bioempidnmbr=" + bioempidnmbr);
				xmlhttptt.send();
			}
		}

		bionumberget.addEventListener('focus', function(event) {
			showBioIDNumbrzExist(bionumberget.value);
		});
		bionumberget.addEventListener('input', function(event) {
			showBioIDNumbrzExist(bionumberget.value);
		});
		bionumberget.addEventListener('blur', function(event) {
			showBioIDNumbrzExist(bionumberget.value);
		});

		function showEmpIDNumbrzExist(bioempidnmbr) {
			if (bioempidnmbr.length == 0) {
				emplidnmbrresult.innerHTML = "<div class='text-danger'><i class='fas fa-ban'></i> Employee ID Number NOT Available</div>";
				return;
			} else {
				const xmlhttptt = new XMLHttpRequest();
				xmlhttptt.onload = function() {
					emplidnmbrresult.innerHTML = this.responseText;
				}
				xmlhttptt.open("GET", "model/employee/ifemplymbrexist.php?bioempidnmbr=" + bioempidnmbr);
				xmlhttptt.send();
			}
		}

		employeeidget.addEventListener('focus', function(event) {
			showEmpIDNumbrzExist(employeeidget.value);
		});
		employeeidget.addEventListener('input', function(event) {
			showEmpIDNumbrzExist(employeeidget.value);
		});
		employeeidget.addEventListener('blur', function(event) {
			showEmpIDNumbrzExist(employeeidget.value);
		});

		clcktopinfo2.addEventListener('click', function(event) {
			if ( imgdata2.value && zipcode.value ) { 
				tabpinfo.click(); 
				stopCameraBtn2.click(); 
			}
		});
		clcktopinfo.addEventListener('click', function(event) {
			tabpinfo.click();
		});
		clcktoimgpic.addEventListener('click', function(event) {
			tabimagepicture.click();
		});
		clcktocontak.addEventListener('click', function(event) {
			if ( knickname.value && kfname.value && klname.value && kyear.value && kmonth.value && kdays.value && pbirth.value ) { tabcontact.click(); }
		});
		clcktocontakb.addEventListener('click', function(event) {
			tabcontact.click();
		});
		clcktouser.addEventListener('click', function(event) {
			tabuser.click();
		});

		clcktoemployee.addEventListener('click', function(event) {
			if ( knameuser.value && kpassword.value && kpassword2.value ) {
				if ( kpassword2.value !== kpassword.value ) {
					alert("Password Mismatched!")
				} else {
					<?php 
						if ( empty(trim($emplyornot)) ) { 
							echo "tabsubmit.click();"; 
						} else {
							if ( $settedsec !== false ) {
								echo "tabemployeeq.click();"; 
							}
						} 
					?>
				}
			}
		});

		clcktoemployeeb.addEventListener('click', function(event) {
			tabuser.click();
		});
		clcktoemployeeq.addEventListener('click', function(event) {
			tabuser.click();
		});
		
		empYesz.addEventListener('click', function(event) {
			$("#type-employee").prop("required",true);
			$("#office").prop("required",true);
			$("#biolocation").prop("required",true);
			$("#bionumber").prop("required",true);
			$("#employeeid").prop("required",true);
			$("#pincode").prop("required",true);
			$("#pincode2").prop("required",true);
			$("#designation").prop("required",true);
			tabemployee.click();
		});
		empNo.addEventListener('click', function(event) {
			$("#type-employee").prop("required",false);
			$("#office").prop("required",false);
			$("#biolocation").prop("required",false);
			$("#bionumber").prop("required",false);
			$("#employeeid").prop("required",false);
			$("#pincode").prop("required",false);
			$("#pincode2").prop("required",false);
			$("#designation").prop("required",false);

			$("#type-employee").removeProp("required");
			$("#office").removeProp("required");
			$("#biolocation").removeProp("required");
			$("#bionumber").removeProp("required");
			$("#employeeid").removeProp("required");
			$("#pincode").removeProp("required");
			$("#pincode2").removeProp("required");
			$("#designation").removeProp("required");
			tabsubmit.click();
		});
		clcktouserb.addEventListener('click', function(event) {
			tabuser.click();
		});

		// Camera for picture
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

		// New elements for camera selection
		let cameraSelect = document.querySelector("#camera-select"); // Assuming a <select> element with id="camera-select"
		let selectContainer = document.querySelector("#select-container"); // Assuming a container for the select element

		let videowidth = video.offsetWidth;
		let videoheight = video.offsetHeight;

		// New elements for zoom
		let zoomContainer = document.querySelector("#zoom-container");
		let zoomSlider = document.querySelector("#zoom-slider");
		let zoomValueSpan = document.querySelector("#zoom-value");
		let videoTrack; // Global variable to hold the active video track

		/**
		 * Populates the camera selection dropdown with available video input devices.
		 */
		async function populateCameraSelect() {
			try {
				// Request permission first, otherwise device labels might be empty
				await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
				// Stop the temporary stream immediately
				if (videoStream) {
					videoStream.getTracks().forEach(track => track.stop());
					videoStream = null;
				}

				const devices = await navigator.mediaDevices.enumerateDevices();
				const videoDevices = devices.filter(device => device.kind === 'videoinput');

				// Clear existing options
				cameraSelect.innerHTML = '';

				if (videoDevices.length > 0) {
					videoDevices.forEach(device => {
						const option = document.createElement('option');
						option.value = device.deviceId;
						// Use device label if available, otherwise default to a generic name
						option.text = device.label || `Camera ${cameraSelect.options.length + 1}`;

						// A simple heuristic to guess front/back based on label
						if (device.label.toLowerCase().includes('front')) {
							option.text += ' (Front)';
						} else if (device.label.toLowerCase().includes('back')) {
							option.text += ' (Back)';
						}

						cameraSelect.appendChild(option);
					});
					// Show the selector if there's more than one device to choose from
					if (videoDevices.length > 1) {
						selectContainer.classList.remove('d-none');
					} else {
						selectContainer.classList.add('d-none'); // Hide if only one camera is found
					}
				} else {
					// No video devices found
					console.warn("No video input devices found.");
					selectContainer.classList.add('d-none');
				}
			} catch (err) {
				console.error("Error enumerating devices: ", err);
				selectContainer.classList.add('d-none');
			}
		}

		// Initial population of the camera select dropdown
		populateCameraSelect();

		/**
		 * Starts the video stream from the selected camera.
		 * @param {string} deviceId - The device ID of the camera to use.
		 */
		async function startCamera(deviceId) {
			// Stop any existing stream first
			if (videoStream) {
				videoStream.getTracks().forEach(track => track.stop());
				video.srcObject = null;
				videoStream = null;
				videoTrack = null; // Clear track reference
				zoomContainer.classList.add('d-none'); // Hide zoom controls on stop
			}

			try {
				// Constraints object using the selected deviceId
				const constraints = {
					video: { deviceId: deviceId ? { exact: deviceId } : undefined },
					audio: false
				};

				// Request video stream and store it in the global variable
				videoStream = await navigator.mediaDevices.getUserMedia(constraints);
				video.srcObject = videoStream;

				// 1. Get the current video track
				videoTrack = videoStream.getVideoTracks()[0];

				// 2. Initialize Zoom Controls
				initializeZoomControls();

				// Show/hide buttons and video elements
				dispvid.classList.remove('d-none');
				disppix.classList.add('d-none');

				camera_button.classList.add("d-none");
				click_button.classList.remove('d-none');
				stopCameraBtn.classList.remove('d-none');
				retakephoto.classList.add('d-none');
			} catch (err) {
				console.error("Error accessing camera: ", err);
				alert("Error: Could not access the camera. Please check your browser permissions or if the device is in use.");
				// Re-enable start button if camera access failed
				camera_button.classList.remove("d-none");
				click_button.classList.add('d-none');
				stopCameraBtn.classList.add('d-none');
				retakephoto.classList.add('d-none');
				dispvid.classList.add('d-none');
				disppix.classList.add('d-none');
				// Ensure zoom controls are hidden on failure
				zoomContainer.classList.add('d-none');
			}
		}

		/**
		 * Checks video track capabilities and sets up the zoom slider.
		 */
		function initializeZoomControls() {
			if (!videoTrack) {
				zoomContainer.classList.add('d-none'); // FIX: Ensure it's hidden if no track
				return;
			}

			const capabilities = videoTrack.getCapabilities();

			// Check if the camera supports the 'zoom' constraint
			if (capabilities.zoom) {
				const { min, max, step } = capabilities.zoom;

				// Configure the slider based on camera capabilities
				zoomSlider.min = min;
				zoomSlider.max = max;
				zoomSlider.step = step || 0.1; // Fallback step
				zoomSlider.value = capabilities.zoom.current || min; // Set initial value

				zoomValueSpan.textContent = `${parseFloat(zoomSlider.value).toFixed(1)}x`;
				zoomContainer.classList.remove('d-none'); // FIX: Show the controls when supported

				// Apply the initial zoom constraint in case the default isn't min
				applyZoom(zoomSlider.value);
			} else {
				// Hide controls if zoom is not supported
				zoomContainer.classList.add('d-none'); // FIX: Hide the controls
				console.log("Zoom not supported by this camera.");
			}
		}

		/**
		 * Applies the new zoom value to the video track.
		 * @param {number} value - The zoom level to apply.
		 */
		async function applyZoom(value) {
			if (videoTrack) {
				try {
					await videoTrack.applyConstraints({
						advanced: [{ zoom: parseFloat(value) }]
					});
					zoomValueSpan.textContent = `${parseFloat(value).toFixed(1)}x`;
				} catch (err) {
					// This error might happen if a non-supported value is set, or the track is no longer active
					console.error("Failed to set zoom constraint: ", err);
				}
			}
		}

		camera_button.addEventListener('click', async function() {
			// Get the selected camera ID from the dropdown
			const selectedDeviceId = cameraSelect.value;
			startCamera(selectedDeviceId);
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
				videoTrack = null; // Clear the track reference

				// FIX: Hide Zoom controls when the camera is stopped
				zoomContainer.classList.add('d-none');

				// Update button visibility
				camera_button.classList.remove("d-none");
				click_button.classList.add('d-none');
				stopCameraBtn.classList.add('d-none');
				retakephoto.classList.add('d-none');
				dispvid.classList.add('d-none');
				disppix.classList.add('d-none');
			}
		});

		// Zoom Slider Event Listener
		zoomSlider.addEventListener('input', function() {
			// Apply the zoom as the user drags the slider
			applyZoom(this.value);
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
			
			// FIX: Hide the zoom controls after the photo is taken
			zoomContainer.classList.add('d-none');
		});

		retakephoto.addEventListener('click', async function() {
			disppix.classList.add('d-none');

			dispvid.classList.remove('d-none');
			camera_button.classList.add("d-none");
			retakephoto.classList.add('d-none');
			click_button.classList.remove('d-none');
			
			// FIX: Show the zoom controls again when retaking a photo (if supported)
			if (videoTrack && videoTrack.getCapabilities().zoom) {
				zoomContainer.classList.remove('d-none');
			}
		});
	</script>
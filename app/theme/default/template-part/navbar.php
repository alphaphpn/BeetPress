	<style>
		#repub-label {
			font-family: '<?php if ($onlineornot==0) { echo "Old English Text MT"; } else { echo "UnifrakturMaguntia"; } ?>',cursive;
		}
	</style>

	<canvas id="particle-animate" style="position: fixed; overflow: hidden; width: 100%; height: 100vh; z-index: -1;"></canvas>

	<div class="primary-bg-color" style="z-index: 2;">
		<nav class="navbar navbar-expand-lg primary-bg-color py-1">
			<div class="container pt-0 pb-0">
				<div class="row pt-0 pb-0 w-100">
					<div class="col-sm-6 pt-0 pb-0">
						<a class="navbar-brand p-0 d-flex" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>">
							<img src="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>assets/media/favicon.png" style="max-height: 52px; margin-top: 4px;">
							<div class="px-2 py-0">
								<label id="repub-label" class="p-0 font-size-14"><b>Republic of the Philippines</b></label>
								<hr class="p-0 m-0" style="border-width: medium; opacity: unset;">
								<hr class="p-0 mb-0 mt-1" style="opacity: unset;">
								<label class="p-0 font-size-12" style="float: inline-start;"><b>PROVINCE OF ZAMBOANGA SIBUGAY</b></label>
							</div>
						</a>
					</div>

					<div id="head-nav-contact" class="col-sm-6 pt-0 pb-0">
						<div style="text-align: end;">
							<a class="text-body text-decoration-none" href="tel:+639154826025"><span class="fas fa-phone"></span> +63 915 482 6025</a>
							<a class="text-body text-decoration-none" href="mailto:info@sibugay.gov.ph"><span class="fas fa-envelope-square"></span> info@sibugay.gov.ph</a>
						</div>
						<p id="nvbr-date" class="my-0 py-0" style="text-align: end"></p>
					</div>
				</div>
			</div>
		</nav>
	</div>

	<header class="sticky-top">
		<nav class="navbar navbar-expand-lg secondary-bg-color py-1">
			<div class="container">
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="mynavbar">
					<ul class="navbar-nav me-auto">
						<li class="nav-item">
							<a class="nav-link" href="<?php if ( $the_homepage == 1) { echo "#home"; } else { echo trim($domainhome).'/'; } ?>">Home</a>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#services">Services</a>
							<ul class="dropdown-menu">
								<li class="nav-item dropend">
									<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Apps</a>
									<ul class="dropdown-menu">
										<!-- JS -->
										<li><a class="dropdown-item" href="run/js/qrcode" target="_blank">QR Code Reader</a></li>
										<li><a class="dropdown-item" href="run/js/gps" target="_blank">GPS</a></li>
										<li><a class="dropdown-item" href="run/js/sign" target="_blank">eSign</a></li>

										<li><hr class="dropdown-divider"></li>

										<!-- PHP -->
										<li><a class="dropdown-item" href="run/php/date" target="_blank">Date</a></li>
										<li><a class="dropdown-item" href="run/php/detect-device-info" target="_blank">Device info.</a></li>
										<li><a class="dropdown-item" href="run/php/password" target="_blank">Data Encrypt</a></li>
									</ul>
								</li>
							</ul>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" data-bs-auto-close="outside" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>/office" data-bs-toggle="dropdown">Office</a>
							<ul class="dropdown-menu">
								<li class="nav-item dropend">
									<a class="nav-link dropdown-toggle" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#executive" role="button" data-bs-toggle="dropdown" aria-expanded="false">Executive</a>
									<ul class="dropdown-menu">
										<li><a class="dropdown-item" href="#">Governor</a></li>
										<li><a class="dropdown-item" href="#">Admin</a></li>
										<li><a class="dropdown-item" href="#">Legal</a></li>

										<li><hr class="dropdown-divider"></li>

										<li><a class="dropdown-item" href="#">Human Resource</a></li>
										<li><a class="dropdown-item" href="#">Budget</a></li>
										<li><a class="dropdown-item" href="#">Accounting</a></li>
										<li><a class="dropdown-item" href="#">Treasury</a></li>
										<li><a class="dropdown-item" href="#">Planning</a></li>
										<li><a class="dropdown-item" href="#">Engineering</a></li>
										<li><a class="dropdown-item" href="#">General Services</a></li>

										<li><hr class="dropdown-divider"></li>

										<li><a class="dropdown-item" href="#">Bids and Awards Committee</a></li>
										<li><a class="dropdown-item" href="#">Procurement Service</a></li>

										<li><hr class="dropdown-divider"></li>

										<li><a class="dropdown-item" href="#">Tourisim</a></li>
										<li><a class="dropdown-item" href="#">ICT</a></li>
										<li><a class="dropdown-item" href="#">Nutrition</a></li>
										<li><a class="dropdown-item" href="#">Security</a></li>
									</ul>
								</li>

								<li><hr class="dropdown-divider"></li>

								<li class="nav-item dropend">
									<a class="nav-link dropdown-toggle" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#legislative" role="button" data-bs-toggle="dropdown" aria-expanded="false">Legislative</a>
									<ul class="dropdown-menu">
										<li><a class="dropdown-item" href="#">Vice Governor</a></li>
										<li><a class="dropdown-item" href="#">Secretary</a></li>

										<li class="nav-item dropend">
											<a class="nav-link dropdown-toggle" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#board-member" role="button" data-bs-toggle="dropdown" aria-expanded="false">Board Member</a>
											<ul class="dropdown-menu">
												<li><a class="dropdown-item" href="#">1st District</a></li>
												<li><a class="dropdown-item" href="#">2nd District</a></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" data-bs-auto-close="outside"  href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#" data-bs-toggle="dropdown">Transparency</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#seal">Seal</a></li>
								<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#careers">Careers</a></li>
								<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#programs-projects">Programs and Projects</a></li>
								<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#gad">Gender and Development</a></li>
								<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#downloads">Downloads</a></li>
								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#faq">Frequently Ask Questions</a></li>
								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#dtr">DTR</a></li>
								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#precinct-finder">Precinct Finder</a></li>
							</ul>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#testimonials">Testimonials</a>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link  dropdown-toggle" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#about">About</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#ceo">Our Governor</a></li>
							</ul>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#contact">Contact Us</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#appointment">Appointment</a></li>
							</ul>
						</li>
					</ul>

					<div class="d-flex">
						<ul class="navbar-nav" id="account-menu">
							<?php
								if ( isset($_SESSION["employeeactivated"]) && !isset($_SESSION["d2s8wu_ustat"]) && !isset($_SESSION["d2s8wu_verified"]) && !isset($_SESSION['d2s8wu_xdel']) && !isset($_SESSION['d2s8wu_ulevel']) ) {
									include_once "navbar-employee.php";
									include_once "navbar-logout.php";
									// echo "<script>alert('1')</script>";
								} elseif ( !isset($_SESSION["employeeactivated"]) && isset($_SESSION["d2s8wu_ustat"]) && isset($_SESSION["d2s8wu_verified"]) && isset($_SESSION['d2s8wu_xdel']) && isset($_SESSION['d2s8wu_ulevel']) ) {
									if ( $_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 1 || 
										$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 2 || 
										$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 15 || 
										$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 16 || 
										$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 17 ) {
										include_once "navbar-admin.php";
										include_once "navbar-logout.php";
										// echo "<script>alert('2')</script>";
									} elseif ( $_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 14 ) {
										include_once "navbar-acct.php";
										include_once "navbar-logout.php";
										// echo "<script>alert('3')</script>";
									}
								} elseif ( isset($_SESSION["employeeactivated"]) && isset($_SESSION["d2s8wu_ustat"]) && isset($_SESSION["d2s8wu_verified"]) && isset($_SESSION['d2s8wu_xdel']) && isset($_SESSION['d2s8wu_ulevel']) ) {
									if ( $_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 1 || 
										$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 2 || 
										$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 15 || 
										$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 16 || 
										$_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 17 ) {
										include_once "navbar-employee.php";
										include_once "navbar-admin.php";
										include_once "navbar-logout.php";
										// echo "<script>alert('4')</script>";
									} elseif ( $_SESSION["d2s8wu_ustat"]==1 && $_SESSION["d2s8wu_verified"]==1 && $_SESSION['d2s8wu_xdel']==0 && $_SESSION['d2s8wu_ulevel'] == 14 ) {
										include_once "navbar-acct.php";
										include_once "navbar-logout.php";
										// echo "<script>alert('6')</script>";
									} 
								} else {
									include_once "navbar-default.php";
									// echo "<script>alert('5')</script>";
								}
							?>
						</ul>

						<a class="btn py-0 my-auto d-mobile-none" href=""><i class='fas fa-sync text-light'></i></a>

						<?php if ($onlineornot==1) { echo '<div id="google_translate_element" class="text-end"></div>'; } ?>
					</div>
				</div>
			</div>
		</nav>
	</header>
<?php 

	$disp_uid = isset($_SESSION["d2s8wu_uid"]) ? trim($_SESSION["d2s8wu_uid"]) : null;
	$alt_name = isset($_SESSION["d2s8wu_uname"]) ? strtoupper(substr($_SESSION["d2s8wu_uname"],0,1)) : null;
	$disp_uname = isset($_SESSION["d2s8wu_uname"]) ? trim($_SESSION["d2s8wu_uname"]) : null;
	$disp_uposition = isset($_SESSION["d2s8wu_uposition"]) ? trim($_SESSION["d2s8wu_uposition"]) : null;
	$disp_officeid = isset($_SESSION["d2s8wu_officeid"]) ? trim($_SESSION["d2s8wu_officeid"]) : null;
	$disp_officecode = isset($_SESSION["d2s8wu_officecode"]) ? trim($_SESSION["d2s8wu_officecode"]) : null;
	$disp_officeabvr = isset($_SESSION["d2s8wu_officeabrv"]) ? trim($_SESSION["d2s8wu_officeabrv"]) : null;
	$disp_ulevel = isset($_SESSION["d2s8wu_ulevel"]) ? trim($_SESSION["d2s8wu_ulevel"]) : null;

?>

	<canvas id="particle-animate" style="position: fixed; overflow: hidden; width: 100%; height: 100vh;"></canvas>

	<div id="layoutSidenav">
		<div id="layoutSidenav_nav">
			<nav class="sb-sidenav accordion sb-sidenav-<?php echo $dashboard_theme_name; ?>" id="sidenavAccordion">
				<div class="sb-sidenav-header d-flex">
					<div title="<?php echo trim($disp_uname); ?>" class="img-responsive rounded-2 w-100" style="min-height: 72px; max-width: 70px; background-color: #393633;background-image: url('<?php echo trim($pixloc); ?>public/userID/<?php echo trim($disp_uid); ?>.jpeg'); background-position: center top; background-repeat: no-repeat; background-size: cover;"></div>

					<div class="d-flex flex-column ms-2">
						<span class="user-name font-size-14">
							<?php echo trim($disp_uname); ?><br>
							<strong><?php echo trim($disp_uposition); ?></strong>
						</span>
						<span class="user-role font-size-10"><?php echo trim($disp_officeid).' '.trim($disp_officeabvr).' '.trim($disp_officecode); ?></span>
						<span class="user-status font-size-10">
							<i class="fa fa-circle text-success"></i>
							<span>Online</span>
						</span>
					</div>
				</div>

				<div class="sb-sidenav-menu">
					<div class="nav pb-4">
						<div class="sb-sidenav-menu-heading">Core</div>
							<a class="nav-link" href="<?php echo trim($domainhome); ?>/bp-mngr">
								<div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
								<span class="w-100">DashPanel</span>
								<span class="badge bg-danger">Main</span>
							</a>

							<?php include_once "sidenav-core.php"; ?>

						<div class="sb-sidenav-menu-heading">Your</div>
							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
								<span class="w-100">Profile</span>
								<span class="badge bg-danger">Info</span>
							</a>

							<a class="nav-link" href="<?php echo trim($domainhome); ?>/dcalendar">
								<div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
								Calendar
							</a>

							<a class="nav-link" href="<?php echo trim($domainhome); ?>/appointment-schedule">
								<div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
								Appointment Schedule
							</a>

							<a class="nav-link" href="<?php echo trim($domainhome); ?>/meeting-schedule">
								<div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
								Meeting Schedule
							</a>

						<div class="sb-sidenav-menu-heading">Apps</div>
							<?php include_once "sidenav-subsys.php"; ?>

						<div class="sb-sidenav-menu-heading">System</div>
							<?php include_once "sidenav-depsys.php"; ?>
					</div>
				</div>
				<div class="sb-sidenav-footer last-bg-color">
					<a href="#" class="text-decoration-none ms-3" title="Notification">
						<i class="fas fa-bell"></i>
						<span class="badge bg-warning rounded-circle position-relative" style="top: -8px; margin-left: -8px;">3</span>
					</a>
					<a href="#" title="Message" class="text-decoration-none ms-3">
						<i class="fa fa-envelope"></i>
						<span class="badge bg-success rounded-circle position-relative" style="top: -8px; margin-left: -8px;">7</span>
					</a>
					<a href="#" title="Settings" class="text-decoration-none ms-3">
						<i class="fa fa-cog"></i>
					</a>
					<a href="" class="text-decoration-none ms-3" title="Refresh">
						<i class="fas fa-sync"></i>
					</a>
					<a href="<?php echo $domainhome; ?>/lib/logout.php" class="text-decoration-none ms-3" title="Logout">
						<i class="fa fa-power-off"></i>
					</a>
				</div>
			</nav>
		</div>
		
		<div id="layoutSidenav_content">
			<main style="padding-bottom: 5rem;">
				<div class="container-fluid px-4 clearfix">
				<?php
					if ($page_title=="DashPanel") {

					} else {
				?>
					<a href="javascript: history.back()" class="float-end btn btn-sm btn-danger mt-2">Back</a>
				<?php
					}
				?>

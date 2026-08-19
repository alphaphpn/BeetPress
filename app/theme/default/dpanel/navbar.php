	<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
		<!-- Navbar Brand-->
		<a class="navbar-brand ps-3" href="<?php echo $domainhome; ?>/bp-mngr">
			<img src="<?php echo $domainhome; ?>/assets/media/Logo-eSibugayPH.png" style="max-height: 38px;">
			<b><span class="text-primary">e</span><span class="text-danger">P</span><span class="text-warning">L</span><span class="text-success">G</span><span class="text-info">U</span>-<span class="txt-color-primary">ZSP</span></b>
		</a>

		<!-- Sidebar Toggle-->
		<button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

		<div class="d-none d-md-inline-block ms-0 me-auto ps-2">
			<div class="d-flex gap-2">
				<h5 class="text-white mb-0"><?php echo $page_title; ?></h5>
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item active m-auto"><?php echo $breadcrumb; ?></li>
				</ol>
			</div>
		</div>

		<!-- Navbar Search-->
		<form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
			<div class="input-group">
				<input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
				<button class="btn btn-success" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
			</div>
		</form>

		<a class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4" href="" title="Refresh"><i class="fas fa-sync"></i></a>

		<!-- Navbar-->
		<ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle show" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="true"><i class="fas fa-user fa-fw text-white"></i></a>
				<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" data-bs-popper="static">
					<li><a class="dropdown-item" href="./">Site</a></li>
					<li><hr class="dropdown-divider" /></li>
					<li><a class="dropdown-item" href="#!">Settings</a></li>
					<li><a class="dropdown-item" href="#!">Activity Log</a></li>
					<li><hr class="dropdown-divider" /></li>
					<li><a class="dropdown-item" href="<?php echo $domainhome; ?>/lib/logout.php">Logout</a></li>
				</ul>
			</li>
		</ul>
	</nav>
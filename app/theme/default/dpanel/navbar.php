	<nav class="sb-topnav navbar navbar-expand <?php echo $dashboard_navbar_class; ?>">
		<!-- Navbar Brand-->
		<a class="navbar-brand ps-3" href="<?php echo $domainhome; ?>/bp-mngr">
			<img src="<?php echo $domainhome; ?>/assets/media/Logo-eSibugayPH.png" style="max-height: 38px;">
			<b><span class="text-primary">e</span><span class="text-danger">P</span><span class="text-warning">L</span><span class="text-success">G</span><span class="text-info">U</span>-<span class="txt-color-primary">ZSP</span></b>
		</a>

		<!-- Sidebar Toggle-->
		<button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

		<div class="d-none d-md-inline-block ms-0 me-auto ps-2">
			<div class="d-flex gap-2">
				<h5 class="<?php echo $dashboard_theme === 1 ? 'text-dark' : 'text-white'; ?> mb-0"><?php echo $page_title; ?></h5>
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

		<div class="form-check form-switch me-3 cursor-hand">
			<input class="form-check-input cursor-hand" type="checkbox" role="switch" id="themecolorswitch" title="<?php echo $dashboard_theme === 1 ? 'Switch to Dark Theme' : 'Switch to Light Theme'; ?>" aria-label="Toggle dashboard theme" <?php echo $dashboard_theme === 0 ? 'checked' : ''; ?>>
		</div>

		<a class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4" href="" title="Refresh"><i class="fas fa-sync"></i></a>

		<!-- Navbar-->
		<ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle show" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="true"><i class="fas fa-user fa-fw <?php echo $dashboard_theme === 1 ? 'text-dark' : 'text-white'; ?>"></i></a>
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

	<script>
		(function () {
			const themeSwitch = document.getElementById('themecolorswitch');

			if (!themeSwitch) {
				return;
			}

			themeSwitch.addEventListener('change', function () {
				const dashboardTheme = this.checked ? 0 : 1;
				this.disabled = true;

				fetch('<?php echo trim($domainhome); ?>/model/dashboard-theme/update.php', {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
					body: 'dashboard_theme=' + dashboardTheme
				})
					.then(function (response) {
						if (!response.ok) {
							throw new Error('Unable to save dashboard theme.');
						}

						return response.json();
					})
					.then(function (result) {
						if (result.status !== 'success') {
							throw new Error(result.message || 'Unable to save dashboard theme.');
						}

						window.location.reload();
					})
					.catch(function () {
						themeSwitch.checked = !themeSwitch.checked;
						themeSwitch.disabled = false;
						alert('The dashboard theme could not be saved. Please try again.');
					});
			});
		}());
	</script>

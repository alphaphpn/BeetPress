						<?php
							if ( $disp_ulevel == 1 || $disp_ulevel == 16 || $disp_ulevel == 16 || $disp_ulevel == 17 ) {
						?>
							<div class="nav-item">
								<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSocialWelfare" aria-expanded="false" aria-controls="collapseSocialWelfare">
									<div class="sb-nav-link-icon"><i class="far fa-folder-open"></i></div>
									Document Tracker
									<div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
								</a>
								<div class="collapse" id="collapseSocialWelfare">
									<nav class="sb-sidenav-menu-nested nav">
										<a class="nav-link" href="<?php echo trim($domainhome); ?>/document-tracker-new">
											<div class="sb-nav-link-icon"><i class="fas fa-folder-plus"></i></div>
										New
										</a>
										<a class="nav-link" href="<?php echo trim($domainhome); ?>/document-tracer">
											<div class="sb-nav-link-icon"><i class="fas fa-search"></i></div>
										Tracer / Tracker
										</a>
										<a class="nav-link" href="<?php echo trim($domainhome); ?>/document-tracker-incoming">
											<div class="sb-nav-link-icon"><i class="far fa-folder"></i></div>
										Incoming
										</a>
										<a class="nav-link" href="<?php echo trim($domainhome); ?>/document-tracker-outgoing">
											<div class="sb-nav-link-icon"><i class="far fa-paper-plane"></i></div>
										Outgoing
										</a>
										<a class="nav-link" href="<?php echo trim($domainhome); ?>/document-tracker-completed">
											<div class="sb-nav-link-icon"><i class="fas fa-clipboard-check"></i></div>
										Completed
										</a>
										<a class="nav-link" href="<?php echo trim($domainhome); ?>/document-tracker-closed">
											<div class="sb-nav-link-icon"><i class="fas fa-ban"></i></div>
										Closed
										</a>
										<a class="nav-link" href="<?php echo trim($domainhome); ?>/document-tracker-archived">
											<div class="sb-nav-link-icon"><i class="fas fa-archive"></i></div>
										Archived
										</a>
									</nav>
								</div>
							</div>

							<a class="nav-link" href="employee-list">
								<div class="sb-nav-link-icon"><i class="fas fa-user-tag"></i></div>
								Employee List
							</a>

							<a class="nav-link" href="employee-tracker">
								<div class="sb-nav-link-icon"><i class="fas fa-map-marker-alt"></i></div>
								Employee Tracker
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-upload"></i></div>
								Upload Timelogs
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="far fa-clock"></i></div>
								Daily TIme Record (DTR)
							</a>

							<!-- a class="nav-link" href="employee-id-reg">
								<div class="sb-nav-link-icon"><i class="far fa-address-card"></i></div>
								Employee ID Registration
							</a -->

						<?php
							}
						?>
							
						<?php
							if ( $disp_ulevel == 1) {
						?>
							<a class="nav-link" href="employee-registration">
								<div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
								Employee Registration
							</a>
							
							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
								DTR Builder
							</a>

							<a class="nav-link" href="office">
								<div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
								Office
							</a>
						<?php
							}
						?>

						<?php
							if ( $disp_ulevel == 99 || $disp_ulevel == 1 ) {
						?>
							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-id-card"></i></div>
								Voter's List
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-id-card"></i></div>
								Unverified Voter's
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-id-card"></i></div>
								Verified Voter's
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-id-card"></i></div>
								Selected Voter's
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-id-card"></i></div>
								Assistance Program
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-id-card"></i></div>
								Assisted Voter's
							</a>
						<?php
							}
						?>

							<!-- a class="nav-link" href="../../routes/dashpanel">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Text Here
							</a -->

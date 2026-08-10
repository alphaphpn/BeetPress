							<!-- a class="nav-link" href="../../routes/#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Text Here
							</a -->

						<?php
							if ( $disp_officeid == 0 || $disp_officeid == null ) {
						?>
							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Executive
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Legislative
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Administrative
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Information and Communications
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Legal
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Human Resource
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Budget
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Bids and Awards
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Accounting
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Treasury
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Planning
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								General Services
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Engineering
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Assessor
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Social Welfare
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Health
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Agriculture
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Veterinary
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Tourism
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Cooperative
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Environment and Natural Resources
							</a>
						<?php
							} elseif ( $disp_officecode == 1032 ) {
						?>
							<div class="nav-item">
								<!-- Sidebar Main Item -->
								<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseHR" aria-expanded="false" aria-controls="collapseHR">
									<div class="sb-nav-link-icon"><i class="fas fa-desktop"></i></div>
								Human Resource
									<div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
								</a>

								<!-- Expanding Sub-menu -->
								<div class="collapse" id="collapseHR">
									<nav class="sb-sidenav-menu-nested nav">
										<a class="nav-link" href="#">
											<div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
										Employee List
										</a>
										<a class="nav-link" href="#">
											<div class="sb-nav-link-icon"><i class="fas fa-user-clock"></i></div>
										Attendance
										</a>
									</nav>
								</div>
							</div>
						<?php
							}
						?>
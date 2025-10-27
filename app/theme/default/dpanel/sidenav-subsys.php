						<?php
							if ( $disp_ulevel == 1 || $disp_ulevel == 16 || $disp_ulevel == 16 || $disp_ulevel == 17 ) {
						?>
							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-upload"></i></div>
								Upload Timelogs
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="far fa-clock"></i></div>
								Daily TIme Record (DTR)
							</a>

							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="far fa-folder-open"></i></div>
								DocTrack
							</a>
						<?php
							}
						?>
							
						<?php
							if ( $disp_ulevel == 1) {
						?>
							<a class="nav-link" href="#">
								<div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
								DTR Builder
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
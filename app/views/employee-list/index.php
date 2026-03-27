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

		/* Floating popup for changing PIN */
		.change-pin-popup {
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
			z-index: 1061;
		}

		/* Fallback for smaller screens */
		@media (max-width: 1100px) {
			.add-id-popup {
				right: 0;
				top: 50px;
				border: 2px solid #198754;
			}
			.change-pin-popup {
				right: 0;
				top: 50px;
				border: 2px solid #ffc107;
			}
		}

		/* Signature Studio Styles */
		.canvas-wrap {
			position: relative;
			background-image:
				linear-gradient(45deg, #e9ecef 25%, transparent 25%),
				linear-gradient(-45deg, #e9ecef 25%, transparent 25%),
				linear-gradient(45deg, transparent 75%, #e9ecef 75%),
				linear-gradient(-45deg, transparent 75%, #e9ecef 75%);
			background-size: 16px 16px;
			background-position: 0 0, 0 8px, 8px -8px, -8px 0px;
			border-radius: 0.375rem;
			overflow: hidden;
			overscroll-behavior: none;
			width: 100%;
			height: clamp(220px, 45vh, 400px); 
			touch-action: none;
		}
		.canvas-placeholder {
			position: absolute; inset: 0;
			display: flex; align-items: center; justify-content: center;
			pointer-events: none; z-index: 1;
		}
		#sigCanvas {
			display: block;
			width: 100%;
			height: 100%; 
			touch-action: none;
			-webkit-user-select: none;
			user-select: none;
			background: transparent;
		}

		#cameraVideo {
			width: 100%; max-height: 200px;
			object-fit: cover; background: #000;
			border-radius: 0.375rem;
		}

		.swatch {
			width: 32px; height: 32px;
			border-radius: 50%;
			border: 2px solid #dee2e6;
			cursor: pointer;
			transition: transform .15s;
			padding: 0;
			min-width: 32px;
		}
		.swatch.active { border: 3px solid #0d6efd; transform: scale(1.15); }

		.nav-tabs .nav-link {
			color: #6c757d;
			padding: 0.6rem 0.9rem;
			font-size: 0.875rem;
		}
		.nav-tabs .nav-link.active { font-weight: 600; color: #212529; }

		.upload-drop {
			cursor: pointer;
			border: 2px dashed #ced4da !important;
			transition: background .2s;
			min-height: 100px;
		}
		.upload-drop:hover, .upload-drop:active { background-color: #f8f9fa !important; }

		.bg-card .card-body { padding: 0.5rem 0.75rem; }
		.bg-card .form-range { min-width: 60px; }

		.sig-label {
			font-size: 10px;
			letter-spacing: 2px;
			text-transform: uppercase;
		}
		
		.btn-crop-active {
			background-color: #0d6efd !important;
			color: white !important;
			border-color: #0d6efd !important;
		}

		@media (max-width: 575.98px) {
			.btn-cam { padding: 0.5rem 1rem; }
		}

		.sticky-col {
			position: -webkit-sticky;
			position: sticky;
			left: 0;
		}

		/* Hover Profile Card */
		#hoverProfileCard {
			position: fixed;
			display: none;
			z-index: 9999;
			width: 320px;
			pointer-events: none; /* Prevents flickering if mouse hits the card */
		}
	</style>

	<div class="container-fluid">
		<div class="pt-3">
			<div class="table-responsive">
				<table id="listRecView" class="table table-dark table-striped table-hover">
					<thead id="remSortH">
						<tr>
							<th class="remove-dropdown"></th> <th class="remove-dropdown"></th> <th class="remove-dropdown"></th> <th class="remove-dropdown"></th> <th class="remove-dropdown"></th> <th class="remove-dropdown"></th> <th></th> <th></th> <th></th> <th class="remove-dropdown"></th> <th class="remove-dropdown"></th> <th class="remove-dropdown"></th> <th class="remove-dropdown"></th> <th class="remove-dropdown"></th> <th></th> <th></th> <th class="remove-dropdown"></th> </tr>
					</thead>

					<thead id="theadtitle">
						<tr>
							<th>No.</th>
							<th>ImgData</th>
							<th>EmployeeID</th>
							<th>Nickname</th>
							<th class="sticky-col">FullName</th>
							<th>Phone</th>
							<th>Designation</th>
							<th>Office</th>
							<th>Type</th>
							<th>Birthday</th>
							<th>Email</th>
							<th>Profile ID</th>
							<th>UserID</th>
							<th>Date</th>
							<th>SlingID</th>
							<th>PocketID</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						<?php 

							include_once "model/employee/index.php";
							$employeeAcctx = new employeeAcct();
							$settedofficeid = $_SESSION['d2s8wu_officeid'];

							if ( $employeeAcctx->fn_ListEmployee($settedofficeid) ) {
								
								$employeeAcctx->fn_ListEmployee($settedofficeid);

								$xno_oo = 0;
								for ($i = 0; $i < count($employeeAcctx->list_empidcodeee); $i++) {
									$xno_oo = $xno_oo + 1;
									$profileautoid_oo = isset($employeeAcctx->list_profileidee[$i]) ? $employeeAcctx->list_profileidee[$i] : null;
									$birthday_oo = isset($employeeAcctx->list_birthdayee[$i]) ? $employeeAcctx->list_birthdayee[$i] : null;
									$email_oo = isset($employeeAcctx->list_empemailee[$i]) ? $employeeAcctx->list_empemailee[$i] : null;
									$mobile_oo = isset($employeeAcctx->list_mphoneee[$i]) ? $employeeAcctx->list_mphoneee[$i] : null;
									$employeeid_oo = isset($employeeAcctx->list_empidcodeee[$i]) ? $employeeAcctx->list_empidcodeee[$i] : null;
									$xdesignation_oo = isset($employeeAcctx->list_designationforidee[$i]) ? $employeeAcctx->list_designationforidee[$i] : null;
									$xoffice_oo = isset($employeeAcctx->list_officenameee[$i]) ? $employeeAcctx->list_officenameee[$i] : null;
									$xtype_oo = isset($employeeAcctx->list_typeemployeeee[$i]) ? $employeeAcctx->list_typeemployeeee[$i] : null;
									$xcreatedatee_oo = isset($employeeAcctx->list_createdatee[$i]) ? $employeeAcctx->list_createdatee[$i] : null;
									
									// Fetch activated status (default to 0 if not found)
									$is_actived = isset($employeeAcctx->list_activatedee[$i]) ? $employeeAcctx->list_activatedee[$i] : 0;

									$xslingid_oo = isset($employeeAcctx->list_slingidee[$i]) ? $employeeAcctx->list_slingidee[$i] : null;
									$xpocket_oo = isset($employeeAcctx->list_pocketidee[$i]) ? $employeeAcctx->list_pocketidee[$i] : null;

									if ( $xslingid_oo == 1) {
										$xslingid_status = "Released";
									} elseif ( $xslingid_oo == null || empty($xslingid_oo) || $xslingid_oo == 0 ) {
										$xslingid_status = "Pending";
									}

									if ( $xpocket_oo == 1) {
										$xpocket_status = "Released";
									} elseif ( $xpocket_oo == null || empty($xpocket_oo) || $xpocket_oo == 0 ) {
										$xpocket_status = "Pending";
									}

									// Setup the Active/Inactive button to pass "this" for instant UI updates
									$activeBtn = ($is_actived == 1) 
										? '<button type="button" class="btn btn-success btn-sm" onclick="toggleStatus(\''.$employeeid_oo.'\', 0, this)" title="Active - Click to Deactivate"><i class="fas fa-check"></i></button>'
										: '<button type="button" class="btn btn-danger btn-sm" onclick="toggleStatus(\''.$employeeid_oo.'\', 1, this)" title="Inactive - Click to Activate"><i class="fas fa-times"></i></button>';

									// Added data-* attributes and mouse events to the TR for the hover effect
									echo '<tr style="cursor: pointer;"
												data-empid="'.$employeeid_oo.'"
												data-name="'.htmlspecialchars($employeeAcctx->list_empnameforidee[$i], ENT_QUOTES).'"
												data-nickname="'.htmlspecialchars($employeeAcctx->list_nicknameee[$i], ENT_QUOTES).'"
												data-designation="'.htmlspecialchars(trim($xdesignation_oo), ENT_QUOTES).'"
												data-office="'.htmlspecialchars(trim($xoffice_oo), ENT_QUOTES).'"
												data-bday="'.htmlspecialchars(trim(date($birthday_oo)), ENT_QUOTES).'"
												data-phone="'.htmlspecialchars(trim($mobile_oo), ENT_QUOTES).'"
												data-email="'.htmlspecialchars(trim($email_oo), ENT_QUOTES).'"
												onmousemove="showHoverCard(event, this)"
												onmouseleave="hideHoverCard()">';
										echo '<td>'.$xno_oo.'</td>';
										echo '<td class="text-center">
												<img src="'.$pixloc.'public/employeeID/'.$employeeid_oo.'.jpeg" style="width: auto; max-width: 50px; height: 50px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#myModalImage" onclick="fnDisplayImage(\''.$pixloc.'public/employeeID/'.$employeeid_oo.'.jpeg\')">
											  </td>';
										echo '<td>'.$employeeid_oo.'</td>';
										echo '<td class="text-nowrap">'.$employeeAcctx->list_nicknameee[$i].'</td>';
										echo '<td class="text-nowrap sticky-col">'.$employeeAcctx->list_empnameforidee[$i].'</td>';
										echo '<td><a href="tel:+63'.trim($mobile_oo).'">'.trim($mobile_oo).'</a></td>';
										echo '<td class="text-nowrap">'.trim($xdesignation_oo).'</td>';
										echo '<td class="text-nowrap">'.trim($xoffice_oo).'</td>';
										echo '<td>'.trim($xtype_oo).'</td>';
										echo '<td class="text-nowrap">'.trim(date($birthday_oo)).'</td>';
										echo '<td>'.trim($email_oo).'</td>';
										echo '<td>'.$profileautoid_oo.'</td>';
										echo '<td>'.$employeeAcctx->list_uidee[$i].'</td>';
										echo '<td class="text-nowrap">'.$xcreatedatee_oo.'</td>';
										echo '<td class="text-center align-middle">
												'.$xslingid_status.'
											</td>';
										echo '<td class="text-center align-middle">'.$xpocket_status.'</td>';
										echo '<td>
												<div class="d-flex gap-1" onmousemove="event.stopPropagation()">
													'.$activeBtn.'
													<button type="button" class="btn btn-primary btn-sm" onclick="openInfoModal(\''.$employeeid_oo.'\', \''.$profileautoid_oo.'\')">Info</button>
													<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#myModalSign" onclick="fnDisplaySignature(\'public/employee_sign/'.$employeeid_oo.'.png\','.$employeeid_oo.')">Sign</button>
												</div>
											</td>';
									echo '</tr>';
								}
							} else {
								echo '<tr>';
									echo '<td colspan="10">No Record Found.</td>';
								echo '</tr>';
							}

						?>
					</tbody>

					<tfoot>
						<tr>
							<td class="remove-dropdown"></td> <td class="remove-dropdown"></td> <td class="remove-dropdown"></td> <td class="remove-dropdown"></td> <td class="remove-dropdown"></td> <td class="remove-dropdown"></td> <td class="remove-dropdown"></td> <td></td> <td></td> <td class="remove-dropdown"></td> <td class="remove-dropdown"></td> <td class="remove-dropdown"></td> <td class="remove-dropdown"></td> <td class="remove-dropdown"></td> <td></td> <td></td> <td class="remove-dropdown"></td> </tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>

	<div id="hoverProfileCard" class="card shadow-lg bg-light text-dark">
		<div class="row g-0">
			<div class="col-4 bg-dark d-flex align-items-center justify-content-center border-end">
				<img id="hoverImg" src="" class="img-fluid rounded-start" style="object-fit: cover; width: 100%; height: 100%; max-height: 160px;" onerror="this.onerror=null; this.src='https://via.placeholder.com/100x120?text=No+Image';">
			</div>
			<div class="col-8">
				<div class="card-body p-2" style="font-size: 0.75rem;">
					<h6 id="hoverName" class="mb-1 text-primary fw-bold text-truncate"></h6>
					<div class="mb-1 text-muted fw-bold border-bottom pb-1"><i class="fas fa-id-badge me-1"></i> <span id="hoverEmpId"></span></div>
					<div class="mb-0 text-truncate"><i class="fas fa-user me-1 text-secondary"></i> <span id="hoverNickname"></span></div>
					<div class="mb-0 text-truncate"><i class="fas fa-briefcase me-1 text-secondary"></i> <span id="hoverDesignation"></span></div>
					<div class="mb-0 text-truncate"><i class="fas fa-building me-1 text-secondary"></i> <span id="hoverOffice"></span></div>
					<div class="mb-0 text-truncate"><i class="fas fa-birthday-cake me-1 text-secondary"></i> <span id="hoverBday"></span></div>
					<div class="mb-0 text-truncate"><i class="fas fa-phone me-1 text-secondary"></i> <span id="hoverPhone"></span></div>
					<div class="mb-0 text-truncate"><i class="fas fa-envelope me-1 text-secondary"></i> <span id="hoverEmail"></span></div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade text-dark" id="myModalInfo" aria-hidden="true">
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

				<div id="changePinPopup" class="change-pin-popup border-warning border-top-0 border-end-0 border-bottom-0 border-4">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h6 class="mb-0 fw-bold">Change PIN</h6>
						<button type="button" class="btn-close" aria-label="Close" onclick="hideChangePinPopup();"></button>
					</div>
					<div class="mb-3">
						<label for="newPinInput" class="form-label mb-1" style="font-size: 0.9rem;">New PIN (Max 6 Digits)</label>
						<input type="password" class="form-control form-control-sm" id="newPinInput" maxlength="6" placeholder="Enter new PIN" oninput="validatePinInput(this)">
						<small id="pinErrorText" class="text-danger fw-bold" style="display:none; font-size: 0.75rem;">Numbers only allowed!</small>
					</div>
					<div class="mb-4">
						<label for="confirmPinInput" class="form-label mb-1" style="font-size: 0.9rem;">Confirm PIN</label>
						<input type="password" class="form-control form-control-sm" id="confirmPinInput" maxlength="6" placeholder="Confirm PIN" oninput="validatePinInput(this)">
					</div>
					<button type="button" class="btn btn-warning btn-sm w-100 fw-bold" onclick="changePin()">Save New PIN</button>
				</div>

				<div class="modal-header">
					<h5 class="modal-title" id="profileModalLabel">Profile Details</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<form id="profileForm" onsubmit="saveDetails(event)">
					<div class="modal-body">
						
						<input type="hidden" id="currentEmpId" value="">
						<input type="hidden" id="currentProfId" value="">

						<div class="row mb-3">
							
							<div class="col-md-3 text-center border-end">
								<img id="profileModalImg" src="" alt="Profile Image" class="img-thumbnail shadow-sm mb-2" style="width: 1.5in; height: 1.5in; object-fit: cover; border-radius: 50%;">
								<button type="button" class="btn btn-sm btn-outline-primary w-100 mt-1 fw-bold" onclick="document.getElementById('profileImageInput').click()">
									<i class="fas fa-camera me-1"></i> Update
								</button>
								<input type="file" id="profileImageInput" accept="image/jpeg, image/png" class="d-none" onchange="uploadProfileImage(event)">
							</div>

							<div class="col-md-9">
								<div class="d-flex justify-content-between align-items-center mb-2">
									<h6 class="mb-0">Identification Cards</h6>
									<button type="button" class="btn btn-sm btn-success" onclick="showAddIdPopup()">
										<i class="fas fa-plus me-1"></i> Add New ID
									</button>
								</div>

								<div class="border rounded p-2" style="max-height: 1.5in; overflow-y: auto;">
									<table class="table table-bordered table-striped table-sm mb-0" id="idTable">
										<thead class="table-light sticky-top">
											<tr>
												<th>ID Type</th>
												<th>ID Number</th>
												<th style="width: 50px;" class="text-center">Action</th>
											</tr>
										</thead>
										<tbody id="idTableBody"></tbody>
									</table>
								</div>
							</div>
						</div>

						<hr class="my-4">

						<h6 class="mb-3">Personal Information</h6>
						
						<div class="row g-2 mb-3">
							<div class="col-md-12">
								<label for="fullNameInput" class="form-label">Full Name</label>
								<input type="text" class="form-control" id="fullNameInput" placeholder="Full Name" value="">
							</div>
						</div>

						<div class="row g-2 mb-3">
							<div class="col-md-4">
								<label for="nicknameInput" class="form-label">Nickname</label>
								<input type="text" class="form-control" id="nicknameInput" placeholder="Nickname" value="">
							</div>
							<div class="col-md-4">
								<label for="designationInput" class="form-label">Designation</label>
								<input type="text" class="form-control" id="designationInput" placeholder="Designation" value="">
							</div>
							<div class="col-md-4">
								<label for="officeInput" class="form-label">Office</label>
								<input type="text" class="form-control" id="officeInput" placeholder="Office" value="">
							</div>
						</div>
						<div class="mb-4">
							<label for="birthplaceInput" class="form-label">Address</label>
							<input type="text" class="form-control" id="birthplaceInput" placeholder="Enter city/province of Address" value="">
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

						<hr class="my-4">

						<div class="d-flex justify-content-between align-items-center mb-3">
							<h6 class="mb-0">Account Security</h6>
							<button type="button" class="btn btn-sm btn-warning" onclick="showChangePinPopup()">
								Change PIN
							</button>
						</div>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save Details</button>
					</div>
				</form>

			</div>
		</div>
	</div>

	<div class="modal" id="myModalImage">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<div class="modal-body m-auto text-center">
					<img id="imgofemployee" style="width: 100%; max-width: 300px; margin: auto; text-align: center;">
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
				</div>

			</div>
		</div>
	</div>

	<div class="modal fade text-dark" id="myModalSign" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg position-relative">
			<div class="modal-content text-start">

				<div class="modal-header">
					<div>
						<h5 class="modal-title" id="sigModalLabel">
							<i class="bi bi-vector-pen me-2"></i>Signature Studio
						</h5>
						<p class="mb-0 text-muted" style="font-size:12px;">EmployeeID: <span id="displayemployeeid" class="fw-bold"></span></p>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<div class="modal-body px-3 px-sm-4 py-3">
					
					<div class="text-center mb-3 border-bottom pb-3">
						<p class="w-100 text-center text-muted mb-1" style="font-size:12px;">Current Signature Preview</p>
						<img id="displayempsign" style="max-width: 250px; height: 100px; border: 1px solid #ced4da; border-radius: 4px; object-fit: contain;">
					</div>

					<ul class="nav nav-tabs mb-3" id="modeTabs">
						<li class="nav-item flex-fill text-center">
							<button class="nav-link active w-100" type="button" onclick="setMode('draw')">
								<i class="bi bi-pencil me-1"></i>Draw
							</button>
						</li>
						<li class="nav-item flex-fill text-center">
							<button class="nav-link w-100" type="button" onclick="setMode('upload')">
								<i class="bi bi-upload me-1"></i>Upload
							</button>
						</li>
						<li class="nav-item flex-fill text-center">
							<button class="nav-link w-100" type="button" onclick="setMode('camera')">
								<i class="bi bi-camera me-1"></i>Camera
							</button>
						</li>
					</ul>

					<div id="drawControls" class="mb-3">
						<div class="d-flex align-items-center gap-2 flex-wrap row-gap-2">
							<div class="d-flex gap-2 flex-wrap" id="swatches"></div>
							<div class="d-flex align-items-center gap-2 ms-auto">
								<label class="form-label mb-0 small text-muted">Size</label>
								<input type="range" class="form-range" id="penSize" min="1" max="12" step="0.5" value="3.5"
									style="width:70px" oninput="document.getElementById('sizeVal').textContent=this.value">
								<span class="small text-muted" id="sizeVal" style="min-width:20px">3.5</span>
								<label class="form-label mb-0 small text-muted ms-2" title="Higher = smoother strokes for shaky hands">
									<i class="bi bi-hand-index me-1"></i>Stabilizer
								</label>
								<input type="range" class="form-range" id="stabilizerSlider" min="0" max="0.97" step="0.01" value="0.82"
									style="width:70px" oninput="updateStabilizer(this.value)">
								<span class="small text-muted" id="stabVal" style="min-width:24px">82%</span>
							</div>
						</div>
					</div>

					<div id="uploadArea" class="mb-3 d-none">
						<div class="rounded p-4 text-center bg-white upload-drop"
							onclick="document.getElementById('fileInput').click()">
							<input type="file" id="fileInput" accept="image/*" capture="environment" class="d-none" onchange="handleUpload(event)">
							<i class="bi bi-image fs-1 text-secondary d-block mb-2"></i>
							<p class="mb-0 text-secondary small">Tap to upload or take a photo</p>
							<p class="mb-0 text-muted" style="font-size:11px">PNG, JPG, GIF supported</p>
						</div>
					</div>

					<div id="cameraArea" class="mb-3 d-none">
						<video id="cameraVideo" class="mb-2 d-block border" autoplay playsinline muted></video>
						<div class="d-flex gap-2 justify-content-center flex-wrap">
							<button class="btn btn-dark btn-sm btn-cam" type="button" id="btnStart" onclick="startCamera()">
								<i class="bi bi-play-fill me-1"></i>Start Camera
							</button>
							<button class="btn btn-warning btn-sm btn-cam d-none" type="button" id="btnSnap" onclick="snapPhoto()">
								<i class="bi bi-camera-fill me-1"></i>Capture
							</button>
							<button class="btn btn-secondary btn-sm btn-cam d-none" type="button" id="btnStop" onclick="stopCamera()">
								<i class="bi bi-stop-fill me-1"></i>Stop
							</button>
						</div>
						<p class="text-muted text-center mt-2 mb-0" style="font-size:11px" id="camStatus">Camera is off</p>
					</div>

					<div class="card bg-light border bg-card d-none mb-2" id="imageControlsCard">
						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
								<h6 class="small mb-0 fw-bold text-secondary" id="transformHintText"><i class="bi bi-arrows-move me-1"></i>Touch & Drag Canvas to Adjust</h6>
								<div class="d-flex gap-1">
									<button class="btn btn-sm btn-outline-primary py-0" type="button" id="btnCropToggle" onclick="toggleCropMode()" style="font-size: 12px;">
										<i class="bi bi-crop"></i> Crop
									</button>
									<button class="btn btn-sm btn-outline-secondary py-0" type="button" onclick="resetOriginalImage()" style="font-size: 12px;">
										<i class="bi bi-arrow-counterclockwise"></i> Reset
									</button>
									<button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#slidersCollapse" style="font-size: 12px;">
										Sliders <i class="bi bi-chevron-down ms-1"></i>
									</button>
								</div>
							</div>
							
							<div class="collapse" id="slidersCollapse">
								<div class="row g-2 align-items-center small mt-1">
									<div class="col-6 col-sm-3">
										<label class="text-muted mb-0 d-block">Zoom</label>
										<input type="range" class="form-range" id="imgZoom" min="0.1" max="3" step="0.05" value="1" oninput="updateImgTransformFromSliders()">
									</div>
									<div class="col-6 col-sm-3">
										<label class="text-muted mb-0 d-block">Left / Right</label>
										<input type="range" class="form-range" id="imgX" min="-500" max="500" value="0" oninput="updateImgTransformFromSliders()">
									</div>
									<div class="col-6 col-sm-3">
										<label class="text-muted mb-0 d-block">Up / Down</label>
										<input type="range" class="form-range" id="imgY" min="-500" max="500" value="0" oninput="updateImgTransformFromSliders()">
									</div>
									<div class="col-6 col-sm-3">
										<label class="text-muted mb-0 d-block">Rotate</label>
										<input type="range" class="form-range" id="imgRot" min="-180" max="180" value="0" oninput="updateImgTransformFromSliders()">
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="canvas-wrap border mb-1" id="canvasWrap">
						<div class="canvas-placeholder" id="placeholder">
							<span class="text-muted fst-italic small" id="placeholderText">Sign here…</span>
						</div>
						<canvas id="sigCanvas"></canvas>
					</div>
					<p class="text-warning border-top border-warning pt-1 mb-3 sig-label">Authorized Signature</p>

					<div class="card bg-light border bg-card d-none" id="bgRemovalCard">
						<div class="card-body">
							<div class="d-flex align-items-center flex-wrap gap-2 row-gap-2">
								<div class="form-check mb-0">
									<input class="form-check-input" type="checkbox" id="chkAutoBg" onchange="renderImage()">
									<label class="form-check-label small" for="chkAutoBg">Auto-remove BG</label>
								</div>
								<div class="d-flex align-items-center gap-2 ms-auto">
									<span class="small text-muted">Tolerance</span>
									<input type="range" class="form-range" id="toleranceSlider" min="10" max="120" value="40"
										style="width:65px" oninput="document.getElementById('tolVal').textContent=this.value; renderImage();">
									<span class="small text-muted" id="tolVal" style="min-width:24px">40</span>
								</div>
								<button class="btn btn-dark btn-sm" type="button" id="btnRemoveBg" onclick="applyBgRemoval(); renderImage(true, false);" disabled>
									<i class="bi bi-scissors me-1"></i>Force BG Removal
								</button>
							</div>
						</div>
					</div>

				</div>

				<div class="modal-footer bg-light py-2 gap-2 flex-nowrap">
					<div class="d-flex gap-2">
						<button class="btn btn-outline-secondary btn-sm" type="button" onclick="clearCanvas()">
							<i class="bi bi-trash"></i>
							<span class="d-none d-sm-inline ms-1">Clear</span>
						</button>
						<button class="btn btn-outline-warning btn-sm" type="button" id="btnUndoStroke" onclick="undoStroke()" disabled>
							<i class="bi bi-arrow-counterclockwise"></i>
							<span class="d-none d-sm-inline ms-1">Undo</span>
						</button>
					</div>
					<div class="d-flex gap-2 ms-auto">
						<button class="btn btn-secondary btn-sm" type="button" data-bs-dismiss="modal">
							<i class="bi bi-x"></i>
							<span class="d-none d-sm-inline ms-1">Cancel</span>
						</button>
						<button class="btn btn-primary btn-sm" type="button" id="signsaveempl" disabled onclick="saveSignature()">
							<i class="bi bi-floppy"></i>
							<span class="ms-1">Save Signature</span>
						</button>
					</div>
				</div>

			</div>
		</div>
	</div>

	<script>
		// ==========================================
		// FIXED: HOVER PROFILE CARD LOGIC
		// ==========================================
		const hoverCard = document.getElementById('hoverProfileCard');
		let currentHoverId = null;

		function showHoverCard(event, rowElement) {
			const empId = rowElement.getAttribute('data-empid');

			if (currentHoverId !== empId) {
				currentHoverId = empId;
				
				const imgPath = '<?php echo trim($pixloc); ?>public/employeeID/' + empId + '.jpeg?t=' + new Date().getTime();
				
				document.getElementById('hoverImg').src = imgPath;
				document.getElementById('hoverEmpId').innerText = empId;
				document.getElementById('hoverName').innerText = rowElement.getAttribute('data-name');
				document.getElementById('hoverNickname').innerText = rowElement.getAttribute('data-nickname') || 'N/A';
				document.getElementById('hoverDesignation').innerText = rowElement.getAttribute('data-designation') || 'N/A';
				document.getElementById('hoverOffice').innerText = rowElement.getAttribute('data-office') || 'N/A';
				document.getElementById('hoverBday').innerText = rowElement.getAttribute('data-bday') || 'N/A';
				document.getElementById('hoverPhone').innerText = rowElement.getAttribute('data-phone') || 'N/A';
				document.getElementById('hoverEmail').innerText = rowElement.getAttribute('data-email') || 'N/A';
			}

			hoverCard.style.display = 'block';

			let x = event.clientX + 15;
			let y = event.clientY + 15;

			const rect = hoverCard.getBoundingClientRect();
			
			if (x + rect.width > window.innerWidth) {
				x = event.clientX - rect.width - 15;
			}
			if (y + rect.height > window.innerHeight) {
				y = event.clientY - rect.height - 15;
			}

			hoverCard.style.left = x + 'px';
			hoverCard.style.top = y + 'px';
		}

		function hideHoverCard() {
			hoverCard.style.display = 'none';
			currentHoverId = null;
		}

		// ==========================================
		// TOGGLE ACTIVATION LOGIC (NO REFRESH)
		// ==========================================
		function toggleStatus(empId, newStatus, btnElement) {
			const actionText = newStatus === 1 ? "Activate" : "Deactivate";
			
			if (!confirm(`Do you want to ${actionText} this Employee?`)) {
				return; 
			}

			const originalHTML = btnElement.innerHTML;
			btnElement.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
			btnElement.disabled = true;

			const formData = new FormData();
			formData.append('employeeid', empId);
			formData.append('actived', newStatus);

			fetch('model/employee/toggle-status.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.text())
			.then(data => {
				btnElement.disabled = false;
				
				if (newStatus === 1) {
					btnElement.classList.remove('btn-danger');
					btnElement.classList.add('btn-success');
					btnElement.innerHTML = '<i class="fas fa-check"></i>';
					btnElement.setAttribute('onclick', `toggleStatus('${empId}', 0, this)`);
					btnElement.setAttribute('title', 'Active - Click to Deactivate');
				} else {
					btnElement.classList.remove('btn-success');
					btnElement.classList.add('btn-danger');
					btnElement.innerHTML = '<i class="fas fa-times"></i>';
					btnElement.setAttribute('onclick', `toggleStatus('${empId}', 1, this)`);
					btnElement.setAttribute('title', 'Inactive - Click to Activate');
				}
			})
			.catch(error => {
				console.error('Error updating status:', error);
				alert('Failed to update status.');
				btnElement.innerHTML = originalHTML;
				btnElement.disabled = false;
			});
		}


		// ==========================================
		// IMAGE AND BASIC MODAL FUNCTIONS
		// ==========================================
		function fnDisplayImage(imgSrc) {
			const modalImg = document.getElementById('imgofemployee');
			if (modalImg) modalImg.src = imgSrc;
		}

		let currentSignEmpId = null;

		function fnDisplaySignature(imgSrc, emplNo) {
			currentSignEmpId = emplNo;
			const displayID = document.getElementById('displayemployeeid');
			const displayImg = document.getElementById('displayempsign');
			
			displayID.innerText = emplNo;
			
			const timeStamp = new Date().getTime();
			const cleanSrc = imgSrc.split('?')[0];
			displayImg.src = cleanSrc + '?t=' + timeStamp;
			
			clearCanvas();
			setMode('draw');
		}

		document.getElementById('myModalSign').addEventListener('shown.bs.modal', function () {
			initCanvas();
		});

		document.getElementById('myModalSign').addEventListener('hidden.bs.modal', function () {
			if (mode === 'camera') stopCamera();
		});


		// ==========================================
		// SIGNATURE STUDIO LOGIC
		// ==========================================
		const sigCanvas = document.getElementById('sigCanvas');
		const sigCtx    = sigCanvas.getContext('2d', { willReadFrequently: true });
		let isDrawing = false, isEmpty = true, mode = 'draw';
		let penColor = '#000000';
		
		let currentCSSWidth = 0;
		let currentCSSHeight = 0;

		let originalRawImg = null; 
		let currentRawImg = null; 
		let imgTransform = { scale: 1, x: 0, y: 0, rotate: 0 };

		let isTransforming = false;
		let activeHandle = null; 
		let startX = 0, startY = 0;
		let startTransform = {};
		let startPinchDist = 0;

		let isCropMode = false;
		let isCroppingDrag = false;
		let cropStart = {x: 0, y: 0};
		let cropRect = null; 

		let points = [];
		let allStrokes = []; 
		let STABILIZER = 0.82;

		function updateStabilizer(v) {
			STABILIZER = parseFloat(v);
			document.getElementById('stabVal').textContent = Math.round(v * 100) + '%';
		}
		const SMOOTH_WINDOW = 6;

		function midPoint(p1, p2) { return { x: (p1.x + p2.x) / 2, y: (p1.y + p2.y) / 2 }; }
		function smoothedPoint(pts) {
			const window = pts.slice(-SMOOTH_WINDOW);
			const avg = window.reduce((acc, p) => ({ x: acc.x + p.x, y: acc.y + p.y }), { x: 0, y: 0 });
			return { x: avg.x / window.length, y: avg.y / window.length };
		}

		let lazyX = 0, lazyY = 0;
		const colors = ['#000000','#1a1a2e','#198754','#0d6efd','#dc3545','#6f42c1'];

		window.addEventListener('resize', () => {
			if (document.getElementById('myModalSign').classList.contains('show')) {
				initCanvas();
				if (mode === 'draw') redrawAllStrokes();
				else if (currentRawImg) renderImage();
			}
		});

		function initCanvas() {
			const wrap = document.getElementById('canvasWrap');
			const dpr  = window.devicePixelRatio || 1;
			const w    = wrap.clientWidth;
			const h    = wrap.clientHeight; 
			
			if (currentCSSWidth > 0 && currentCSSHeight > 0 && (currentCSSWidth !== w || currentCSSHeight !== h)) {
				const scaleX = w / currentCSSWidth;
				const scaleY = h / currentCSSHeight;
				
				allStrokes.forEach(s => {
					s.pts.forEach(p => { p.x *= scaleX; p.y *= scaleY; });
					s.size *= Math.min(scaleX, scaleY); 
				});
				
				imgTransform.x *= scaleX;
				imgTransform.y *= scaleY;
				imgTransform.scale *= Math.min(scaleX, scaleY);
				syncSlidersToTransform();
			}
			
			currentCSSWidth = w;
			currentCSSHeight = h;

			sigCanvas.width  = w * dpr;
			sigCanvas.height = h * dpr;
			sigCtx.scale(dpr, dpr);
			sigCtx.lineCap  = 'round';
			sigCtx.lineJoin = 'round';
		}

		const swatchContainer = document.getElementById('swatches');
		if(swatchContainer) {
			colors.forEach(c => {
				const btn = document.createElement('button');
				btn.className = 'swatch' + (c === penColor ? ' active' : '');
				btn.style.background = c;
				btn.type = 'button';
				btn.onclick = () => {
					penColor = c;
					document.querySelectorAll('.swatch').forEach(s => s.classList.remove('active'));
					btn.classList.add('active');
				};
				swatchContainer.appendChild(btn);
			});
		}

		function getPos(e) {
			const rect = sigCanvas.getBoundingClientRect();
			const src  = e.touches ? e.touches[0] : e;
			return { x: src.clientX - rect.left, y: src.clientY - rect.top };
		}

		function getPinchDist(e) {
			const dx = e.touches[0].clientX - e.touches[1].clientX;
			const dy = e.touches[0].clientY - e.touches[1].clientY;
			return Math.sqrt(dx * dx + dy * dy);
		}

		function getTransformedPoint(x, y) {
			const dpr = window.devicePixelRatio || 1;
			const cx = (sigCanvas.width / dpr) / 2 + imgTransform.x;
			const cy = (sigCanvas.height / dpr) / 2 + imgTransform.y;
			const rad = imgTransform.rotate * Math.PI / 180;
			
			const dx = x - cx;
			const dy = y - cy;
			
			const unRotX = dx * Math.cos(-rad) - dy * Math.sin(-rad);
			const unRotY = dx * Math.sin(-rad) + dy * Math.cos(-rad);
			
			return { x: unRotX / imgTransform.scale, y: unRotY / imgTransform.scale };
		}

		function handlePointerDown(e) {
			if (mode === 'draw') return startDraw(e);
			
			if (!currentRawImg) return;
			e.preventDefault();

			if (isCropMode) {
				isCroppingDrag = true;
				const p = getPos(e);
				cropStart = p;
				cropRect = {x: p.x, y: p.y, w: 0, h: 0};
				return;
			}

			if (e.touches && e.touches.length === 2) {
				isTransforming = true;
				activeHandle = 'pinch';
				startPinchDist = getPinchDist(e);
				startTransform = { ...imgTransform };
				return;
			}

			const p = getPos(e);
			const lp = getTransformedPoint(p.x, p.y);
			
			const hw = currentRawImg.width / 2;
			const hh = currentRawImg.height / 2;
			
			const margin = Math.max(25 / imgTransform.scale, 20); 

			if (Math.abs(lp.x) < margin * 2 && lp.y < -hh && lp.y > -hh - 60 / imgTransform.scale) activeHandle = 'rotate';
			else if (Math.abs(lp.x - hw) < margin && Math.abs(lp.y - hh) < margin) activeHandle = 'br';
			else if (Math.abs(lp.x + hw) < margin && Math.abs(lp.y - hh) < margin) activeHandle = 'bl';
			else if (Math.abs(lp.x - hw) < margin && Math.abs(lp.y + hh) < margin) activeHandle = 'tr';
			else if (Math.abs(lp.x + hw) < margin && Math.abs(lp.y + hh) < margin) activeHandle = 'tl';
			else if (lp.x >= -hw && lp.x <= hw && lp.y >= -hh && lp.y <= hh) activeHandle = 'pan';
			else activeHandle = null;

			if (activeHandle) {
				isTransforming = true;
				startX = p.x;
				startY = p.y;
				startTransform = { ...imgTransform };
				sigCanvas.style.cursor = activeHandle === 'pan' ? 'grabbing' : 'crosshair';
			}
		}

		function handlePointerMove(e) {
			if (mode === 'draw') return draw(e);
			
			if (!currentRawImg) return;
			e.preventDefault();

			if (isCropMode && isCroppingDrag) {
				const p = getPos(e);
				cropRect.w = p.x - cropStart.x;
				cropRect.h = p.y - cropStart.y;
				renderImage();
				return;
			}

			if (!isTransforming || !activeHandle) return;

			if (activeHandle === 'pinch' && e.touches && e.touches.length === 2) {
				const dist = getPinchDist(e);
				const scaleDiff = dist / startPinchDist;
				imgTransform.scale = startTransform.scale * scaleDiff;
				syncSlidersToTransform();
				renderImage();
				return;
			}

			const p = getPos(e);
			const dx = p.x - startX;
			const dy = p.y - startY;

			if (activeHandle === 'pan') {
				imgTransform.x = startTransform.x + dx;
				imgTransform.y = startTransform.y + dy;
			} else if (activeHandle === 'rotate') {
				const dpr = window.devicePixelRatio || 1;
				const cx = (sigCanvas.width / dpr) / 2 + startTransform.x;
				const cy = (sigCanvas.height / dpr) / 2 + startTransform.y;
				const startAngle = Math.atan2(startY - cy, startX - cx);
				const currentAngle = Math.atan2(p.y - cy, p.x - cx);
				let angleDiff = (currentAngle - startAngle) * 180 / Math.PI;
				imgTransform.rotate = startTransform.rotate + angleDiff;
			} else if (activeHandle.length === 2) { 
				const dpr = window.devicePixelRatio || 1;
				const cx = (sigCanvas.width / dpr) / 2 + startTransform.x;
				const cy = (sigCanvas.height / dpr) / 2 + startTransform.y;
				const startDist = Math.hypot(startX - cx, startY - cy);
				const currentDist = Math.hypot(p.x - cx, p.y - cy);
				imgTransform.scale = startTransform.scale * (currentDist / startDist);
				if(imgTransform.scale < 0.05) imgTransform.scale = 0.05; 
			}

			syncSlidersToTransform();
			renderImage();
		}

		function handlePointerUp(e) {
			if (mode === 'draw') return stopDraw(e);
			
			if (isCropMode && isCroppingDrag) {
				isCroppingDrag = false;
				if (cropRect) {
					if (cropRect.w < 0) { cropRect.x += cropRect.w; cropRect.w = Math.abs(cropRect.w); }
					if (cropRect.h < 0) { cropRect.y += cropRect.h; cropRect.h = Math.abs(cropRect.h); }
					
					if (cropRect.w > 10 && cropRect.h > 10) {
						applyCrop();
					} else {
						cropRect = null;
						renderImage();
					}
				}
				return;
			}

			isTransforming = false;
			activeHandle = null;
			if (!isCropMode) sigCanvas.style.cursor = mode === 'draw' ? 'crosshair' : 'default';
		}

		function startDraw(e) {
			e.preventDefault();
			isDrawing = true;
			points = [];
			const p = getPos(e);
			lazyX = p.x; lazyY = p.y;
			points.push({ x: lazyX, y: lazyY });
			const penSize = parseFloat(document.getElementById('penSize').value);
			sigCtx.beginPath();
			sigCtx.arc(p.x, p.y, penSize / 2, 0, Math.PI * 2);
			sigCtx.fillStyle = penColor; sigCtx.fill();
			markUsed();
		}

		function draw(e) {
			if (!isDrawing) return;
			e.preventDefault();
			const raw = getPos(e);
			lazyX += (raw.x - lazyX) * (1 - STABILIZER);
			lazyY += (raw.y - lazyY) * (1 - STABILIZER);
			points.push({ x: lazyX, y: lazyY });
			const smoothed = smoothedPoint(points);
			if (points.length < 3) return;
			const penSize = parseFloat(document.getElementById('penSize').value);
			sigCtx.strokeStyle = penColor;
			sigCtx.lineWidth   = penSize;
			sigCtx.lineCap     = 'round';
			sigCtx.lineJoin    = 'round';
			const prev = smoothedPoint(points.slice(0, -1));
			const curr = smoothed;
			const mid  = midPoint(prev, curr);
			sigCtx.beginPath();
			sigCtx.moveTo(midPoint(smoothedPoint(points.slice(0, -2)), prev).x,
						midPoint(smoothedPoint(points.slice(0, -2)), prev).y);
			sigCtx.quadraticCurveTo(prev.x, prev.y, mid.x, mid.y);
			sigCtx.stroke();
		}

		function stopDraw(e) {
			if (!isDrawing) return;
			isDrawing = false;
			if (points.length >= 1) {
			const smoothed = chaikinSmooth(points, 3);
			const penSize = parseFloat(document.getElementById('penSize').value);
			allStrokes.push({ pts: smoothed, color: penColor, size: penSize });
			redrawAllStrokes();
			document.getElementById('btnUndoStroke').disabled = false;
			}
			points = [];
		}

		function chaikinSmooth(rawPts, passes) {
			let pts = rawPts.slice();
			for (let pass = 0; pass < passes; pass++) {
			const s = [pts[0]];
			for (let i = 0; i < pts.length - 1; i++) {
				const p0 = pts[i], p1 = pts[i + 1];
				s.push({ x: 0.75 * p0.x + 0.25 * p1.x, y: 0.75 * p0.y + 0.25 * p1.y });
				s.push({ x: 0.25 * p0.x + 0.75 * p1.x, y: 0.25 * p0.y + 0.75 * p1.y });
			}
			s.push(pts[pts.length - 1]);
			pts = s;
			}
			return pts;
		}

		function undoStroke() {
			if (allStrokes.length === 0) return;
			allStrokes.pop();
			if (allStrokes.length === 0) {
			clearCanvasOnly();
			} else {
			redrawAllStrokes();
			document.getElementById('btnUndoStroke').disabled = false;
			}
		}

		function clearCanvasOnly() {
			sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
			isEmpty = true;
			document.getElementById('placeholder').style.display = 'flex';
			document.getElementById('signsaveempl').disabled  = true;
			document.getElementById('btnUndoStroke').disabled = true;
		}

		function redrawAllStrokes() {
			sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
			allStrokes.forEach(stroke => drawStroke(stroke.pts, stroke.color, stroke.size));
		}

		function drawStroke(pts, color, size) {
			if (pts.length === 0) return;
			sigCtx.beginPath();
			sigCtx.strokeStyle = color;
			sigCtx.lineWidth   = size;
			sigCtx.lineCap     = 'round';
			sigCtx.lineJoin    = 'round';
			if (pts.length === 1) {
			sigCtx.arc(pts[0].x, pts[0].y, size / 2, 0, Math.PI * 2);
			sigCtx.fillStyle = color;
			sigCtx.fill();
			return;
			}
			sigCtx.moveTo(pts[0].x, pts[0].y);
			for (let i = 1; i < pts.length - 1; i++) {
			const mx = (pts[i].x + pts[i + 1].x) / 2;
			const my = (pts[i].y + pts[i + 1].y) / 2;
			sigCtx.quadraticCurveTo(pts[i].x, pts[i].y, mx, my);
			}
			sigCtx.lineTo(pts[pts.length - 1].x, pts[pts.length - 1].y);
			sigCtx.stroke();
		}

		sigCanvas.addEventListener('mousedown',  handlePointerDown);
		sigCanvas.addEventListener('mousemove',  handlePointerMove);
		sigCanvas.addEventListener('mouseup',    handlePointerUp);
		sigCanvas.addEventListener('mouseleave', handlePointerUp);
		sigCanvas.addEventListener('touchstart', handlePointerDown, { passive: false });
		sigCanvas.addEventListener('touchmove',  handlePointerMove, { passive: false });
		sigCanvas.addEventListener('touchend',   handlePointerUp);
		sigCanvas.addEventListener('touchcancel',handlePointerUp);

		function markUsed() {
			isEmpty = false;
			document.getElementById('placeholder').style.display = 'none';
			document.getElementById('signsaveempl').disabled   = false;
			document.getElementById('btnRemoveBg').disabled    = false;
			document.getElementById('btnUndoStroke').disabled  = (allStrokes.length === 0 && mode === 'draw');
		}

		function clearCanvas() {
			sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
			allStrokes = [];
			currentRawImg = null;
			originalRawImg = null;
			isCropMode = false;
			const cropBtn = document.getElementById('btnCropToggle');
			if(cropBtn) cropBtn.classList.remove('btn-crop-active');
			
			resetImgTransform();
			isEmpty = true; 
			document.getElementById('placeholder').style.display = 'flex';
			document.getElementById('signsaveempl').disabled   = true;
			document.getElementById('btnRemoveBg').disabled    = true;
			document.getElementById('btnUndoStroke').disabled  = true;
			document.getElementById('imageControlsCard').classList.add('d-none');
		}

		function setMode(m) {
			try {
				if (mode === 'camera' && m !== 'camera') {
					stopCamera();
				}
				mode = m;
				
				if (m !== 'draw' && allStrokes.length > 0 && !currentRawImg) {
					clearCanvas(); 
				} else if (m === 'draw') {
					if (currentRawImg) renderImage(false); 
					else if (allStrokes.length > 0) redrawAllStrokes();
					else clearCanvas();
				} else {
					clearCanvas();
				}

				document.querySelectorAll('#modeTabs .nav-link').forEach((btn, i) => {
				btn.classList.toggle('active', ['draw','upload','camera'][i] === m);
				});

				document.getElementById('drawControls').classList.toggle('d-none', m !== 'draw');
				document.getElementById('uploadArea').classList.toggle('d-none',   m !== 'upload');
				document.getElementById('cameraArea').classList.toggle('d-none',   m !== 'camera');
				
				const hasImg = currentRawImg !== null;
				document.getElementById('imageControlsCard').classList.toggle('d-none', m === 'draw' || !hasImg);
				document.getElementById('bgRemovalCard').classList.toggle('d-none', m === 'draw');

				const hints = { draw: 'Sign here…', upload: 'Tap to upload or take a photo', camera: 'Capture from camera…' };
				if (isEmpty) document.getElementById('placeholderText').textContent = hints[m];
				sigCanvas.style.cursor = m === 'draw' ? 'crosshair' : 'default';
			} catch(err) {
				console.error("setMode Error:", err);
			}
		}

		function resetImgTransform() {
			imgTransform = { scale: 1, x: 0, y: 0, rotate: 0 };
			syncSlidersToTransform();
		}

		function syncSlidersToTransform() {
			document.getElementById('imgZoom').value = imgTransform.scale;
			document.getElementById('imgX').value = imgTransform.x;
			document.getElementById('imgY').value = imgTransform.y;
			let rot = imgTransform.rotate % 360;
			if (rot > 180) rot -= 360;
			if (rot < -180) rot += 360;
			document.getElementById('imgRot').value = rot;
		}

		function updateImgTransformFromSliders() {
			imgTransform.scale = parseFloat(document.getElementById('imgZoom').value);
			imgTransform.x = parseInt(document.getElementById('imgX').value);
			imgTransform.y = parseInt(document.getElementById('imgY').value);
			imgTransform.rotate = parseInt(document.getElementById('imgRot').value);
			renderImage();
		}

		function setImage(img, isCropResult = false) {
			if (!isCropResult) originalRawImg = img;
			currentRawImg = img;
			resetImgTransform();
			
			const dpr = window.devicePixelRatio || 1;
			const fitScale = Math.min((sigCanvas.width/dpr) / img.width, (sigCanvas.height/dpr) / img.height) * 0.85;
			imgTransform.scale = fitScale;
			syncSlidersToTransform();
			
			if (mode !== 'draw') {
				document.getElementById('imageControlsCard').classList.remove('d-none');
			}
			renderImage();
			markUsed();
		}
		
		function resetOriginalImage() {
			if (originalRawImg) {
				if(isCropMode) toggleCropMode();
				setImage(originalRawImg, false);
			}
		}

		function toggleCropMode() {
			isCropMode = !isCropMode;
			const btn = document.getElementById('btnCropToggle');
			const hint = document.getElementById('transformHintText');
			if (isCropMode) {
				btn.classList.add('btn-crop-active');
				hint.innerHTML = '<i class="bi bi-crop me-1"></i>Draw a box to crop';
				sigCanvas.style.cursor = 'crosshair';
				cropRect = null;
			} else {
				btn.classList.remove('btn-crop-active');
				hint.innerHTML = '<i class="bi bi-arrows-move me-1"></i>Touch & Drag Canvas to Adjust';
				sigCanvas.style.cursor = 'default';
				cropRect = null;
			}
			renderImage();
		}

		function applyCrop() {
			renderImage(false, false, true); 

			const dpr = window.devicePixelRatio || 1;
			const cx = cropRect.x * dpr;
			const cy = cropRect.y * dpr;
			const cw = cropRect.w * dpr;
			const ch = cropRect.h * dpr;

			const cropCanvas = document.createElement('canvas');
			cropCanvas.width = cw;
			cropCanvas.height = ch;
			const cCtx = cropCanvas.getContext('2d');
			
			cCtx.drawImage(sigCanvas, cx, cy, cw, ch, 0, 0, cw, ch);

			const newImg = new Image();
			newImg.onload = () => {
				setImage(newImg, true); 
				toggleCropMode(); 
			};
			newImg.src = cropCanvas.toDataURL('image/png');
		}

		function drawTransformBoxOverlay(w, h) {
			sigCtx.save();
			sigCtx.translate(w / 2 + imgTransform.x, h / 2 + imgTransform.y);
			sigCtx.rotate(imgTransform.rotate * Math.PI / 180);
			sigCtx.scale(imgTransform.scale, imgTransform.scale);

			sigCtx.strokeStyle = '#0d6efd';
			sigCtx.lineWidth = 2 / imgTransform.scale; 
			const imgW = currentRawImg.width;
			const imgH = currentRawImg.height;

			sigCtx.strokeRect(-imgW/2, -imgH/2, imgW, imgH);

			const hs = 14 / imgTransform.scale;
			sigCtx.fillStyle = '#ffffff';

			const drawHandle = (x, y) => {
				sigCtx.fillRect(x - hs/2, y - hs/2, hs, hs);
				sigCtx.strokeRect(x - hs/2, y - hs/2, hs, hs);
			};
			drawHandle(-imgW/2, -imgH/2);
			drawHandle(imgW/2, -imgH/2);  
			drawHandle(-imgW/2, imgH/2);  
			drawHandle(imgW/2, imgH/2);   

			sigCtx.beginPath();
			sigCtx.moveTo(0, -imgH/2);
			sigCtx.lineTo(0, -imgH/2 - 40/imgTransform.scale);
			sigCtx.stroke();
			sigCtx.beginPath();
			sigCtx.arc(0, -imgH/2 - 40/imgTransform.scale, hs/2, 0, Math.PI*2);
			sigCtx.fill();
			sigCtx.stroke();

			sigCtx.restore();
		}

		function renderImage(drawBox = true, renderStrokes = true, bypassCropOverlay = false) {
			if (!currentRawImg) return;
			
			const dpr = window.devicePixelRatio || 1;
			const cssW = sigCanvas.width / dpr;
			const cssH = sigCanvas.height / dpr;
			
			sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
			
			sigCtx.save();
			sigCtx.translate(cssW / 2 + imgTransform.x, cssH / 2 + imgTransform.y);
			sigCtx.rotate(imgTransform.rotate * Math.PI / 180);
			sigCtx.scale(imgTransform.scale, imgTransform.scale);
			sigCtx.drawImage(currentRawImg, -currentRawImg.width / 2, -currentRawImg.height / 2);
			sigCtx.restore();

			if (document.getElementById('chkAutoBg').checked) applyBgRemoval(); 

			if (drawBox && mode !== 'draw' && !isCropMode) {
				drawTransformBoxOverlay(cssW, cssH);
			}
			
			if (isCropMode && !bypassCropOverlay) {
				sigCtx.fillStyle = 'rgba(0, 0, 0, 0.5)';
				sigCtx.fillRect(0, 0, cssW, cssH);
				
				if (cropRect) {
					sigCtx.clearRect(cropRect.x, cropRect.y, cropRect.w, cropRect.h);
					sigCtx.strokeStyle = '#ffffff';
					sigCtx.lineWidth = 2;
					sigCtx.setLineDash([5, 5]);
					sigCtx.strokeRect(cropRect.x, cropRect.y, cropRect.w, cropRect.h);
					sigCtx.setLineDash([]);
				} else {
					sigCtx.fillStyle = '#ffffff';
					sigCtx.font = "14px Arial";
					sigCtx.textAlign = "center";
					sigCtx.fillText("Click and drag to crop", cssW/2, cssH/2);
				}
			}

			if (renderStrokes && allStrokes.length > 0) {
				allStrokes.forEach(stroke => drawStroke(stroke.pts, stroke.color, stroke.size));
			}
		}

		function applyBgRemoval() {
			const tolerance = parseInt(document.getElementById('toleranceSlider').value);
			const imageData = sigCtx.getImageData(0, 0, sigCanvas.width, sigCanvas.height);
			const data      = imageData.data;

			function getPixel(x, y) {
			const i = (y * sigCanvas.width + x) * 4;
			return [data[i], data[i+1], data[i+2]];
			}
			const corners = [
			getPixel(0, 0), getPixel(sigCanvas.width - 1, 0),
			getPixel(0, sigCanvas.height - 1), getPixel(sigCanvas.width - 1, sigCanvas.height - 1)
			];
			const bgR = corners.reduce((s,c) => s + c[0], 0) / 4;
			const bgG = corners.reduce((s,c) => s + c[1], 0) / 4;
			const bgB = corners.reduce((s,c) => s + c[2], 0) / 4;

			for (let i = 0; i < data.length; i += 4) {
			if(data[i+3] === 0) continue; 
			
			const dist = Math.sqrt(
				Math.pow(data[i]   - bgR, 2) +
				Math.pow(data[i+1] - bgG, 2) +
				Math.pow(data[i+2] - bgB, 2)
			);
			if (dist < tolerance) {
				data[i + 3] = Math.round(Math.min(1, (dist / tolerance) * 3) * 255);
			}
			}
			sigCtx.putImageData(imageData, 0, 0);
		}

		function handleUpload(e) {
			const file = e.target.files[0];
			if (!file) return;
			const reader = new FileReader();
			reader.onload = ev => {
			const img = new Image();
			img.onload = () => setImage(img);
			img.src = ev.target.result;
			};
			reader.readAsDataURL(file);
			e.target.value = '';
		}

		let cameraStream = null;

		async function startCamera() {
			try {
			document.getElementById('btnStart').innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Starting...';
			cameraStream = await navigator.mediaDevices.getUserMedia({
				video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 } },
				audio: false
			});
			
			if (mode !== 'camera') {
				cameraStream.getTracks().forEach(t => t.stop());
				cameraStream = null;
				return;
			}

			document.getElementById('cameraVideo').srcObject = cameraStream;
			document.getElementById('btnStart').classList.add('d-none');
			document.getElementById('btnSnap').classList.remove('d-none');
			document.getElementById('btnStop').classList.remove('d-none');
			document.getElementById('camStatus').textContent = 'Camera active — tap Capture when ready';
			} catch (err) {
			document.getElementById('camStatus').textContent = '⚠ Camera access denied: ' + err.message;
			document.getElementById('btnStart').innerHTML = '<i class="bi bi-play-fill me-1"></i>Start Camera';
			}
		}

		function snapPhoto() {
			const video = document.getElementById('cameraVideo');
			const offscreen = document.createElement('canvas');
			offscreen.width = video.videoWidth;
			offscreen.height = video.videoHeight;
			const offCtx = offscreen.getContext('2d');
			offCtx.drawImage(video, 0, 0);
			
			const img = new Image();
			img.onload = () => {
				setImage(img);
				stopCamera();
				document.getElementById('camStatus').textContent = '✓ Photo captured';
			};
			img.src = offscreen.toDataURL('image/png');
		}

		function stopCamera() {
			try {
				if (cameraStream) { cameraStream.getTracks().forEach(t => t.stop()); cameraStream = null; }
				const videoEl = document.getElementById('cameraVideo');
				if (videoEl) videoEl.srcObject = null;
				
				document.getElementById('btnStart').innerHTML = '<i class="bi bi-play-fill me-1"></i>Start Camera';
				document.getElementById('btnStart').classList.remove('d-none');
				document.getElementById('btnSnap').classList.add('d-none');
				document.getElementById('btnStop').classList.add('d-none');
				
				const statusEl = document.getElementById('camStatus');
				if (statusEl && statusEl.textContent !== '✓ Photo captured') {
					statusEl.textContent = 'Camera is off';
				}
			} catch(err) {
				console.error("Error stopping camera:", err);
			}
		}

		function getFlattenedDataURL() {
			if (currentRawImg && mode !== 'draw') {
				renderImage(false, true, true); 
			}
			
			const flat = document.createElement('canvas');
			flat.width  = sigCanvas.width;
			flat.height = sigCanvas.height;
			const fCtx = flat.getContext('2d');
			fCtx.drawImage(sigCanvas, 0, 0);
			const dataUrl = flat.toDataURL('image/png');
			
			if (currentRawImg && mode !== 'draw') {
				renderImage(true, true, false);
			}
			return dataUrl;
		}

		function saveSignature() {
			if (isEmpty && !currentRawImg && allStrokes.length === 0) {
				alert("Please provide a signature first.");
				return;
			}

			const btn = document.getElementById('signsaveempl');
			const originalText = btn.innerHTML;
			btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';
			btn.disabled = true;

			const signatureData = getFlattenedDataURL();

			$.ajax({
				url: 'lib/employee-sign-saved.php',
				method: 'POST',
				data: {
					employee_id: currentSignEmpId,
					signature: signatureData
				},
				dataType: 'json',
				success: function(response) {
					alert('Signature saved successfully!');
					
					const displayImgE = document.getElementById('displayempsign');
					const freshSrc = "public/employee_sign/" + currentSignEmpId + ".png?t=" + new Date().getTime();
					displayImgE.src = freshSrc;
					
					const rowBtns = document.querySelectorAll(`button[onclick*="fnDisplaySignature"]`);
					rowBtns.forEach(btnEl => {
						if (btnEl.getAttribute('onclick').includes(currentSignEmpId)) {
							btnEl.setAttribute('onclick', `fnDisplaySignature('public/employee_sign/${currentSignEmpId}.png', '${currentSignEmpId}')`);
						}
					});

					btn.innerHTML = originalText;
					btn.disabled = false;
				},
				error: function(xhr) {
					alert('Error saving signature: ' + xhr.statusText);
					btn.innerHTML = originalText;
					btn.disabled = false;
				}
			});
		}


		// ==========================================
		// INFO / PIN / ID MODAL LOGIC 
		// ==========================================

		function uploadProfileImage(event) {
			const file = event.target.files[0];
			if (!file) return;

			const empId = document.getElementById('currentEmpId').value;
			const formData = new FormData();
			formData.append('employee_id', empId);
			formData.append('profile_image', file);

			const btn = event.target.previousElementSibling;
			const origText = btn.innerHTML;
			btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
			btn.disabled = true;

			fetch('model/employee/update-profile-image.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					alert('Profile image updated successfully!');
					const newSrc = data.path + '?t=' + new Date().getTime();
					
					// Update modal image
					document.getElementById('profileModalImg').src = newSrc;
					
					// Update table row images
					document.querySelectorAll(`img[src*="public/employeeID/${empId}.jpeg"]`).forEach(img => {
						img.src = newSrc;
					});
					
					// Update hover card if it's currently showing this employee
					if (currentHoverId === empId) {
						document.getElementById('hoverImg').src = newSrc;
					}
				} else {
					alert('Error updating image: ' + (data.message || 'Unknown error'));
				}
			})
			.catch(err => {
				console.error('Error:', err);
				alert('Failed to upload image.');
			})
			.finally(() => {
				btn.innerHTML = origText;
				btn.disabled = false;
				event.target.value = ''; // reset input
			});
		}
		
		function showChangePinPopup() {
			hideAddIdPopup(); 
			document.getElementById('changePinPopup').style.display = 'block';
			document.getElementById('newPinInput').focus();
		}

		function hideChangePinPopup() {
			document.getElementById('changePinPopup').style.display = 'none';
			document.getElementById('newPinInput').value = '';
			document.getElementById('confirmPinInput').value = '';
			document.getElementById('newPinInput').classList.remove('is-invalid');
			document.getElementById('confirmPinInput').classList.remove('is-invalid');
			document.getElementById('pinErrorText').style.display = 'none';
		}

		function validatePinInput(input) {
			const originalValue = input.value;
			const newValue = originalValue.replace(/[^0-9]/g, ''); 
			
			if (originalValue !== newValue) {
				input.value = newValue; 
				input.classList.add('is-invalid');
				document.getElementById('pinErrorText').style.display = 'block';
				setTimeout(() => {
					input.classList.remove('is-invalid');
					document.getElementById('pinErrorText').style.display = 'none';
				}, 1500);
			}
		}

		function changePin() {
			const empId = document.getElementById('currentEmpId').value;
			const newPin = document.getElementById('newPinInput').value;
			const confPin = document.getElementById('confirmPinInput').value;

			if (newPin === "" || confPin === "") {
				alert("Please enter both the new PIN and confirm PIN.");
				return;
			}

			if (newPin !== confPin) {
				alert("PINs do not match. Please make sure both fields are identical.");
				return;
			}

			const formData = new FormData();
			formData.append('employeeid', empId);
			formData.append('new_password', newPin); 

			fetch('model/employee/update-password.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.text())
			.then(data => {
				alert("PIN successfully changed!");
				hideChangePinPopup();
			})
			.catch(error => {
				console.error('Error changing PIN:', error);
				alert('Failed to change the PIN.');
			});
		}

		function openInfoModal(empId, profId) {
			document.getElementById('currentEmpId').value = empId;
			document.getElementById('currentProfId').value = profId;

			// Set the profile image source
			const imgPath = "<?php echo isset($pixloc) ? $pixloc : (isset($domainhome) ? trim($domainhome).'/' : ''); ?>public/employeeID/" + empId + ".jpeg?t=" + new Date().getTime();
			document.getElementById('profileModalImg').src = imgPath;
			
			// Fallback for missing image
			document.getElementById('profileModalImg').onerror = function() {
				this.src = 'https://via.placeholder.com/150?text=No+Image';
			};

			hideAddIdPopup();
			hideChangePinPopup();
			fn_getdataides(profId, empId);
			fn_getProfileDetails(profId, empId);

			var infoModal = new bootstrap.Modal(document.getElementById('myModalInfo'));
			infoModal.show();
		}

		function showAddIdPopup() {
			hideChangePinPopup(); 
			document.getElementById('addIdPopup').style.display = 'block';
			document.getElementById('idTypeInput').focus();
		}

		function hideAddIdPopup() {
			document.getElementById('addIdPopup').style.display = 'none';
			document.getElementById('idTypeInput').value = '';
			document.getElementById('idNumberInput').value = '';
		}

		function addId() {
			const empId = document.getElementById('currentEmpId').value;
			const profId = document.getElementById('currentProfId').value;
			const idType = document.getElementById('idTypeInput').value;
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

				const row = tbody.insertRow();
				const cell1 = row.insertCell(0);
				const cell2 = row.insertCell(1);
				const cell3 = row.insertCell(2);
				
				cell1.textContent = idType;
				cell2.textContent = idNumber;
				
				cell3.className = 'text-center align-middle';
				cell3.innerHTML = '<button type="button" class="btn btn-sm text-danger p-0 border-0" style="font-size: 1.2rem; line-height: 1;" onclick="deleteRow(this, null)">&times;</button>';

				document.getElementById('idTypeInput').value = '';
				document.getElementById('idNumberInput').value = '';
				document.getElementById('idTypeInput').focus();

				const formData = new FormData();
				formData.append('profileidx', profId);
				formData.append('employeeidx', empId);
				formData.append('idtypex', idType);
				formData.append('idnumberx', idNumber);

				fetch('model/id_type_person_tbl/add-employee-ids.php', {
					method: 'POST',
					body: formData
				})
				.then(response => response.text())
				.then(data => {
					console.log("Successfully saved ID to DB:", data);
					fn_getdataides(profId, empId);
				})
				.catch(error => {
					console.error("Error saving to DB:", error);
					alert("Failed to save the ID. Check your console for details.");
				});
				
			} else {
				alert('Please select an ID type and enter an ID number.');
			}
		}

		function deleteRow(buttonElement, detetedid) {
			const row = buttonElement.closest('tr');
			row.remove();

			if (detetedid) {
				const formData = new FormData();
				formData.append('detetedid', detetedid);

				fetch('model/id_type_person_tbl/delete-employee-ids.php', {
					method: 'POST',
					body: formData
				})
				.then(response => response.text())
				.then(data => console.log("Deleted from DB:", data))
				.catch(error => console.error("Error deleting from DB:", error));
			}
		}

		function fn_getdataides(profileid, employeeid) {
			const tbody = document.getElementById('idTableBody');
			tbody.innerHTML = '<tr><td colspan="3" class="text-center">Loading ID details...</td></tr>';

			const formData = new FormData();
			formData.append('profileid', profileid);
			formData.append('employeeid', employeeid);

			fetch('model/id_type_person_tbl/get-employee-ids.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.text())
			.then(data => {
				tbody.innerHTML = data;
			})
			.catch(error => {
				console.error('Error:', error);
				tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error loading ID details.</td></tr>';
			});
		}

		function fn_getProfileDetails(profileid, employeeid) {
			document.getElementById('fullNameInput').value = '';
			document.getElementById('nicknameInput').value = '';
			document.getElementById('designationInput').value = '';
			document.getElementById('officeInput').value = '';
			document.getElementById('birthplaceInput').value = '';
			document.getElementById('iceName').value = '';
			document.getElementById('iceRelationship').value = '';
			document.getElementById('iceNumber').value = '';

			const formData = new FormData();
			formData.append('profileid', profileid);
			formData.append('employeeid', employeeid);

			fetch('model/employee/get-profile-details.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.json()) 
			.then(data => {
				if(data) {
					document.getElementById('fullNameInput').value = data.emp_name_forid || data.emp_name || '';
					document.getElementById('nicknameInput').value = data.nickname || '';
					document.getElementById('designationInput').value = data.designationforid || data.designation || ''; 
					document.getElementById('officeInput').value = data.officename_forid || '';
					
					document.getElementById('birthplaceInput').value = data.address || '';
					
					document.getElementById('iceName').value = data.ice_name || '';
					document.getElementById('iceRelationship').value = data.ice_relationship || '';
					document.getElementById('iceNumber').value = data.ice_number || '';
				}
			})
			.catch(error => {
				console.error('Error fetching profile details:', error);
			});
		}

		function saveDetails(event) {
			if (event) {
				event.preventDefault();
			}

			const empId = document.getElementById('currentEmpId').value;
			const profId = document.getElementById('currentProfId').value;
			
			const fullname = document.getElementById('fullNameInput').value;
			const nickname = document.getElementById('nicknameInput').value;
			const designation = document.getElementById('designationInput').value;
			const officename = document.getElementById('officeInput').value;
			const address = document.getElementById('birthplaceInput').value;
			const iceName = document.getElementById('iceName').value;
			const iceRelationship = document.getElementById('iceRelationship').value;
			const iceNumber = document.getElementById('iceNumber').value;

			const formData = new FormData();
			formData.append('employeeid', empId);
			formData.append('profileid', profId);
			formData.append('fullname', fullname);
			formData.append('nickname', nickname);
			formData.append('designation', designation);
			formData.append('officename', officename);
			formData.append('address', address);
			formData.append('ice_name', iceName);
			formData.append('ice_relationship', iceRelationship);
			formData.append('ice_number', iceNumber);

			fetch('model/employee/update-profile-details.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.text())
			.then(data => {
				console.log("Save Response:", data);
				alert('Details saved successfully!');
				
				const modalElement = document.getElementById('myModalInfo');
				if (typeof bootstrap !== 'undefined') {
					const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
					if (modalInstance) {
						modalInstance.hide();
					}
				}
			})
			.catch(error => {
				console.error('Error saving details:', error);
				alert('Failed to save the details.');
			});
		}
	</script>
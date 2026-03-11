	<style>
		/* Floating popup for adding ID */
		.add-id-popup {
			position: absolute;
			top: -1px; /* Align with the top border of the modal */
			right: -280px; /* Push it completely outside the right border */
			width: 270px;
			background: #ffffff;
			border: 1px solid #dee2e6;
			border-radius: 0.375rem;
			box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
			padding: 15px;
			display: none; /* Hidden by default */
			z-index: 1060;
		}

		/* Fallback for smaller screens to ensure it doesn't get cut off */
		@media (max-width: 1100px) {
			.add-id-popup {
				right: 0;
				top: 50px;
				border: 2px solid #198754;
			}
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
							<th>FullName</th>
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
							$modals_html = ''; // Variable to hold all modals outside the table

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

									echo '<tr>';
										echo '<td>'.$xno_oo.'</td>';
										echo '<td class="text-center"><img src="'.$pixloc.'public/employeeID/'.$employeeid_oo.'.jpeg" style="width: auto; max-width: 50px; height: 50px;" data-bs-toggle="modal" data-bs-target="#myModal-'.trim($employeeid_oo).'"></td>';
										echo '<td>'.$employeeid_oo.'</td>';
										echo '<td class="text-nowrap">'.$employeeAcctx->list_nicknameee[$i].'</td>';
										echo '<td class="text-nowrap">'.$employeeAcctx->list_empnameforidee[$i].'</td>';
										echo '<td><a href="tel:+63'.trim($mobile_oo).'">'.trim($mobile_oo).'</a></td>';
										echo '<td class="text-nowrap">'.trim($xdesignation_oo).'</td>';
										echo '<td class="text-nowrap">'.trim($xoffice_oo).'</td>';
										echo '<td>'.trim($xtype_oo).'</td>';
										echo '<td>'.trim(date($birthday_oo)).'</td>';
										echo '<td>'.trim($email_oo).'</td>';
										echo '<td>'.$profileautoid_oo.'</td>';
										echo '<td>'.$employeeAcctx->list_uidee[$i].'</td>';
										echo '<td class="text-nowrap">'.$xcreatedatee_oo.'</td>';
										echo '<td class="text-center align-middle">'.$xslingid_status.'</td>';
										echo '<td class="text-center align-middle">'.$xpocket_status.'</td>';
										echo '<td class="align-middle">
												<div class="d-flex gap-1 align-items-center">
													<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#myModalUpInfo-'.trim($employeeid_oo).'">Info</button>
													<button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#myModalSign-'.trim($employeeid_oo).'">Sign</button>
												</div>
											</td>';
									echo '</tr>';

									// Start output buffering to capture the modals securely outside the table
									ob_start();
						?>
							<div class="modal text-dark" id="myModal-<?php echo trim($employeeid_oo); ?>">
								<div class="modal-dialog">
									<div class="modal-content">

										<div class="modal-header">
											<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
										</div>

										<div class="modal-body m-auto text-center">
											<img src="<?php echo trim($pixloc).'public/employeeID/'.trim($employeeid_oo).'.jpeg'; ?>" style="width: 100%; max-width: 300px; margin: auto; text-align: center;">
										</div>

										<div class="modal-footer">
											<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
										</div>

									</div>
								</div>
							</div>

							<div class="modal fade text-dark" id="myModalUpInfo-<?php echo trim($employeeid_oo); ?>" tabindex="-1" aria-labelledby="profileModalLabel-<?php echo trim($employeeid_oo); ?>" aria-hidden="true">
								<div class="modal-dialog modal-lg position-relative">
									<div class="modal-content text-start">
										
										<div id="addIdPopup-<?php echo trim($employeeid_oo); ?>" class="add-id-popup border-success border-top-0 border-end-0 border-bottom-0 border-4">
											<div class="d-flex justify-content-between align-items-center mb-3">
												<h6 class="mb-0 fw-bold">Add ID</h6>
												<button type="button" class="btn-close" aria-label="Close" onclick="hideAddIdPopup('<?php echo trim($employeeid_oo); ?>')"></button>
											</div>
											<div class="mb-3">
												<label for="idTypeInput-<?php echo trim($employeeid_oo); ?>" class="form-label mb-1" style="font-size: 0.9rem;">ID Type</label>
												<select class="form-select form-select-sm" id="idTypeInput-<?php echo trim($employeeid_oo); ?>">
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
												<label for="idNumberInput-<?php echo trim($employeeid_oo); ?>" class="form-label mb-1" style="font-size: 0.9rem;">ID Number</label>
												<input type="text" class="form-control form-control-sm" id="idNumberInput-<?php echo trim($employeeid_oo); ?>" placeholder="Enter ID number">
											</div>
											<button type="button" class="btn btn-success btn-sm w-100" onclick="addId('<?php echo trim($employeeid_oo); ?>')">Add to Table</button>
										</div>

										<div class="modal-header">
											<h5 class="modal-title" id="profileModalLabel-<?php echo trim($employeeid_oo); ?>">Profile Details</h5>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										
										<form id="profileForm-<?php echo trim($employeeid_oo); ?>" onsubmit="saveDetails(event, '<?php echo trim($employeeid_oo); ?>')">
											<div class="modal-body">
												
												<div class="d-flex justify-content-between align-items-center mb-3">
													<h6 class="mb-0">Identification Cards</h6>
													<button type="button" class="btn btn-sm btn-success" onclick="showAddIdPopup('<?php echo trim($employeeid_oo); ?>')">
														+ Add New ID
													</button>
												</div>
												
												<div class="border rounded p-2 mb-4">
													<table class="table table-bordered table-striped table-sm mb-0" id="idTable-<?php echo trim($employeeid_oo); ?>">
														<thead class="table-light">
															<tr>
																<th>ID Type</th>
																<th>ID Number</th>
																<th style="width: 50px;" class="text-center">Action</th>
															</tr>
														</thead>
														<tbody id="idTableBody-<?php echo trim($employeeid_oo); ?>">
														</tbody>
													</table>
												</div>

												<hr class="my-4">

												<h6 class="mb-3">Personal Information</h6>
												<div class="mb-4">
													<label for="birthplaceInput-<?php echo trim($employeeid_oo); ?>" class="form-label">Address</label>
													<input type="text" class="form-control" id="birthplaceInput-<?php echo trim($employeeid_oo); ?>" placeholder="Enter city/province of Address">
												</div>

												<hr class="my-4">

												<h6 class="mb-3">In Case of Emergency (ICE)</h6>
												<div class="row g-2 mb-2">
													<div class="col-md-4">
														<label for="iceName-<?php echo trim($employeeid_oo); ?>" class="form-label">Contact Name</label>
														<input type="text" class="form-control" id="iceName-<?php echo trim($employeeid_oo); ?>" placeholder="Full name">
													</div>
													<div class="col-md-4">
														<label for="iceRelationship-<?php echo trim($employeeid_oo); ?>" class="form-label">Relationship</label>
														<input type="text" class="form-control" id="iceRelationship-<?php echo trim($employeeid_oo); ?>" placeholder="e.g. Parent, Spouse">
													</div>
													<div class="col-md-4">
														<label for="iceNumber-<?php echo trim($employeeid_oo); ?>" class="form-label">Contact Number</label>
														<input type="tel" class="form-control" id="iceNumber-<?php echo trim($employeeid_oo); ?>" placeholder="Phone number">
													</div>
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

							<div class="modal text-dark" id="myModalSign-<?php echo trim($employeeid_oo); ?>">
								<div class="modal-dialog">
									<div class="modal-content">

										<div class="modal-header">
											<p>EmployeeID: <span><?php echo trim($employeeid_oo); ?></span></p>
											<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
										</div>

										<div class="modal-body m-auto text-center">
											<canvas id="signatureCanvas-<?php echo trim($employeeid_oo); ?>" width="400" height="200" style="border: 1px solid black;"></canvas>
										</div>

										<div class="modal-footer">
											<button type="button" class="btn btn-primary" onclick="saveSignature('<?php echo trim($employeeid_oo); ?>')">Save</button>
											<button type="button" class="btn btn-secondary" onclick="clearSignature('<?php echo trim($employeeid_oo); ?>')">Clear</button>
											<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
										</div>

									</div>
								</div>
							</div>

						<?php
									// End output buffering and add it to our variable
									$modals_html .= ob_get_clean();
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

		<?php echo $modals_html; ?>

	</div>

	<script src="assets/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
	<script>
		// ---------------------------------------------
		// Info Profile Modal Functions
		// ---------------------------------------------
		function showAddIdPopup(empId) {
			document.getElementById('addIdPopup-' + empId).style.display = 'block';
			document.getElementById('idTypeInput-' + empId).focus();
		}

		function hideAddIdPopup(empId) {
			document.getElementById('addIdPopup-' + empId).style.display = 'none';
			document.getElementById('idTypeInput-' + empId).value = '';
			document.getElementById('idNumberInput-' + empId).value = '';
		}

		function addId(empId) {
			const idType = document.getElementById('idTypeInput-' + empId).value;
			const idNumber = document.getElementById('idNumberInput-' + empId).value;

			if (idType && idNumber) {
				const tbody = document.getElementById('idTableBody-' + empId);
				
				// Check for existing duplicates in the table
				const existingRows = tbody.getElementsByTagName('tr');
				for (let i = 0; i < existingRows.length; i++) {
					if (existingRows[i].cells[0].textContent === idType) {
						alert(`The ID type "${idType}" has already been added.`);
						return; // Stop the function here so it doesn't add the row
					}
				}

				const row = tbody.insertRow();
				
				const cell1 = row.insertCell(0);
				const cell2 = row.insertCell(1);
				const cell3 = row.insertCell(2);
				
				cell1.textContent = idType;
				cell2.textContent = idNumber;
				
				// Add the red 'x' delete button
				cell3.className = 'text-center align-middle';
				cell3.innerHTML = '<button type="button" class="btn btn-sm text-danger p-0 border-0" style="font-size: 1.2rem; line-height: 1;" onclick="deleteRow(this)">&times;</button>';

				// Clear inputs for the next entry
				document.getElementById('idTypeInput-' + empId).value = '';
				document.getElementById('idNumberInput-' + empId).value = '';
				
				// Put focus back on the first input
				document.getElementById('idTypeInput-' + empId).focus();
				
			} else {
				alert('Please select an ID type and enter an ID number.');
			}
		}

		function deleteRow(buttonElement) {
			const row = buttonElement.closest('tr');
			row.remove();
		}

		function saveDetails(event, empId) {
			if (event) {
				event.preventDefault();
			}

			// This is where you'll eventually add your AJAX request to save the profile data
			alert('Form data for employee ' + empId + ' is ready to be processed without refreshing!');
			
			const modalElement = document.getElementById('myModalUpInfo-' + empId);
			// Check if Bootstrap 5 is available
			if (typeof bootstrap !== 'undefined') {
				const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
				if (modalInstance) {
					modalInstance.hide();
				}
			}
		}

		// ---------------------------------------------
		// Signature Pad Functions
		// ---------------------------------------------
		
		// Object to hold multiple signature pad instances
		var signaturePads = {};

		// Initialize a signature pad for every canvas created in the loop
		document.querySelectorAll('canvas[id^="signatureCanvas-"]').forEach(function(canvas) {
			var empId = canvas.id.split('-')[1];
			signaturePads[empId] = new SignaturePad(canvas);
		});

		// Function to clear a specific signature
		function clearSignature(empId) {
			if (signaturePads[empId]) {
				signaturePads[empId].clear();
			}
		}

		// Function to save a specific signature
		function saveSignature(empId) {
			if (signaturePads[empId].isEmpty()) {
				alert("Please provide a signature first.");
				return;
			}

			// Extract base64 PNG data
			var imageData = signaturePads[empId].toDataURL("image/png");

			// Setup form data for POST request
			var formData = new FormData();
			formData.append('imgdata', imageData);
			formData.append('employeeidfinale', empId);

			// Send to backend
			fetch('lib/employee-sign-saved.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.text())
			.then(data => {
				alert("Signature saved successfully!");
				console.log("Server Response:", data);
			})
			.catch(error => {
				console.error('Error:', error);
				alert("There was an error saving the signature.");
			});
		}
	</script>
<?php 
	
	// Adjust the path to your connection file as needed
	if (file_exists("lib/cnn.php")) {
		require_once "lib/cnn.php";
	} elseif (file_exists("../../lib/cnn.php")) {
		require_once "../../lib/cnn.php";
	}

	try {
		$db = new myDatabase();
		$conn = $db->getConnection();

		$query = "SELECT `user_tbl`.`authid` AS `authid`,`user_tbl`.`uid` AS `uid`,`user_tbl`.`profileid` AS `profileid`,`user_tbl`.`uname` AS `uname`,`user_tbl`.`pword` AS `pword`,`user_tbl`.`verified` AS `verified`,`user_tbl`.`ustat` AS `ustat`,`user_tbl`.`ulevel` AS `ulevel`,`user_tbl`.`uposition` AS `uposition`,`user_tbl`.`officeabrv` AS `officeabrv`,`profile_tbl`.`first_name` AS `first_name`,`profile_tbl`.`middle_name` AS `middle_name`,`profile_tbl`.`last_name` AS `last_name`,`profile_tbl`.`suffix` AS `suffix` FROM (`user_tbl` LEFT JOIN `profile_tbl` ON(`user_tbl`.`profileid` = `profile_tbl`.`profileid`))";

		$stmt = $conn->prepare($query);
		$stmt->execute();
		$userRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$posQuery = "SELECT `user_tbl`.`ulevel` AS `ulevel`,`user_tbl`.`uposition` AS `uposition` FROM `user_tbl` GROUP BY `user_tbl`.`uposition`";
				 
		$posStmt = $conn->prepare($posQuery);
		$posStmt->execute();
		$positionList = $posStmt->fetchAll(PDO::FETCH_ASSOC);

	} catch (PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
		$userRecords = [];
		$positionList = [];
	}
?>

<style>
	@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");
	@import url("https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css");

	.sticky-col {
		position: -webkit-sticky;
		position: sticky;
		left: 0;
		background-color: #212529 !important;
		z-index: 2;
	}

	.filter-container {
		background-color: #f8f9fa;
		padding: 15px;
		border-radius: 0.375rem;
		border: 1px solid #dee2e6;
		margin-bottom: 20px;
	}
	
	table.dataTable thead th {
		border-bottom: 1px solid #495057;
	}
	.dataTables_scrollHeadInner, .dataTables_scrollHeadInner table {
		width: 100% !important;
	}
</style>

<section class="position-relative primary-bg-color-light w-100 h-100 pt-3 pb-5 clearfix">
	<div class="container-fluid">
		
		<div class="filter-container shadow-sm">
			<div class="row align-items-end g-3">
				<div class="col-md-4 col-sm-6">
					<label for="filterVerified" class="form-label fw-bold mb-1" style="font-size: 0.85rem;"><i class="bi bi-shield-check me-1 text-success"></i> Filter by Verification</label>
					<select id="filterVerified" class="form-select form-select-sm">
						<option value="">All Accounts</option>
						<option value="Activated">Activated (Verified)</option>
						<option value="Disabled">Disabled (Unverified)</option>
					</select>
				</div>
				<div class="col-md-4 col-sm-6">
					<label for="filterStatus" class="form-label fw-bold mb-1" style="font-size: 0.85rem;"><i class="bi bi-person-circle me-1 text-primary"></i> Filter by User Status</label>
					<select id="filterStatus" class="form-select form-select-sm">
						<option value="">All Statuses</option>
						<option value="Active">Active</option>
						<option value="Inactive">Inactive</option>
					</select>
				</div>
				<div class="col-md-4 col-sm-12 text-md-end text-center">
					<button class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
						<i class="bi bi-arrow-counterclockwise"></i> Reset Filters
					</button>
				</div>
			</div>
		</div>

		<div class="pt-3">
			<div class="shadow border rounded p-3 bg-white w-100">
				<table id="userTable" class="table table-dark table-striped table-hover mb-0 w-100" style="width:100%">
					<thead>
						<tr>
							<th>No.</th>
							<th>UserID</th>
							<th>ProfileID</th>
							<th>Username</th>
							<th class="sticky-col">First Name</th>
							<th>Middle Name</th>
							<th>Last Name</th>
							<th>Suffix</th>
							<th>Position</th>
							<th>Level</th>
							<th class="text-center">Verified</th>
							<th class="text-center">Status</th>
							<th class="text-center" style="width: 100px;">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							if (count($userRecords) > 0) {
								$xno = 0;
								foreach ($userRecords as $row) {
									$xno++;
									$authid = $row['authid']; 
									$uid = htmlspecialchars($row['uid']);
									$profileid = htmlspecialchars($row['profileid']);
									$uname = htmlspecialchars($row['uname']);
									$position = htmlspecialchars($row['uposition']);
									$level = htmlspecialchars($row['ulevel']);
									$verified = $row['verified'];
									$status = $row['ustat'];
									
									$firstName = htmlspecialchars(trim($row['first_name']));
									$middleName = htmlspecialchars(trim($row['middle_name']));
									$lastName = htmlspecialchars(trim($row['last_name']));
									$suffix = htmlspecialchars(trim($row['suffix']));
									
									$verifiedBadge = ($verified == 1) 
										? '<span class="badge bg-success">Activated</span>' 
										: '<span class="badge bg-danger">Disabled</span>';
										
									$statusBadge = ($status == 1) 
										? '<span class="badge bg-primary">Active</span>' 
										: '<span class="badge bg-secondary">Inactive</span>';

									echo "<tr id='row-{$authid}'>";
										echo "<td>{$xno}</td>";
										echo "<td>{$uid}</td>";
										echo "<td>{$profileid}</td>";
										echo "<td>{$uname}</td>";
										echo "<td class='sticky-col fw-bold text-nowrap'>{$firstName}</td>";
										echo "<td class='text-nowrap'>{$middleName}</td>";
										echo "<td class='fw-bold text-nowrap'>{$lastName}</td>";
										echo "<td>{$suffix}</td>";
										echo "<td class='text-nowrap'>{$position}</td>";
										echo "<td>{$level}</td>";
										echo "<td class='text-center'>{$verifiedBadge}</td>";
										echo "<td class='text-center'>{$statusBadge}</td>";
										echo "<td class='text-center'>
												<button type='button' class='btn btn-warning btn-sm fw-bold' 
													onclick=\"openUpdateModal('{$authid}', '{$position}', '{$level}', '{$verified}', '{$status}')\">
													<i class='bi bi-pencil-square'></i> Update
												</button>
											  </td>";
									echo "</tr>";
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<div class="modal fade text-dark" id="myModalUpdateUser" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 shadow">
			
			<div class="modal-header bg-warning">
				<h5 class="modal-title fw-bold text-dark">
					<i class="bi bi-pencil-square me-2"></i>Update User Account
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

			<form id="updateUserForm" onsubmit="saveUserUpdate(event)">
				<div class="modal-body p-4">
					
					<input type="hidden" id="upd_authid" value="">

					<div class="row g-3 mb-4">
						<div class="col-md-8">
							<label for="upd_position" class="form-label fw-bold small text-muted text-uppercase mb-1">Position</label>
							<select class="form-select" id="upd_position" required onchange="autoFillLevel()">
								<option value="" selected disabled>Select Position</option>
								<?php
									if (count($positionList) > 0) {
										foreach ($positionList as $posRow) {
											$posName = htmlspecialchars($posRow['uposition']);
											$posLevel = htmlspecialchars($posRow['ulevel']);
											echo "<option value=\"{$posName}\" data-level=\"{$posLevel}\">{$posName}</option>";
										}
									}
								?>
							</select>
						</div>
						<div class="col-md-4">
							<label for="upd_level" class="form-label fw-bold small text-muted text-uppercase mb-1">User Level</label>
							<input type="text" class="form-control bg-light" id="upd_level" readonly title="User level is tied to the position.">
						</div>
					</div>

					<hr class="mb-4">

					<div class="row g-4 mb-4">
						<div class="col-md-6">
							<label class="form-label fw-bold small text-muted text-uppercase mb-2 d-block">Verification Status</label>
							<div class="btn-group w-100" role="group">
								<input type="radio" class="btn-check" name="upd_verified" id="ver_activated" value="1" autocomplete="off">
								<label class="btn btn-outline-success" for="ver_activated"><i class="bi bi-check-circle"></i> Activated</label>

								<input type="radio" class="btn-check" name="upd_verified" id="ver_disabled" value="0" autocomplete="off">
								<label class="btn btn-outline-danger" for="ver_disabled"><i class="bi bi-x-circle"></i> Disabled</label>
							</div>
						</div>

						<div class="col-md-6">
							<label class="form-label fw-bold small text-muted text-uppercase mb-2 d-block">Account Status</label>
							<div class="btn-group w-100" role="group">
								<input type="radio" class="btn-check" name="upd_status" id="stat_active" value="1" autocomplete="off">
								<label class="btn btn-outline-primary" for="stat_active"><i class="bi bi-person-check"></i> Active</label>

								<input type="radio" class="btn-check" name="upd_status" id="stat_inactive" value="0" autocomplete="off">
								<label class="btn btn-outline-secondary" for="stat_inactive"><i class="bi bi-person-slash"></i> Inactive</label>
							</div>
						</div>
					</div>

					<hr class="mb-4">

					<!-- CHANGE PASSWORD SECTION -->
					<div class="mb-2">
						<label class="form-label fw-bold small text-muted text-uppercase mb-2 d-block">
							<i class="bi bi-key me-1"></i> Change Password (MD5)
						</label>
						<div class="row g-2">
							<div class="col-md-6">
					    <input type="password" class="form-control" id="upd_newpass" placeholder="New Password" maxlength="50" oninput="clearPassError()">
					</div>
					<div class="col-md-6">
					    <input type="password" class="form-control" id="upd_confirmpass" placeholder="Confirm Password" maxlength="50" oninput="checkPassMatch()">
                    </div>
						</div>
						<small id="passErrorText" class="text-danger fw-bold mt-1" style="display:none; font-size: 0.75rem;">
							<i class="bi bi-exclamation-circle me-1"></i><span id="passErrorMsg"></span>
						</small>
						<small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">
							<i class="bi bi-info-circle me-1"></i> Leave blank to keep the current password.
						</small>
					</div>

				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" id="btnSaveUser" class="btn btn-primary fw-bold">
						<i class="bi bi-floppy me-1"></i> Save Changes
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
	// ==========================================
	// DATATABLES INIT & FILTER LOGIC
	// ==========================================
	let userTable;

	$(document).ready(function() {
		userTable = $('#userTable').DataTable({
			"pageLength": 10,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"scrollY": "50vh",
			"scrollX": true,
			"scrollCollapse": true,
			"language": {
				"search": "Search Table:"
			}
		});

		$('#filterVerified').on('change', function() {
			let val = $(this).val();
			userTable.column(10).search(val ? '^' + val + '$' : '', true, false).draw();
		});

		$('#filterStatus').on('change', function() {
			let val = $(this).val();
			userTable.column(11).search(val ? '^' + val + '$' : '', true, false).draw();
		});
	});

	function resetFilters() {
		$('#filterVerified').val('').trigger('change');
		$('#filterStatus').val('').trigger('change');
		userTable.search('').draw();
	}

	function checkPassMatch() {
    const newpass  = document.getElementById('upd_newpass').value;
    const confpass = document.getElementById('upd_confirmpass').value;

    if (confpass === '') {
        clearPassError();
        return;
    }

    if (newpass !== confpass) {
        showPassError('Passwords do not match.');
        document.getElementById('upd_confirmpass').classList.add('is-invalid');
        document.getElementById('upd_newpass').classList.remove('is-invalid');
    } else {
        clearPassError();
        document.getElementById('upd_confirmpass').classList.add('is-valid');
        document.getElementById('upd_newpass').classList.add('is-valid');
    }
}

	// ==========================================
	// MODAL LOGIC
	// ==========================================
	
	function autoFillLevel() {
		const posSelect = document.getElementById('upd_position');
		const levelInput = document.getElementById('upd_level');
		const selectedOption = posSelect.options[posSelect.selectedIndex];
		
		if (selectedOption && selectedOption.value !== "") {
			levelInput.value = selectedOption.getAttribute('data-level');
		} else {
			levelInput.value = "";
		}
	}

	function openUpdateModal(authid, position, level, verified, status) {
		document.getElementById('upd_authid').value = authid;
		
		// Clear password fields every time modal opens
		document.getElementById('upd_newpass').value = '';
		document.getElementById('upd_confirmpass').value = '';
		clearPassError();

		const posSelect = document.getElementById('upd_position');
		let positionFound = false;
		
		for (let i = 0; i < posSelect.options.length; i++) {
			if (posSelect.options[i].value === position) {
				posSelect.selectedIndex = i;
				positionFound = true;
				break;
			}
		}
		
		if (!positionFound && position !== "") {
			const newOption = new Option(position, position);
			newOption.setAttribute('data-level', level);
			posSelect.add(newOption);
			posSelect.value = position;
		}

		document.getElementById('upd_level').value = level;

		if (verified == 1) {
			document.getElementById('ver_activated').checked = true;
		} else {
			document.getElementById('ver_disabled').checked = true;
		}

		if (status == 1) {
			document.getElementById('stat_active').checked = true;
		} else {
			document.getElementById('stat_inactive').checked = true;
		}

		var updateModal = new bootstrap.Modal(document.getElementById('myModalUpdateUser'));
		updateModal.show();
	}

	function clearPassError() {
    document.getElementById('passErrorText').style.display = 'none';
    document.getElementById('passErrorMsg').textContent = '';
    document.getElementById('upd_newpass').classList.remove('is-invalid', 'is-valid');
    document.getElementById('upd_confirmpass').classList.remove('is-invalid', 'is-valid');
	}

	function showPassError(msg) {
		document.getElementById('passErrorMsg').textContent = msg;
		document.getElementById('passErrorText').style.display = 'block';
		document.getElementById('upd_newpass').classList.add('is-invalid');
		document.getElementById('upd_confirmpass').classList.add('is-invalid');
	}

	function saveUserUpdate(event) {
		event.preventDefault();

		const authid   = document.getElementById('upd_authid').value;
		const position = document.getElementById('upd_position').value;
		const level    = document.getElementById('upd_level').value; 
		const verified = document.querySelector('input[name="upd_verified"]:checked').value;
		const status   = document.querySelector('input[name="upd_status"]:checked').value;
		const newpass  = document.getElementById('upd_newpass').value;
		const confpass = document.getElementById('upd_confirmpass').value;

		// Password validation — only if user typed something
		if (newpass !== '' || confpass !== '') {
			if (newpass === '') {
				showPassError('Please enter the new password.');
				return;
			}
			if (confpass === '') {
				showPassError('Please confirm the new password.');
				return;
			}
			if (newpass !== confpass) {
				showPassError('Passwords do not match.');
				return;
			}
			if (newpass.length < 4) {
				showPassError('Password must be at least 4 characters.');
				return;
			}
		}

		const formData = new FormData();
		formData.append('authid',    authid);
		formData.append('position',  position);
		formData.append('level',     level); 
		formData.append('verified',  verified);
		formData.append('status',    status);
		formData.append('newpass',   newpass); // empty string = no change

		const saveBtn = document.getElementById('btnSaveUser');
		const originalText = saveBtn.innerHTML;
		saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';
		saveBtn.disabled = true;

		fetch('model/userAcct/update-user.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success') {
				alert('User account updated successfully!');
				
				const verifiedBadge = (verified == 1) 
					? '<span class="badge bg-success">Activated</span>' 
					: '<span class="badge bg-danger">Disabled</span>';
					
				const statusBadge = (status == 1) 
					? '<span class="badge bg-primary">Active</span>' 
					: '<span class="badge bg-secondary">Inactive</span>';
					
				const actionBtn = `<button type="button" class="btn btn-warning btn-sm fw-bold" 
									onclick="openUpdateModal('${authid}', '${position}', '${level}', '${verified}', '${status}')">
									<i class="bi bi-pencil-square"></i> Update
								  </button>`;
				
				const rowIndex = '#row-' + authid;
				userTable.cell(rowIndex, 8).data(position);
				userTable.cell(rowIndex, 9).data(level);
				userTable.cell(rowIndex, 10).data(verifiedBadge);
				userTable.cell(rowIndex, 11).data(statusBadge);
				userTable.cell(rowIndex, 12).data(actionBtn);
				userTable.draw(false);
				
				var updateModalEl = document.getElementById('myModalUpdateUser');
				var updateModal = bootstrap.Modal.getInstance(updateModalEl);
				updateModal.hide();
			} else {
				alert('Error: ' + (data.message || 'Failed to update.'));
			}

			saveBtn.innerHTML = originalText;
			saveBtn.disabled = false;
		})
		.catch(error => {
			console.error('Error updating user:', error);
			alert('Failed to update the user account.');
			saveBtn.innerHTML = originalText;
			saveBtn.disabled = false;
		});
	}
</script>
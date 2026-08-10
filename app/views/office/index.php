<style>
	#listRecView th,
	#listRecView td { text-align: center; vertical-align: middle; }
</style>

<div class="container-fluid">
	<div class="pt-3">
		<div class="table-responsive">
			<table id="listRecView" class="table table-striped table-hover">
				<thead id="remSortH">
					<tr>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
						<th class="remove-dropdown"></th>
					</tr>
				</thead>

				<thead id="theadtitle">
					<tr>
						<th>No.</th>
						<th>Agency Code</th>
						<th>Office ID</th>
						<th>Office Code</th>
						<th>Office Name</th>
						<th>Office Title</th>
						<th>Abbreviation</th>
						<th>Old Abbreviation</th>
						<th>Head Officer</th>
						<th>Head Title</th>
						<th>Auth Head</th>
						<th>Auth Title</th>
						<th>Auth Description</th>
						<th>Effectivity Date</th>
						<th>Status</th>
						<th>Latitude</th>
						<th>Longitude</th>
						<th>Meter</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody>
					<?php

						include_once "model/office-signatory/index.php";
						$officeSignx = new officeSignatory();
						$officeSignx->vwofficeSignatoryZero();

						$count = count($officeSignx->list_officesignatoryautoidjj);

						if ($count > 0) {
							for ($i = 0; $i < $count; $i++) {
								$autoid     = $officeSignx->list_officesignatoryautoidjj[$i];
								$status_val = $officeSignx->list_signatorystatusjj[$i];
								$status_lbl = ($status_val == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';

								$raw_gps    = $officeSignx->list_officegpslocationjj[$i] ?? '';
								$gps_parts  = $raw_gps ? explode(',', $raw_gps) : ['', ''];
								$disp_lat   = trim($gps_parts[0] ?? '');
								$disp_lng   = trim($gps_parts[1] ?? '');
								$raw_meter  = $officeSignx->list_meterjj[$i];
								$meter_disp = ($raw_meter !== null && $raw_meter !== '') ? number_format((float)$raw_meter, 2) . ' m' : '—';

								echo '<tr>';
									echo '<td>'.($i + 1).'</td>';
									echo '<td>'.htmlspecialchars($officeSignx->list_agencycodejj[$i]).'</td>';
									echo '<td>'.htmlspecialchars($officeSignx->list_officeidjj[$i]).'</td>';
									echo '<td>'.htmlspecialchars($officeSignx->list_officecodejj[$i]).'</td>';
									echo '<td class="text-nowrap">'.htmlspecialchars($officeSignx->list_officenamejj[$i]).'</td>';
									echo '<td class="text-nowrap">'.htmlspecialchars($officeSignx->list_officetitlejj[$i]).'</td>';
									echo '<td>'.htmlspecialchars($officeSignx->list_officeabrvjj[$i]).'</td>';
									echo '<td>'.htmlspecialchars($officeSignx->list_oldofficeabrvjj[$i]).'</td>';
									echo '<td class="text-nowrap">'.htmlspecialchars($officeSignx->list_headofficerjj[$i]).'</td>';
									echo '<td class="text-nowrap">'.htmlspecialchars($officeSignx->list_headtitlejj[$i]).'</td>';
									echo '<td class="text-nowrap">'.htmlspecialchars($officeSignx->list_authheadjj[$i]).'</td>';
									echo '<td class="text-nowrap">'.htmlspecialchars($officeSignx->list_authtitlejj[$i]).'</td>';
									echo '<td>'.htmlspecialchars($officeSignx->list_authdescriptionjj[$i]).'</td>';
									echo '<td class="text-nowrap">'.htmlspecialchars($officeSignx->list_effectivitydatejj[$i]).'</td>';
									echo '<td class="text-center">'.$status_lbl.'</td>';
									echo '<td>'.($disp_lat ?: '—').'</td>';
									echo '<td>'.($disp_lng ?: '—').'</td>';
									echo '<td>'.$meter_disp.'</td>';
									echo '<td>
											<button type="button" class="btn btn-warning btn-sm text-dark fw-bold"
												onclick="openEditModal(
													\''.addslashes($autoid).'\',
													\''.addslashes($officeSignx->list_agencycodejj[$i]).'\',
													\''.addslashes($officeSignx->list_officeidjj[$i]).'\',
													\''.addslashes($officeSignx->list_officecodejj[$i]).'\',
													\''.addslashes($officeSignx->list_officenamejj[$i]).'\',
													\''.addslashes($officeSignx->list_officetitlejj[$i]).'\',
													\''.addslashes($officeSignx->list_officeabrvjj[$i]).'\',
													\''.addslashes($officeSignx->list_oldofficeabrvjj[$i]).'\',
													\''.addslashes($officeSignx->list_headofficerjj[$i]).'\',
													\''.addslashes($officeSignx->list_headtitlejj[$i]).'\',
													\''.addslashes($officeSignx->list_authheadjj[$i]).'\',
													\''.addslashes($officeSignx->list_authtitlejj[$i]).'\',
													\''.addslashes($officeSignx->list_authdescriptionjj[$i]).'\',
													\''.addslashes($officeSignx->list_effectivitydatejj[$i]).'\',
													\''.addslashes($status_val).'\',
													\''.addslashes($raw_gps).'\',
													\''.addslashes($raw_meter ?? '').'\',
													\''.addslashes($officeSignx->list_modifiedatjj[$i]).'\'
												)">
												<i class="fas fa-edit me-1"></i>Edit
											</button>
										</td>';
								echo '</tr>';
							}
						} else {
							echo '<tr><td colspan="19">No records found.</td></tr>';
						}

					?>
				</tbody>

				<tfoot>
					<tr>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
						<td class="remove-dropdown"></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<!-- Edit Modal -->
<div class="modal fade text-dark" id="modalEditOffice" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title"><i class="fas fa-building me-2"></i>Edit Office Record</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

			<form id="formEditOffice" onsubmit="saveOfficeEdit(event)">
				<div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

					<input type="hidden" id="edit_autoid">

					<h6 class="fw-bold border-bottom pb-2 mb-3">Office Information</h6>

					<div class="row g-2 mb-3">
						<div class="col-md-4">
							<label class="form-label">Agency Code</label>
							<input type="text" class="form-control" id="edit_agency_code">
						</div>
						<div class="col-md-4">
							<label class="form-label">Office ID</label>
							<input type="text" class="form-control" id="edit_officeid">
						</div>
						<div class="col-md-4">
							<label class="form-label">Office Code</label>
							<input type="text" class="form-control" id="edit_officecode">
						</div>
					</div>

					<div class="row g-2 mb-3">
						<div class="col-md-6">
							<label class="form-label">Office Name</label>
							<input type="text" class="form-control" id="edit_officename">
						</div>
						<div class="col-md-6">
							<label class="form-label">Office Title</label>
							<input type="text" class="form-control" id="edit_officetitle">
						</div>
					</div>

					<div class="row g-2 mb-3">
						<div class="col-md-6">
							<label class="form-label">Abbreviation</label>
							<input type="text" class="form-control" id="edit_officeabrv">
						</div>
						<div class="col-md-6">
							<label class="form-label">Old Abbreviation</label>
							<input type="text" class="form-control" id="edit_oldofficeabrv">
						</div>
					</div>

					<h6 class="fw-bold border-bottom pb-2 mb-3 mt-4">Head & Signatory</h6>

					<div class="row g-2 mb-3">
						<div class="col-md-6">
							<label class="form-label">Head Officer</label>
							<input type="text" class="form-control" id="edit_headofficer">
						</div>
						<div class="col-md-6">
							<label class="form-label">Head Title</label>
							<input type="text" class="form-control" id="edit_headtitle">
						</div>
					</div>

					<div class="row g-2 mb-3">
						<div class="col-md-6">
							<label class="form-label">Auth Head</label>
							<input type="text" class="form-control" id="edit_auth_head">
						</div>
						<div class="col-md-6">
							<label class="form-label">Auth Title</label>
							<input type="text" class="form-control" id="edit_auth_title">
						</div>
					</div>

					<div class="mb-3">
						<label class="form-label">Auth Description</label>
						<textarea class="form-control" id="edit_auth_description" rows="2"></textarea>
					</div>

					<h6 class="fw-bold border-bottom pb-2 mb-3 mt-4">Other Details</h6>

					<div class="row g-2 mb-3">
						<div class="col-md-6">
							<label class="form-label">Effectivity Date</label>
							<input type="date" class="form-control" id="edit_effectivity_date">
						</div>
						<div class="col-md-6">
							<label class="form-label">Status</label>
							<select class="form-select" id="edit_signatory_status">
								<option value="1">Active</option>
								<option value="0">Inactive</option>
							</select>
						</div>
					</div>

					<div class="row g-2 mb-3">
						<div class="col-md-4">
							<label class="form-label">Latitude</label>
							<input type="text" class="form-control" id="edit_office_lat" placeholder="e.g. 10.3157">
						</div>
						<div class="col-md-4">
							<label class="form-label">Longitude</label>
							<input type="text" class="form-control" id="edit_office_lng" placeholder="e.g. 123.8854">
						</div>
						<div class="col-md-4">
							<label class="form-label">Meter</label>
							<input type="number" step="0.01" class="form-control" id="edit_meter" placeholder="e.g. 150.00">
						</div>
					</div>

					<div class="mb-2">
						<label class="form-label text-muted small">Modified At (auto-updated)</label>
						<input type="text" class="form-control form-control-sm text-muted" id="edit_modified_at" readonly>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-warning fw-bold text-dark" id="btnSaveOffice">
						<i class="fas fa-save me-1"></i>Save Changes
					</button>
				</div>
			</form>

		</div>
	</div>
</div>

<script>
	function openEditModal(autoid, agency_code, officeid, officecode, officename, officetitle,
		officeabrv, oldofficeabrv, headofficer, headtitle, auth_head, auth_title,
		auth_description, effectivity_date, signatory_status, office_gps_location,
		meter, modified_at) {

		var gpsParts = office_gps_location ? office_gps_location.split(',') : ['', ''];
		var gpsLat   = gpsParts[0] ? gpsParts[0].trim() : '';
		var gpsLng   = gpsParts[1] ? gpsParts[1].trim() : '';

		document.getElementById('edit_autoid').value              = autoid;
		document.getElementById('edit_agency_code').value         = agency_code;
		document.getElementById('edit_officeid').value            = officeid;
		document.getElementById('edit_officecode').value          = officecode;
		document.getElementById('edit_officename').value          = officename;
		document.getElementById('edit_officetitle').value         = officetitle;
		document.getElementById('edit_officeabrv').value          = officeabrv;
		document.getElementById('edit_oldofficeabrv').value       = oldofficeabrv;
		document.getElementById('edit_headofficer').value         = headofficer;
		document.getElementById('edit_headtitle').value           = headtitle;
		document.getElementById('edit_auth_head').value           = auth_head;
		document.getElementById('edit_auth_title').value          = auth_title;
		document.getElementById('edit_auth_description').value    = auth_description;
		document.getElementById('edit_effectivity_date').value    = effectivity_date;
		document.getElementById('edit_signatory_status').value    = signatory_status;
		document.getElementById('edit_office_lat').value          = gpsLat;
		document.getElementById('edit_office_lng').value          = gpsLng;
		document.getElementById('edit_meter').value               = meter || '';
		document.getElementById('edit_modified_at').value         = modified_at;

		var modal = new bootstrap.Modal(document.getElementById('modalEditOffice'));
		modal.show();
	}

	function saveOfficeEdit(e) {
		e.preventDefault();

		var btn = document.getElementById('btnSaveOffice');
		var origHtml = btn.innerHTML;
		btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
		btn.disabled = true;

		var formData = new FormData();
		formData.append('autoid',               document.getElementById('edit_autoid').value);
		formData.append('agency_code',          document.getElementById('edit_agency_code').value);
		formData.append('officeid',             document.getElementById('edit_officeid').value);
		formData.append('officecode',           document.getElementById('edit_officecode').value);
		formData.append('officename',           document.getElementById('edit_officename').value);
		formData.append('officetitle',          document.getElementById('edit_officetitle').value);
		formData.append('officeabrv',           document.getElementById('edit_officeabrv').value);
		formData.append('oldofficeabrv',        document.getElementById('edit_oldofficeabrv').value);
		formData.append('headofficer',          document.getElementById('edit_headofficer').value);
		formData.append('headtitle',            document.getElementById('edit_headtitle').value);
		formData.append('auth_head',            document.getElementById('edit_auth_head').value);
		formData.append('auth_title',           document.getElementById('edit_auth_title').value);
		formData.append('auth_description',     document.getElementById('edit_auth_description').value);
		formData.append('effectivity_date',     document.getElementById('edit_effectivity_date').value);
		formData.append('signatory_status',     document.getElementById('edit_signatory_status').value);
		var _lat = document.getElementById('edit_office_lat').value.trim();
		var _lng = document.getElementById('edit_office_lng').value.trim();
		formData.append('office_gps_location',  (_lat && _lng) ? _lat + ',' + _lng : (_lat || _lng));
		formData.append('meter',                document.getElementById('edit_meter').value.trim());

		fetch('model/office-signatory/update.php', {
			method: 'POST',
			body: formData
		})
		.then(function(response) { return response.json(); })
		.then(function(data) {
			btn.innerHTML = origHtml;
			btn.disabled = false;

			if (data.status === 'success') {
				alert('Office record updated successfully.');
				location.reload();
			} else {
				alert('Error: ' + (data.message || 'Could not save changes.'));
			}
		})
		.catch(function(err) {
			btn.innerHTML = origHtml;
			btn.disabled = false;
			alert('Request failed: ' + err.message);
		});
	}
</script>

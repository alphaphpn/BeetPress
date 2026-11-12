<?php
	if (empty($_SESSION['ore_transport_permit_csrf'])) {
		$_SESSION['ore_transport_permit_csrf'] = bin2hex(random_bytes(32));
	}
	$csrfToken = $_SESSION['ore_transport_permit_csrf'];
	$currentUser = trim((string) ($_SESSION['d2s8wu_uname'] ?? $_SESSION['d2s8wu_uid'] ?? 'System'));

	try {
		$db = new myDatabase();
		$conn = $db->getConnection();
		$conn->exec("CREATE TABLE IF NOT EXISTS ore_transport_permit_tbl (
			permit_autoid INT AUTO_INCREMENT PRIMARY KEY,
			permit_no VARCHAR(191) NOT NULL,
			otp_no VARCHAR(191) NOT NULL DEFAULT '',
			permittee_name TEXT NOT NULL,
			date_approved DATE NULL,
			date_issuance DATE NULL,
			date_expiry DATE NULL,
			operator_name TEXT NULL,
			transporter_name TEXT NULL,
			truck_registry_no TEXT NULL,
			ship_registry_no TEXT NULL,
			buyer_name TEXT NULL,
			commodity TEXT NULL,
			tonnage_volume TEXT NULL,
			permit_value DECIMAL(15,2) NULL,
			source_location TEXT NULL,
			destination_location TEXT NULL,
			permit_file VARCHAR(255) NULL,
			xdel INT(1) NOT NULL DEFAULT 0,
			createdby TEXT NULL,
			modifiedby TEXT NULL,
			modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			UNIQUE KEY uniq_permit_no (permit_no)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
		$col = $conn->query("SHOW COLUMNS FROM ore_transport_permit_tbl LIKE 'otp_no'");
		if ($col && $col->rowCount() == 0) {
			$conn->exec("ALTER TABLE ore_transport_permit_tbl ADD COLUMN otp_no VARCHAR(191) NOT NULL DEFAULT '' AFTER permit_no");
		}

		$stmt = $conn->prepare("SELECT * FROM ore_transport_permit_tbl WHERE xdel = 0 ORDER BY permit_autoid DESC");
		$stmt->execute();
		$permits = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		$permits = array();
		$loadError = $e->getMessage();
	}
?>

<div class="pt-3">
	<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
		<div>
			<h5 class="mb-1 fw-bold text-light">ORE Transport Permit</h5>
			<p class="text-muted mb-0">List of permits with add, edit, delete, and file upload.</p>
		</div>
		<div>
			<button type="button" class="btn btn-primary" id="btnAddPermit">
				<i class="fas fa-plus"></i> Add New ORE Transport Permit
			</button>
		</div>
	</div>

	<div id="permitAlert"></div>
	<?php if (!empty($loadError)): ?>
		<div class="alert alert-danger">Unable to load permits.</div>
	<?php endif; ?>

	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table id="orePermitTable" class="table table-dark table-striped table-hover align-middle" style="width:100%">
					<thead>
						<tr>
							<th>OTP No.</th>
							<th>Permit No.</th>
							<th>Name of Permittee</th>
							<th>Date Approved</th>
							<th>Date Issuance</th>
							<th>Date Expiry</th>
							<th>Operator</th>
							<th>Transporter</th>
							<th>Truck Registry No.</th>
							<th>Ship Registry No.</th>
							<th>Buyer</th>
							<th>Commodity</th>
							<th>Tonnage or Volume</th>
							<th>Value</th>
							<th>Source</th>
							<th>Destination</th>
							<th>File</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($permits as $permit): ?>
							<?php
								$permitNo = (string) ($permit['permit_no'] ?? '');
								$fileName = trim((string) ($permit['permit_file'] ?? ''));
								$fileBase = $fileName !== '' ? pathinfo($fileName, PATHINFO_FILENAME) : $permitNo;
								$previewHref = $domainhome . '/ore-transport-permit?pid=' . rawurlencode($fileBase);
								$valueNum = $permit['permit_value'];
								$valueDisplay = ($valueNum !== null && $valueNum !== '') ? number_format((float) $valueNum, 2) : '';
								$json = htmlspecialchars(json_encode($permit, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');
							?>
							<tr data-permit='<?php echo $json; ?>'>
								<td><?php echo htmlspecialchars((string) ($permit['otp_no'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars($permitNo); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['permittee_name'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['date_approved'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['date_issuance'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['date_expiry'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['operator_name'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['transporter_name'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['truck_registry_no'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['ship_registry_no'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['buyer_name'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['commodity'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['tonnage_volume'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars($valueDisplay); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['source_location'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string) ($permit['destination_location'] ?? '')); ?></td>
								<td>
									<?php if ($fileName !== ''): ?>
										<a href="<?php echo htmlspecialchars($previewHref, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Preview</a>
									<?php else: ?>
										<span class="text-muted">No file</span>
									<?php endif; ?>
								</td>
								<td class="text-nowrap">
									<button type="button" class="btn btn-sm btn-outline-info btn-edit-permit" title="Edit"><i class="fas fa-edit"></i></button>
									<button type="button" class="btn btn-sm btn-outline-danger btn-delete-permit" title="Delete"><i class="fas fa-trash"></i></button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="permitQrModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Permit QR Code</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body text-center">
				<p class="mb-2">Permit No.: <strong id="qrPermitNoLabel"></strong></p>
				<div id="permitQrCode" class="d-inline-block p-2 bg-white"></div>
				<div class="small text-muted mt-2" id="qrPermitUrl" style="word-break: break-all;"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" id="btnQrClose">Close</button>
				<button type="button" class="btn btn-primary" id="btnQrPrintPreview"><i class="fas fa-print"></i> Print Preview</button>
			</div>
		</div>
	</div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="permitModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<form id="permitForm" class="modal-content" enctype="multipart/form-data">
			<div class="modal-header">
				<h5 class="modal-title" id="permitModalTitle">Add New ORE Transport Permit</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
				<input type="hidden" name="permit_autoid" id="permit_autoid" value="">
				<div id="permitFormAlert"></div>
				<div class="row g-3">
					<div class="col-md-4">
						<label class="form-label" for="otp_no">OTP No. <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="otp_no" name="otp_no" required>
					</div>
					<div class="col-md-4">
						<label class="form-label" for="permit_no">Permit No. <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="permit_no" name="permit_no" required>
						<div class="form-text" id="permitNoStatus"></div>
					</div>
					<div class="col-md-4">
						<label class="form-label" for="permittee_name">Name of Permittee <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="permittee_name" name="permittee_name" required>
					</div>
					<div class="col-md-4">
						<label class="form-label" for="date_approved">Date Approved</label>
						<input type="date" class="form-control" id="date_approved" name="date_approved">
					</div>
					<div class="col-md-4">
						<label class="form-label" for="date_issuance">Date Issuance</label>
						<input type="date" class="form-control" id="date_issuance" name="date_issuance">
					</div>
					<div class="col-md-4">
						<label class="form-label" for="date_expiry">Date Expiry</label>
						<input type="date" class="form-control" id="date_expiry" name="date_expiry">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="operator_name">Operator</label>
						<input type="text" class="form-control" id="operator_name" name="operator_name">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="transporter_name">Transporter</label>
						<input type="text" class="form-control" id="transporter_name" name="transporter_name">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="truck_registry_no">Truck Registry No.</label>
						<input type="text" class="form-control" id="truck_registry_no" name="truck_registry_no">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="ship_registry_no">Ship Registry No.</label>
						<input type="text" class="form-control" id="ship_registry_no" name="ship_registry_no">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="buyer_name">Buyer</label>
						<input type="text" class="form-control" id="buyer_name" name="buyer_name">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="commodity">Commodity</label>
						<input type="text" class="form-control" id="commodity" name="commodity">
					</div>
					<div class="col-md-4">
						<label class="form-label" for="tonnage_volume">Tonnage or Volume</label>
						<input type="text" class="form-control" id="tonnage_volume" name="tonnage_volume">
					</div>
					<div class="col-md-4">
						<label class="form-label" for="permit_value">Value (Currency)</label>
						<input type="number" step="0.01" min="0" class="form-control" id="permit_value" name="permit_value">
					</div>
					<div class="col-md-4">
						<label class="form-label" for="permit_file">File Upload (PDF)</label>
						<input type="file" class="form-control" id="permit_file" name="permit_file" accept="application/pdf,.pdf">
						<div class="form-text">Saved to public/ore/transport/permit/ as the permit number filename.</div>
						<div class="form-text" id="currentFileInfo"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label" for="source_location">Source</label>
						<input type="text" class="form-control" id="source_location" name="source_location">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="destination_location">Destination</label>
						<input type="text" class="form-control" id="destination_location" name="destination_location">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary" id="btnSavePermit">Save Permit</button>
			</div>
		</form>
	</div>
</div>

<script src="<?php echo $domainhome; ?>/assets/gh/davidshimjs/qrcodejs@gh-pages/qrcode.min.js"></script>
<script>
(function() {
	function showAlert(containerId, type, message) {
		var el = document.getElementById(containerId);
		if (!el) return;
		el.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
			message +
			'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
	}

	function clearAlert(containerId) {
		var el = document.getElementById(containerId);
		if (el) el.innerHTML = '';
	}

	function formatDate(value) {
		if (!value) return '';
		return String(value).substring(0, 10);
	}

	function resetForm() {
		var form = document.getElementById('permitForm');
		form.reset();
		document.getElementById('permit_autoid').value = '';
		document.getElementById('permit_no').readOnly = false;
		document.getElementById('currentFileInfo').textContent = '';
		document.getElementById('permitNoStatus').textContent = '';
		document.getElementById('permitNoStatus').className = 'form-text';
		clearAlert('permitFormAlert');
	}

	function fillForm(data) {
		document.getElementById('permit_autoid').value = data.permit_autoid || '';
		document.getElementById('permit_no').value = data.permit_no || '';
		document.getElementById('permit_no').readOnly = false;
		document.getElementById('otp_no').value = data.otp_no || '';
		document.getElementById('permittee_name').value = data.permittee_name || '';
		document.getElementById('date_approved').value = formatDate(data.date_approved);
		document.getElementById('date_issuance').value = formatDate(data.date_issuance);
		document.getElementById('date_expiry').value = formatDate(data.date_expiry);
		document.getElementById('operator_name').value = data.operator_name || '';
		document.getElementById('transporter_name').value = data.transporter_name || '';
		document.getElementById('truck_registry_no').value = data.truck_registry_no || '';
		document.getElementById('ship_registry_no').value = data.ship_registry_no || '';
		document.getElementById('buyer_name').value = data.buyer_name || '';
		document.getElementById('commodity').value = data.commodity || '';
		document.getElementById('tonnage_volume').value = data.tonnage_volume || '';
		document.getElementById('permit_value').value = (data.permit_value !== null && data.permit_value !== undefined && data.permit_value !== '') ? data.permit_value : '';
		document.getElementById('source_location').value = data.source_location || '';
		document.getElementById('destination_location').value = data.destination_location || '';
		document.getElementById('permit_file').value = '';
		document.getElementById('currentFileInfo').textContent = data.permit_file ? ('Current file: ' + data.permit_file) : 'No file uploaded yet.';
		document.getElementById('permitNoStatus').textContent = '';
		document.getElementById('permitNoStatus').className = 'form-text';
		clearAlert('permitFormAlert');
	}

	var permitTable;
	$(document).ready(function() {
		permitTable = $('#orePermitTable').DataTable({
			pageLength: 10,
			lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
			scrollX: true,
			order: []
		});
	});

	var qrModalEl = document.getElementById('permitQrModal');
	var qrModal = null;
	var qrInstance = null;
	var currentQrUrl = '';
	var currentQrPermitNo = '';
	function getQrModal() {
		if (!qrModal) qrModal = bootstrap.Modal.getOrCreateInstance(qrModalEl);
		return qrModal;
	}
	function showPermitQr(permitNo) {
		currentQrPermitNo = permitNo || '';
		currentQrUrl = 'http://222.167.240.254/ore-transport-permit?pid=' + encodeURIComponent(currentQrPermitNo);
		document.getElementById('qrPermitNoLabel').textContent = currentQrPermitNo;
		document.getElementById('qrPermitUrl').textContent = currentQrUrl;
		var container = document.getElementById('permitQrCode');
		container.innerHTML = '';
		qrInstance = null;
		if (typeof QRCode !== 'undefined') {
			qrInstance = new QRCode(container, {
				text: currentQrUrl,
				width: 220,
				height: 220,
				correctLevel: QRCode.CorrectLevel.M
			});
		} else {
			container.innerHTML = '<div class="text-danger">QR library not available.</div>';
		}
		getQrModal().show();
	}
	var qrReloaded = false;
	function reloadAfterQr() {
		if (qrReloaded) return;
		qrReloaded = true;
		window.location.reload();
	}
	document.getElementById('btnQrClose').addEventListener('click', function() {
		getQrModal().hide();
	});
	qrModalEl.addEventListener('hidden.bs.modal', function () {
		// reload so newly created permit appears in list
		reloadAfterQr();
	});
	document.getElementById('btnQrPrintPreview').addEventListener('click', function() {
		var container = document.getElementById('permitQrCode');
		var img = container.querySelector('img');
		var canvas = container.querySelector('canvas');
		var dataUrl = '';
		if (img && img.src) {
			dataUrl = img.src;
		} else if (canvas && canvas.toDataURL) {
			dataUrl = canvas.toDataURL('image/png');
		}
		var w = window.open('', '_blank');
		if (!w) { alert('Unable to open print preview window. Please allow pop-ups.'); return; }
		var html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Permit QR Code</title>' +
			'<style>body{margin:0;display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:Arial,sans-serif;} .wrap{text-align:center;} img{width:320px;height:320px;} .permit{margin-top:12px;font-size:18px;font-weight:bold;} .url{margin-top:6px;font-size:12px;word-break:break-all;max-width:420px;margin-left:auto;margin-right:auto;}</style>' +
			'</head><body><div class="wrap">' +
			(dataUrl ? '<img src="' + dataUrl + '" alt="QR Code">' : '<div>QR Code not available</div>') +
			'<div class="permit">Permit No.: ' + String(currentQrPermitNo).replace(/</g,'&lt;') + '</div>' +
			'<div class="url">' + String(currentQrUrl).replace(/</g,'&lt;') + '</div>' +
			'</div><script>window.onload=function(){window.focus();window.print();};<\/script></body></html>';
		w.document.open();
		w.document.write(html);
		w.document.close();
	});

	var modalEl = document.getElementById('permitModal');
	var permitModal = null;
	function getModal() {
		if (!permitModal) {
			permitModal = bootstrap.Modal.getOrCreateInstance(modalEl);
		}
		return permitModal;
	}

	document.getElementById('btnAddPermit').addEventListener('click', function() {
		resetForm();
		document.getElementById('permitModalTitle').textContent = 'Add New ORE Transport Permit';
		getModal().show();
	});

	$('#orePermitTable').on('click', '.btn-edit-permit', function() {
		var tr = $(this).closest('tr');
		var data = tr.attr('data-permit');
		if (!data) return;
		try { data = JSON.parse(data); } catch (e) { return; }
		resetForm();
		fillForm(data);
		document.getElementById('permitModalTitle').textContent = 'Edit ORE Transport Permit';
		getModal().show();
	});

	async function checkPermitExists(permitNo, excludeId) {
		var url = 'model/oretransportpermit/check.php?permit_no=' + encodeURIComponent(permitNo) + '&exclude_id=' + encodeURIComponent(excludeId || 0);
		var res = await fetch(url, { credentials: 'same-origin' });
		return res.json();
	}

	var permitNoInput = document.getElementById('permit_no');
	var permitNoTimer = null;
	permitNoInput.addEventListener('input', function() {
		var permitId = document.getElementById('permit_autoid').value || 0;
		var status = document.getElementById('permitNoStatus');
		var val = permitNoInput.value.trim();
		clearTimeout(permitNoTimer);
		if (!val) { status.textContent = ''; status.className = 'form-text'; return; }
		status.textContent = 'Checking permit number...';
		status.className = 'form-text text-muted';
		permitNoTimer = setTimeout(async function() {
			try {
				var data = await checkPermitExists(val, permitId);
				if (data.status === 'ok' && data.exists) {
					status.textContent = 'Permit No. already exists. Duplicate permit numbers are not allowed.';
					status.className = 'form-text text-danger';
				} else if (data.status === 'ok') {
					status.textContent = 'Permit No. is available.';
					status.className = 'form-text text-success';
				} else {
					status.textContent = 'Unable to verify permit number right now.';
					status.className = 'form-text text-warning';
				}
			} catch (e) {
				status.textContent = 'Unable to verify permit number right now.';
				status.className = 'form-text text-warning';
			}
		}, 400);
	});

	document.getElementById('permitForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		clearAlert('permitFormAlert');
		var form = e.currentTarget;
		var permitId = document.getElementById('permit_autoid').value;
		var permitNo = document.getElementById('permit_no').value.trim();
		if (!permitNo) {
			showAlert('permitFormAlert', 'danger', 'Permit No. is required.');
			return;
		}
		if (!document.getElementById('otp_no').value.trim()) {
			showAlert('permitFormAlert', 'danger', 'OTP No. is required.');
			return;
		}
		if (!document.getElementById('permittee_name').value.trim()) {
			showAlert('permitFormAlert', 'danger', 'Name of Permittee is required.');
			return;
		}

		var saveBtn = document.getElementById('btnSavePermit');
		saveBtn.disabled = true;

		try {
			var dup = await checkPermitExists(permitNo, permitId || 0);
			if (dup.status === 'ok' && dup.exists) {
				showAlert('permitFormAlert', 'danger', 'Permit No. already exists. Duplicate permit numbers are not allowed.');
				saveBtn.disabled = false;
				return;
			}

			var fd = new FormData(form);
			var res = await fetch('model/oretransportpermit/save.php', {
				method: 'POST',
				body: fd,
				credentials: 'same-origin'
			});
			var result = await res.json();
			if (result.status === 'success') {
				getModal().hide();
				if (result.action === 'created') {
					showAlert('permitAlert', 'success', result.message || 'Saved successfully.');
					showPermitQr(result.permit_no || permitNo);
				} else {
					showAlert('permitAlert', 'success', result.message || 'Saved successfully.');
					setTimeout(function() { window.location.reload(); }, 700);
				}
			} else {
				showAlert('permitFormAlert', 'danger', result.message || 'Unable to save permit.');
			}
		} catch (err) {
			showAlert('permitFormAlert', 'danger', 'An error occurred while saving the permit.');
		} finally {
			saveBtn.disabled = false;
		}
	});

	$('#orePermitTable').on('click', '.btn-delete-permit', async function() {
		var tr = $(this).closest('tr');
		var data = tr.attr('data-permit');
		if (!data) return;
		try { data = JSON.parse(data); } catch (e) { return; }
		if (!confirm('Delete permit "' + (data.permit_no || '') + '"? This action cannot be undone.')) return;

		try {
			var fd = new FormData();
			fd.append('permit_autoid', data.permit_autoid);
			var res = await fetch('model/oretransportpermit/delete.php', {
				method: 'POST',
				body: fd,
				credentials: 'same-origin'
			});
			var result = await res.json();
			if (result.status === 'success') {
				showAlert('permitAlert', 'success', result.message || 'Permit deleted.');
				setTimeout(function() { window.location.reload(); }, 500);
			} else {
				showAlert('permitAlert', 'danger', result.message || 'Unable to delete permit.');
			}
		} catch (e) {
			showAlert('permitAlert', 'danger', 'An error occurred while deleting the permit.');
		}
	});
})();
</script>
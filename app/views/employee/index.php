
	<style>
		.employee-verification { min-height: calc(100vh - 160px); background: linear-gradient(135deg, #f7f9fc 0%, #eef3f8 100%); }
		.verification-panel, .employee-result-card { border: 0; border-radius: 1rem; box-shadow: 0 1rem 2.5rem rgba(29, 53, 87, .10); }
		.qr-reader { min-height: 245px; overflow: hidden; border: 2px dashed #aebdce; border-radius: .75rem; background: #f8fafc; }
		.qr-reader video { width: 100% !important; border-radius: .65rem; }
		.or-divider { display: flex; align-items: center; gap: .75rem; color: #6c757d; font-size: .8rem; font-weight: 600; letter-spacing: .08em; }
		.or-divider::before, .or-divider::after { content: ''; flex: 1; height: 1px; background: #dee2e6; }
		.profile-photo, .profile-placeholder { width: 100%; height: 200px; border-radius: 20px; border: 5px solid #fff; box-shadow: 0 .5rem 1.25rem rgba(29, 53, 87, .18); }
		.profile-photo { object-fit: cover; background: #e9ecef; }
		.profile-placeholder { display: none; align-items: center; justify-content: center; background: #e9f1f9; color: #315a7d; font-size: 3.5rem; }
		.result-label { color: #6c757d; font-size: .74rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; }
		.result-value { color: #22313f; font-size: 1rem; font-weight: 600; }
	</style>

	<section class="employee-verification w-100 pt-4 pb-5 clearfix">
		<div class="container pb-4">
			<div class="row justify-content-center">
				<div class="col-lg-8 col-xl-7">
					<div class="text-center mb-4">
						<span class="badge text-bg-primary px-3 py-2 mb-2"><i class="fas fa-shield-alt me-1"></i> Employee Verification</span>
						<h1 class="h3 mb-2">Verify an employee record</h1>
						<p class="text-secondary mb-0">Scan the employee QR code or search using the details below.</p>
					</div>

					<div id="verification-search" class="card verification-panel">
						<div class="card-body p-4 p-md-5">
							<div class="text-center mb-3">
								<h2 class="h5 mb-1">Scan QR Code</h2>
								<p class="small text-secondary mb-0">Allow camera access, then place the QR code in view.</p>
							</div>
							<div id="qr-reader" class="qr-reader mb-3"></div>
							<div id="scanner-message" class="small text-secondary text-center mb-3">Camera scanner is ready to start.</div>
							<div class="d-flex justify-content-center gap-2 mb-4">
								<button id="start-scanner" type="button" class="btn btn-primary"><i class="fas fa-camera me-1"></i> Start Camera</button>
								<button id="stop-scanner" type="button" class="btn btn-outline-secondary d-none">Stop Camera</button>
							</div>

							<div class="or-divider mb-4">OR SEARCH MANUALLY</div>
							<form id="employee-search-form" novalidate>
								<label for="employee-number" class="form-label fw-semibold">Employee Number</label>
								<div class="input-group mb-4">
									<span class="input-group-text"><i class="fas fa-id-card"></i></span>
									<input id="employee-number" name="employee_number" type="text" class="form-control" autocomplete="off" placeholder="Enter employee number">
								</div>

								<div class="or-divider mb-4">OR USE PERSONAL DETAILS</div>
								<div class="row g-3">
									<div class="col-md-6"><label for="first-name" class="form-label">First Name</label><input id="first-name" name="first_name" type="text" class="form-control" autocomplete="given-name"></div>
									<div class="col-md-6"><label for="last-name" class="form-label">Last Name</label><input id="last-name" name="last_name" type="text" class="form-control" autocomplete="family-name"></div>
									<div class="col-md-6"><label for="gender" class="form-label">Gender</label><select id="gender" name="gender" class="form-select"><option value="">Select gender</option><option value="Male">Male</option><option value="Female">Female</option></select></div>
									<div class="col-md-6"><label for="birthday" class="form-label">Birthday</label><input id="birthday" name="birthday" type="date" class="form-control"></div>
								</div>
								<div id="search-message" class="alert d-none mt-4 mb-0" role="alert"></div>
								<button id="search-button" type="submit" class="btn btn-primary w-100 mt-4"><i class="fas fa-search me-1"></i> Verify Employee</button>
							</form>
						</div>
					</div>

					<div id="verification-result" class="card employee-result-card d-none">
						<div class="card-body p-4 p-md-5">
							<div class="text-center border-bottom pb-4 mb-4">
								<div class="position-relative d-inline-block mb-3"><img id="result-photo" class="profile-photo" alt="Employee profile photo"><div id="result-photo-placeholder" class="profile-placeholder"><i class="fas fa-user"></i></div></div>
								<div><span class="badge text-bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Verified Employee</span></div>
								<h2 id="result-name" class="h3 mt-3 mb-1"></h2>
								<p id="result-number" class="text-secondary mb-0"></p>
							</div>
							<div class="row g-4">
								<div class="col-sm-6"><div class="result-label"><i class="fas fa-birthday-cake me-1"></i> Age</div><div id="result-age" class="result-value"></div></div>
								<div class="col-sm-6"><div class="result-label"><i class="fas fa-venus-mars me-1"></i> Gender</div><div id="result-gender" class="result-value"></div></div>
								<div class="col-sm-6"><div class="result-label"><i class="fas fa-building me-1"></i> Office</div><div id="result-office" class="result-value"></div></div>
								<div class="col-sm-6"><div class="result-label"><i class="fas fa-briefcase me-1"></i> Designation / Position</div><div id="result-designation" class="result-value"></div></div>
							</div>
							<button id="new-search" type="button" class="btn btn-outline-primary w-100 mt-5"><i class="fas fa-redo me-1"></i> Verify Another Employee</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
	<script>
		(function () {
			const baseUrl = <?php echo json_encode(rtrim($domainhome, '/')); ?>;
			const endpoint = baseUrl + '/api/employeeverification/';
			const form = document.getElementById('employee-search-form');
			const searchPanel = document.getElementById('verification-search');
			const resultPanel = document.getElementById('verification-result');
			const message = document.getElementById('search-message');
			const scannerMessage = document.getElementById('scanner-message');
			const startButton = document.getElementById('start-scanner');
			const stopButton = document.getElementById('stop-scanner');
			let scanner = null;
			let scannerRunning = false;
			let scanHandled = false;

			function showMessage(text, type) {
				message.textContent = text;
				message.className = 'alert alert-' + type + ' mt-4 mb-0';
			}

			function calculateAge(birthday) {
				const birthDate = new Date(birthday + 'T00:00:00');
				const today = new Date();
				let age = today.getFullYear() - birthDate.getFullYear();
				const monthDifference = today.getMonth() - birthDate.getMonth();
				if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) age--;
				return Number.isFinite(age) && age >= 0 ? age + ' years old' : 'Not available';
			}

			function normalizeEmployeeNumber(value) {
				const match = String(value || '').trim().match(/^(\d{8})/);
				return match ? match[1] : String(value || '').trim();
			}

			function stopScanner() {
				if (!scanner || !scannerRunning) return Promise.resolve();
				return scanner.stop().catch(function () {}).finally(function () {
					scannerRunning = false;
					scanHandled = false;
					startButton.classList.remove('d-none');
					stopButton.classList.add('d-none');
				});
			}

			function showEmployee(employee) {
				document.getElementById('result-name').textContent = employee.emp_name_forid || 'Employee';
				document.getElementById('result-number').textContent = 'Employee No. ' + (employee.emp_idcode || '');
				document.getElementById('result-age').textContent = calculateAge(employee.birthday);
				document.getElementById('result-gender').textContent = employee.gender || 'Not available';
				document.getElementById('result-office').textContent = employee.officename_forid || 'Not available';
				document.getElementById('result-designation').textContent = employee.designationforid || 'Not available';
				const photo = document.getElementById('result-photo');
				const placeholder = document.getElementById('result-photo-placeholder');
				placeholder.style.display = 'none';
				photo.hidden = false;
				photo.onerror = function () { photo.hidden = true; placeholder.style.display = 'flex'; };
				photo.src = employee.profile_image;
				stopScanner().finally(function () { searchPanel.classList.add('d-none'); resultPanel.classList.remove('d-none'); });
			}

			function verify(values) {
				const button = document.getElementById('search-button');
				button.disabled = true;
				button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Searching...';
				message.classList.add('d-none');
				fetch(endpoint, { method: 'POST', body: new URLSearchParams(values) })
					.then(function (response) { return response.json().then(function (data) { return { ok: response.ok, data: data }; }); })
					.then(function (result) { if (!result.ok) throw new Error(result.data.message || 'Unable to verify this employee.'); showEmployee(result.data.employee); })
					.catch(function (error) { showMessage(error.message, 'warning'); })
					.finally(function () { button.disabled = false; button.innerHTML = '<i class="fas fa-search me-1"></i> Verify Employee'; });
			}

			form.addEventListener('submit', function (event) {
				event.preventDefault();
				const values = Object.fromEntries(new FormData(form).entries());
				values.employee_number = normalizeEmployeeNumber(values.employee_number);
				document.getElementById('employee-number').value = values.employee_number;
				if (values.employee_number.trim() === '' && (!values.first_name.trim() || !values.last_name.trim() || !values.gender || !values.birthday)) {
					showMessage('Enter an employee number, or complete first name, last name, gender, and birthday.', 'warning');
					return;
				}
				verify(values);
			});

			startButton.addEventListener('click', function () {
				if (!window.Html5Qrcode) { scannerMessage.textContent = 'QR scanner could not load. Please use manual search.'; return; }
				scanner = scanner || new Html5Qrcode('qr-reader');
				scannerMessage.textContent = 'Opening camera...';
				scanner.start({ facingMode: 'environment' }, { fps: 10, qrbox: { width: 220, height: 220 } }, function (decodedText) {
					if (!scannerRunning || scanHandled) return;
					scanHandled = true;
					const employeeNumber = normalizeEmployeeNumber(decodedText);
					document.getElementById('employee-number').value = employeeNumber;
					verify({ employee_number: employeeNumber });
				}, function () {}).then(function () {
					scannerRunning = true;
					scanHandled = false;
					scannerMessage.textContent = 'Scanning for an employee QR code...';
					startButton.classList.add('d-none'); stopButton.classList.remove('d-none');
				}).catch(function () { scannerMessage.textContent = 'Camera access was unavailable. Please allow access or use manual search.'; });
			});

			stopButton.addEventListener('click', stopScanner);
			document.getElementById('new-search').addEventListener('click', function () { resultPanel.classList.add('d-none'); searchPanel.classList.remove('d-none'); form.reset(); message.classList.add('d-none'); });
		}());
	</script>

<?php 

	include_once "app/views/index/feat-img.php";
	include_once "app/views/index/footer-info.php";

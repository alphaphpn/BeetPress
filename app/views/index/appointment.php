<?php
	$appointmentMessage = null;
	$appointmentError = null;
	$appointmentBooked = null;
	$appointmentForm = array('name' => '', 'phone' => '', 'email' => '', 'address' => '', 'purpose' => '', 'officeid' => '');
	$appointmentSlots = array();
	$appointmentOffices = array();
	$appointmentTimes = array();

	for ($hour = 9; $hour <= 16; $hour++) {
		$appointmentTimes[] = sprintf('%02d:00:00', $hour);
		$appointmentTimes[] = sprintf('%02d:30:00', $hour);
	}

	if (empty($_SESSION['appointment_csrf'])) {
		$_SESSION['appointment_csrf'] = bin2hex(random_bytes(32));
	}

	try {
		$appointmentCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$appointmentCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$appointmentCnn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$appointmentCnn->exec("CREATE TABLE IF NOT EXISTS appointment_tbl (
			appointment_autoid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			appointment_name VARCHAR(150) NOT NULL,
			appointment_phone VARCHAR(50) NOT NULL,
			appointment_email VARCHAR(150) DEFAULT NULL,
			appointment_address VARCHAR(255) DEFAULT NULL,
			appointment_purpose TEXT DEFAULT NULL,
			officeid VARCHAR(100) NOT NULL,
			officecode VARCHAR(100) NOT NULL,
			officetitle VARCHAR(255) NOT NULL,
			appointment_date DATE NOT NULL,
			appointment_time TIME NOT NULL,
			appointment_status VARCHAR(30) NOT NULL DEFAULT 'Pending',
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (appointment_autoid),
			UNIQUE KEY appointment_schedule_unique (appointment_date, appointment_time),
			KEY appointment_date_index (appointment_date)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		$officeStmt = $appointmentCnn->query("SELECT officeid, officecode, officetitle FROM office_signatory_tbl WHERE xdel = 0 ORDER BY officetitle ASC");
		foreach ($officeStmt->fetchAll(PDO::FETCH_ASSOC) as $office) {
			if (!empty($office['officeid']) && !isset($appointmentOffices[$office['officeid']])) {
				$appointmentOffices[$office['officeid']] = $office;
			}
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_submit'])) {
			foreach ($appointmentForm as $field => $value) {
				$appointmentForm[$field] = trim((string) ($_POST[$field] ?? ''));
			}
			$selectedDate = trim((string) ($_POST['appointment_date'] ?? ''));
			$selectedTime = trim((string) ($_POST['appointment_time'] ?? ''));
			$csrfToken = (string) ($_POST['appointment_csrf'] ?? '');
			$selectedDateObject = DateTime::createFromFormat('Y-m-d', $selectedDate);
			$today = new DateTime('today');
			$selectedSchedule = DateTime::createFromFormat('Y-m-d H:i:s', $selectedDate . ' ' . $selectedTime);

			if (!hash_equals($_SESSION['appointment_csrf'], $csrfToken)) {
				$appointmentError = 'Your booking session has expired. Please try again.';
			} elseif ($appointmentForm['name'] === '' || $appointmentForm['phone'] === '' || $appointmentForm['address'] === '' || $appointmentForm['purpose'] === '' || $appointmentForm['officeid'] === '') {
				$appointmentError = 'Please provide your name, phone number, address, purpose, and office.';
			} elseif ($appointmentForm['email'] !== '' && !filter_var($appointmentForm['email'], FILTER_VALIDATE_EMAIL)) {
				$appointmentError = 'Please enter a valid email address.';
			} elseif (!isset($appointmentOffices[$appointmentForm['officeid']])) {
				$appointmentError = 'Please select a valid office.';
			} elseif (!$selectedDateObject || $selectedDateObject->format('Y-m-d') !== $selectedDate || $selectedDateObject < $today || (int) $selectedDateObject->format('N') > 5) {
				$appointmentError = 'Please select an available weekday.';
			} elseif (!in_array($selectedTime, $appointmentTimes, true)) {
				$appointmentError = 'Please select an available appointment time.';
			} elseif (!$selectedSchedule || $selectedSchedule <= new DateTime()) {
				$appointmentError = 'Please select an appointment time that has not yet passed.';
			} else {
				$office = $appointmentOffices[$appointmentForm['officeid']];
				$scheduleLock = 'appointment_' . str_replace(array('-', ':'), '', $selectedDate . '_' . $selectedTime);
				$lockStatement = $appointmentCnn->prepare("SELECT GET_LOCK(:schedule_lock, 5)");
				$lockStatement->execute(array(':schedule_lock' => $scheduleLock));
				$hasScheduleLock = (int) $lockStatement->fetchColumn() === 1;
				try {
					if (!$hasScheduleLock) {
						$appointmentError = 'That time is currently being booked. Please choose another available time.';
					} else {
						$existingSchedule = $appointmentCnn->prepare("SELECT COUNT(*) FROM appointment_tbl WHERE appointment_date = :appointment_date AND appointment_time = :appointment_time AND appointment_status <> 'Cancelled'");
						$existingSchedule->execute(array(':appointment_date' => $selectedDate, ':appointment_time' => $selectedTime));
						if ((int) $existingSchedule->fetchColumn() > 0) {
							$appointmentError = 'That time was just booked. Please choose another available time.';
						} else {
							$insertAppointment = $appointmentCnn->prepare("INSERT INTO appointment_tbl
								(appointment_name, appointment_phone, appointment_email, appointment_address, appointment_purpose, officeid, officecode, officetitle, appointment_date, appointment_time)
								VALUES (:name, :phone, :email, :address, :purpose, :officeid, :officecode, :officetitle, :appointment_date, :appointment_time)");
							$insertAppointment->execute(array(
								':name' => $appointmentForm['name'], ':phone' => $appointmentForm['phone'], ':email' => $appointmentForm['email'] ?: null,
								':address' => $appointmentForm['address'], ':purpose' => $appointmentForm['purpose'],
								':officeid' => $office['officeid'], ':officecode' => $office['officecode'], ':officetitle' => $office['officetitle'],
								':appointment_date' => $selectedDate, ':appointment_time' => $selectedTime
							));
							$appointmentMessage = 'Your appointment request has been booked successfully.';
							$appointmentBooked = array(
								'name' => $appointmentForm['name'], 'phone' => $appointmentForm['phone'], 'email' => $appointmentForm['email'],
								'address' => $appointmentForm['address'], 'purpose' => $appointmentForm['purpose'],
								'office' => $office['officetitle'], 'date' => $selectedDate, 'time' => $selectedTime
							);
							$_SESSION['appointment_print_id'] = (int) $appointmentCnn->lastInsertId();
							$appointmentForm = array('name' => '', 'phone' => '', 'email' => '', 'address' => '', 'purpose' => '', 'officeid' => '');
						}
					}
				} catch (PDOException $exception) {
					if ($exception->getCode() === '23000') {
						$appointmentError = 'That time was just booked. Please choose another available time.';
					} else {
						throw $exception;
					}
				} finally {
					if ($hasScheduleLock) {
						$appointmentCnn->prepare("SELECT RELEASE_LOCK(:schedule_lock)")->execute(array(':schedule_lock' => $scheduleLock));
					}
				}
			}
		}

		$bookedSlotsStmt = $appointmentCnn->prepare("SELECT appointment_date, appointment_time FROM appointment_tbl WHERE appointment_date >= CURDATE() AND appointment_status <> 'Cancelled'");
		$bookedSlotsStmt->execute();
		foreach ($bookedSlotsStmt->fetchAll(PDO::FETCH_ASSOC) as $slot) {
			$date = $slot['appointment_date'];
			if (!isset($appointmentSlots[$date])) $appointmentSlots[$date] = array();
			$appointmentSlots[$date][] = substr($slot['appointment_time'], 0, 5);
		}
	} catch (PDOException $exception) {
		$appointmentError = 'Appointments are temporarily unavailable. Please try again later.';
	}
?>

<style>
	#appointment .appointment-calendar td { height: 52px; vertical-align: middle; }
	#appointment .appointment-calendar button { width: 100%; min-height: 38px; border: 0; background: transparent; }
	#appointment .appointment-calendar .calendar-available { color: #0d6efd; font-weight: 600; border-radius: .35rem; }
	#appointment .appointment-calendar .calendar-available:hover,
	#appointment .appointment-calendar .calendar-selected { color: #fff; background: #0d6efd; }
	#appointment .appointment-calendar .calendar-disabled { color: #adb5bd; cursor: not-allowed; }
	#appointment .time-slot { min-width: 105px; }
</style>

<section id="appointment" class="position-relative bg-light w-100 h-100 pt-5 pb-5 clearfix">
	<div class="container">
		<div class="w-100 text-center d-flex justify-content-center">
			<div class="text-center mb-3" style="width: fit-content;"><h2 class="slideanim text-center">Book Appointment</h2><hr></div>
		</div>
		<div class="row"><div class="slideanim col m-auto"><div class="position-relative clearfix"><div class="card"><div class="card-body">
			<?php if ($appointmentMessage): ?><div class="alert alert-success"><?php echo htmlspecialchars($appointmentMessage); ?></div><?php endif; ?>
			<?php if ($appointmentError): ?><div class="alert alert-danger"><?php echo htmlspecialchars($appointmentError); ?></div><?php endif; ?>
			<form method="post" id="appointment-form" novalidate>
				<input type="hidden" name="appointment_csrf" value="<?php echo htmlspecialchars($_SESSION['appointment_csrf']); ?>">
				<input type="hidden" name="appointment_date" id="appointment-date">
				<input type="hidden" name="appointment_time" id="appointment-time">
				<div class="row">
					<div class="col-lg-5 border-end pe-lg-4">
						<h5 class="mb-3">Your Details</h5>
						<div class="mb-3"><label class="form-label" for="appointment-name">Name <span class="text-danger">*</span></label><input class="form-control" id="appointment-name" name="name" required value="<?php echo htmlspecialchars($appointmentForm['name']); ?>"></div>
						<div class="mb-3"><label class="form-label" for="appointment-phone">Phone <span class="text-danger">*</span></label><input class="form-control" id="appointment-phone" name="phone" type="tel" required value="<?php echo htmlspecialchars($appointmentForm['phone']); ?>"></div>
						<div class="mb-3"><label class="form-label" for="appointment-email">Email</label><input class="form-control" id="appointment-email" name="email" type="email" value="<?php echo htmlspecialchars($appointmentForm['email']); ?>"></div>
						<div class="mb-3"><label class="form-label" for="appointment-address">Address <span class="text-danger">*</span></label><input class="form-control" id="appointment-address" name="address" required value="<?php echo htmlspecialchars($appointmentForm['address']); ?>"></div>
						<div class="mb-3"><label class="form-label" for="appointment-purpose">Purpose <span class="text-danger">*</span></label><textarea class="form-control" id="appointment-purpose" name="purpose" required rows="3"><?php echo htmlspecialchars($appointmentForm['purpose']); ?></textarea></div>
						<div class="mb-3"><label class="form-label" for="appointment-office">Office <span class="text-danger">*</span></label><select class="form-select" id="appointment-office" name="officeid" required><option value="">Select an office</option><?php foreach ($appointmentOffices as $office): ?><option value="<?php echo htmlspecialchars($office['officeid']); ?>" <?php echo $appointmentForm['officeid'] === $office['officeid'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($office['officetitle']); ?></option><?php endforeach; ?></select></div>
					</div>
					<div class="col-lg-7 ps-lg-4 pt-4 pt-lg-0">
						<h5>Select a Date &amp; Time</h5>
						<ul class="nav nav-tabs mb-3" id="appointment-tabs" role="tablist">
							<li class="nav-item" role="presentation"><button class="nav-link active" id="date-tab" data-bs-toggle="tab" data-bs-target="#date-pane" type="button">1. Select Date</button></li>
							<li class="nav-item" role="presentation"><button class="nav-link" id="time-tab" data-bs-toggle="tab" data-bs-target="#time-pane" type="button" disabled>2. Select Time</button></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="date-pane"><p id="calendar-help" class="text-muted small">Choose an office to see its available dates.</p><div id="appointment-calendar"></div><div class="text-end mt-3"><button type="button" class="btn btn-primary" id="date-next" disabled>Next</button></div></div>
							<div class="tab-pane fade" id="time-pane"><p id="selected-date-label" class="mb-3"></p><div id="appointment-times" class="d-flex flex-wrap gap-2"></div><div class="d-flex justify-content-between mt-4"><button type="button" class="btn btn-outline-secondary" id="time-back">Back</button><button type="submit" class="btn btn-success" name="appointment_submit" id="appointment-submit" disabled>Book Appointment</button></div></div>
						</div>
					</div>
				</div>
			</form>
		</div></div></div></div></div>
	</div>
</section>

<?php if ($appointmentBooked): ?>
<div class="modal fade" id="appointment-confirmation-modal" tabindex="-1" aria-labelledby="appointment-confirmation-title" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered"><div class="modal-content">
		<div class="modal-header"><h5 class="modal-title" id="appointment-confirmation-title">Appointment Booked</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
		<div class="modal-body">
			<p class="text-success fw-semibold">Your appointment request has been booked successfully.</p>
			<dl class="row mb-0">
				<dt class="col-sm-4">Name</dt><dd class="col-sm-8"><?php echo htmlspecialchars($appointmentBooked['name']); ?></dd>
				<dt class="col-sm-4">Phone</dt><dd class="col-sm-8"><?php echo htmlspecialchars($appointmentBooked['phone']); ?></dd>
				<?php if ($appointmentBooked['email'] !== ''): ?><dt class="col-sm-4">Email</dt><dd class="col-sm-8"><?php echo htmlspecialchars($appointmentBooked['email']); ?></dd><?php endif; ?>
				<dt class="col-sm-4">Address</dt><dd class="col-sm-8"><?php echo htmlspecialchars($appointmentBooked['address']); ?></dd>
				<dt class="col-sm-4">Purpose</dt><dd class="col-sm-8"><?php echo htmlspecialchars($appointmentBooked['purpose']); ?></dd>
				<dt class="col-sm-4">Office</dt><dd class="col-sm-8"><?php echo htmlspecialchars($appointmentBooked['office']); ?></dd>
				<dt class="col-sm-4">Schedule</dt><dd class="col-sm-8"><?php echo htmlspecialchars(date('F j, Y', strtotime($appointmentBooked['date']))); ?><br><?php echo htmlspecialchars(date('g:i A', strtotime($appointmentBooked['time']))); ?></dd>
			</dl>
		</div>
		<div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button><a class="btn btn-primary" href="<?php echo trim($domainhome); ?>/appointment-print" target="_blank" rel="noopener">Print Appointment</a></div>
	</div></div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const bookedSlots = <?php echo json_encode($appointmentSlots, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
	const times = <?php echo json_encode(array_map(function ($time) { return substr($time, 0, 5); }, $appointmentTimes)); ?>;
	const office = document.getElementById('appointment-office');
	const calendar = document.getElementById('appointment-calendar');
	const timesContainer = document.getElementById('appointment-times');
	const dateInput = document.getElementById('appointment-date');
	const timeInput = document.getElementById('appointment-time');
	const dateNext = document.getElementById('date-next');
	const timeTab = document.getElementById('time-tab');
	const submit = document.getElementById('appointment-submit');
	const calendarHelp = document.getElementById('calendar-help');
	let view = new Date(); view.setDate(1); view.setHours(0, 0, 0, 0);
	let selectedDate = ''; let selectedTime = '';
	const today = new Date(); today.setHours(0, 0, 0, 0);
	const now = new Date();
	const pad = number => String(number).padStart(2, '0');
	const dateKey = date => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
	const labelDate = key => new Date(`${key}T00:00:00`).toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
	const timeLabel = time => new Date(`2000-01-01T${time}`).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });

	function renderCalendar() {
		const year = view.getFullYear(), month = view.getMonth();
		const monthName = view.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
		const firstDay = new Date(year, month, 1); const start = (firstDay.getDay() + 6) % 7;
		const days = new Date(year, month + 1, 0).getDate(); let html = `<div class="d-flex justify-content-between align-items-center mb-2"><button type="button" class="btn btn-sm btn-outline-secondary" id="calendar-prev">&lsaquo;</button><strong>${monthName}</strong><button type="button" class="btn btn-sm btn-outline-secondary" id="calendar-next">&rsaquo;</button></div><table class="table table-bordered appointment-calendar mb-0"><thead><tr>${['Mon','Tue','Wed','Thu','Fri','Sat','Sun'].map(day => `<th class="small text-center">${day}</th>`).join('')}</tr></thead><tbody>`;
		let day = 1;
		for (let week = 0; week < 6 && day <= days; week++) { html += '<tr>'; for (let weekday = 0; weekday < 7; weekday++) { if ((week === 0 && weekday < start) || day > days) { html += '<td></td>'; continue; } const date = new Date(year, month, day++); const key = dateKey(date); const isUnavailable = date < today || weekday > 4 || (bookedSlots[key] || []).length >= times.length; const isSelected = key === selectedDate; html += `<td><button type="button" data-date="${key}" ${isUnavailable || !office.value ? 'disabled' : ''} class="${isUnavailable || !office.value ? 'calendar-disabled' : 'calendar-available'} ${isSelected ? 'calendar-selected' : ''}">${date.getDate()}</button></td>`; } html += '</tr>'; }
		calendar.innerHTML = html + '</tbody></table>';
		document.getElementById('calendar-prev').onclick = () => { const previous = new Date(view.getFullYear(), view.getMonth() - 1, 1); if (previous >= new Date(today.getFullYear(), today.getMonth(), 1)) { view = previous; renderCalendar(); } };
		document.getElementById('calendar-next').onclick = () => { view = new Date(view.getFullYear(), view.getMonth() + 1, 1); renderCalendar(); };
		calendar.querySelectorAll('[data-date]').forEach(button => button.onclick = () => { selectedDate = button.dataset.date; selectedTime = ''; dateInput.value = selectedDate; timeInput.value = ''; dateNext.disabled = false; submit.disabled = true; renderCalendar(); });
	}

	function renderTimes() { const booked = bookedSlots[selectedDate] || []; document.getElementById('selected-date-label').textContent = `Available times for ${labelDate(selectedDate)}`; timesContainer.innerHTML = times.map(time => { const slotDateTime = new Date(`${selectedDate}T${time}:00`); const unavailable = booked.includes(time) || slotDateTime <= now; return `<button type="button" class="btn time-slot ${unavailable ? 'btn-outline-secondary' : 'btn-outline-primary'}" data-time="${time}" ${unavailable ? 'disabled' : ''}>${timeLabel(time)}</button>`; }).join(''); timesContainer.querySelectorAll('[data-time]').forEach(button => button.onclick = () => { selectedTime = button.dataset.time; timeInput.value = selectedTime + ':00'; submit.disabled = false; timesContainer.querySelectorAll('[data-time]').forEach(item => item.classList.remove('active')); button.classList.add('active'); }); }

	office.addEventListener('change', function () { selectedDate = ''; selectedTime = ''; dateInput.value = ''; timeInput.value = ''; dateNext.disabled = true; timeTab.disabled = true; submit.disabled = true; calendarHelp.textContent = office.value ? 'Select an available weekday.' : 'Choose an office to see its available dates.'; renderCalendar(); });
	document.getElementById('date-next').onclick = () => { if (!selectedDate) return; renderTimes(); timeTab.disabled = false; bootstrap.Tab.getOrCreateInstance(timeTab).show(); };
	document.getElementById('time-back').onclick = () => bootstrap.Tab.getOrCreateInstance(document.getElementById('date-tab')).show();
	renderCalendar();
	<?php if ($appointmentBooked): ?>bootstrap.Modal.getOrCreateInstance(document.getElementById('appointment-confirmation-modal')).show();<?php endif; ?>
});
</script>

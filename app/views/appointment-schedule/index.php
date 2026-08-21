<?php
	$officeId = trim((string) ($_SESSION['d2s8wu_officeid'] ?? ''));
	$appointments = array();
	$appointmentLoadError = null;
	$appointmentStatusMessage = null;
	try {
		$appointmentScheduleCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$appointmentScheduleCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if (empty($_SESSION['appointment_schedule_csrf'])) $_SESSION['appointment_schedule_csrf'] = bin2hex(random_bytes(32));
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_toggle_status'])) {
			$appointmentId = (int) ($_POST['appointment_id'] ?? 0);
			$csrfToken = (string) ($_POST['appointment_schedule_csrf'] ?? '');
			if (!hash_equals($_SESSION['appointment_schedule_csrf'], $csrfToken)) {
				$appointmentLoadError = 'Your appointment update session has expired. Please try again.';
			} elseif ($appointmentId > 0) {
				$status = isset($_POST['appointment_confirmed']) ? 'Confirmed' : 'Pending';
				$updateAppointment = $appointmentScheduleCnn->prepare("UPDATE appointment_tbl SET appointment_status = :status WHERE appointment_autoid = :appointment_id AND officeid = :officeid AND appointment_status <> 'Cancelled'");
				$updateAppointment->execute(array(':status' => $status, ':appointment_id' => $appointmentId, ':officeid' => $officeId));
				$appointmentStatusMessage = $updateAppointment->rowCount() ? 'Appointment status updated.' : 'The appointment could not be updated.';
			}
		}
		$appointmentQuery = $appointmentScheduleCnn->prepare("SELECT appointment_autoid, appointment_name, appointment_phone, appointment_email, appointment_address, appointment_purpose, officetitle, appointment_date, appointment_time, appointment_status FROM appointment_tbl WHERE officeid = :officeid ORDER BY appointment_date ASC, appointment_time ASC");
		$appointmentQuery->execute(array(':officeid' => $officeId));
		$appointments = $appointmentQuery->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $exception) {
		$appointmentLoadError = 'Appointment schedules are not available yet.';
	}

	$calendarAppointments = array();
	foreach ($appointments as $appointment) {
		$date = $appointment['appointment_date'];
		if (!isset($calendarAppointments[$date])) $calendarAppointments[$date] = array();
		$calendarAppointments[$date][] = array(
			'time' => date('g:i A', strtotime($appointment['appointment_time'])),
			'name' => $appointment['appointment_name'],
			'purpose' => $appointment['appointment_purpose'],
			'status' => $appointment['appointment_status']
		);
	}
?>

<style>
	.appointment-admin-calendar td { height: 70px; vertical-align: top; }
	.appointment-admin-calendar .appointment-day { width: 100%; min-height: 52px; border: 0; background: transparent; text-align: left; padding: 7px; border-radius: .35rem; }
	.appointment-admin-calendar .appointment-booked { background: #0d6efd; color: #fff; cursor: pointer; }
	.appointment-admin-calendar .appointment-booked:hover { background: #0b5ed7; }
	.appointment-count { display: block; font-size: .72rem; margin-top: 3px; }
</style>

<div class="pt-3">
	<h5 class="mb-1 fw-bold text-light">Appointment Schedule</h5>
	<p class="text-muted mb-4">Bookings for your office only. Use the switch to confirm an appointment, or select a highlighted calendar date to view its schedule.</p>
	<?php if ($appointmentStatusMessage): ?><div class="alert alert-success"><?php echo htmlspecialchars($appointmentStatusMessage); ?></div><?php endif; ?>
	<?php if ($appointmentLoadError): ?><div class="alert alert-warning"><?php echo htmlspecialchars($appointmentLoadError); ?></div><?php endif; ?>
	<div class="row g-4">
		<div class="col-xl-6"><div class="card"><div class="card-body"><div id="appointment-admin-calendar"></div></div></div></div>
		<div class="col-xl-6"><div class="card"><div class="card-body"><h6 class="mb-3">Booked Appointments</h6><div class="table-responsive"><table id="listRecView" class="table table-dark table-striped table-hover align-middle"><thead><tr><th>Date</th><th>Time</th><th>Name</th><th>Phone</th><th>Purpose</th><th>Status</th><th>Confirmed</th></tr></thead><tbody>
			<?php foreach ($appointments as $appointment): ?><tr><td><?php echo htmlspecialchars(date('M j, Y', strtotime($appointment['appointment_date']))); ?></td><td><?php echo htmlspecialchars(date('g:i A', strtotime($appointment['appointment_time']))); ?></td><td><?php echo htmlspecialchars($appointment['appointment_name']); ?></td><td><?php echo htmlspecialchars($appointment['appointment_phone']); ?></td><td><?php echo htmlspecialchars($appointment['appointment_purpose']); ?></td><td><?php echo htmlspecialchars($appointment['appointment_status']); ?></td><td><form method="post" class="m-0"><input type="hidden" name="appointment_schedule_csrf" value="<?php echo htmlspecialchars($_SESSION['appointment_schedule_csrf']); ?>"><input type="hidden" name="appointment_id" value="<?php echo (int) $appointment['appointment_autoid']; ?>"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" name="appointment_confirmed" aria-label="Confirm appointment for <?php echo htmlspecialchars($appointment['appointment_name']); ?>" <?php echo $appointment['appointment_status'] === 'Confirmed' ? 'checked' : ''; ?> <?php echo $appointment['appointment_status'] === 'Cancelled' ? 'disabled' : ''; ?> onchange="this.form.submit()"><input type="hidden" name="appointment_toggle_status" value="1"></div></form></td></tr><?php endforeach; ?>
		</tbody></table></div></div></div></div>
	</div>
</div>

<div class="modal fade" id="appointment-date-modal" tabindex="-1" aria-labelledby="appointment-date-modal-title" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="appointment-date-modal-title">Booked Appointments</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body" id="appointment-date-modal-body"></div></div></div></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const schedules = <?php echo json_encode($calendarAppointments, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
	const calendar = document.getElementById('appointment-admin-calendar');
	const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('appointment-date-modal'));
	const modalTitle = document.getElementById('appointment-date-modal-title');
	const modalBody = document.getElementById('appointment-date-modal-body');
	let view = new Date(); view.setDate(1);
	const pad = number => String(number).padStart(2, '0');
	const dateKey = date => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
	const dateLabel = key => new Date(`${key}T00:00:00`).toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
	const escapeHtml = value => String(value || '').replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[char]));
	function renderCalendar() {
		const year = view.getFullYear(), month = view.getMonth(), first = new Date(year, month, 1), start = (first.getDay() + 6) % 7, days = new Date(year, month + 1, 0).getDate();
		let html = `<div class="d-flex justify-content-between align-items-center mb-2"><button type="button" id="appointment-calendar-prev" class="btn btn-sm btn-outline-secondary">&lsaquo;</button><strong>${view.toLocaleDateString(undefined, {month:'long', year:'numeric'})}</strong><button type="button" id="appointment-calendar-next" class="btn btn-sm btn-outline-secondary">&rsaquo;</button></div><table class="table table-bordered appointment-admin-calendar mb-0"><thead><tr>${['Mon','Tue','Wed','Thu','Fri','Sat','Sun'].map(day => `<th class="small text-center">${day}</th>`).join('')}</tr></thead><tbody>`;
		let day = 1; for (let week = 0; week < 6 && day <= days; week++) { html += '<tr>'; for (let weekday = 0; weekday < 7; weekday++) { if ((week === 0 && weekday < start) || day > days) { html += '<td></td>'; continue; } const date = new Date(year, month, day++), key = dateKey(date), booked = schedules[key] || [], tooltip = booked.map(item => `${item.time} — ${item.name}`).join('\n'); html += `<td><button type="button" class="appointment-day ${booked.length ? 'appointment-booked' : ''}" ${booked.length ? `data-date="${key}" title="${escapeHtml(tooltip)}"` : ''}>${date.getDate()}${booked.length ? `<span class="appointment-count">${booked.length} booked</span>` : ''}</button></td>`; } html += '</tr>'; }
		calendar.innerHTML = html + '</tbody></table>';
		document.getElementById('appointment-calendar-prev').onclick = () => { view = new Date(year, month - 1, 1); renderCalendar(); };
		document.getElementById('appointment-calendar-next').onclick = () => { view = new Date(year, month + 1, 1); renderCalendar(); };
		calendar.querySelectorAll('[data-date]').forEach(button => { new bootstrap.Tooltip(button, { placement: 'top' }); button.onclick = () => { const date = button.dataset.date, items = schedules[date]; modalTitle.textContent = `Appointments — ${dateLabel(date)}`; modalBody.innerHTML = `<div class="list-group">${items.map(item => `<div class="list-group-item"><strong>${escapeHtml(item.time)} — ${escapeHtml(item.name)}</strong><br><span class="small">${escapeHtml(item.purpose)}</span><br><span class="badge text-bg-secondary mt-1">${escapeHtml(item.status)}</span></div>`).join('')}</div>`; modal.show(); }; });
	}
	renderCalendar();
});
</script>

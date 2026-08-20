<?php
	$calendarOfficeId = trim((string) ($_SESSION['d2s8wu_officeid'] ?? ''));
	$calendarCanViewAll = in_array((int) ($_SESSION['d2s8wu_ulevel'] ?? 0), array(1, 2), true);
	$calendarMeetings = array();
	$calendarBirthdays = array();
	$calendarError = null;
	try {
		$calendarCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$calendarCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$calendarQuery = $calendarCanViewAll
			? $calendarCnn->prepare("SELECT officeid, officetitle, related_officeids, related_officetitles, meeting_date, meeting_time_from, meeting_time_to, meeting_title, meeting_with, meeting_description FROM meeting_schedule_tbl ORDER BY meeting_date ASC, meeting_time_from ASC")
			: $calendarCnn->prepare("SELECT officeid, officetitle, related_officeids, related_officetitles, meeting_date, meeting_time_from, meeting_time_to, meeting_title, meeting_with, meeting_description FROM meeting_schedule_tbl WHERE officeid = :officeid OR JSON_CONTAINS(COALESCE(related_officeids, '[]'), JSON_QUOTE(:officeid)) ORDER BY meeting_date ASC, meeting_time_from ASC");
		$calendarQuery->execute($calendarCanViewAll ? array() : array(':officeid' => $calendarOfficeId));
		foreach ($calendarQuery->fetchAll(PDO::FETCH_ASSOC) as $meeting) {
			$date = $meeting['meeting_date'];
			if (!isset($calendarMeetings[$date])) $calendarMeetings[$date] = array();
			
			// Handle related_officetitles if stored as JSON array or string/comma-separated
			$rawTitles = $meeting['related_officetitles'] ?? '';
			$decodedTitles = json_decode($rawTitles, true);
			if (is_array($decodedTitles)) {
				$formattedTitles = implode(', ', array_filter($decodedTitles));
			} else {
				$formattedTitles = trim((string)$rawTitles);
			}

			// Use officetitle for Owner Office, fallback to officeid if title is empty
			$ownerOfficeTitle = !empty($meeting['officetitle']) ? $meeting['officetitle'] : $meeting['officeid'];

			$calendarMeetings[$date][] = array(
				'type' => 'meeting',
				'title' => $meeting['meeting_title'], 
				'time' => date('g:i A', strtotime($meeting['meeting_time_from'])) . ' - ' . date('g:i A', strtotime($meeting['meeting_time_to'])), 
				'with' => $meeting['meeting_with'], 
				'description' => $meeting['meeting_description'],
				'owner_office' => $ownerOfficeTitle,
				'related_officetitles' => $formattedTitles
			);
		}

		$appointmentQuery = $calendarCanViewAll
			? $calendarCnn->prepare("SELECT officeid, officetitle, appointment_name, appointment_phone, appointment_purpose, appointment_date, appointment_time, appointment_status FROM appointment_tbl ORDER BY appointment_date ASC, appointment_time ASC")
			: $calendarCnn->prepare("SELECT officeid, officetitle, appointment_name, appointment_phone, appointment_purpose, appointment_date, appointment_time, appointment_status FROM appointment_tbl WHERE officeid = :officeid ORDER BY appointment_date ASC, appointment_time ASC");
		$appointmentQuery->execute($calendarCanViewAll ? array() : array(':officeid' => $calendarOfficeId));
		foreach ($appointmentQuery->fetchAll(PDO::FETCH_ASSOC) as $appointment) {
			$date = $appointment['appointment_date'];
			if (!isset($calendarMeetings[$date])) $calendarMeetings[$date] = array();
			$calendarMeetings[$date][] = array(
				'type' => 'appointment',
				'title' => $appointment['appointment_name'],
				'time' => date('g:i A', strtotime($appointment['appointment_time'])),
				'purpose' => $appointment['appointment_purpose'],
				'phone' => $appointment['appointment_phone'],
				'status' => $appointment['appointment_status'],
				'owner_office' => !empty($appointment['officetitle']) ? $appointment['officetitle'] : $appointment['officeid']
			);
		}

		$birthdayQuery = $calendarCanViewAll
			? $calendarCnn->prepare("SELECT emp_name, birthday, officeid, officetitle FROM employee_tbl WHERE xdel = 0 AND birthday IS NOT NULL AND birthday <> '0000-00-00' ORDER BY MONTH(birthday), DAY(birthday), emp_name")
			: $calendarCnn->prepare("SELECT emp_name, birthday, officeid, officetitle FROM employee_tbl WHERE officeid = :officeid AND xdel = 0 AND birthday IS NOT NULL AND birthday <> '0000-00-00' ORDER BY MONTH(birthday), DAY(birthday), emp_name");
		$birthdayQuery->execute($calendarCanViewAll ? array() : array(':officeid' => $calendarOfficeId));
		foreach ($birthdayQuery->fetchAll(PDO::FETCH_ASSOC) as $employee) {
			$birthdayKey = date('m-d', strtotime($employee['birthday']));
			if (!isset($calendarBirthdays[$birthdayKey])) $calendarBirthdays[$birthdayKey] = array();
			$calendarBirthdays[$birthdayKey][] = array(
				'type' => 'birthday',
				'title' => $employee['emp_name'],
				'time' => 'Birthday',
				'owner_office' => !empty($employee['officetitle']) ? $employee['officetitle'] : $employee['officeid']
			);
		}
	} catch (PDOException $exception) { $calendarError = 'Schedules are not available yet.'; }
?>
<style>
	.dashboard-calendar { table-layout: fixed; width: 100%; }
	.dashboard-calendar th, .dashboard-calendar td { width: 14.285%; }
	.dashboard-calendar td { height: 94px; vertical-align: top; }
	.dashboard-calendar .calendar-day { border: 0; width: 100%; min-height: 76px; background: transparent; text-align: left; padding: 8px; border-radius: .4rem; }
	.dashboard-calendar .calendar-meeting { background: #198754; color: #fff; cursor: pointer; }
	.dashboard-calendar .calendar-meeting:hover { background: #157347; }
	.dashboard-calendar .meeting-preview { display: block; font-size: .72rem; line-height: 1.2; margin-top: 5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
	@media (max-width: 575px) { .dashboard-calendar td { height: 62px; padding: 2px; } .dashboard-calendar .calendar-day { min-height: 54px; padding: 5px; } .dashboard-calendar .meeting-preview { display: none; } }
</style>
<div class="pt-3"><h5 class="mb-1 fw-bold text-light">Calendar</h5><p class="text-muted mb-4"><?php echo $calendarCanViewAll ? 'All appointment and meeting schedules, and employee birthdays.' : 'Appointment and meeting schedules for your office and related offices, plus birthdays for employees in your office.'; ?></p><?php if ($calendarError): ?><div class="alert alert-warning"><?php echo htmlspecialchars($calendarError); ?></div><?php endif; ?><div class="card"><div class="card-body"><div id="dashboard-meeting-calendar"></div></div></div></div>
<div class="modal fade" id="meeting-calendar-modal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="meeting-calendar-modal-title">Schedules</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body" id="meeting-calendar-modal-body"></div></div></div></div>
<script>
document.addEventListener('DOMContentLoaded', function () {
	const schedules = <?php echo json_encode($calendarMeetings, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
	const birthdays = <?php echo json_encode($calendarBirthdays, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
	const calendar = document.getElementById('dashboard-meeting-calendar');
	const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('meeting-calendar-modal'));
	const title = document.getElementById('meeting-calendar-modal-title');
	const body = document.getElementById('meeting-calendar-modal-body');
	let view = new Date();
	view.setDate(1);
	const pad = value => String(value).padStart(2, '0');
	const keyOf = date => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
	const escapeHtml = value => String(value || '').replace(/[&<>'"]/g, char => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', "'":'&#39;', '"':'&quot;' }[char]));
	const eventLabel = item => item.type === 'appointment' ? 'Appointment' : (item.type === 'birthday' ? 'Birthday' : 'Meeting');
	function eventDetails(item) {
		const office = item.owner_office ? `<br><span class="small text-muted"><strong>Office:</strong> ${escapeHtml(item.owner_office)}</span>` : '';
		if (item.type === 'birthday') return `<div class="list-group-item"><span class="badge text-bg-warning mb-1">Birthday</span><br><strong>🎂 ${escapeHtml(item.title)}</strong>${office}</div>`;
		if (item.type === 'appointment') return `<div class="list-group-item"><span class="badge text-bg-primary mb-1">Appointment</span><br><strong>${escapeHtml(item.time)} — ${escapeHtml(item.title)}</strong>${item.phone ? `<br><span class="small">Phone: ${escapeHtml(item.phone)}</span>` : ''}${item.purpose ? `<br><span class="small">Purpose: ${escapeHtml(item.purpose)}</span>` : ''}${item.status ? `<br><span class="badge text-bg-secondary mt-1">${escapeHtml(item.status)}</span>` : ''}${office}</div>`;
		return `<div class="list-group-item"><span class="badge text-bg-success mb-1">Meeting</span><br><strong>${escapeHtml(item.time)} — ${escapeHtml(item.title)}</strong><br><span class="small">With: ${escapeHtml(item.with)}</span>${office}${item.related_officetitles ? `<br><span class="small text-muted"><strong>Related Offices:</strong> ${escapeHtml(item.related_officetitles)}</span>` : ''}${item.description ? `<br><span class="small text-muted mt-1 d-block">${escapeHtml(item.description)}</span>` : ''}</div>`;
	}
	function render() {
		const year = view.getFullYear(), month = view.getMonth(), first = new Date(year, month, 1), start = first.getDay(), days = new Date(year, month + 1, 0).getDate();
		let html = `<div class="d-flex justify-content-between align-items-center mb-3"><button type="button" id="meeting-prev" class="btn btn-outline-secondary btn-sm">&lsaquo;</button><h6 class="mb-0">${view.toLocaleDateString(undefined, { month: 'long', year: 'numeric' })}</h6><button type="button" id="meeting-next" class="btn btn-outline-secondary btn-sm">&rsaquo;</button></div><div class="table-responsive"><table class="table table-bordered dashboard-calendar mb-0"><thead><tr>${['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map(day => `<th class="text-center small">${day}</th>`).join('')}</tr></thead><tbody>`;
		let day = 1;
		for (let week = 0; week < 6 && day <= days; week++) { html += '<tr>'; for (let weekday = 0; weekday < 7; weekday++) { if ((week === 0 && weekday < start) || day > days) { html += '<td></td>'; continue; } const date = new Date(year, month, day++), key = keyOf(date), birthdayKey = `${pad(month + 1)}-${pad(date.getDate())}`, events = (schedules[key] || []).concat(birthdays[birthdayKey] || []), tooltip = events.map(item => `${eventLabel(item)}: ${item.time} — ${item.title}`).join('\n'); html += `<td><button type="button" class="calendar-day ${events.length ? 'calendar-meeting' : ''}" ${events.length ? `data-date="${key}" title="${escapeHtml(tooltip)}"` : ''}>${date.getDate()}${events.length ? `<span class="meeting-preview">${escapeHtml(events[0].time)} — ${escapeHtml(events[0].title)}</span>` : ''}</button></td>`; } html += '</tr>'; }
		calendar.innerHTML = html + '</tbody></table></div>';
		document.getElementById('meeting-prev').onclick = () => { view = new Date(year, month - 1, 1); render(); };
		document.getElementById('meeting-next').onclick = () => { view = new Date(year, month + 1, 1); render(); };
		calendar.querySelectorAll('[data-date]').forEach(button => { new bootstrap.Tooltip(button, { placement: 'top' }); button.onclick = () => { const date = button.dataset.date, birthdayKey = date.slice(5), items = (schedules[date] || []).concat(birthdays[birthdayKey] || []); title.textContent = `Schedules — ${new Date(`${date}T00:00:00`).toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' })}`; body.innerHTML = `<div class="list-group">${items.map(eventDetails).join('')}</div>`; modal.show(); }; });
	}
	render();
});
</script>

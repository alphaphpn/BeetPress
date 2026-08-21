<?php
	$calendarOfficeId = trim((string) ($_SESSION['d2s8wu_officeid'] ?? ''));
	$calendarCanViewAll = in_array((int) ($_SESSION['d2s8wu_ulevel'] ?? 0), array(1, 2), true);
	$calendarMeetings = array();
	$calendarError = null;
	try {
		$calendarCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$calendarCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$calendarCnn->exec("CREATE TABLE IF NOT EXISTS event_schedule_tbl (
			event_autoid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, event_date DATE NOT NULL, event_time_from TIME NOT NULL, event_time_to TIME NOT NULL,
			event_title VARCHAR(255) NOT NULL, event_venue VARCHAR(255) NOT NULL, event_description TEXT DEFAULT NULL,
			officeid VARCHAR(100) NOT NULL, officecode VARCHAR(100) NOT NULL, officetitle VARCHAR(255) NOT NULL,
			related_officeids TEXT DEFAULT NULL, related_officecodes TEXT DEFAULT NULL, related_officetitles TEXT DEFAULT NULL,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (event_autoid), KEY event_date_index (event_date), KEY event_office_index (officeid)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
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

		$eventQuery = $calendarCanViewAll
			? $calendarCnn->prepare("SELECT officeid, officetitle, related_officeids, related_officetitles, event_date, event_time_from, event_time_to, event_title, event_venue, event_description FROM event_schedule_tbl ORDER BY event_date ASC, event_time_from ASC")
			: $calendarCnn->prepare("SELECT officeid, officetitle, related_officeids, related_officetitles, event_date, event_time_from, event_time_to, event_title, event_venue, event_description FROM event_schedule_tbl WHERE officeid = :officeid OR JSON_CONTAINS(COALESCE(related_officeids, '[]'), JSON_QUOTE(:officeid)) ORDER BY event_date ASC, event_time_from ASC");
		$eventQuery->execute($calendarCanViewAll ? array() : array(':officeid' => $calendarOfficeId));
		foreach ($eventQuery->fetchAll(PDO::FETCH_ASSOC) as $event) {
			$date = $event['event_date'];
			if (!isset($calendarMeetings[$date])) $calendarMeetings[$date] = array();
			$relatedTitles = json_decode($event['related_officetitles'] ?? '[]', true);
			$calendarMeetings[$date][] = array(
				'type' => 'event', 'title' => $event['event_title'],
				'time' => date('g:i A', strtotime($event['event_time_from'])) . ' - ' . date('g:i A', strtotime($event['event_time_to'])),
				'venue' => $event['event_venue'], 'description' => $event['event_description'],
				'owner_office' => !empty($event['officetitle']) ? $event['officetitle'] : $event['officeid'],
				'related_officetitles' => is_array($relatedTitles) ? implode(', ', array_filter($relatedTitles)) : trim((string) $event['related_officetitles'])
			);
		}

		$hasHolidayTable = (bool) $calendarCnn->query("SHOW TABLES LIKE 'holidays_tbl'")->fetchColumn();
		if ($hasHolidayTable) {
			$holidayQuery = $calendarCanViewAll
				? $calendarCnn->prepare("SELECT holiday_name, holiday_year, holiday_mno, holiday_day, agency_code, agency_name FROM holidays_tbl WHERE xdel=0 ORDER BY holiday_year, holiday_mno, holiday_day")
				: $calendarCnn->prepare("SELECT holiday_name, holiday_year, holiday_mno, holiday_day, agency_code, agency_name FROM holidays_tbl WHERE xdel=0 AND (agency_code='PH' OR agency_code=:agency_code) ORDER BY holiday_year, holiday_mno, holiday_day");
			$holidayQuery->execute($calendarCanViewAll ? array() : array(':agency_code' => trim((string) ($_SESSION['d2s8wu_officecode'] ?? ''))));
			foreach ($holidayQuery->fetchAll(PDO::FETCH_ASSOC) as $holiday) {
				$date = sprintf('%04d-%02d-%02d', $holiday['holiday_year'], $holiday['holiday_mno'], $holiday['holiday_day']);
				if (!isset($calendarMeetings[$date])) $calendarMeetings[$date] = array();
				$calendarMeetings[$date][] = array('type' => 'holiday', 'title' => $holiday['holiday_name'], 'time' => 'Holiday', 'agency' => $holiday['agency_name']);
			}
		}
	} catch (PDOException $exception) { $calendarError = 'Schedules are not available yet.'; }
?>
<style>
	.dashboard-calendar { table-layout: fixed; width: 100%; }
	.dashboard-calendar th, .dashboard-calendar td { width: 14.285%; }
	.dashboard-calendar td { height: 112px; vertical-align: top; padding: 4px; }
	.dashboard-calendar .calendar-day { border: 0; width: 100%; height: 100%; min-height: 102px; background: transparent; text-align: left; padding: 8px; border-radius: .4rem; display: flex; align-items: stretch; gap: 6px; }
	.dashboard-calendar .calendar-date-number { flex: 0 0 auto; min-width: 1.45em; font-size: 1.8rem; font-weight: 700; line-height: 1; text-align: left; }
	.dashboard-calendar .calendar-day-content { min-width: 0; flex: 1; display: flex; flex-direction: column; align-items: flex-end; justify-content: space-between; text-align: right; }
	.dashboard-calendar .calendar-meeting { background: #198754; color: #fff; cursor: pointer; }
	.dashboard-calendar .calendar-meeting:hover { background: #157347; }
	.dashboard-calendar .calendar-today { background: #0dcaf0 !important; color: #073b4c; cursor: pointer; }
	.dashboard-calendar .calendar-today:hover { background: #0bb5d4 !important; }
	.dashboard-calendar .calendar-past { opacity: .45; }
	.dashboard-calendar .today-label { display: block; font-size: .65rem; font-weight: 700; line-height: 1; text-transform: uppercase; }
	.dashboard-calendar .meeting-preview { display: block; max-width: 100%; font-size: .72rem; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
	.dashboard-calendar .calendar-badges { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 3px; }
	.dashboard-calendar .calendar-badges .badge { font-size: .62rem; line-height: 1.1; }
	#meeting-calendar-modal .modal-dialog { max-width: 720px; }
	#meeting-calendar-modal .modal-title { font-size: 1.35rem; }
	#meeting-calendar-modal .calendar-schedule-item { font-size: 1.05rem; padding: 1.35rem; border-radius: .5rem; }
	#meeting-calendar-modal .calendar-schedule-item strong { font-size: 1.3rem; line-height: 1.35; }
	#meeting-calendar-modal .calendar-schedule-item .small { font-size: 1rem; line-height: 1.45; }
	#meeting-calendar-modal .calendar-schedule-item .badge { font-size: .85rem; }
	#meeting-calendar-modal .schedule-pagination { font-size: 1rem; }
	@media (max-width: 575px) { .dashboard-calendar td { height: 74px; padding: 2px; } .dashboard-calendar .calendar-day { min-height: 68px; padding: 4px; gap: 3px; } .dashboard-calendar .calendar-date-number { font-size: 1.35rem; } .dashboard-calendar .meeting-preview { font-size: .58rem; } .dashboard-calendar .calendar-badges .badge { font-size: .5rem; } }
</style>
<div class="pt-3"><h5 class="mb-1 fw-bold text-light">Calendar</h5><p class="text-muted mb-4"><?php echo $calendarCanViewAll ? 'All appointment, meeting, event, and holiday schedules.' : 'Appointment, meeting, event, and holiday schedules for your office and related offices.'; ?></p><?php if ($calendarError): ?><div class="alert alert-warning"><?php echo htmlspecialchars($calendarError); ?></div><?php endif; ?><div class="card"><div class="card-body"><div id="dashboard-meeting-calendar"></div></div></div></div>
<div class="modal fade" id="meeting-calendar-modal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="meeting-calendar-modal-title">Schedules</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body" id="meeting-calendar-modal-body"></div></div></div></div>
<script>
	document.addEventListener('DOMContentLoaded', function () {
	const schedules = <?php echo json_encode($calendarMeetings, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
	const calendar = document.getElementById('dashboard-meeting-calendar');
	const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('meeting-calendar-modal'));
	const title = document.getElementById('meeting-calendar-modal-title');
	const body = document.getElementById('meeting-calendar-modal-body');
	let view = new Date();
	view.setDate(1);
	const pad = value => String(value).padStart(2, '0');
	const keyOf = date => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
	const today = new Date();
	today.setHours(0, 0, 0, 0);
	const todayKey = keyOf(today);
	const escapeHtml = value => String(value || '').replace(/[&<>'"]/g, char => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', "'":'&#39;', '"':'&quot;' }[char]));
	const eventLabel = item => item.type === 'appointment' ? 'Appointment' : (item.type === 'event' ? 'Event' : (item.type === 'holiday' ? 'Holiday' : 'Meeting'));
	function eventDetails(item) {
		const office = item.owner_office ? `<br><span class="small text-muted"><strong>Office:</strong> ${escapeHtml(item.owner_office)}</span>` : '';
		if (item.type === 'holiday') return `<div class="list-group-item calendar-schedule-item"><span class="badge text-bg-danger mb-2">Holiday</span><br><strong>${escapeHtml(item.title)}</strong>${item.agency ? `<br><span class="small text-muted">${escapeHtml(item.agency)}</span>` : ''}</div>`;
		if (item.type === 'appointment') return `<div class="list-group-item calendar-schedule-item"><span class="badge text-bg-primary mb-2">Appointment</span><br><strong>${escapeHtml(item.time)} — ${escapeHtml(item.title)}</strong>${item.phone ? `<br><span class="small">Phone: ${escapeHtml(item.phone)}</span>` : ''}${item.purpose ? `<br><span class="small">Purpose: ${escapeHtml(item.purpose)}</span>` : ''}${item.status ? `<br><span class="badge text-bg-secondary mt-2">${escapeHtml(item.status)}</span>` : ''}${office}</div>`;
		if (item.type === 'event') return `<div class="list-group-item calendar-schedule-item"><span class="badge text-bg-warning mb-2">Event</span><br><strong>${escapeHtml(item.time)} — ${escapeHtml(item.title)}</strong><br><span class="small">Venue: ${escapeHtml(item.venue)}</span>${office}${item.related_officetitles ? `<br><span class="small text-muted"><strong>Related Offices:</strong> ${escapeHtml(item.related_officetitles)}</span>` : ''}${item.description ? `<br><span class="small text-muted mt-2 d-block">${escapeHtml(item.description)}</span>` : ''}</div>`;
		return `<div class="list-group-item calendar-schedule-item"><span class="badge text-bg-success mb-2">Meeting</span><br><strong>${escapeHtml(item.time)} — ${escapeHtml(item.title)}</strong><br><span class="small">With: ${escapeHtml(item.with)}</span>${office}${item.related_officetitles ? `<br><span class="small text-muted"><strong>Related Offices:</strong> ${escapeHtml(item.related_officetitles)}</span>` : ''}${item.description ? `<br><span class="small text-muted mt-2 d-block">${escapeHtml(item.description)}</span>` : ''}</div>`;
	}
	function showSchedulePage(items, page) {
		const total = items.length;
		const current = Math.max(0, Math.min(page, total - 1));
		body.innerHTML = `<div class="list-group">${eventDetails(items[current])}</div>${total > 1 ? `<nav class="schedule-pagination d-flex justify-content-between align-items-center mt-4" aria-label="Schedule pagination"><button type="button" class="btn btn-outline-secondary" id="schedule-prev" ${current === 0 ? 'disabled' : ''}>&lsaquo; Previous</button><strong>${current + 1} of ${total}</strong><button type="button" class="btn btn-outline-secondary" id="schedule-next" ${current === total - 1 ? 'disabled' : ''}>Next &rsaquo;</button></nav>` : ''}`;
		const previous = document.getElementById('schedule-prev'), next = document.getElementById('schedule-next');
		if (previous) previous.onclick = () => showSchedulePage(items, current - 1);
		if (next) next.onclick = () => showSchedulePage(items, current + 1);
	}
	function render() {
		const year = view.getFullYear(), month = view.getMonth(), first = new Date(year, month, 1), start = first.getDay(), days = new Date(year, month + 1, 0).getDate();
		let html = `<div class="d-flex justify-content-between align-items-center mb-3"><button type="button" id="meeting-prev" class="btn btn-outline-secondary btn-sm">&lsaquo;</button><h6 class="mb-0">${view.toLocaleDateString(undefined, { month: 'long', year: 'numeric' })}</h6><button type="button" id="meeting-next" class="btn btn-outline-secondary btn-sm">&rsaquo;</button></div><div class="table-responsive"><table class="table table-bordered dashboard-calendar mb-0"><thead><tr>${['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map(day => `<th class="text-center small">${day}</th>`).join('')}</tr></thead><tbody>`;
		let day = 1;
		for (let week = 0; week < 6 && day <= days; week++) { html += '<tr>'; for (let weekday = 0; weekday < 7; weekday++) { if ((week === 0 && weekday < start) || day > days) { html += '<td></td>'; continue; } const date = new Date(year, month, day++), key = keyOf(date), events = schedules[key] || [], isToday = key === todayKey, isPast = date < today, tooltip = events.map(item => `${eventLabel(item)}: ${item.time} — ${item.title}`).join('\n'), types = [...new Set(events.map(item => item.type))], badges = types.map(type => `<span class="badge ${type === 'appointment' ? 'text-bg-primary' : (type === 'meeting' ? 'text-bg-success' : (type === 'holiday' ? 'text-bg-danger' : 'text-bg-warning'))}">${eventLabel({type})}</span>`).join(''), content = isToday || events.length ? `<span class="calendar-day-content">${isToday ? '<span class="today-label">Today</span>' : '<span></span>'}${events.length ? `<span class="calendar-badges">${badges}</span><span class="meeting-preview">${escapeHtml(events[0].time)} — ${escapeHtml(events[0].title)}</span>` : '<span></span>'}</span>` : ''; html += `<td><button type="button" class="calendar-day ${events.length ? 'calendar-meeting' : ''} ${isToday ? 'calendar-today' : ''} ${isPast ? 'calendar-past' : ''}" ${events.length ? `data-date="${key}" title="${escapeHtml(tooltip)}"` : ''}><span class="calendar-date-number">${date.getDate()}</span>${content}</button></td>`; } html += '</tr>'; }
		calendar.innerHTML = html + '</tbody></table></div>';
		document.getElementById('meeting-prev').onclick = () => { view = new Date(year, month - 1, 1); render(); };
		document.getElementById('meeting-next').onclick = () => { view = new Date(year, month + 1, 1); render(); };
		calendar.querySelectorAll('[data-date]').forEach(button => { new bootstrap.Tooltip(button, { placement: 'top' }); button.onclick = () => { const date = button.dataset.date, items = schedules[date] || []; title.textContent = `Schedules — ${new Date(`${date}T00:00:00`).toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' })}`; showSchedulePage(items, 0); modal.show(); }; });
	}
	render();
});
</script>

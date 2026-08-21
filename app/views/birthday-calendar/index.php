<?php
	$birthdayOfficeId = trim((string) ($_SESSION['d2s8wu_officeid'] ?? ''));
	$birthdayCanViewAll = in_array((int) ($_SESSION['d2s8wu_ulevel'] ?? 0), array(1, 2), true);
	$birthdays = array();
	$birthdayError = null;
	try {
		$birthdayCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$birthdayCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$birthdayQuery = $birthdayCanViewAll
			? $birthdayCnn->prepare("SELECT emp_name, birthday, officeid, officetitle FROM employee_tbl WHERE xdel = 0 AND birthday IS NOT NULL AND birthday <> '0000-00-00' ORDER BY MONTH(birthday), DAY(birthday), emp_name")
			: $birthdayCnn->prepare("SELECT emp_name, birthday, officeid, officetitle FROM employee_tbl WHERE officeid = :officeid AND xdel = 0 AND birthday IS NOT NULL AND birthday <> '0000-00-00' ORDER BY MONTH(birthday), DAY(birthday), emp_name");
		$birthdayQuery->execute($birthdayCanViewAll ? array() : array(':officeid' => $birthdayOfficeId));
		foreach ($birthdayQuery->fetchAll(PDO::FETCH_ASSOC) as $employee) {
			$key = date('m-d', strtotime($employee['birthday']));
			if (!isset($birthdays[$key])) $birthdays[$key] = array();
			$birthdays[$key][] = array(
				'name' => $employee['emp_name'],
				'office' => !empty($employee['officetitle']) ? $employee['officetitle'] : $employee['officeid']
			);
		}
	} catch (PDOException $exception) { $birthdayError = 'Birthdays are not available yet.'; }
?>
<style>
	.birthday-calendar { table-layout: fixed; width: 100%; }
	.birthday-calendar th, .birthday-calendar td { width: 14.285%; }
	.birthday-calendar td { height: 112px; vertical-align: top; padding: 4px; }
	.birthday-calendar .birthday-day { border: 0; width: 100%; height: 100%; min-height: 102px; background: transparent; text-align: left; padding: 8px; border-radius: .4rem; display: flex; align-items: stretch; gap: 6px; }
	.birthday-calendar .birthday-date-number { flex: 0 0 auto; min-width: 1.45em; font-size: 1.8rem; font-weight: 700; line-height: 1; text-align: left; }
	.birthday-calendar .birthday-day-content { min-width: 0; flex: 1; display: flex; flex-direction: column; align-items: flex-end; justify-content: space-between; text-align: right; }
	.birthday-calendar .birthday-event { background: #ffc107; color: #212529; cursor: pointer; }
	.birthday-calendar .birthday-event:hover { background: #e0a800; }
	.birthday-calendar .birthday-today { background: #0dcaf0 !important; color: #073b4c; cursor: pointer; }
	.birthday-calendar .birthday-today:hover { background: #0bb5d4 !important; }
	.birthday-calendar .birthday-past { opacity: .45; }
	.birthday-calendar .today-label { display: block; font-size: .65rem; font-weight: 700; line-height: 1; text-transform: uppercase; }
	.birthday-calendar .birthday-preview { display: block; max-width: 100%; font-size: .72rem; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
	#birthday-calendar-modal .modal-dialog { max-width: 720px; }
	#birthday-calendar-modal .modal-title { font-size: 1.35rem; }
	#birthday-calendar-modal .birthday-schedule-item { font-size: 1.05rem; padding: 1.35rem; border-radius: .5rem; }
	#birthday-calendar-modal .birthday-schedule-item strong { font-size: 1.3rem; line-height: 1.35; }
	#birthday-calendar-modal .birthday-schedule-item .small { font-size: 1rem; line-height: 1.45; }
	#birthday-calendar-modal .birthday-schedule-item .badge { font-size: .85rem; }
	#birthday-calendar-modal .birthday-pagination { font-size: 1rem; }
	@media (max-width: 575px) { .birthday-calendar td { height: 74px; padding: 2px; } .birthday-calendar .birthday-day { min-height: 68px; padding: 4px; gap: 3px; } .birthday-calendar .birthday-date-number { font-size: 1.35rem; } .birthday-calendar .birthday-preview { font-size: .58rem; } }
</style>
<div class="pt-3"><h5 class="mb-1 fw-bold text-light">Birthday Calendar</h5><p class="text-muted mb-4"><?php echo $birthdayCanViewAll ? 'Employee birthdays across all offices.' : 'Employee birthdays for your office.'; ?></p><?php if ($birthdayError): ?><div class="alert alert-warning"><?php echo htmlspecialchars($birthdayError); ?></div><?php endif; ?><div class="card"><div class="card-body"><div id="birthday-calendar"></div></div></div></div>
<div class="modal fade" id="birthday-calendar-modal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="birthday-calendar-modal-title">Birthdays</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body" id="birthday-calendar-modal-body"></div></div></div></div>
<script>
document.addEventListener('DOMContentLoaded', function () {
	const birthdays = <?php echo json_encode($birthdays, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
	const calendar = document.getElementById('birthday-calendar');
	const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('birthday-calendar-modal'));
	const title = document.getElementById('birthday-calendar-modal-title');
	const body = document.getElementById('birthday-calendar-modal-body');
	let view = new Date();
	view.setDate(1);
	const pad = value => String(value).padStart(2, '0');
	const today = new Date();
	today.setHours(0, 0, 0, 0);
	const todayKey = `${pad(today.getMonth() + 1)}-${pad(today.getDate())}`;
	const escapeHtml = value => String(value || '').replace(/[&<>'"]/g, char => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', "'":'&#39;', '"':'&quot;' }[char]));
	function birthdayDetails(item) {
		return `<div class="list-group-item birthday-schedule-item"><span class="badge text-bg-warning mb-2">Birthday</span><br><strong>🎂 ${escapeHtml(item.name)}</strong>${item.office ? `<br><span class="small text-muted"><strong>Office:</strong> ${escapeHtml(item.office)}</span>` : ''}</div>`;
	}
	function showBirthdayPage(items, page) {
		const total = items.length;
		const current = Math.max(0, Math.min(page, total - 1));
		body.innerHTML = `<div class="list-group">${birthdayDetails(items[current])}</div>${total > 1 ? `<nav class="birthday-pagination d-flex justify-content-between align-items-center mt-4" aria-label="Birthday pagination"><button type="button" class="btn btn-outline-secondary" id="birthday-prev-item" ${current === 0 ? 'disabled' : ''}>&lsaquo; Previous</button><strong>${current + 1} of ${total}</strong><button type="button" class="btn btn-outline-secondary" id="birthday-next-item" ${current === total - 1 ? 'disabled' : ''}>Next &rsaquo;</button></nav>` : ''}`;
		const previous = document.getElementById('birthday-prev-item'), next = document.getElementById('birthday-next-item');
		if (previous) previous.onclick = () => showBirthdayPage(items, current - 1);
		if (next) next.onclick = () => showBirthdayPage(items, current + 1);
	}
	function render() {
		const year = view.getFullYear(), month = view.getMonth(), first = new Date(year, month, 1), start = first.getDay(), days = new Date(year, month + 1, 0).getDate();
		let html = `<div class="d-flex justify-content-between align-items-center mb-3"><button type="button" id="birthday-prev" class="btn btn-outline-secondary btn-sm">&lsaquo;</button><h6 class="mb-0">${view.toLocaleDateString(undefined, { month: 'long', year: 'numeric' })}</h6><button type="button" id="birthday-next" class="btn btn-outline-secondary btn-sm">&rsaquo;</button></div><div class="table-responsive"><table class="table table-bordered birthday-calendar mb-0"><thead><tr>${['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map(day => `<th class="text-center small">${day}</th>`).join('')}</tr></thead><tbody>`;
		let day = 1;
		for (let week = 0; week < 6 && day <= days; week++) { html += '<tr>'; for (let weekday = 0; weekday < 7; weekday++) { if ((week === 0 && weekday < start) || day > days) { html += '<td></td>'; continue; } const date = new Date(year, month, day++), key = `${pad(month + 1)}-${pad(date.getDate())}`, events = birthdays[key] || [], isToday = year === today.getFullYear() && key === todayKey, isPast = date < today, tooltip = events.map(item => `Birthday: ${item.name}`).join('\n'), content = isToday || events.length ? `<span class="birthday-day-content">${isToday ? '<span class="today-label">Today</span>' : '<span></span>'}${events.length ? `<span class="birthday-preview">🎂 ${escapeHtml(events[0].name)}</span>` : '<span></span>'}</span>` : ''; html += `<td><button type="button" class="birthday-day ${events.length ? 'birthday-event' : ''} ${isToday ? 'birthday-today' : ''} ${isPast ? 'birthday-past' : ''}" ${events.length ? `data-date="${key}" title="${escapeHtml(tooltip)}"` : ''}><span class="birthday-date-number">${date.getDate()}</span>${content}</button></td>`; } html += '</tr>'; }
		calendar.innerHTML = html + '</tbody></table></div>';
		document.getElementById('birthday-prev').onclick = () => { view = new Date(year, month - 1, 1); render(); };
		document.getElementById('birthday-next').onclick = () => { view = new Date(year, month + 1, 1); render(); };
		calendar.querySelectorAll('[data-date]').forEach(button => { new bootstrap.Tooltip(button, { placement: 'top' }); button.onclick = () => { const date = button.dataset.date, items = birthdays[date] || []; title.textContent = `Birthdays — ${new Date(`${year}-${date}T00:00:00`).toLocaleDateString(undefined, { month: 'long', day: 'numeric' })}`; showBirthdayPage(items, 0); modal.show(); }; });
	}
	render();
});
</script>

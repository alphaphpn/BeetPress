<?php
	$calendarOfficeId = trim((string) ($_SESSION['d2s8wu_officeid'] ?? ''));
	$calendarMeetings = array();
	$calendarError = null;
	try {
		$calendarCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$calendarCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$calendarQuery = $calendarCnn->prepare("SELECT officeid, officetitle, related_officeids, related_officetitles, meeting_date, meeting_time_from, meeting_time_to, meeting_title, meeting_with, meeting_description FROM meeting_schedule_tbl WHERE officeid = :officeid OR JSON_CONTAINS(COALESCE(related_officeids, '[]'), JSON_QUOTE(:officeid)) ORDER BY meeting_time_from ASC");
		$calendarQuery->execute(array(':officeid' => $calendarOfficeId));
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
				'title' => $meeting['meeting_title'], 
				'time' => date('g:i A', strtotime($meeting['meeting_time_from'])) . ' - ' . date('g:i A', strtotime($meeting['meeting_time_to'])), 
				'with' => $meeting['meeting_with'], 
				'description' => $meeting['meeting_description'],
				'owner_office' => $ownerOfficeTitle,
				'related_officetitles' => $formattedTitles
			);
		}
	} catch (PDOException $exception) { $calendarError = 'Meeting schedules are not available yet.'; }
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
<div class="pt-3"><h5 class="mb-1 fw-bold text-light">Calendar</h5><p class="text-muted mb-4">Meeting schedules for your office and related offices.</p><?php if ($calendarError): ?><div class="alert alert-warning"><?php echo htmlspecialchars($calendarError); ?></div><?php endif; ?><div class="card"><div class="card-body"><div id="dashboard-meeting-calendar"></div></div></div></div>
<div class="modal fade" id="meeting-calendar-modal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="meeting-calendar-modal-title">Meeting Schedules</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body" id="meeting-calendar-modal-body"></div></div></div></div>
<script>
document.addEventListener('DOMContentLoaded', function () {
	const schedules = <?php echo json_encode($calendarMeetings, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
	const calendar = document.getElementById('dashboard-meeting-calendar'), modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('meeting-calendar-modal')), title = document.getElementById('meeting-calendar-modal-title'), body = document.getElementById('meeting-calendar-modal-body');
	let view = new Date(); view.setDate(1); const pad = value => String(value).padStart(2, '0'); const keyOf = date => `${date.getFullYear()}-${pad(date.getMonth()+1)}-${pad(date.getDate())}`; const escapeHtml = value => String(value || '').replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[char]));
	function render() { const year=view.getFullYear(), month=view.getMonth(), first=new Date(year,month,1), start=first.getDay(), days=new Date(year,month+1,0).getDate(); let html=`<div class="d-flex justify-content-between align-items-center mb-3"><button type="button" id="meeting-prev" class="btn btn-outline-secondary btn-sm">&lsaquo;</button><h6 class="mb-0">${view.toLocaleDateString(undefined,{month:'long',year:'numeric'})}</h6><button type="button" id="meeting-next" class="btn btn-outline-secondary btn-sm">&rsaquo;</button></div><div class="table-responsive"><table class="table table-bordered dashboard-calendar mb-0"><thead><tr>${['Sun','Mon','Tue','Wed','Thu','Fri','Sat'].map(day=>`<th class="text-center small">${day}</th>`).join('')}</tr></thead><tbody>`; let day=1; for(let week=0;week<6&&day<=days;week++){html+='<tr>';for(let weekday=0;weekday<7;weekday++){if((week===0&&weekday<start)||day>days){html+='<td></td>';continue;}const date=new Date(year,month,day++),key=keyOf(date),meetings=schedules[key]||[],tooltip=meetings.map(item=>`${item.time} — ${item.title}`).join('\n');html+=`<td><button type="button" class="calendar-day ${meetings.length?'calendar-meeting':''}" ${meetings.length?`data-date="${key}" title="${escapeHtml(tooltip)}"`:''}>${date.getDate()}${meetings.length?`<span class="meeting-preview">${escapeHtml(meetings[0].time)} — ${escapeHtml(meetings[0].title)}</span>`:''}</button></td>`;}html+='</tr>';} calendar.innerHTML=html+'</tbody></table></div>'; document.getElementById('meeting-prev').onclick=()=>{view=new Date(year,month-1,1);render();};document.getElementById('meeting-next').onclick=()=>{view=new Date(year,month+1,1);render();};calendar.querySelectorAll('[data-date]').forEach(button=>{new bootstrap.Tooltip(button,{placement:'top'});button.onclick=()=>{const items=schedules[button.dataset.date];title.textContent=`Meetings — ${new Date(`${button.dataset.date}T00:00:00`).toLocaleDateString(undefined,{weekday:'long',month:'long',day:'numeric',year:'numeric'})}`;body.innerHTML=`<div class="list-group">${items.map(item=>`<div class="list-group-item"><strong>${escapeHtml(item.time)} — ${escapeHtml(item.title)}</strong><br><span class="small">With: ${escapeHtml(item.with)}</span>${item.owner_office?`<br><span class="small text-muted"><strong>Owner Office:</strong> ${escapeHtml(item.owner_office)}</span>`:''}${item.related_officetitles?`<br><span class="small text-muted"><strong>Related Offices:</strong> ${escapeHtml(item.related_officetitles)}</span>`:''}${item.description?`<br><span class="small text-muted mt-1 d-block">${escapeHtml(item.description)}</span>`:''}</div>`).join('')}</div>`;modal.show();};}); }
	render();
});
</script>
<?php
	$meetingOfficeId = trim((string) ($_SESSION['d2s8wu_officeid'] ?? ''));
	$meetingOfficeCode = trim((string) ($_SESSION['d2s8wu_officecode'] ?? ''));
	$meetingOfficeTitle = trim((string) ($_SESSION['d2s8wu_officetitle'] ?? $_SESSION['d2s8wu_officeabrv'] ?? ''));
	$meetingError = null;
	$meetingSuccess = null;
	$meetingForm = array('date' => '', 'time_from' => '', 'time_to' => '', 'title' => '', 'meeting_with' => '', 'description' => '', 'related_offices' => array());
	$meetingOffices = array();
	$meetings = array();
	try {
		$meetingCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$meetingCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$meetingCnn->exec("CREATE TABLE IF NOT EXISTS meeting_schedule_tbl (
			meeting_autoid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			meeting_date DATE NOT NULL, meeting_time_from TIME NOT NULL, meeting_time_to TIME NOT NULL,
			meeting_title VARCHAR(255) NOT NULL, meeting_with VARCHAR(255) NOT NULL, meeting_description TEXT DEFAULT NULL,
			officeid VARCHAR(100) NOT NULL, officecode VARCHAR(100) NOT NULL, officetitle VARCHAR(255) NOT NULL,
			related_officeids TEXT DEFAULT NULL, related_officecodes TEXT DEFAULT NULL, related_officetitles TEXT DEFAULT NULL,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (meeting_autoid), KEY meeting_date_index (meeting_date), KEY meeting_office_index (officeid)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
		$officeQuery = $meetingCnn->query("SELECT officeid, officecode, officetitle FROM office_signatory_tbl WHERE xdel = 0 ORDER BY officetitle ASC");
		foreach ($officeQuery->fetchAll(PDO::FETCH_ASSOC) as $office) if (!empty($office['officeid']) && !isset($meetingOffices[$office['officeid']])) $meetingOffices[$office['officeid']] = $office;

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meeting_submit'])) {
			foreach (array('date', 'time_from', 'time_to', 'title', 'meeting_with', 'description') as $field) $meetingForm[$field] = trim((string) ($_POST[$field] ?? ''));
			$meetingForm['related_offices'] = array_values(array_filter((array) ($_POST['related_offices'] ?? array()), function ($id) use ($meetingOffices) { return isset($meetingOffices[$id]); }));
			$dateObject = DateTime::createFromFormat('Y-m-d', $meetingForm['date']);
			if ($meetingOfficeId === '') $meetingError = 'Your session office is not available.';
			elseif ($meetingForm['date'] === '' || $meetingForm['time_from'] === '' || $meetingForm['time_to'] === '' || $meetingForm['title'] === '' || $meetingForm['meeting_with'] === '') $meetingError = 'Please complete all required meeting fields.';
			elseif (!$dateObject || $dateObject->format('Y-m-d') !== $meetingForm['date'] || $meetingForm['time_from'] >= $meetingForm['time_to']) $meetingError = 'Please provide a valid date and a time range.';
			else {
				$related = array_map(function ($id) use ($meetingOffices) { return $meetingOffices[$id]; }, $meetingForm['related_offices']);
				$saveMeeting = $meetingCnn->prepare("INSERT INTO meeting_schedule_tbl (meeting_date, meeting_time_from, meeting_time_to, meeting_title, meeting_with, meeting_description, officeid, officecode, officetitle, related_officeids, related_officecodes, related_officetitles) VALUES (:date, :time_from, :time_to, :title, :meeting_with, :description, :officeid, :officecode, :officetitle, :related_ids, :related_codes, :related_titles)");
				$saveMeeting->execute(array(':date' => $meetingForm['date'], ':time_from' => $meetingForm['time_from'], ':time_to' => $meetingForm['time_to'], ':title' => $meetingForm['title'], ':meeting_with' => $meetingForm['meeting_with'], ':description' => $meetingForm['description'] ?: null, ':officeid' => $meetingOfficeId, ':officecode' => $meetingOfficeCode, ':officetitle' => $meetingOfficeTitle, ':related_ids' => json_encode(array_column($related, 'officeid')), ':related_codes' => json_encode(array_column($related, 'officecode')), ':related_titles' => json_encode(array_column($related, 'officetitle'))));
				$meetingSuccess = 'Meeting schedule saved successfully.';
				$meetingForm = array('date' => '', 'time_from' => '', 'time_to' => '', 'title' => '', 'meeting_with' => '', 'description' => '', 'related_offices' => array());
			}
		}
		$meetingsQuery = $meetingCnn->prepare("SELECT * FROM meeting_schedule_tbl WHERE officeid = :officeid OR JSON_CONTAINS(COALESCE(related_officeids, '[]'), JSON_QUOTE(:officeid)) ORDER BY meeting_date DESC, meeting_time_from DESC");
		$meetingsQuery->execute(array(':officeid' => $meetingOfficeId));
		$meetings = $meetingsQuery->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $exception) { $meetingError = 'Meeting schedules are temporarily unavailable.'; }
?>
<div class="pt-3">
	<h5 class="mb-1 fw-bold text-light">Meeting Schedule</h5><p class="text-muted mb-4">Create and view meetings for your office.</p>
	<?php if ($meetingSuccess): ?><div class="alert alert-success"><?php echo htmlspecialchars($meetingSuccess); ?></div><?php endif; ?>
	<?php if ($meetingError): ?><div class="alert alert-danger"><?php echo htmlspecialchars($meetingError); ?></div><?php endif; ?>
	<div class="card mb-4"><div class="card-body"><form method="post"><div class="row g-3">
		<div class="col-md-4"><label class="form-label">Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="date" required value="<?php echo htmlspecialchars($meetingForm['date']); ?>"></div>
		<div class="col-md-4"><label class="form-label">Time (From) <span class="text-danger">*</span></label><input type="time" class="form-control" name="time_from" required value="<?php echo htmlspecialchars($meetingForm['time_from']); ?>"></div>
		<div class="col-md-4"><label class="form-label">Time (To) <span class="text-danger">*</span></label><input type="time" class="form-control" name="time_to" required value="<?php echo htmlspecialchars($meetingForm['time_to']); ?>"></div>
		<div class="col-md-6"><label class="form-label">Title of the Meeting <span class="text-danger">*</span></label><input class="form-control" name="title" required value="<?php echo htmlspecialchars($meetingForm['title']); ?>"></div>
		<div class="col-md-6"><label class="form-label">Meeting With <span class="text-danger">*</span></label><input class="form-control" name="meeting_with" required value="<?php echo htmlspecialchars($meetingForm['meeting_with']); ?>"></div>
		<div class="col-md-6"><label class="form-label">Related Offices</label><select class="form-select" name="related_offices[]" multiple size="5"><?php foreach ($meetingOffices as $id => $office): ?><option value="<?php echo htmlspecialchars($id); ?>" <?php echo in_array($id, $meetingForm['related_offices'], true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($office['officetitle']); ?></option><?php endforeach; ?></select><small class="text-muted">Use Ctrl/Cmd to select more than one office.</small></div>
		<div class="col-md-6"><label class="form-label">Description</label><textarea class="form-control" rows="5" name="description"><?php echo htmlspecialchars($meetingForm['description']); ?></textarea></div>
		<div class="col-12 text-end"><button class="btn btn-primary" type="submit" name="meeting_submit">Save Meeting Schedule</button></div>
	</div></form></div></div>
	<div class="card"><div class="card-body"><div class="table-responsive"><table id="listRecView" class="table table-dark table-striped table-hover align-middle"><thead><tr><th>Date</th><th>Time</th><th>Title</th><th>Meeting With</th><th>Related Offices</th><th>Description</th></tr></thead><tbody><?php foreach ($meetings as $meeting): ?><tr><td><?php echo htmlspecialchars(date('M j, Y', strtotime($meeting['meeting_date']))); ?></td><td><?php echo htmlspecialchars(date('g:i A', strtotime($meeting['meeting_time_from'])) . ' - ' . date('g:i A', strtotime($meeting['meeting_time_to']))); ?></td><td><?php echo htmlspecialchars($meeting['meeting_title']); ?></td><td><?php echo htmlspecialchars($meeting['meeting_with']); ?></td><td><?php echo htmlspecialchars(implode(', ', json_decode($meeting['related_officetitles'] ?: '[]', true) ?: array())); ?></td><td><?php echo htmlspecialchars($meeting['meeting_description']); ?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
</div>

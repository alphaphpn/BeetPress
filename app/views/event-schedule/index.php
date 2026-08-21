<?php
	$eventOfficeId = trim((string) ($_SESSION['d2s8wu_officeid'] ?? ''));
	$eventOfficeCode = trim((string) ($_SESSION['d2s8wu_officecode'] ?? ''));
	$eventOfficeTitle = trim((string) ($_SESSION['d2s8wu_officetitle'] ?? $_SESSION['d2s8wu_officeabrv'] ?? ''));
	$eventError = null; $eventSuccess = null; $events = array(); $eventOffices = array();
	$eventForm = array('id' => 0, 'date' => '', 'time_from' => '', 'time_to' => '', 'title' => '', 'venue' => '', 'description' => '', 'related_offices' => array());
	if (empty($_SESSION['event_schedule_csrf'])) $_SESSION['event_schedule_csrf'] = bin2hex(random_bytes(32));
	try {
		$eventCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$eventCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$eventCnn->exec("CREATE TABLE IF NOT EXISTS event_schedule_tbl (
			event_autoid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, event_date DATE NOT NULL, event_time_from TIME NOT NULL, event_time_to TIME NOT NULL,
			event_title VARCHAR(255) NOT NULL, event_venue VARCHAR(255) NOT NULL, event_description TEXT DEFAULT NULL,
			officeid VARCHAR(100) NOT NULL, officecode VARCHAR(100) NOT NULL, officetitle VARCHAR(255) NOT NULL,
			related_officeids TEXT DEFAULT NULL, related_officecodes TEXT DEFAULT NULL, related_officetitles TEXT DEFAULT NULL,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (event_autoid), KEY event_date_index (event_date), KEY event_office_index (officeid)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
		foreach ($eventCnn->query("SELECT officeid, officecode, officetitle FROM office_signatory_tbl WHERE xdel = 0 ORDER BY officetitle ASC")->fetchAll(PDO::FETCH_ASSOC) as $office) if (!empty($office['officeid']) && !isset($eventOffices[$office['officeid']])) $eventOffices[$office['officeid']] = $office;
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_action'])) {
			if (!hash_equals($_SESSION['event_schedule_csrf'], (string) ($_POST['event_schedule_csrf'] ?? ''))) $eventError = 'Your event session has expired. Please try again.';
			elseif ($_POST['event_action'] === 'delete') {
				$delete = $eventCnn->prepare("DELETE FROM event_schedule_tbl WHERE event_autoid = :id AND officeid = :officeid");
				$delete->execute(array(':id' => (int) ($_POST['id'] ?? 0), ':officeid' => $eventOfficeId));
				$eventSuccess = $delete->rowCount() ? 'Event deleted successfully.' : 'The event could not be deleted.';
			} else {
				foreach (array('id', 'date', 'time_from', 'time_to', 'title', 'venue', 'description') as $field) $eventForm[$field] = trim((string) ($_POST[$field] ?? ''));
				$eventForm['related_offices'] = array_values(array_filter((array) ($_POST['related_offices'] ?? array()), function ($id) use ($eventOffices) { return isset($eventOffices[$id]); }));
				$dateObject = DateTime::createFromFormat('Y-m-d', $eventForm['date']);
				if ($eventOfficeId === '') $eventError = 'Your session office is not available.';
				elseif ($eventForm['date'] === '' || $eventForm['time_from'] === '' || $eventForm['time_to'] === '' || $eventForm['title'] === '' || $eventForm['venue'] === '') $eventError = 'Please complete all required event fields.';
				elseif (!$dateObject || $dateObject->format('Y-m-d') !== $eventForm['date'] || $eventForm['time_from'] >= $eventForm['time_to']) $eventError = 'Please provide a valid date and time range.';
				else {
					$related = array_map(function ($id) use ($eventOffices) { return $eventOffices[$id]; }, $eventForm['related_offices']);
					$params = array(':date'=>$eventForm['date'], ':time_from'=>$eventForm['time_from'], ':time_to'=>$eventForm['time_to'], ':title'=>$eventForm['title'], ':venue'=>$eventForm['venue'], ':description'=>$eventForm['description'] ?: null, ':related_ids'=>json_encode(array_column($related, 'officeid')), ':related_codes'=>json_encode(array_column($related, 'officecode')), ':related_titles'=>json_encode(array_column($related, 'officetitle')));
					if ($_POST['event_action'] === 'update' && (int) $eventForm['id'] > 0) {
						$params[':id'] = (int) $eventForm['id']; $params[':officeid'] = $eventOfficeId;
						$save = $eventCnn->prepare("UPDATE event_schedule_tbl SET event_date=:date, event_time_from=:time_from, event_time_to=:time_to, event_title=:title, event_venue=:venue, event_description=:description, related_officeids=:related_ids, related_officecodes=:related_codes, related_officetitles=:related_titles WHERE event_autoid=:id AND officeid=:officeid");
						$save->execute($params); $eventSuccess = $save->rowCount() ? 'Event updated successfully.' : 'No event changes were made.';
					} else {
						$params += array(':officeid'=>$eventOfficeId, ':officecode'=>$eventOfficeCode, ':officetitle'=>$eventOfficeTitle);
						$save = $eventCnn->prepare("INSERT INTO event_schedule_tbl (event_date,event_time_from,event_time_to,event_title,event_venue,event_description,officeid,officecode,officetitle,related_officeids,related_officecodes,related_officetitles) VALUES (:date,:time_from,:time_to,:title,:venue,:description,:officeid,:officecode,:officetitle,:related_ids,:related_codes,:related_titles)");
						$save->execute($params); $eventSuccess = 'Event created successfully.';
					}
					$eventForm = array('id'=>0, 'date'=>'', 'time_from'=>'', 'time_to'=>'', 'title'=>'', 'venue'=>'', 'description'=>'', 'related_offices'=>array());
				}
			}
		}
		if (isset($_GET['edit'])) { $edit = $eventCnn->prepare("SELECT * FROM event_schedule_tbl WHERE event_autoid=:id AND officeid=:officeid"); $edit->execute(array(':id'=>(int) $_GET['edit'], ':officeid'=>$eventOfficeId)); if ($row = $edit->fetch(PDO::FETCH_ASSOC)) $eventForm = array('id'=>$row['event_autoid'], 'date'=>$row['event_date'], 'time_from'=>substr($row['event_time_from'],0,5), 'time_to'=>substr($row['event_time_to'],0,5), 'title'=>$row['event_title'], 'venue'=>$row['event_venue'], 'description'=>$row['event_description'], 'related_offices'=>json_decode($row['related_officeids'] ?: '[]', true) ?: array()); }
		$list = $eventCnn->prepare("SELECT * FROM event_schedule_tbl WHERE officeid=:officeid ORDER BY event_date DESC,event_time_from DESC"); $list->execute(array(':officeid'=>$eventOfficeId)); $events = $list->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $exception) { $eventError = 'Event schedules are temporarily unavailable.'; }
?>
<div class="pt-3"><h5 class="mb-1 fw-bold text-light">Events Schedule</h5><p class="text-muted mb-4">Create, edit, and remove events for your office.</p>
	<?php if ($eventSuccess): ?><div class="alert alert-success"><?php echo htmlspecialchars($eventSuccess); ?></div><?php endif; ?><?php if ($eventError): ?><div class="alert alert-danger"><?php echo htmlspecialchars($eventError); ?></div><?php endif; ?>
	<div class="card mb-4"><div class="card-body"><form method="post"><input type="hidden" name="event_schedule_csrf" value="<?php echo htmlspecialchars($_SESSION['event_schedule_csrf']); ?>"><input type="hidden" name="id" value="<?php echo (int) $eventForm['id']; ?>"><input type="hidden" name="event_action" value="<?php echo $eventForm['id'] ? 'update' : 'create'; ?>"><div class="row g-3">
		<div class="col-md-4"><label class="form-label">Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="date" required value="<?php echo htmlspecialchars($eventForm['date']); ?>"></div><div class="col-md-4"><label class="form-label">Time (From) <span class="text-danger">*</span></label><input type="time" class="form-control" name="time_from" required value="<?php echo htmlspecialchars($eventForm['time_from']); ?>"></div><div class="col-md-4"><label class="form-label">Time (To) <span class="text-danger">*</span></label><input type="time" class="form-control" name="time_to" required value="<?php echo htmlspecialchars($eventForm['time_to']); ?>"></div>
		<div class="col-md-6"><label class="form-label">Event Title <span class="text-danger">*</span></label><input class="form-control" name="title" required value="<?php echo htmlspecialchars($eventForm['title']); ?>"></div><div class="col-md-6"><label class="form-label">Venue <span class="text-danger">*</span></label><input class="form-control" name="venue" required value="<?php echo htmlspecialchars($eventForm['venue']); ?>"></div>
		<div class="col-md-6"><label class="form-label">Related Offices</label><select class="form-select" name="related_offices[]" multiple size="5"><?php foreach ($eventOffices as $id=>$office): ?><option value="<?php echo htmlspecialchars($id); ?>" <?php echo in_array($id,$eventForm['related_offices'],true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($office['officetitle']); ?></option><?php endforeach; ?></select><small class="text-muted">Use Ctrl/Cmd to select more than one office.</small></div><div class="col-md-6"><label class="form-label">Description</label><textarea class="form-control" rows="5" name="description"><?php echo htmlspecialchars($eventForm['description']); ?></textarea></div>
		<div class="col-12 text-end"><?php if ($eventForm['id']): ?><a class="btn btn-outline-secondary" href="<?php echo htmlspecialchars(trim($domainhome)); ?>/event-schedule">Cancel Edit</a><?php endif; ?> <button class="btn btn-primary" type="submit"><?php echo $eventForm['id'] ? 'Update Event' : 'Save Event'; ?></button></div>
	</div></form></div></div>
	<div class="card"><div class="card-body"><div class="table-responsive"><table id="listRecView" class="table table-dark table-striped table-hover align-middle"><thead><tr><th>Date</th><th>Time</th><th>Title</th><th>Venue</th><th>Related Offices</th><th>Description</th><th>Actions</th></tr></thead><tbody><?php foreach ($events as $event): ?><tr><td><?php echo htmlspecialchars(date('M j, Y',strtotime($event['event_date']))); ?></td><td><?php echo htmlspecialchars(date('g:i A',strtotime($event['event_time_from'])).' - '.date('g:i A',strtotime($event['event_time_to']))); ?></td><td><?php echo htmlspecialchars($event['event_title']); ?></td><td><?php echo htmlspecialchars($event['event_venue']); ?></td><td><?php echo htmlspecialchars(implode(', ',json_decode($event['related_officetitles'] ?: '[]',true) ?: array())); ?></td><td><?php echo htmlspecialchars($event['event_description']); ?></td><td class="text-nowrap"><a class="btn btn-sm btn-outline-info" href="?edit=<?php echo (int) $event['event_autoid']; ?>">Edit</a> <form method="post" class="d-inline" onsubmit="return confirm('Delete this event?');"><input type="hidden" name="event_schedule_csrf" value="<?php echo htmlspecialchars($_SESSION['event_schedule_csrf']); ?>"><input type="hidden" name="event_action" value="delete"><input type="hidden" name="id" value="<?php echo (int) $event['event_autoid']; ?>"><button class="btn btn-sm btn-outline-danger" type="submit">Delete</button></form></td></tr><?php endforeach; ?></tbody></table></div></div></div>
</div>

<?php
	$contactMessages = array();
	$contactMessagesError = null;
	$contactMessagesSuccess = null;

	if (empty($_SESSION['dmessages_csrf'])) {
		$_SESSION['dmessages_csrf'] = bin2hex(random_bytes(32));
	}

	try {
		$contactMessagesCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$contactMessagesCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$contactMessagesCnn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$contactMessagesCnn->exec("CREATE TABLE IF NOT EXISTS tbl_contactusmsg (
			contactusmsg_autoid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(150) NOT NULL,
			phone VARCHAR(50) NOT NULL,
			email VARCHAR(150) DEFAULT NULL,
			address VARCHAR(255) NOT NULL,
			message TEXT NOT NULL,
			status TINYINT(1) NOT NULL DEFAULT 0,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (contactusmsg_autoid)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
		$contactStatusColumn = $contactMessagesCnn->query("SHOW COLUMNS FROM tbl_contactusmsg LIKE 'status'")->fetch(PDO::FETCH_ASSOC);
		if (!$contactStatusColumn) {
			$contactMessagesCnn->exec("ALTER TABLE tbl_contactusmsg ADD COLUMN status TINYINT(1) NOT NULL DEFAULT 0 AFTER message");
		} elseif ((string) $contactStatusColumn['Default'] !== '0') {
			$contactMessagesCnn->exec("ALTER TABLE tbl_contactusmsg MODIFY COLUMN status TINYINT(1) NOT NULL DEFAULT 0");
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_message_status'])) {
			$messageId = filter_input(INPUT_POST, 'message_id', FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
			$status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
			$csrfToken = (string) ($_POST['dmessages_csrf'] ?? '');

			if (!hash_equals($_SESSION['dmessages_csrf'], $csrfToken)) {
				$contactMessagesError = 'Your session has expired. Please try again.';
			} elseif ($messageId === false || !in_array($status, array(0, 1), true)) {
				$contactMessagesError = 'Please select a valid message status.';
			} else {
				$updateStatus = $contactMessagesCnn->prepare("UPDATE tbl_contactusmsg SET status = :status WHERE contactusmsg_autoid = :message_id");
				$updateStatus->execute(array(':status' => $status, ':message_id' => $messageId));
				$contactMessagesSuccess = $updateStatus->rowCount() ? 'Message status updated successfully.' : 'No status change was needed.';
			}
		}

		$contactMessages = $contactMessagesCnn->query("SELECT contactusmsg_autoid, name, phone, email, address, message, status, created_at FROM tbl_contactusmsg ORDER BY created_at DESC, contactusmsg_autoid DESC")->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $exception) {
		$contactMessagesError = 'Contact messages are temporarily unavailable.';
	}
?>

<div class="pt-3">
	<h5 class="mb-1 fw-bold text-light">Contact Messages</h5>
	<p class="text-muted mb-4">Messages submitted through the homepage Contact Us form.</p>
	<?php if ($contactMessagesSuccess): ?><div class="alert alert-success"><?php echo htmlspecialchars($contactMessagesSuccess); ?></div><?php endif; ?>
	<?php if ($contactMessagesError): ?><div class="alert alert-danger"><?php echo htmlspecialchars($contactMessagesError); ?></div><?php endif; ?>
	<div class="card"><div class="card-body"><div class="table-responsive">
		<table id="listRecView" class="table table-dark table-striped table-hover align-middle">
			<thead><tr><th>Date</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th>Message</th><th>Status</th><th class="remove-dropdown">Action</th></tr></thead>
			<tbody>
				<?php foreach ($contactMessages as $contactMessage): ?>
				<tr>
					<td data-order="<?php echo htmlspecialchars($contactMessage['created_at']); ?>"><?php echo htmlspecialchars(date('M j, Y g:i A', strtotime($contactMessage['created_at']))); ?></td>
					<td><?php echo htmlspecialchars($contactMessage['name']); ?></td>
					<td><?php echo htmlspecialchars($contactMessage['phone']); ?></td>
					<td><?php echo htmlspecialchars($contactMessage['email']); ?></td>
					<td><?php echo htmlspecialchars($contactMessage['address']); ?></td>
					<td style="white-space: pre-wrap; min-width: 220px;"><?php echo htmlspecialchars($contactMessage['message']); ?></td>
					<td><?php if ((int) $contactMessage['status'] === 1): ?><span class="badge bg-success">Seen / Read</span><?php else: ?><span class="badge bg-warning text-dark">Pending</span><?php endif; ?></td>
					<td>
						<form method="post" class="d-flex gap-2">
							<input type="hidden" name="dmessages_csrf" value="<?php echo htmlspecialchars($_SESSION['dmessages_csrf']); ?>">
							<input type="hidden" name="message_id" value="<?php echo (int) $contactMessage['contactusmsg_autoid']; ?>">
							<select class="form-select form-select-sm" name="status" aria-label="Message status">
								<option value="0" <?php echo (int) $contactMessage['status'] === 0 ? 'selected' : ''; ?>>Pending</option>
								<option value="1" <?php echo (int) $contactMessage['status'] === 1 ? 'selected' : ''; ?>>Seen / Read</option>
							</select>
							<button class="btn btn-sm btn-primary" type="submit" name="update_message_status">Save</button>
						</form>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div></div></div>
</div>

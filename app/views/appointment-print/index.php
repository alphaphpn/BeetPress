<?php
	$appointment = null;
	$appointmentId = (int) ($_SESSION['appointment_print_id'] ?? 0);
	if ($appointmentId > 0) {
		try {
			$printCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
			$printCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$printStmt = $printCnn->prepare("SELECT * FROM appointment_tbl WHERE appointment_autoid = :appointment_id LIMIT 1");
			$printStmt->execute(array(':appointment_id' => $appointmentId));
			$appointment = $printStmt->fetch(PDO::FETCH_ASSOC) ?: null;
		} catch (PDOException $exception) {
			$appointment = null;
		}
	}
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Appointment Schedule</title>
<style>
	body { font-family: Arial, sans-serif; color: #222; margin: 0; background: #f4f4f4; }
	.schedule { width: 720px; max-width: calc(100% - 40px); margin: 36px auto; background: #fff; border: 1px solid #bbb; padding: 42px; box-sizing: border-box; }
	h1 { margin: 0; font-size: 26px; text-align: center; } .subtitle { text-align: center; margin: 8px 0 28px; color: #555; }
	.reference { text-align: right; font-size: 12px; color: #666; margin-bottom: 12px; } table { width: 100%; border-collapse: collapse; } th, td { padding: 12px; border: 1px solid #bbb; text-align: left; vertical-align: top; } th { width: 32%; background: #f3f3f3; } .note { margin-top: 26px; font-size: 13px; color: #555; } .actions { text-align: center; margin: 20px; } button { padding: 10px 18px; font-size: 15px; cursor: pointer; }
	@media print { body { background: #fff; } .schedule { max-width: none; width: auto; margin: 0; border: 0; } .actions { display: none; } }
</style></head><body>
<?php if ($appointment): ?>
	<div class="schedule">
		<h1>Appointment Schedule</h1><p class="subtitle">Please present this schedule during your appointment.</p>
		<p class="reference">Reference No. <?php echo str_pad((string) $appointment['appointment_autoid'], 6, '0', STR_PAD_LEFT); ?></p>
		<table><tbody>
			<tr><th>Name</th><td><?php echo htmlspecialchars($appointment['appointment_name']); ?></td></tr>
			<tr><th>Phone</th><td><?php echo htmlspecialchars($appointment['appointment_phone']); ?></td></tr>
			<?php if (!empty($appointment['appointment_email'])): ?><tr><th>Email</th><td><?php echo htmlspecialchars($appointment['appointment_email']); ?></td></tr><?php endif; ?>
			<tr><th>Address</th><td><?php echo htmlspecialchars($appointment['appointment_address']); ?></td></tr>
			<tr><th>Purpose</th><td><?php echo nl2br(htmlspecialchars($appointment['appointment_purpose'])); ?></td></tr>
			<tr><th>Office</th><td><?php echo htmlspecialchars($appointment['officetitle']); ?></td></tr>
			<tr><th>Date</th><td><?php echo htmlspecialchars(date('F j, Y', strtotime($appointment['appointment_date']))); ?></td></tr>
			<tr><th>Time</th><td><?php echo htmlspecialchars(date('g:i A', strtotime($appointment['appointment_time']))); ?></td></tr>
			<tr><th>Status</th><td><?php echo htmlspecialchars($appointment['appointment_status']); ?></td></tr>
		</tbody></table>
		<p class="note">Please arrive a few minutes before your scheduled time. Contact the office if you need to reschedule or cancel.</p>
	</div>
	<div class="actions"><button type="button" onclick="window.print()">Print Appointment Schedule</button></div>
<?php else: ?>
	<div class="schedule"><h1>Appointment Not Found</h1><p class="subtitle">No recent appointment schedule is available to print.</p></div>
<?php endif; ?>
</body></html>

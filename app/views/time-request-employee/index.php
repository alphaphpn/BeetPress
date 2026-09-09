<?php
$timeRequestMessage = null;
$timeRequestError = null;
$timeRequests = array();
$canReviewTimeRequests = in_array((int) ($_SESSION['d2s8wu_ulevel'] ?? 0), array(1, 2), true);
$timeRequestOfficeId = trim((string) ($_SESSION['d2s8wu_officeid'] ?? ''));

if (empty($_SESSION['time_request_employee_csrf'])) {
    $_SESSION['time_request_employee_csrf'] = bin2hex(random_bytes(32));
}

try {
    $timeRequestCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
    $timeRequestCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['time_request_action'])) {
        $action = (string) ($_POST['time_request_action'] ?? '');
        $recordId = (int) ($_POST['record_id'] ?? 0);
        $field = (string) ($_POST['time_field'] ?? '');
        $csrf = (string) ($_POST['time_request_employee_csrf'] ?? '');
        $allowedFields = array('amtimein', 'amtimeout', 'pmtimein', 'pmtimeout');

        if (!$canReviewTimeRequests) {
            $timeRequestError = 'You are not authorized to review time requests.';
        } elseif (!hash_equals($_SESSION['time_request_employee_csrf'], $csrf)) {
            $timeRequestError = 'Your session has expired. Please try again.';
        } elseif ($recordId <= 0 || !in_array($field, $allowedFields, true) || !in_array($action, array('approved', 'disapproved'), true)) {
            $timeRequestError = 'Invalid time request.';
        } else {
            $newStatus = $action === 'approved' ? 'APPROVED' : 'DISAPPROVED';
            $officeScope = '';
            $params = array(':record_id' => $recordId);
            if ($timeRequestOfficeId !== '' && $timeRequestOfficeId !== '0') {
                $officeScope = ' AND EXISTS (SELECT 1 FROM employee_tbl e WHERE e.emp_idcode = employee_dtr_sub_tbl.emp_idcode AND e.officeid = :office_id AND e.xdel = 0)';
                $params[':office_id'] = $timeRequestOfficeId;
            }
            $update = $timeRequestCnn->prepare(
                "UPDATE employee_dtr_sub_tbl
                 SET {$field} = REPLACE({$field}, ' - REQUEST', ' - {$newStatus}')
                 WHERE empdtr_sub_autoid = :record_id
                   AND {$field} LIKE '% - REQUEST%'{$officeScope}"
            );
            $update->execute($params);
            $timeRequestMessage = $update->rowCount()
                ? 'Time request ' . ($action === 'approved' ? 'approved.' : 'disapproved.')
                : 'This time request is no longer pending or is outside your office.';
        }
    }

    $officeWhere = '';
    $queryParams = array();
    if ($timeRequestOfficeId !== '' && $timeRequestOfficeId !== '0') {
        $officeWhere = ' AND e.officeid = ?';
        $queryParams = array($timeRequestOfficeId, $timeRequestOfficeId, $timeRequestOfficeId, $timeRequestOfficeId);
    }
    $timeRequestSql = "
        SELECT d.empdtr_sub_autoid AS record_id, d.emp_idcode, e.officetitle, 'AM-In' AS requested_period, d.amtimein AS requested_time
        FROM employee_dtr_sub_tbl d INNER JOIN employee_tbl e ON e.emp_idcode = d.emp_idcode
        WHERE d.amtimein LIKE '% - REQUEST%' AND e.xdel = 0{$officeWhere}
        UNION ALL
        SELECT d.empdtr_sub_autoid AS record_id, d.emp_idcode, e.officetitle, 'AM-Out' AS requested_period, d.amtimeout AS requested_time
        FROM employee_dtr_sub_tbl d INNER JOIN employee_tbl e ON e.emp_idcode = d.emp_idcode
        WHERE d.amtimeout LIKE '% - REQUEST%' AND e.xdel = 0{$officeWhere}
        UNION ALL
        SELECT d.empdtr_sub_autoid AS record_id, d.emp_idcode, e.officetitle, 'PM-In' AS requested_period, d.pmtimein AS requested_time
        FROM employee_dtr_sub_tbl d INNER JOIN employee_tbl e ON e.emp_idcode = d.emp_idcode
        WHERE d.pmtimein LIKE '% - REQUEST%' AND e.xdel = 0{$officeWhere}
        UNION ALL
        SELECT d.empdtr_sub_autoid AS record_id, d.emp_idcode, e.officetitle, 'PM-Out' AS requested_period, d.pmtimeout AS requested_time
        FROM employee_dtr_sub_tbl d INNER JOIN employee_tbl e ON e.emp_idcode = d.emp_idcode
        WHERE d.pmtimeout LIKE '% - REQUEST%' AND e.xdel = 0{$officeWhere}
        ORDER BY record_id DESC, requested_period ASC";
    $timeRequestStmt = $timeRequestCnn->prepare($timeRequestSql);
    $timeRequestStmt->execute($queryParams);
    $timeRequests = $timeRequestStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $exception) {
    $timeRequestError = 'Unable to load time requests. Please try again.';
}
?>

<div class="container-fluid pt-3">
    <h5 class="mb-3 fw-bold text-light">Time Request - Employee</h5>
    <?php if ($timeRequestMessage): ?><div class="alert alert-success"><?php echo htmlspecialchars($timeRequestMessage); ?></div><?php endif; ?>
    <?php if ($timeRequestError): ?><div class="alert alert-warning"><?php echo htmlspecialchars($timeRequestError); ?></div><?php endif; ?>
    <div class="table-responsive">
        <table id="listRecView" class="table table-dark table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Employee ID</th>
                    <th>Requested Time</th>
                    <th>Office</th>
                    <th class="remove-dropdown">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($timeRequests as $index => $request): ?>
                    <?php
                        $fieldMap = array('AM-In' => 'amtimein', 'AM-Out' => 'amtimeout', 'PM-In' => 'pmtimein', 'PM-Out' => 'pmtimeout');
                        $requestedTime = trim((string) $request['requested_time']);
                    ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($request['emp_idcode']); ?></td>
                        <td><strong><?php echo htmlspecialchars($request['requested_period']); ?>:</strong> <?php echo htmlspecialchars($requestedTime); ?></td>
                        <td><?php echo htmlspecialchars($request['officetitle'] ?: '—'); ?></td>
                        <td class="text-nowrap">
                            <?php if ($canReviewTimeRequests): ?>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="time_request_employee_csrf" value="<?php echo htmlspecialchars($_SESSION['time_request_employee_csrf']); ?>">
                                    <input type="hidden" name="record_id" value="<?php echo (int) $request['record_id']; ?>">
                                    <input type="hidden" name="time_field" value="<?php echo $fieldMap[$request['requested_period']]; ?>">
                                    <button type="submit" name="time_request_action" value="approved" class="btn btn-success btn-sm">Approved</button>
                                    <button type="submit" name="time_request_action" value="disapproved" class="btn btn-outline-danger btn-sm" onclick="return confirm('Disapprove this requested time?');">Disapproved</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">Review unavailable</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

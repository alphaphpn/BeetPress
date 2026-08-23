<?php
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$routeName = basename($path);
$viewStatus = array('document-tracker-incoming' => 'In', 'document-tracker-outgoing' => 'Out', 'document-tracker-completed' => 'Completed', 'document-tracker-closed' => 'Closed', 'document-tracker-archived' => 'Archived');
$isNew = $routeName === 'document-tracker-new';
$isDetail = $routeName === 'document-tracker-detail';
$message = null; $error = null; $offices = array(); $documents = array(); $documentDetail = null; $documentTraces = array();
$statuses = array('In', 'Out', 'Completed', 'Closed', 'Archived');
$documentTypes = array();
$currentOfficeId = trim((string) ($_SESSION['d2s8wu_officeid'] ?? $_SESSION['officeid'] ?? ''));
$currentEmployee  = array(
    'id'      => trim((string) ($_SESSION['empidcode'] ?? '')),
    'name'    => trim((string) ($_SESSION['empname'] ?? '')),
    'office'  => trim((string) ($_SESSION['officename'] ?? $_SESSION['officetitle'] ?? '')),
    'officeid'=> trim((string) ($_SESSION['officeid'] ?? $currentOfficeId)),
);

try {
    $cnn = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $uname, $pw, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
    $cnn->exec("CREATE TABLE IF NOT EXISTS document_tracker_tbl (
        document_autoid BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        document_control_number VARCHAR(30) NOT NULL UNIQUE,
        document_number VARCHAR(150) NULL,
        document_type VARCHAR(255) NOT NULL,
        document_title VARCHAR(255) NOT NULL,
        document_description TEXT NULL,
        source_office VARCHAR(255) NULL,
        source_office_type ENUM('Internal','External') NOT NULL DEFAULT 'Internal',
        source_personnel VARCHAR(255) NULL,
        source_employee_id VARCHAR(30) NULL,
        officeid VARCHAR(50) NULL,
        recipient_office_id VARCHAR(50) NULL, recipient_office_name VARCHAR(255) NULL,
        thumbnail_path VARCHAR(500) NULL,
        attachment_json LONGTEXT NULL,
        current_status ENUM('In','Out','Completed','Closed','Archived') NOT NULL DEFAULT 'In',
        created_employee_id VARCHAR(30) NULL, created_employee_name VARCHAR(255) NULL, created_employee_office VARCHAR(255) NULL,
        created_user_id VARCHAR(100) NULL, created_user_name VARCHAR(255) NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, modified_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_document_status (current_status), INDEX idx_document_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	$documentColumns = array('officeid' => 'VARCHAR(50) NULL', 'recipient_office_id' => 'VARCHAR(50) NULL', 'recipient_office_name' => 'VARCHAR(255) NULL', 'amount' => 'DECIMAL(15,2) NULL');
	foreach ($documentColumns as $column => $definition) { $columnCheck = $cnn->prepare('SHOW COLUMNS FROM document_tracker_tbl LIKE :column'); $columnCheck->execute(array(':column' => $column)); if (!$columnCheck->fetch()) $cnn->exec("ALTER TABLE document_tracker_tbl ADD COLUMN {$column} {$definition}"); }
    $cnn->exec("CREATE TABLE IF NOT EXISTS document_trace_tbl (
        trace_autoid BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, document_autoid BIGINT UNSIGNED NOT NULL,
        trace_status ENUM('Pending','Received') NOT NULL DEFAULT 'Pending',
        employee_id VARCHAR(30) NULL, employee_name VARCHAR(255) NULL, employee_office VARCHAR(255) NULL,
        user_id VARCHAR(100) NULL, user_name VARCHAR(255) NULL, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_trace_document (document_autoid), INDEX idx_trace_status (trace_status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	$traceColumns = array('office_id' => 'VARCHAR(50) NULL', 'trace_action' => 'VARCHAR(50) NULL', 'remarks' => 'TEXT NULL');
	foreach ($traceColumns as $tcol => $tdef) { $tc = $cnn->prepare('SHOW COLUMNS FROM document_trace_tbl LIKE :col'); $tc->execute(array(':col' => $tcol)); if (!$tc->fetch()) $cnn->exec("ALTER TABLE document_trace_tbl ADD COLUMN {$tcol} {$tdef}"); }
    $cnn->exec("CREATE TABLE IF NOT EXISTS document_type_tbl (
        type_autoid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        type_name VARCHAR(255) NOT NULL,
        UNIQUE KEY uq_type_name (type_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $_typeCount = (int) $cnn->query('SELECT COUNT(*) FROM document_type_tbl')->fetchColumn();
    if ($_typeCount === 0) {
        $_defaultTypes = array('Administrative Order (AO)', 'Contract of Services', 'Department Order (DO) / Department Circular (DC)', 'Disbursement Voucher (DV)', 'Executive Order (EO)', 'Indorsement', 'Internal Memorandum (Memo)', 'Invoice', 'Job Order (JO)', 'Memorandum Circular (MC)', 'Notice of Award (NOA)', 'Notice of Meeting / Agenda', 'Notice to Proceed (NTP)', 'Obligation Request', 'Office Order (OO) / Special Order (SO)', 'Official Letter / Formal Letter', 'Payroll', 'Personal Data Sheet', 'Policy', 'Purchase Order', 'Purchase Request', 'Resolution', 'Salary', 'Statement of Account (SOA)');
        $_insType = $cnn->prepare('INSERT IGNORE INTO document_type_tbl (type_name) VALUES (:name)');
        foreach ($_defaultTypes as $_dt) { $_insType->execute(array(':name' => $_dt)); }
    }
    $documentTypes = $cnn->query('SELECT type_name FROM document_type_tbl ORDER BY type_name ASC')->fetchAll(PDO::FETCH_COLUMN);
    $offices = $cnn->query("SELECT officeid, officename, officetitle FROM office_tbl WHERE xdel = 0 ORDER BY officetitle ASC, officename ASC")->fetchAll();
    if (empty($_SESSION['document_tracker_csrf'])) $_SESSION['document_tracker_csrf'] = bin2hex(random_bytes(32));

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_document'])) {
		if (!hash_equals($_SESSION['document_tracker_csrf'], (string) ($_POST['document_tracker_csrf'] ?? ''))) throw new RuntimeException('Your form session expired. Please try again.');
		$documentId = (int) ($_POST['document_autoid'] ?? 0); $userId = trim((string) ($_SESSION['d2s8wu_uid'] ?? ''));
		$owner = $cnn->prepare('SELECT created_user_id, current_status, thumbnail_path, attachment_json FROM document_tracker_tbl WHERE document_autoid = :id LIMIT 1'); $owner->execute(array(':id' => $documentId)); $ownerRow = $owner->fetch(); $ownerId = $ownerRow ? $ownerRow['created_user_id'] : false;
		if ($documentId <= 0 || $ownerRow === false) throw new RuntimeException('Document not found.');
		if ($userId === '' || !hash_equals((string) $ownerId, $userId)) throw new RuntimeException('Only the user who created this document can delete it.');
		if (!in_array($ownerRow['current_status'], array('Closed'), true)) throw new RuntimeException('This document cannot be deleted. It must be Closed before it can be deleted.');
		$_rootDir = dirname(__DIR__, 3);
		if (!empty($ownerRow['thumbnail_path'])) { $_thumbFile = $_rootDir . '/' . ltrim($ownerRow['thumbnail_path'], '/'); if (is_file($_thumbFile)) @unlink($_thumbFile); }
		$_attachJson = json_decode((string) $ownerRow['attachment_json'], true);
		if (is_array($_attachJson)) { foreach ($_attachJson as $_att) { if (!empty($_att['path'])) { $_attFile = $_rootDir . '/' . ltrim($_att['path'], '/'); if (is_file($_attFile)) @unlink($_attFile); } } }
		$cnn->beginTransaction(); $deleteTraces = $cnn->prepare('DELETE FROM document_trace_tbl WHERE document_autoid = :id'); $deleteTraces->execute(array(':id' => $documentId)); $deleteDocument = $cnn->prepare('DELETE FROM document_tracker_tbl WHERE document_autoid = :id AND created_user_id = :user_id'); $deleteDocument->execute(array(':id' => $documentId, ':user_id' => $userId)); $cnn->commit();
		$message = 'Document, its trace history, and all associated files were deleted.'; $isDetail = false; $routeName = 'document-tracker';
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_received'])) {
		if (!hash_equals($_SESSION['document_tracker_csrf'], (string) ($_POST['document_tracker_csrf'] ?? ''))) throw new RuntimeException('Your form session expired. Please try again.');
		$documentId = (int) ($_POST['document_autoid'] ?? 0); $userId = trim((string) ($_SESSION['d2s8wu_uid'] ?? '')); $userName = trim((string) ($_SESSION['d2s8wu_uname'] ?? ''));
		$receiver = array('id' => '', 'name' => '', 'office' => '');
		if ($userId !== '') { $receiverStmt = $cnn->prepare('SELECT emp_idcode, emp_name_forid, officename_forid, officeid FROM employee_tbl WHERE uid = :uid AND xdel = 0 LIMIT 1'); $receiverStmt->execute(array(':uid' => $userId)); $employee = $receiverStmt->fetch(); if ($employee) { $receiver = array('id' => $employee['emp_idcode'], 'name' => $employee['emp_name_forid'], 'office' => $employee['officename_forid']); $currentOfficeId = trim((string) ($employee['officeid'] ?: $currentOfficeId)); } }
		if ($documentId <= 0) throw new RuntimeException('Invalid document selected.');
		$receiveRemarks = trim((string) ($_POST['receive_remarks'] ?? ''));
		$receiveTrace = $cnn->prepare('INSERT INTO document_trace_tbl (document_autoid, trace_status, trace_action, office_id, employee_id, employee_name, employee_office, user_id, user_name, remarks) SELECT document_autoid, "Received", "Received", :office_id, :employee_id, :employee_name, :employee_office, :user_id, :user_name, :remarks FROM document_tracker_tbl WHERE document_autoid = :document AND recipient_office_id = :officeid');
		$receiveTrace->execute(array(':office_id'=>$currentOfficeId, ':employee_id'=>$receiver['id'], ':employee_name'=>$receiver['name'], ':employee_office'=>$receiver['office'], ':user_id'=>$userId, ':user_name'=>$userName, ':document'=>$documentId, ':officeid'=>$currentOfficeId, ':remarks'=>$receiveRemarks !== '' ? $receiveRemarks : null));
		$message = $receiveTrace->rowCount() ? 'Document receipt recorded.' : 'The document could not be found.';
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_out'])) {
		if (!hash_equals($_SESSION['document_tracker_csrf'], (string) ($_POST['document_tracker_csrf'] ?? ''))) throw new RuntimeException('Your form session expired. Please try again.');
		$documentId = (int) ($_POST['document_autoid'] ?? 0);
		$recipientOfficeId = trim((string) ($_POST['recipient_office_id'] ?? ''));
		if ($documentId <= 0 || $recipientOfficeId === '') throw new RuntimeException('Please select a receiving office.');
		$recipientRow = $cnn->prepare('SELECT officename, officetitle FROM office_tbl WHERE officeid = :oid AND xdel = 0 LIMIT 1');
		$recipientRow->execute(array(':oid' => $recipientOfficeId));
		$recipientOffice = $recipientRow->fetch();
		if (!$recipientOffice) throw new RuntimeException('Selected office is not valid.');
		$recipientOfficeName = trim((string) ($recipientOffice['officename'] ?: $recipientOffice['officetitle']));
		$docCheck = $cnn->prepare('SELECT document_autoid FROM document_tracker_tbl WHERE document_autoid = :id AND (officeid = :owner OR recipient_office_id = :recv) AND current_status IN ("In","Out") LIMIT 1');
		$docCheck->execute(array(':id' => $documentId, ':owner' => $currentOfficeId, ':recv' => $currentOfficeId));
		if (!$docCheck->fetch()) throw new RuntimeException('Document not found or cannot be forwarded.');
		$outRemarks = trim((string) ($_POST['out_remarks'] ?? ''));
		$cnn->beginTransaction();
		$cnn->prepare('UPDATE document_tracker_tbl SET current_status="Out", recipient_office_id=:rid, recipient_office_name=:rname WHERE document_autoid=:id')->execute(array(':rid' => $recipientOfficeId, ':rname' => $recipientOfficeName, ':id' => $documentId));
		$cnn->prepare('INSERT INTO document_trace_tbl (document_autoid, trace_status, trace_action, office_id, user_id, user_name, remarks) VALUES (:doc,"Pending","Out",:oid,:uid,:uname,:remarks)')->execute(array(':doc' => $documentId, ':oid' => $currentOfficeId, ':uid' => trim((string) ($_SESSION['d2s8wu_uid'] ?? '')), ':uname' => trim((string) ($_SESSION['d2s8wu_uname'] ?? '')), ':remarks' => $outRemarks !== '' ? $outRemarks : null));
		$cnn->commit();
		$message = 'Document sent out to ' . htmlspecialchars($recipientOfficeName) . '.';
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recall_document'])) {
		if (!hash_equals($_SESSION['document_tracker_csrf'], (string) ($_POST['document_tracker_csrf'] ?? ''))) throw new RuntimeException('Your form session expired. Please try again.');
		$documentId = (int) ($_POST['document_autoid'] ?? 0);
		if ($documentId <= 0) throw new RuntimeException('Invalid document selected.');
		$recallRow = $cnn->prepare('SELECT document_autoid, recipient_office_id FROM document_tracker_tbl WHERE document_autoid = :id AND officeid = :owner AND current_status = "Out" LIMIT 1');
		$recallRow->execute(array(':id' => $documentId, ':owner' => $currentOfficeId));
		$recallDoc = $recallRow->fetch();
		if (!$recallDoc) throw new RuntimeException('Document not found or you do not have permission to recall it.');
		$hasReceived = $cnn->prepare('SELECT 1 FROM document_trace_tbl WHERE document_autoid = :id AND office_id = :recv AND trace_action = "Received" LIMIT 1');
		$hasReceived->execute(array(':id' => $documentId, ':recv' => $recallDoc['recipient_office_id']));
		if ($hasReceived->fetch()) throw new RuntimeException('Cannot recall — the recipient office has already marked this document as Received.');
		$cnn->beginTransaction();
		$cnn->prepare('UPDATE document_tracker_tbl SET current_status = "In", recipient_office_id = NULL, recipient_office_name = NULL WHERE document_autoid = :id')->execute(array(':id' => $documentId));
		$cnn->prepare('INSERT INTO document_trace_tbl (document_autoid, trace_status, trace_action, office_id, user_id, user_name) VALUES (:doc,"Pending","Recalled",:oid,:uid,:uname)')->execute(array(':doc' => $documentId, ':oid' => $currentOfficeId, ':uid' => trim((string) ($_SESSION['d2s8wu_uid'] ?? '')), ':uname' => trim((string) ($_SESSION['d2s8wu_uname'] ?? ''))));
		$cnn->commit();
		$message = 'Document recalled back to Incoming.';
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
		if (!hash_equals($_SESSION['document_tracker_csrf'], (string) ($_POST['document_tracker_csrf'] ?? ''))) throw new RuntimeException('Your form session expired. Please try again.');
		$documentId = (int) ($_POST['document_autoid'] ?? 0);
		$newStatus = trim((string) ($_POST['new_status'] ?? ''));
		if ($documentId <= 0 || !in_array($newStatus, array('Completed','Closed','Archived'), true)) throw new RuntimeException('Invalid status.');
		$docCheck = $cnn->prepare('SELECT document_autoid FROM document_tracker_tbl WHERE document_autoid = :id AND (officeid = :owner OR recipient_office_id = :recv) LIMIT 1');
		$docCheck->execute(array(':id' => $documentId, ':owner' => $currentOfficeId, ':recv' => $currentOfficeId));
		if (!$docCheck->fetch()) throw new RuntimeException('Document not found or access denied.');
		$statusRemarks = trim((string) ($_POST['status_remarks'] ?? ''));
		$cnn->beginTransaction();
		$cnn->prepare('UPDATE document_tracker_tbl SET current_status = :status WHERE document_autoid = :id')->execute(array(':status' => $newStatus, ':id' => $documentId));
		$cnn->prepare('INSERT INTO document_trace_tbl (document_autoid, trace_status, trace_action, office_id, user_id, user_name, remarks) VALUES (:doc,"Pending",:action,:oid,:uid,:uname,:remarks)')->execute(array(':doc' => $documentId, ':action' => $newStatus, ':oid' => $currentOfficeId, ':uid' => trim((string) ($_SESSION['d2s8wu_uid'] ?? '')), ':uname' => trim((string) ($_SESSION['d2s8wu_uname'] ?? '')), ':remarks' => $statusRemarks !== '' ? $statusRemarks : null));
		$cnn->commit();
		$message = 'Document marked as ' . $newStatus . '.';
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_document'])) {
        if (!hash_equals($_SESSION['document_tracker_csrf'], (string) ($_POST['document_tracker_csrf'] ?? ''))) throw new RuntimeException('Your form session expired. Please try again.');
        $type = trim((string) ($_POST['document_type'] ?? '')); $title = trim((string) ($_POST['document_title'] ?? '')); $amountRaw = trim((string) ($_POST['document_amount'] ?? '')); $amount = ($amountRaw !== '' && is_numeric($amountRaw)) ? round((float) $amountRaw, 2) : null;
        $status = trim((string) ($_POST['current_status'] ?? 'In'));
        if ($type === '' || $title === '' || !in_array($status, $statuses, true)) throw new RuntimeException('Document Type, Title, and a valid Status are required.');
        $cnn->prepare('INSERT IGNORE INTO document_type_tbl (type_name) VALUES (:name)')->execute(array(':name' => $type));
        $sourceType = ($_POST['source_office_type'] ?? 'Internal') === 'External' ? 'External' : 'Internal';
        $sourceOffice = trim((string) ($sourceType === 'External' ? ($_POST['external_source_office'] ?? '') : ($_POST['source_office'] ?? '')));
        $sourceEmployeeId = trim((string) ($_POST['source_employee_id'] ?? ''));
        $sourcePersonnel = trim((string) ($_POST['source_personnel'] ?? ''));
		$recipientOfficeId = trim((string) ($_POST['recipient_office_id'] ?? '')); $recipientOfficeName = '';
		if ($status === 'Out') { if ($recipientOfficeId === '') throw new RuntimeException('Select the receiving office before sending a document Out.'); $recipient = $cnn->prepare('SELECT officename, officetitle FROM office_tbl WHERE officeid = :officeid AND xdel = 0 LIMIT 1'); $recipient->execute(array(':officeid' => $recipientOfficeId)); $recipientRow = $recipient->fetch(); if (!$recipientRow) throw new RuntimeException('Select a valid receiving office.'); $recipientOfficeName = trim((string) ($recipientRow['officename'] ?: $recipientRow['officetitle'])); }
        $createdUserId = trim((string) ($_SESSION['d2s8wu_uid'] ?? '')); $createdUserName = trim((string) ($_SESSION['d2s8wu_uname'] ?? ''));
        $createdEmployee = $currentEmployee;
		if ($sourceType === 'Internal' && $sourceOffice === '') $sourceOffice = $createdEmployee['office'];
		if ($sourcePersonnel === '') $sourcePersonnel = $createdEmployee['name'];
		if ($sourceEmployeeId === '') $sourceEmployeeId = $createdEmployee['id'];
		if ($createdEmployee['officeid'] === '') throw new RuntimeException('Your user or employee account must have an Office ID before adding a document.');
        $uploadDirectory = 'public/document_tracker/' . date('Y/m') . '/'; $absoluteDirectory = dirname(__DIR__, 3) . '/' . $uploadDirectory;
        if (!is_dir($absoluteDirectory) && !mkdir($absoluteDirectory, 0755, true)) throw new RuntimeException('Could not create the document storage folder.');
        $thumbnailPath = null;
        if (!empty($_POST['thumbnail_capture']) && preg_match('#^data:image/(png|jpeg);base64,(.+)$#', $_POST['thumbnail_capture'], $capture)) { $thumbnailPath = $uploadDirectory . 'thumb-' . bin2hex(random_bytes(10)) . '.jpg'; file_put_contents(dirname(__DIR__, 3) . '/' . $thumbnailPath, base64_decode($capture[2], true)); }
        $attachments = array();
        if (!empty($_FILES['attachments']['name'][0])) foreach ($_FILES['attachments']['name'] as $key => $originalName) { if ($_FILES['attachments']['error'][$key] !== UPLOAD_ERR_OK) continue; if ($_FILES['attachments']['size'][$key] > 15 * 1024 * 1024) throw new RuntimeException('Each attachment must be 15 MB or smaller.'); $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION)); if (!in_array($extension, array('pdf','doc','docx','xls','xlsx','jpg','jpeg','png','gif','webp'), true)) throw new RuntimeException('Only PDF, Word, Excel, and image attachments are allowed.'); $storedName = 'file-' . bin2hex(random_bytes(10)) . '.' . $extension; if (move_uploaded_file($_FILES['attachments']['tmp_name'][$key], $absoluteDirectory . $storedName)) $attachments[] = array('name' => basename($originalName), 'path' => $uploadDirectory . $storedName); }
        if ($thumbnailPath === null && !empty($attachments)) {
            $_fa = $attachments[0]; $_faExt = strtolower(pathinfo($_fa['path'], PATHINFO_EXTENSION));
            $_faFile = dirname(__DIR__, 3) . '/' . $_fa['path'];
            $_thumbDest = $absoluteDirectory . 'thumb-' . bin2hex(random_bytes(10)) . '.jpg';
            $_thumbOk = false;
            if (in_array($_faExt, array('jpg','jpeg','png','gif','webp'), true) && extension_loaded('gd')) {
                $_gdMap = array('jpg'=>'imagecreatefromjpeg','jpeg'=>'imagecreatefromjpeg','png'=>'imagecreatefrompng','gif'=>'imagecreatefromgif','webp'=>'imagecreatefromwebp');
                $_src = @$_gdMap[$_faExt]($_faFile);
                if ($_src) {
                    $_w = imagesx($_src); $_h = imagesy($_src); $_max = 600;
                    $_r = min($_max / $_w, $_max / $_h, 1); $_nw = (int)round($_w * $_r); $_nh = (int)round($_h * $_r);
                    $_dst = imagecreatetruecolor($_nw, $_nh);
                    if ($_faExt === 'png') { imagealphablending($_dst, false); imagesavealpha($_dst, true); $bg = imagecolorallocate($_dst, 255, 255, 255); imagefill($_dst, 0, 0, $bg); }
                    imagecopyresampled($_dst, $_src, 0, 0, 0, 0, $_nw, $_nh, $_w, $_h);
                    $_thumbOk = imagejpeg($_dst, $_thumbDest, 85);
                    imagedestroy($_src); imagedestroy($_dst);
                }
            } elseif ($_faExt === 'pdf' && extension_loaded('imagick')) {
                try { $_im = new Imagick(); $_im->setResolution(150, 150); $_im->readImage($_faFile . '[0]'); $_im->setImageFormat('jpeg'); $_im->thumbnailImage(600, 0); $_im->writeImage($_thumbDest); $_im->destroy(); $_thumbOk = true; } catch (Exception $_ie) {}
            }
            if ($_thumbOk) $thumbnailPath = $uploadDirectory . basename($_thumbDest);
        }
        $cnn->beginTransaction();
        $prefix = date('Y-m-d-'); $control = ''; $counter = 1;
        do { $control = $prefix . str_pad((string) $counter++, 6, '0', STR_PAD_LEFT); $check = $cnn->prepare('SELECT document_autoid FROM document_tracker_tbl WHERE document_control_number = :number FOR UPDATE'); $check->execute(array(':number' => $control)); } while ($check->fetch());
        $insert = $cnn->prepare('INSERT INTO document_tracker_tbl (document_control_number, document_number, document_type, document_title, document_description, amount, source_office, source_office_type, source_personnel, source_employee_id, officeid, recipient_office_id, recipient_office_name, thumbnail_path, attachment_json, current_status, created_employee_id, created_employee_name, created_employee_office, created_user_id, created_user_name) VALUES (:control,:number,:type,:title,:description,:amount,:source_office,:source_type,:personnel,:source_employee,:officeid,:recipient_office_id,:recipient_office_name,:thumbnail,:attachments,:status,:employee_id,:employee_name,:employee_office,:user_id,:user_name)');
        $insert->execute(array(':control'=>$control, ':number'=>trim((string)($_POST['document_number'] ?? '')), ':type'=>$type, ':title'=>$title, ':description'=>trim((string)($_POST['document_description'] ?? '')), ':amount'=>$amount, ':source_office'=>$sourceOffice, ':source_type'=>$sourceType, ':personnel'=>$sourcePersonnel, ':source_employee'=>$sourceEmployeeId, ':officeid'=>$createdEmployee['officeid'], ':recipient_office_id'=>$recipientOfficeId ?: null, ':recipient_office_name'=>$recipientOfficeName ?: null, ':thumbnail'=>$thumbnailPath, ':attachments'=>json_encode($attachments), ':status'=>$status, ':employee_id'=>$createdEmployee['id'], ':employee_name'=>$createdEmployee['name'], ':employee_office'=>$createdEmployee['office'], ':user_id'=>$createdUserId, ':user_name'=>$createdUserName));
        $documentId = (int) $cnn->lastInsertId();
        $trace = $cnn->prepare('INSERT INTO document_trace_tbl (document_autoid, trace_status, trace_action, office_id, employee_id, employee_name, employee_office, user_id, user_name) VALUES (:document, "Pending", "Created", :office_id, :emp_id, :emp_name, :emp_office, :user_id, :user_name)'); $trace->execute(array(':document'=>$documentId, ':office_id'=>$createdEmployee['officeid'], ':emp_id'=>$createdEmployee['id'], ':emp_name'=>$createdEmployee['name'], ':emp_office'=>$createdEmployee['office'], ':user_id'=>$createdUserId, ':user_name'=>$createdUserName));
        $cnn->commit(); $message = 'Document saved. Control Number: ' . $control;
        $savedDocData = array('control' => $control, 'title' => $title, 'description' => trim((string)($_POST['document_description'] ?? '')), 'source_office' => $sourceOffice, 'source_personnel' => $sourcePersonnel, 'source_employee_id' => $sourceEmployeeId, 'status' => $status);
    }
	if ($isDetail) { $detailId = (int) ($_GET['id'] ?? 0); if ($detailId <= 0) throw new RuntimeException('A valid document was not selected.'); $detail = $cnn->prepare('SELECT * FROM document_tracker_tbl WHERE document_autoid = :id AND (officeid = :owner_officeid OR recipient_office_id = :recipient_officeid OR (SELECT t3.office_id FROM document_trace_tbl t3 WHERE t3.document_autoid = :id2 ORDER BY t3.trace_autoid DESC LIMIT 1) = :last_oid) LIMIT 1'); $detail->execute(array(':id' => $detailId, ':id2' => $detailId, ':owner_officeid' => $currentOfficeId, ':recipient_officeid' => $currentOfficeId, ':last_oid' => $currentOfficeId)); $documentDetail = $detail->fetch(); if (!$documentDetail) throw new RuntimeException('Document not found or not assigned to your office.'); $traces = $cnn->prepare('SELECT t.*, o.officename AS office_name_lookup, o.officetitle AS office_title_lookup FROM document_trace_tbl t LEFT JOIN office_tbl o ON o.officeid = t.office_id WHERE t.document_autoid = :id ORDER BY t.created_at DESC, t.trace_autoid DESC'); $traces->execute(array(':id' => $detailId)); $documentTraces = $traces->fetchAll(); }
	elseif (!$isNew) { if ($routeName === 'document-tracker-incoming') { $list = $cnn->prepare('SELECT d.*, (SELECT t.trace_status FROM document_trace_tbl t WHERE t.document_autoid=d.document_autoid ORDER BY t.trace_autoid DESC LIMIT 1) AS trace_status FROM document_tracker_tbl d WHERE (d.officeid = :owner_officeid AND d.current_status = "In") OR (d.recipient_office_id = :recipient_officeid AND d.current_status = "Out") ORDER BY d.created_at DESC'); $list->execute(array(':owner_officeid' => $currentOfficeId, ':recipient_officeid' => $currentOfficeId)); } elseif ($routeName === 'document-tracker-outgoing') { $list = $cnn->prepare('SELECT d.*, (SELECT t.trace_status FROM document_trace_tbl t WHERE t.document_autoid=d.document_autoid ORDER BY t.trace_autoid DESC LIMIT 1) AS trace_status FROM document_tracker_tbl d WHERE d.current_status = "Out" AND d.recipient_office_id != :excl AND (d.officeid = :officeid OR (SELECT t2.office_id FROM document_trace_tbl t2 WHERE t2.document_autoid=d.document_autoid ORDER BY t2.trace_autoid DESC LIMIT 1) = :last_oid) GROUP BY d.document_autoid ORDER BY d.created_at DESC'); $list->execute(array(':excl' => $currentOfficeId, ':officeid' => $currentOfficeId, ':last_oid' => $currentOfficeId)); } else { $selectedStatus = $viewStatus[$routeName] ?? 'In'; $list = $cnn->prepare('SELECT d.*, (SELECT t.trace_status FROM document_trace_tbl t WHERE t.document_autoid=d.document_autoid ORDER BY t.trace_autoid DESC LIMIT 1) AS trace_status FROM document_tracker_tbl d WHERE d.current_status = :status AND (d.officeid = :officeid OR (d.recipient_office_id = :recv AND EXISTS (SELECT 1 FROM document_trace_tbl t_r WHERE t_r.document_autoid=d.document_autoid AND t_r.office_id=:recv2 AND t_r.trace_action="Received")) OR (SELECT t2.office_id FROM document_trace_tbl t2 WHERE t2.document_autoid=d.document_autoid ORDER BY t2.trace_autoid DESC LIMIT 1) = :last_oid) GROUP BY d.document_autoid ORDER BY d.created_at DESC'); $list->execute(array(':status' => $selectedStatus, ':officeid' => $currentOfficeId, ':recv' => $currentOfficeId, ':recv2' => $currentOfficeId, ':last_oid' => $currentOfficeId)); } $documents = $list->fetchAll(); }
} catch (Throwable $exception) { if (isset($cnn) && $cnn->inTransaction()) $cnn->rollBack(); $error = $exception->getMessage() === '' ? 'The document tracker is temporarily unavailable.' : $exception->getMessage(); }
?>
<style>.document-camera{min-height:300px;background:#161b22;border-radius:.5rem;overflow:hidden}.document-camera video{width:100%;height:300px;object-fit:cover}.thumbnail-preview{max-height:200px;max-width:100%;object-fit:contain;border-radius:.5rem}.document-control{font-family:monospace;font-weight:700}</style>
<div class="pt-5">
<?php if ($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?><?php if ($error): ?><div class="alert alert-warning"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<?php if (!empty($savedDocData)): ?>
<div class="modal fade" id="savedDocModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="savedDocModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content text-dark">
      <div class="modal-header bg-success text-white py-2">
        <h6 class="modal-title fw-bold mb-0"><i class="fas fa-check-circle me-2"></i>Document Saved</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <div class="fw-bold text-muted small mb-1">Document Control Number</div>
          <div class="font-monospace fw-bold fs-5 mb-3" id="sdm-control"><?php echo htmlspecialchars($savedDocData['control']); ?></div>
          <div class="d-flex justify-content-center gap-5 flex-wrap mb-2">
            <div>
              <div class="small text-muted mb-1">QR Code</div>
              <div id="sdm-qr" style="width:140px;height:140px;margin:auto;"></div>
            </div>
            <div>
              <div class="small text-muted mb-1">Barcode</div>
              <div style="background:#fff;padding:8px 12px;border-radius:6px;display:inline-block;">
                <svg id="sdm-barcode"></svg>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="row g-2 small">
          <div class="col-sm-6"><span class="text-muted">Title:</span> <strong><?php echo htmlspecialchars($savedDocData['title']); ?></strong></div>
          <div class="col-sm-6"><span class="text-muted">Status:</span> <span class="badge text-bg-primary"><?php echo htmlspecialchars($savedDocData['status']); ?></span></div>
          <div class="col-sm-6"><span class="text-muted">Source Office:</span> <?php echo htmlspecialchars($savedDocData['source_office'] ?: '—'); ?></div>
          <div class="col-sm-6"><span class="text-muted">Source Personnel:</span> <?php echo htmlspecialchars($savedDocData['source_personnel'] ?: '—'); ?></div>
          <div class="col-sm-6"><span class="text-muted">Employee ID:</span> <?php echo htmlspecialchars($savedDocData['source_employee_id'] ?: '—'); ?></div>
          <div class="col-12"><span class="text-muted">Description:</span> <?php echo nl2br(htmlspecialchars($savedDocData['description'] ?: '—')); ?></div>
        </div>
      </div>
      <div class="modal-footer py-2">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="printDocumentLabel()"><i class="fas fa-print me-1"></i>Print</button>
      </div>
    </div>
  </div>
</div>
<script>
(function(){
  var _sdmControl = <?php echo json_encode($savedDocData['control']); ?>;
  var _sdmData = <?php echo json_encode(array(
    'title'       => $savedDocData['title'],
    'description' => $savedDocData['description'],
    'source_office'      => $savedDocData['source_office'],
    'source_personnel'   => $savedDocData['source_personnel'],
    'source_employee_id' => $savedDocData['source_employee_id'],
    'status'      => $savedDocData['status'],
  )); ?>;

  function loadScript(src, cb) { var s=document.createElement('script'); s.src=src; s.onload=cb; document.head.appendChild(s); }

  loadScript('https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js', function(){
    loadScript('https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js', function(){
      var modal = new bootstrap.Modal(document.getElementById('savedDocModal'));
      modal.show();
      document.getElementById('savedDocModal').addEventListener('shown.bs.modal', function(){
        new QRCode(document.getElementById('sdm-qr'), {
          text: _sdmControl, width: 140, height: 140, correctLevel: QRCode.CorrectLevel.M
        });
        JsBarcode('#sdm-barcode', _sdmControl, {
          format: 'CODE128', lineColor: '#000', background: '#fff',
          width: 2, height: 60, displayValue: false, margin: 0
        });
      }, {once: true});
    });
  });

  window.printDocumentLabel = function() {
    var qrEl = document.querySelector('#sdm-qr canvas');
    var qrSrc = qrEl ? qrEl.toDataURL() : '';
    var bcSvg = document.getElementById('sdm-barcode');
    var bcSrc = '';
    if (bcSvg) {
      var svgData = new XMLSerializer().serializeToString(bcSvg);
      bcSrc = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svgData);
    }
    var w = window.open('', '_blank');
    w.document.write('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Document Label - ' + _sdmControl + '</title><style>@page{size:A6 portrait;margin:0}*{margin:0;padding:0;box-sizing:border-box}body{font-family:Arial,sans-serif;width:105mm;padding:6mm;color:#000;background:#fff;font-size:8pt}.control-no{font-family:monospace;font-size:11pt;font-weight:700;text-align:left;margin-bottom:4mm;letter-spacing:1px}.codes{display:flex;justify-content:flex-start;align-items:flex-end;gap:6mm;margin-bottom:3mm}.codes img{display:block}.codes .qr-img{width:22mm;height:22mm}.codes .bc-img{width:52mm;height:14mm}.codes-wrap{text-align:left}.codes-label{font-size:6pt;color:#555;margin-bottom:1mm}hr{border:none;border-top:0.5px solid #ccc;margin:2mm 0}table{width:100%;border-collapse:collapse}table td{padding:1mm 2mm;vertical-align:top;line-height:1.3}table td:first-child{color:#555;width:36%;font-size:7pt;white-space:nowrap}table td:last-child{font-weight:600;font-size:7.5pt;word-break:break-word}</style></head><body><div class="control-no">' + _sdmControl + '</div><div class="codes"><div class="codes-wrap"><div class="codes-label">QR Code</div><img class="qr-img" src="' + qrSrc + '"></div><div class="codes-wrap"><div class="codes-label">Barcode</div><img class="bc-img" src="' + bcSrc + '"></div></div><hr><table><tr><td>Title</td><td>' + _esc(_sdmData.title) + '</td></tr><tr><td>Status</td><td>' + _esc(_sdmData.status) + '</td></tr><tr><td>Source Office</td><td>' + _esc(_sdmData.source_office||'—') + '</td></tr><tr><td>Personnel</td><td>' + _esc(_sdmData.source_personnel||'—') + '</td></tr><tr><td>Employee ID</td><td>' + _esc(_sdmData.source_employee_id||'—') + '</td></tr><tr><td>Description</td><td>' + _esc(_sdmData.description||'—') + '</td></tr></table><script>window.onload=function(){window.print();};<\/script></body></html>');
    w.document.close();
  };

  function _esc(s){ var d=document.createElement('div');d.textContent=String(s);return d.innerHTML; }
}());
</script>
<?php endif; ?>
<?php if ($isNew): ?>
<div class="card shadow-sm"><div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-folder-plus me-2 text-primary"></i>New Document</h5></div><div class="card-body p-4"><form method="post" enctype="multipart/form-data" id="document-form"><input type="hidden" name="document_tracker_csrf" value="<?php echo htmlspecialchars($_SESSION['document_tracker_csrf'] ?? ''); ?>"><input type="hidden" name="save_document" value="1"><input type="hidden" id="thumbnail-capture" name="thumbnail_capture"><div class="row g-4">
<div class="col-lg-4"><label class="form-label fw-semibold">Document Thumbnail</label><div class="btn-group btn-group-sm w-100 mb-2" role="group"><button type="button" class="btn btn-outline-primary active" id="thumb-mode-camera"><i class="fas fa-camera me-1"></i>Camera</button><button type="button" class="btn btn-outline-secondary" id="thumb-mode-upload"><i class="fas fa-upload me-1"></i>Upload Image</button></div><div id="thumb-camera-section"><div id="thumb-select-wrap" class="mb-2 d-none"><select id="thumb-camera-select" class="form-select form-select-sm"><option value="">Select Camera</option></select></div><div class="document-camera"><video id="document-camera" autoplay playsinline muted></video></div><canvas id="document-canvas" class="d-none"></canvas><div class="d-flex gap-1 flex-wrap mt-2"><button class="btn btn-outline-primary btn-sm" type="button" id="start-camera"><i class="fas fa-camera me-1"></i>Start Camera</button><button class="btn btn-success btn-sm d-none" type="button" id="capture-thumbnail"><i class="fas fa-camera me-1"></i>Click Photo</button><button class="btn btn-secondary btn-sm d-none" type="button" id="stop-camera"><i class="fas fa-stop me-1"></i>Stop</button><button class="btn btn-warning btn-sm d-none" type="button" id="retake-photo"><i class="fas fa-redo me-1"></i>Re-Take</button></div></div><div id="thumb-upload-section" class="d-none mt-1"><div class="form-text mb-1">Select an image file to use as thumbnail.</div><input type="file" class="form-control form-control-sm" id="thumb-upload-input" accept="image/*"></div><img id="thumbnail-preview" class="thumbnail-preview d-none mt-2 w-100" alt="Document thumbnail preview"></div>
<div class="col-lg-8"><div class="row g-3"><div class="col-md-6"><label class="form-label">Document Control Number</label><input class="form-control document-control" value="Generated automatically when saved" disabled></div><div class="col-md-6"><label class="form-label">Document Number</label><input class="form-control" name="document_number" maxlength="150"></div><div class="col-md-6"><label class="form-label">Document Type <span class="text-danger">*</span></label><input class="form-control" id="doc-type-input" name="document_type" list="document-type-options" required><datalist id="document-type-options"><?php foreach ($documentTypes as $documentType): ?><option value="<?php echo htmlspecialchars($documentType); ?>"><?php endforeach; ?></datalist></div><div class="col-md-6"><label class="form-label">Current Status</label><select class="form-select" name="current_status" id="current-status"><?php foreach ($statuses as $status): ?><option value="<?php echo $status; ?>" <?php echo $status === 'In' ? 'selected' : ''; ?>><?php echo $status; ?></option><?php endforeach; ?></select></div><div class="col-12"><label class="form-label">Title <span class="text-danger">*</span></label><input class="form-control" name="document_title" maxlength="255" required></div><div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="document_description" rows="3"></textarea></div><div class="col-md-6"><label class="form-label">Amount<span id="amount-req-star" class="text-danger d-none"> *</span></label><div class="input-group"><span class="input-group-text">₱</span><input class="form-control" id="doc-amount-input" type="number" name="document_amount" min="0" step="0.01" placeholder="0.00"></div><div class="form-text" id="amount-form-text">Leave blank if not applicable.</div></div></div></div>
<div class="col-md-4"><label class="form-label">Source Office</label><select class="form-select" name="source_office_type" id="source-office-type"><option value="Internal">Internal Office</option><option value="External">External Document</option></select></div><div class="col-md-8"><label class="form-label">Office</label><select class="form-select" name="source_office" id="source-office"><option value="">Select internal office</option><?php foreach ($offices as $office): $officeName=trim(($office['officename'] ?: $office['officetitle'])); $officeLabel=trim(($office['officetitle'] ?: $officeName)); ?><option value="<?php echo htmlspecialchars($officeName); ?>" <?php echo ($currentEmployee['officeid'] !== '' && $office['officeid'] === $currentEmployee['officeid']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($officeLabel); ?></option><?php endforeach; ?></select><input class="form-control d-none" id="external-source-office" name="external_source_office" placeholder="Enter external office or organization"></div>
<div class="col-md-4"><label class="form-label">Source Personnel</label><input class="form-control" name="source_personnel" id="source-personnel" placeholder="Name of personnel" value="<?php echo htmlspecialchars($currentEmployee['name']); ?>"></div><div class="col-md-4"><label class="form-label">Source Employee ID</label><input class="form-control" name="source_employee_id" id="source-employee-id" maxlength="8" inputmode="numeric" placeholder="8-digit employee ID" value="<?php echo htmlspecialchars($currentEmployee['id']); ?>"><div id="employee-lookup-message" class="form-text"></div></div><div class="col-md-4"><label class="form-label">Attachments</label><input class="form-control" type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,image/*"><div class="form-text">PDF, Word, Excel, or image; max 15 MB each.</div></div>
<div class="col-md-6 d-none" id="recipient-office-wrap"><label class="form-label">Receiving Office <span class="text-danger">*</span></label><select class="form-select" name="recipient_office_id" id="recipient-office"><option value="">Select receiving office</option><?php foreach ($offices as $office): $recipientName=trim(($office['officetitle'] ?: $office['officename'])); ?><option value="<?php echo htmlspecialchars($office['officeid']); ?>"><?php echo htmlspecialchars($recipientName); ?></option><?php endforeach; ?></select><div class="form-text">The receiving office will see this in Incoming documents.</div></div>
<script>(function(){const status=document.getElementById('current-status'),wrap=document.getElementById('recipient-office-wrap'),recipient=document.getElementById('recipient-office');const toggle=function(){const isOut=status.value==='Out';wrap.classList.toggle('d-none',!isOut);recipient.required=isOut;};status.addEventListener('change',toggle);toggle();}());
(function(){var AMOUNT_TYPES=['Contract of Services','Disbursement Voucher (DV)','Invoice','Obligation Request','Payroll','Purchase Order','Purchase Request','Salary'];var tIn=document.getElementById('doc-type-input'),aIn=document.getElementById('doc-amount-input'),aStar=document.getElementById('amount-req-star'),aText=document.getElementById('amount-form-text');function check(){var req=AMOUNT_TYPES.indexOf(tIn.value.trim())!==-1;aIn.required=req;aStar.classList.toggle('d-none',!req);aText.textContent=req?'Required for this document type.':'Leave blank if not applicable.';}tIn.addEventListener('input',check);tIn.addEventListener('change',check);check();}());</script>
<div class="col-12"><hr><button class="btn btn-primary px-4" type="submit"><i class="fas fa-save me-1"></i> Save Document</button></div></div></form></div></div>
<?php elseif ($isDetail && is_array($documentDetail)): $attachments = json_decode((string) $documentDetail['attachment_json'], true); if (!is_array($attachments)) $attachments = array(); $canDeleteDocument = !empty($_SESSION['d2s8wu_uid']) && hash_equals((string) $documentDetail['created_user_id'], (string) $_SESSION['d2s8wu_uid']) && $documentDetail['current_status'] === 'Closed'; ?><div class="d-flex justify-content-between align-items-center mb-3"><div><h5 class="mb-1 fw-bold text-light">Document Details</h5><span class="document-control text-light"><?php echo htmlspecialchars($documentDetail['document_control_number']); ?></span></div><div class="d-flex gap-2"><?php if ($canDeleteDocument): ?><form method="post" onsubmit="return confirm('Delete this document and all of its trace records?');"><input type="hidden" name="document_tracker_csrf" value="<?php echo htmlspecialchars($_SESSION['document_tracker_csrf']); ?>"><input type="hidden" name="document_autoid" value="<?php echo (int) $documentDetail['document_autoid']; ?>"><button class="btn btn-danger btn-sm" name="delete_document" value="1"><i class="fas fa-trash"></i> Delete</button></form><?php endif; ?><button class="btn btn-outline-light btn-sm" onclick="printDetailView()"><i class="fas fa-print me-1"></i> Print</button><button class="btn btn-outline-warning btn-sm" onclick="printDetailQR()"><i class="fas fa-qrcode me-1"></i> Print QR</button><a class="btn btn-outline-light btn-sm" href="javascript:history.back()"><i class="fas fa-arrow-left"></i> Back to documents</a></div></div><div class="row g-4"><div class="col-lg-8"><div class="card mb-4"><div class="card-header bg-white"><h6 class="mb-0"><?php echo htmlspecialchars($documentDetail['document_title']); ?></h6></div><div class="card-body"><div class="row g-3"><div class="col-md-6"><small class="text-muted d-block">Document Number</small><?php echo htmlspecialchars($documentDetail['document_number'] ?: '—'); ?></div><div class="col-md-6"><small class="text-muted d-block">Document Type</small><?php echo htmlspecialchars($documentDetail['document_type']); ?></div><div class="col-md-6"><small class="text-muted d-block">Current Status</small><span class="badge text-bg-primary"><?php echo htmlspecialchars($documentDetail['current_status']); ?></span></div><div class="col-md-6"><small class="text-muted d-block">Document Office ID</small><?php echo htmlspecialchars($documentDetail['officeid'] ?: '—'); ?></div><div class="col-md-6"><small class="text-muted d-block">Source Office</small><?php echo htmlspecialchars($documentDetail['source_office'] ?: '—'); ?></div><div class="col-md-6"><small class="text-muted d-block">Receiving Office</small><?php echo htmlspecialchars($documentDetail['recipient_office_name'] ?: '—'); ?><small class="d-block text-muted"><?php echo htmlspecialchars($documentDetail['recipient_office_id'] ?: ''); ?></small></div><div class="col-md-6"><small class="text-muted d-block">Source Personnel</small><?php echo htmlspecialchars($documentDetail['source_personnel'] ?: '—'); ?></div><div class="col-md-6"><small class="text-muted d-block">Source Employee ID</small><?php echo htmlspecialchars($documentDetail['source_employee_id'] ?: '—'); ?></div><div class="col-md-6"><small class="text-muted d-block">Amount</small><?php echo $documentDetail['amount'] !== null ? '₱ ' . number_format((float)$documentDetail['amount'], 2) : '—'; ?></div><div class="col-12"><small class="text-muted d-block">Description</small><div class="border rounded p-3 bg-light"><?php echo nl2br(htmlspecialchars($documentDetail['document_description'] ?: 'No description provided.')); ?></div></div></div></div></div><div class="card"><div class="card-header bg-white"><h6 class="mb-0"><i class="fas fa-paperclip me-2"></i>Attachments (<?php echo count($attachments); ?>)</h6></div><div class="list-group list-group-flush"><?php if (!$attachments): ?><div class="list-group-item text-muted">No attachments were added.</div><?php endif; ?><?php foreach ($attachments as $attachment): if (empty($attachment['path'])) continue; ?><a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?php echo htmlspecialchars($domainhome . '/' . ltrim($attachment['path'], '/')); ?>" target="_blank" rel="noopener"><span><i class="fas fa-file me-2 text-primary"></i><?php echo htmlspecialchars($attachment['name'] ?? basename($attachment['path'])); ?></span><i class="fas fa-external-link-alt small"></i></a><?php endforeach; ?></div></div></div><div class="col-lg-4"><div class="card mb-4"><div class="card-header bg-white"><h6 class="mb-0">Document Thumbnail</h6></div><div class="card-body text-center"><?php if (!empty($documentDetail['thumbnail_path'])): ?><img class="img-fluid rounded" src="<?php echo htmlspecialchars($domainhome . '/' . ltrim($documentDetail['thumbnail_path'], '/')); ?>" alt="Document thumbnail"><?php else: ?><div class="text-muted py-5"><i class="fas fa-camera fa-2x d-block mb-2"></i>No thumbnail captured</div><?php endif; ?></div></div><div class="card"><div class="card-header bg-white"><h6 class="mb-0">Created By</h6></div><div class="card-body small"><div><strong>Employee:</strong> <?php echo htmlspecialchars($documentDetail['created_employee_name'] ?: '—'); ?></div><div><strong>Employee ID:</strong> <?php echo htmlspecialchars($documentDetail['created_employee_id'] ?: '—'); ?></div><div><strong>Office:</strong> <?php echo htmlspecialchars($documentDetail['created_employee_office'] ?: '—'); ?></div><hr><div><strong>User:</strong> <?php echo htmlspecialchars($documentDetail['created_user_name'] ?: '—'); ?></div><div><strong>Created:</strong> <?php echo htmlspecialchars(date('M j, Y g:i A', strtotime($documentDetail['created_at']))); ?></div><div><strong>Modified:</strong> <?php echo htmlspecialchars(date('M j, Y g:i A', strtotime($documentDetail['modified_at']))); ?></div></div></div></div><div class="col-12"><div class="card"><div class="card-header bg-white"><h6 class="mb-0"><i class="fas fa-route me-2"></i>Receipt History</h6></div><div class="card-body p-0"><div class="table-responsive"><table class="table table-striped mb-0"><thead><tr><th>Action</th><th>Employee</th><th>Office</th><th>User</th><th>Remarks</th><th>Date and Time</th></tr></thead><tbody><?php foreach ($documentTraces as $trace): $ta=trim((string)($trace['trace_action']??'')); $taColors=array('Created'=>'secondary','Received'=>'success','Out'=>'warning','Completed'=>'primary','Closed'=>'dark','Archived'=>'info','Recalled'=>'danger'); $taColor=$taColors[$ta]??'secondary'; $taLabel=$ta?:$trace['trace_status']; $traceOfficeName=trim((string)($trace['office_name_lookup']??'')); if(!$traceOfficeName) $traceOfficeName=trim((string)($trace['office_title_lookup']??'')); if(!$traceOfficeName) $traceOfficeName=trim((string)($trace['employee_office']??'')); ?><tr><td><span class="badge text-bg-<?php echo $taColor;?>"><?php echo htmlspecialchars($taLabel);?></span></td><td><?php echo htmlspecialchars($trace['employee_name']?:'—');?><small class="d-block text-muted"><?php echo htmlspecialchars($trace['employee_id']??'');?></small></td><td><?php echo htmlspecialchars($traceOfficeName?:'—');?><?php if($trace['office_id']??''): ?><small class="d-block text-muted">ID: <?php echo htmlspecialchars($trace['office_id']);?></small><?php endif;?></td><td><?php echo htmlspecialchars($trace['user_name']?:'—');?></td><td><?php echo !empty($trace['remarks']) ? nl2br(htmlspecialchars($trace['remarks'])) : '<span class="text-muted">—</span>'; ?></td><td><?php echo htmlspecialchars(date('M j, Y g:i A', strtotime($trace['created_at'])));?></td></tr><?php endforeach; ?></tbody></table></div></div></div></div></div>
<script>
function printDetailView() {
  var doc = <?php echo json_encode(array(
    'control'     => $documentDetail['document_control_number'],
    'title'       => $documentDetail['document_title'],
    'type'        => $documentDetail['document_type'],
    'status'      => $documentDetail['current_status'],
    'doc_number'  => $documentDetail['document_number'] ?: '—',
    'source'      => $documentDetail['source_office'] ?: '—',
    'personnel'   => $documentDetail['source_personnel'] ?: '—',
    'employee_id' => $documentDetail['source_employee_id'] ?: '—',
    'recipient'   => $documentDetail['recipient_office_name'] ?: '—',
    'description' => $documentDetail['document_description'] ?: '—',
    'created'     => date('M j, Y g:i A', strtotime($documentDetail['created_at'])),
  )); ?>;
  var traces = <?php echo json_encode(array_map(function($t) {
    $ta = trim((string)($t['trace_action'] ?? ''));
    $office = trim((string)($t['office_name_lookup'] ?? '')) ?: trim((string)($t['office_title_lookup'] ?? '')) ?: trim((string)($t['employee_office'] ?? ''));
    return array(
      'action'   => $ta ?: $t['trace_status'],
      'office'   => $office ?: '—',
      'employee' => $t['employee_name'] ?: '—',
      'user'     => $t['user_name'] ?: '—',
      'date'     => date('M j, Y g:i A', strtotime($t['created_at'])),
    );
  }, $documentTraces)); ?>;
  function esc(s){var d=document.createElement('div');d.textContent=String(s);return d.innerHTML;}
  var traceRows = traces.map(function(t){return '<tr><td>'+esc(t.action)+'</td><td>'+esc(t.office)+'</td><td>'+esc(t.employee)+'</td><td>'+esc(t.user)+'</td><td>'+esc(t.date)+'</td></tr>';}).join('');
  var w = window.open('', '_blank');
  w.document.write('<!DOCTYPE html><html><head><meta charset="utf-8"><title>'+esc(doc.control)+'</title><style>@page{margin:14mm}*{margin:0;padding:0;box-sizing:border-box}body{font-family:Arial,sans-serif;font-size:9pt;color:#000}h2{font-size:13pt;margin-bottom:4px}h3{font-size:10pt;margin:12px 0 5px;border-bottom:1px solid #ccc;padding-bottom:3px}.ctrl{font-family:monospace;font-size:12pt;font-weight:700}.badge{display:inline-block;padding:2px 8px;border-radius:4px;font-size:8pt;background:#0d6efd;color:#fff}table{width:100%;border-collapse:collapse;margin-top:4px}th,td{border:1px solid #ccc;padding:4px 6px;text-align:left;font-size:8.5pt}th{background:#f0f0f0;font-weight:700;white-space:nowrap}tr:nth-child(even){background:#f9f9f9}.info-table td:first-child{color:#555;width:32%;white-space:nowrap}.meta{font-size:8pt;color:#555;margin-bottom:8px}</style></head><body><div class="ctrl">'+esc(doc.control)+'</div><h2>'+esc(doc.title)+'</h2><p class="meta">Printed: '+new Date().toLocaleString()+'</p><h3>Document Details</h3><table class="info-table"><tr><td>Document Number</td><td>'+esc(doc.doc_number)+'</td><td>Document Type</td><td>'+esc(doc.type)+'</td></tr><tr><td>Current Status</td><td><span class="badge">'+esc(doc.status)+'</span></td><td>Receiving Office</td><td>'+esc(doc.recipient)+'</td></tr><tr><td>Source Office</td><td>'+esc(doc.source)+'</td><td>Source Personnel</td><td>'+esc(doc.personnel)+'</td></tr><tr><td>Employee ID</td><td>'+esc(doc.employee_id)+'</td><td>Date Created</td><td>'+esc(doc.created)+'</td></tr><tr><td>Description</td><td colspan="3">'+esc(doc.description)+'</td></tr></table><h3>Receipt History ('+traces.length+' records)</h3><table><thead><tr><th>Action</th><th>Office</th><th>Employee</th><th>User</th><th>Date &amp; Time</th></tr></thead><tbody>'+traceRows+'</tbody></table><script>window.onload=function(){window.print();};<\/script></body></html>');
  w.document.close();
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
<div style="position:absolute;left:-9999px;top:-9999px;pointer-events:none;" aria-hidden="true">
  <div id="_qrDetailDiv"></div>
  <svg id="_bcDetailSvg"></svg>
</div>

<!-- QR Modal -->
<div class="modal fade" id="qrDetailModal" tabindex="-1" aria-labelledby="qrDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white" style="background:#198754;">
        <h5 class="modal-title" id="qrDetailModalLabel"><i class="fas fa-check-circle me-2"></i>Document QR Code</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div class="text-muted small mb-1">Document Control Number</div>
        <div class="fw-bold fs-5 font-monospace mb-3" id="_qrModalControl"></div>
        <div class="d-flex justify-content-center gap-4 mb-3">
          <div>
            <div class="text-muted small mb-1">QR Code</div>
            <img id="_qrModalImg" src="" alt="QR Code" style="width:140px;height:140px;">
          </div>
          <div>
            <div class="text-muted small mb-1">Barcode</div>
            <img id="_qrModalBc" src="" alt="Barcode" style="width:200px;height:60px;margin-top:40px;">
          </div>
        </div>
        <hr>
        <div class="text-start">
          <div class="row g-2 small">
            <div class="col-6"><span class="text-muted">Title:</span> <strong id="_qrModalTitle"></strong></div>
            <div class="col-6"><span class="text-muted">Status:</span> <span id="_qrModalStatus" class="badge text-bg-primary"></span></div>
            <div class="col-6"><span class="text-muted">Source Office:</span> <strong id="_qrModalOffice"></strong></div>
            <div class="col-6"><span class="text-muted">Source Personnel:</span> <strong id="_qrModalPersonnel"></strong></div>
            <div class="col-6"><span class="text-muted">Employee ID:</span> <strong id="_qrModalEmpId"></strong></div>
            <div class="col-12"><span class="text-muted">Description:</span> <strong id="_qrModalDesc"></strong></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Close</button>
        <button type="button" class="btn btn-primary" onclick="_doPrintQR()"><i class="fas fa-print me-1"></i>Print</button>
      </div>
    </div>
  </div>
</div>

<script>
var _qrDetailData = <?php echo json_encode(array(
  'control'            => $documentDetail['document_control_number'],
  'title'              => $documentDetail['document_title'],
  'description'        => $documentDetail['document_description'] ?: '',
  'source_office'      => $documentDetail['source_office'] ?: '',
  'source_personnel'   => $documentDetail['source_personnel'] ?: '',
  'source_employee_id' => $documentDetail['source_employee_id'] ?: '',
  'status'             => $documentDetail['current_status'],
)); ?>;
var _qrDetailSrc = '', _bcDetailSrc = '';

document.addEventListener('DOMContentLoaded', function () {
  new QRCode(document.getElementById('_qrDetailDiv'), {
    text: _qrDetailData.control, width: 140, height: 140, correctLevel: QRCode.CorrectLevel.M
  });
  JsBarcode('#_bcDetailSvg', _qrDetailData.control, {
    format: 'CODE128', lineColor: '#000', background: '#fff', width: 2, height: 60, displayValue: false, margin: 0
  });
  setTimeout(function () {
    var c = document.querySelector('#_qrDetailDiv canvas');
    _qrDetailSrc = c ? c.toDataURL() : '';
    _bcDetailSrc = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(
      new XMLSerializer().serializeToString(document.getElementById('_bcDetailSvg'))
    );
  }, 400);
});

function printDetailQR() {
  if (!_qrDetailSrc) { alert('QR code is still loading, please try again.'); return; }
  var d = _qrDetailData;
  document.getElementById('_qrModalControl').textContent   = d.control;
  document.getElementById('_qrModalImg').src               = _qrDetailSrc;
  document.getElementById('_qrModalBc').src                = _bcDetailSrc;
  document.getElementById('_qrModalTitle').textContent     = d.title || '—';
  document.getElementById('_qrModalStatus').textContent    = d.status || '—';
  document.getElementById('_qrModalOffice').textContent    = d.source_office || '—';
  document.getElementById('_qrModalPersonnel').textContent = d.source_personnel || '—';
  document.getElementById('_qrModalEmpId').textContent     = d.source_employee_id || '—';
  document.getElementById('_qrModalDesc').textContent      = d.description || '—';
  var modal = new bootstrap.Modal(document.getElementById('qrDetailModal'));
  modal.show();
}

function _doPrintQR() {
  if (!_qrDetailSrc) return;
  function _e(s) { var d = document.createElement('div'); d.textContent = String(s || '—'); return d.innerHTML; }
  var d = _qrDetailData;
  var w = window.open('', '_blank');
  w.document.write('<!DOCTYPE html><html><head><meta charset="utf-8"><title>QR - ' + _e(d.control) + '</title><style>@page{size:A6 portrait;margin:0}*{margin:0;padding:0;box-sizing:border-box}body{font-family:Arial,sans-serif;width:105mm;padding:6mm;color:#000;background:#fff;font-size:8pt}.control-no{font-family:monospace;font-size:11pt;font-weight:700;text-align:left;margin-bottom:4mm;letter-spacing:1px}.codes{display:flex;justify-content:flex-start;align-items:flex-end;gap:6mm;margin-bottom:3mm}.codes img{display:block}.qr-img{width:22mm;height:22mm}.bc-img{width:52mm;height:14mm}.codes-wrap{text-align:left}.codes-label{font-size:6pt;color:#555;margin-bottom:1mm}hr{border:none;border-top:0.5px solid #ccc;margin:2mm 0}table{width:100%;border-collapse:collapse}td{padding:1mm 2mm;vertical-align:top;line-height:1.3}td:first-child{color:#555;width:36%;font-size:7pt;white-space:nowrap}td:last-child{font-weight:600;font-size:7.5pt;word-break:break-word}</style></head><body><div class="control-no">' + _e(d.control) + '</div><div class="codes"><div class="codes-wrap"><div class="codes-label">QR Code</div><img class="qr-img" src="' + _qrDetailSrc + '"></div><div class="codes-wrap"><div class="codes-label">Barcode</div><img class="bc-img" src="' + _bcDetailSrc + '"></div></div><hr><table><tr><td>Title</td><td>' + _e(d.title) + '</td></tr><tr><td>Status</td><td>' + _e(d.status) + '</td></tr><tr><td>Source Office</td><td>' + _e(d.source_office) + '</td></tr><tr><td>Personnel</td><td>' + _e(d.source_personnel) + '</td></tr><tr><td>Employee ID</td><td>' + _e(d.source_employee_id) + '</td></tr><tr><td>Description</td><td>' + _e(d.description) + '</td></tr></table><script>window.onload=function(){window.print();};<\/script></body></html>');
  w.document.close();
}
</script>
<?php else: $heading = $viewStatus[$routeName] ?? 'In'; $isIncomingView = ($routeName === 'document-tracker-incoming'); $isOutgoingView = ($routeName === 'document-tracker-outgoing'); ?>
<div class="card">
  <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h5 class="mb-0" id="dt-list-heading"><?php echo htmlspecialchars($heading); ?> Documents</h5>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" onclick="printDocumentTable()"><i class="fas fa-print me-1"></i>Print</button>
      <a class="btn btn-primary btn-sm" href="<?php echo htmlspecialchars($domainhome); ?>/document-tracker-new"><i class="fas fa-plus"></i> New Document</a>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="listRecView" class="table table-dark table-striped table-hover align-middle">
        <thead class="remSortH">
          <tr>
            <th class="remove-dropdown"></th>
            <th class="remove-dropdown"></th>
            <th></th>
            <th class="remove-dropdown"></th>
            <th></th>
            <th></th>
            <th class="remove-dropdown"></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="remove-dropdown"></th>
            <?php if($isIncomingView||$isOutgoingView):?><th class="remove-dropdown"></th><?php endif;?>
          </tr>
        </thead>
        <thead class="theadtitle">
          <tr>
            <th>Control No.</th><th>Document No.</th><th>Type</th><th>Title</th>
            <th>Source Office</th><th>Personnel</th><th>Status</th><th>Date</th><th>Time</th>
            <th>Recipient Office</th><th>Amount</th>
            <?php if($isIncomingView||$isOutgoingView):?><th>Action</th><?php endif;?>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($documents as $document):
          $isRcvd    = ($document['trace_status'] ?? 'Pending') === 'Received';
          $statusTxt = $isRcvd ? 'Received' : $document['current_status'];
          $scBadge   = $isRcvd ? 'text-bg-success' : 'text-bg-primary';
          $dateISO   = date('Y-m-d', strtotime($document['created_at']));
          $dateDisp  = date('M j, Y', strtotime($document['created_at']));
          $timeISO   = date('H:i', strtotime($document['created_at']));
          $timeDisp  = date('g:i A', strtotime($document['created_at']));
        ?>
        <tr>
          <td class="document-control"><a class="link-info" href="<?php echo htmlspecialchars($domainhome); ?>/document-tracker-detail?id=<?php echo (int)$document['document_autoid']; ?>"><?php echo htmlspecialchars($document['document_control_number']); ?></a></td>
          <td><?php echo htmlspecialchars($document['document_number']); ?></td>
          <td><?php echo htmlspecialchars($document['document_type']); ?></td>
          <td><?php echo htmlspecialchars($document['document_title']); ?></td>
          <td><?php echo htmlspecialchars($document['source_office']); ?></td>
          <td><?php echo htmlspecialchars($document['source_personnel']); ?></td>
          <td data-search="<?php echo htmlspecialchars($statusTxt); ?>"><span class="badge <?php echo $scBadge; ?>"><?php echo htmlspecialchars($statusTxt); ?></span></td>
          <td data-order="<?php echo $dateISO; ?>" data-search="<?php echo htmlspecialchars($dateDisp); ?>"><?php echo htmlspecialchars($dateDisp); ?></td>
          <td data-order="<?php echo $timeISO; ?>" data-search="<?php echo htmlspecialchars($timeDisp); ?>"><?php echo htmlspecialchars($timeDisp); ?></td>
          <td><?php echo htmlspecialchars($document['recipient_office_name'] ?: '—'); ?></td>
          <td><?php echo $document['amount'] !== null ? '₱ ' . number_format((float)$document['amount'], 2) : '—'; ?></td>
          <?php if($isIncomingView):
            $isRecipient=($document['recipient_office_id']===$currentOfficeId);
            $isOwner=($document['officeid']===$currentOfficeId);
            $docStatus=$document['current_status'];
            $isReceived=($document['trace_status']??'Pending')==='Received';
            $docId=(int)$document['document_autoid'];
            $csrf_=htmlspecialchars($_SESSION['document_tracker_csrf']??'');
          ?><td class="text-nowrap"><?php if($isRecipient&&$docStatus==='Out'&&!$isReceived): ?><button class="btn btn-sm btn-success" onclick="openReceiveModal(<?php echo $docId;?>,'<?php echo $csrf_;?>')"><i class="fas fa-check me-1"></i>Receive</button><?php elseif($isReceived||($isOwner&&$docStatus==='In')): ?><button class="btn btn-sm btn-warning me-1" onclick="openOutModal(<?php echo $docId;?>,'<?php echo $csrf_;?>')"><i class="fas fa-share me-1"></i>Out</button><button class="btn btn-sm btn-primary me-1" onclick="openStatusModal(<?php echo $docId;?>,'<?php echo $csrf_;?>','Completed')">Completed</button><button class="btn btn-sm btn-secondary me-1" onclick="openStatusModal(<?php echo $docId;?>,'<?php echo $csrf_;?>','Closed')">Closed</button><button class="btn btn-sm btn-dark" onclick="openStatusModal(<?php echo $docId;?>,'<?php echo $csrf_;?>','Archived')">Archived</button><?php else: ?><span class="text-muted small">—</span><?php endif; ?></td><?php endif; ?>
          <?php if($isOutgoingView):
            $outDocId=(int)$document['document_autoid'];
            $outCsrf_=htmlspecialchars($_SESSION['document_tracker_csrf']??'');
            $isOwnerOut=($document['officeid']===$currentOfficeId);
            $notReceived=(($document['trace_status']??'Pending')==='Pending');
          ?><td class="text-nowrap"><?php if($isOwnerOut&&$notReceived): ?><form method="post" class="d-inline" onsubmit="return confirm('Recall this document back to Incoming?');"><input type="hidden" name="document_tracker_csrf" value="<?php echo $outCsrf_;?>"><input type="hidden" name="document_autoid" value="<?php echo $outDocId;?>"><button class="btn btn-sm btn-danger" name="recall_document" value="1"><i class="fas fa-undo me-1"></i>Recall</button></form><?php else: ?><span class="text-muted small">—</span><?php endif; ?></td><?php endif; ?>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
function printDocumentTable() {
  var dt = $('#listRecView').DataTable();
  var isIncoming = <?php echo $isIncomingView ? 'true' : 'false'; ?>;
  var isOutgoing = <?php echo $isOutgoingView ? 'true' : 'false'; ?>;
  var heading = <?php echo json_encode($heading . ' Documents'); ?>;
  var headers = ['Control No.','Document No.','Type','Title','Source Office','Personnel','Status','Recipient Office','Amount','Signature','Date','Time'];
  var rows = dt.rows({search:'applied'}).nodes();
  var rowsHtml = '';
  $(rows).each(function(){
    rowsHtml += '<tr>';
    $(this).find('td').each(function(i){
      if((isIncoming || isOutgoing) && i === 11) return;
      if(i === 7 || i === 8) return;
      var cell = $(this);
      var text = cell.find('a').length ? cell.find('a').text().trim() : (cell.find('.badge').length ? cell.find('.badge').text().trim() : cell.text().trim());
      rowsHtml += '<td>' + $('<div>').text(text).html() + '</td>';
    });
    rowsHtml += '<td></td><td></td><td></td></tr>';
  });
  var w = window.open('', '_blank');
  w.document.write('<!DOCTYPE html><html><head><meta charset="utf-8"><title>' + heading + '</title><style>@page{size:legal landscape;margin:12mm}*{margin:0;padding:0;box-sizing:border-box}body{font-family:Arial,sans-serif;font-size:9pt;color:#000}.doc-transmittal{text-align:center;font-size:16pt;font-weight:700;letter-spacing:1px;margin-bottom:4px}h2{font-size:12pt;text-align:center;font-weight:normal;margin-bottom:8px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:4px 6px;text-align:left;font-size:8.5pt}th{background:#f0f0f0;font-weight:700}tr:nth-child(even){background:#f9f9f9}.meta{font-size:8pt;color:#555;margin-bottom:10px}</style></head><body><div class="doc-transmittal">Document Transmittal</div><h2>' + heading + '</h2><p class="meta">Printed: ' + new Date().toLocaleString() + ' &nbsp;|&nbsp; ' + rows.length + ' record(s)</p><table><thead><tr>' + headers.map(function(h){return '<th>'+h+'</th>';}).join('') + '</tr></thead><tbody>' + rowsHtml + '</tbody></table><script>window.onload=function(){window.print();};<\/script></body></html>');
  w.document.close();
}
</script>
<?php endif; ?></div>
<?php if($routeName==='document-tracker-incoming'): ?>
<div class="modal fade text-dark" id="outModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2 bg-warning">
                <h6 class="modal-title fw-bold mb-0"><i class="fas fa-share me-2"></i>Send Document Out</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="send_out" value="1">
                    <input type="hidden" name="document_tracker_csrf" id="outCsrf">
                    <input type="hidden" name="document_autoid" id="outDocId">
                    <label class="form-label fw-semibold">Select Receiving Office</label>
                    <select class="form-select mb-3" name="recipient_office_id" required>
                        <option value="">— Select Office —</option>
                        <?php foreach($offices as $off): ?>
                        <option value="<?php echo htmlspecialchars($off['officeid']);?>">
                            <?php echo htmlspecialchars($off['officetitle']?:$off['officename']);?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text mb-1">The selected office will see this document in their Incoming.</div>
                    <label class="form-label fw-semibold mt-2">Remarks <small class="text-muted fw-normal">(optional)</small></label>
                    <textarea class="form-control" name="out_remarks" rows="3" placeholder="Enter reason or remarks for sending out..."></textarea>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-share me-1"></i>Send Out</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-dark" id="receiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2 bg-success text-white">
                <h6 class="modal-title fw-bold mb-0"><i class="fas fa-check me-2"></i>Mark as Received</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="mark_received" value="1">
                    <input type="hidden" name="document_tracker_csrf" id="receiveCsrf">
                    <input type="hidden" name="document_autoid" id="receiveDocId">
                    <p class="mb-3">Confirm receipt of this document?</p>
                    <label class="form-label fw-semibold">Remarks <small class="text-muted fw-normal">(optional)</small></label>
                    <textarea class="form-control" name="receive_remarks" id="receiveRemarks" rows="3" placeholder="Enter remarks or notes on receipt..."></textarea>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check me-1"></i>Confirm Received</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-dark" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2" id="statusModalHeader">
                <h6 class="modal-title fw-bold mb-0" id="statusModalTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="update_status" value="1">
                    <input type="hidden" name="document_tracker_csrf" id="statusCsrf">
                    <input type="hidden" name="document_autoid" id="statusDocId">
                    <input type="hidden" name="new_status" id="statusValue">
                    <p class="mb-3">Mark this document as <strong id="statusLabel"></strong>?</p>
                    <label class="form-label fw-semibold">Remarks <small class="text-muted fw-normal">(optional)</small></label>
                    <textarea class="form-control" name="status_remarks" id="statusRemarks" rows="3" placeholder="Enter reason or remarks..."></textarea>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm" id="statusSubmitBtn">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReceiveModal(docId, csrf) {
    document.getElementById('receiveDocId').value = docId;
    document.getElementById('receiveCsrf').value  = csrf;
    document.getElementById('receiveRemarks').value = '';
    new bootstrap.Modal(document.getElementById('receiveModal')).show();
}
function openOutModal(docId, csrf) {
    document.getElementById('outDocId').value = docId;
    document.getElementById('outCsrf').value  = csrf;
    new bootstrap.Modal(document.getElementById('outModal')).show();
}
var _statusColors = {Completed:'btn-primary',Closed:'btn-secondary',Archived:'btn-dark'};
var _statusHeaderColors = {Completed:'bg-primary text-white',Closed:'bg-secondary text-white',Archived:'bg-dark text-white'};
function openStatusModal(docId, csrf, status) {
    document.getElementById('statusDocId').value = docId;
    document.getElementById('statusCsrf').value  = csrf;
    document.getElementById('statusValue').value = status;
    document.getElementById('statusLabel').textContent = status;
    document.getElementById('statusRemarks').value = '';
    document.getElementById('statusModalTitle').textContent = 'Mark as ' + status;
    document.getElementById('statusSubmitBtn').className = 'btn btn-sm ' + (_statusColors[status]||'btn-secondary');
    var hdr = document.getElementById('statusModalHeader');
    hdr.className = 'modal-header py-2 ' + (_statusHeaderColors[status]||'');
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}
</script>
<?php endif; ?>
<?php if ($isNew): ?><script>document.addEventListener('DOMContentLoaded',function(){const video=document.getElementById('document-camera'),canvas=document.getElementById('document-canvas'),capture=document.getElementById('thumbnail-capture'),preview=document.getElementById('thumbnail-preview'),cameraSelect=document.getElementById('thumb-camera-select'),selectWrap=document.getElementById('thumb-select-wrap'),startBtn=document.getElementById('start-camera'),captureBtn=document.getElementById('capture-thumbnail'),stopBtn=document.getElementById('stop-camera'),retakeBtn=document.getElementById('retake-photo'),cameraModeBtn=document.getElementById('thumb-mode-camera'),uploadModeBtn=document.getElementById('thumb-mode-upload'),cameraSection=document.getElementById('thumb-camera-section'),uploadSection=document.getElementById('thumb-upload-section'),uploadInput=document.getElementById('thumb-upload-input');let stream=null;async function populateCameras(){try{const devs=await navigator.mediaDevices.enumerateDevices();const vids=devs.filter(d=>d.kind==='videoinput');cameraSelect.innerHTML='<option value="">Select Camera</option>';vids.forEach((d,i)=>{const o=document.createElement('option');o.value=d.deviceId;o.text=d.label||'Camera '+(i+1);cameraSelect.appendChild(o);});if(vids.length>0)cameraSelect.selectedIndex=1;selectWrap.classList.remove('d-none');}catch(e){}}async function startStream(){const did=cameraSelect.value;stream=await navigator.mediaDevices.getUserMedia({video:did?{deviceId:{exact:did}}:{facingMode:'environment'},audio:false});video.srcObject=stream;startBtn.classList.add('d-none');captureBtn.classList.remove('d-none');stopBtn.classList.remove('d-none');retakeBtn.classList.add('d-none');preview.classList.add('d-none');capture.value='';}cameraModeBtn.onclick=function(){cameraModeBtn.classList.add('active');uploadModeBtn.classList.remove('active');cameraSection.classList.remove('d-none');uploadSection.classList.add('d-none');};uploadModeBtn.onclick=function(){uploadModeBtn.classList.add('active');cameraModeBtn.classList.remove('active');uploadSection.classList.remove('d-none');cameraSection.classList.add('d-none');if(stream){stream.getTracks().forEach(t=>t.stop());stream=null;video.srcObject=null;}};startBtn.onclick=async function(){try{await startStream();await populateCameras();}catch(e){alert('Camera access is unavailable. Please allow camera access and try again.');}};cameraSelect.onchange=async function(){if(!stream)return;stream.getTracks().forEach(t=>t.stop());stream=null;try{await startStream();}catch(e){}};captureBtn.onclick=function(){if(!video.videoWidth){alert('Open the camera before capturing.');return;}canvas.width=video.videoWidth;canvas.height=video.videoHeight;canvas.getContext('2d').drawImage(video,0,0);capture.value=canvas.toDataURL('image/jpeg',.82);preview.src=capture.value;preview.classList.remove('d-none');captureBtn.classList.add('d-none');stopBtn.classList.add('d-none');retakeBtn.classList.remove('d-none');if(stream){stream.getTracks().forEach(t=>t.stop());stream=null;}video.srcObject=null;};stopBtn.onclick=function(){if(stream){stream.getTracks().forEach(t=>t.stop());stream=null;}video.srcObject=null;startBtn.classList.remove('d-none');captureBtn.classList.add('d-none');stopBtn.classList.add('d-none');};retakeBtn.onclick=function(){preview.classList.add('d-none');preview.src='';capture.value='';retakeBtn.classList.add('d-none');startBtn.classList.remove('d-none');};uploadInput.onchange=function(){if(!this.files||!this.files[0])return;const reader=new FileReader();reader.onload=function(e){const img=new Image();img.onload=function(){const maxW=800,scale=img.width>maxW?maxW/img.width:1;canvas.width=Math.round(img.width*scale);canvas.height=Math.round(img.height*scale);canvas.getContext('2d').drawImage(img,0,0,canvas.width,canvas.height);capture.value=canvas.toDataURL('image/jpeg',.82);preview.src=capture.value;preview.classList.remove('d-none');};img.src=e.target.result;};reader.readAsDataURL(this.files[0]);};const sourceType=document.getElementById('source-office-type'),sourceOffice=document.getElementById('source-office'),externalOffice=document.getElementById('external-source-office');sourceType.onchange=()=>{const external=sourceType.value==='External';sourceOffice.classList.toggle('d-none',external);externalOffice.classList.toggle('d-none',!external);};document.getElementById('source-employee-id').addEventListener('change',async function(){const id=this.value.trim(),note=document.getElementById('employee-lookup-message');if(!/^\d{8}$/.test(id))return;note.textContent='Looking up employee...';try{const r=await fetch('<?php echo htmlspecialchars(rtrim($domainhome,'/')); ?>/api/document-tracker-employee/?employee_id='+encodeURIComponent(id)),d=await r.json();if(!r.ok)throw new Error(d.message);document.getElementById('source-personnel').value=d.employee.emp_name_forid||'';sourceType.value='Internal';sourceType.onchange();sourceOffice.value=d.employee.officename_forid||'';if(sourceOffice.value===''){const o=[...sourceOffice.options].find(x=>x.text===d.employee.officename_forid);if(o)sourceOffice.value=o.value;}note.textContent='Employee and source office filled.';}catch(e){note.textContent=e.message;}});window.addEventListener('beforeunload',()=>{if(stream)stream.getTracks().forEach(t=>t.stop());});});</script><?php endif; ?>

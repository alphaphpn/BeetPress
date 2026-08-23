<?php
$searchQuery  = trim((string) ($_GET['q'] ?? ''));
$document     = null;
$traces       = array();
$searchError  = null;

if ($searchQuery !== '') {
    try {
        $cnn = new PDO(
            "mysql:host={$host};dbname={$db};charset=utf8mb4", $uname, $pw,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC)
        );
        $stmt = $cnn->prepare('SELECT * FROM document_tracker_tbl WHERE document_control_number = :q LIMIT 1');
        $stmt->execute(array(':q' => $searchQuery));
        $document = $stmt->fetch();
        if ($document) {
            $tStmt = $cnn->prepare(
                'SELECT t.*, o.officename AS office_name_lookup, o.officetitle AS office_title_lookup
                 FROM document_trace_tbl t
                 LEFT JOIN office_tbl o ON o.officeid = t.office_id
                 WHERE t.document_autoid = :id
                 ORDER BY t.created_at DESC, t.trace_autoid DESC'
            );
            $tStmt->execute(array(':id' => $document['document_autoid']));
            $traces = $tStmt->fetchAll();
        } else {
            $searchError = 'No document found for control number: ' . htmlspecialchars($searchQuery);
        }
    } catch (Throwable $e) {
        $searchError = 'Search failed: ' . $e->getMessage();
    }
}

$actionColors = array(
    'Created'   => 'secondary',
    'Received'  => 'success',
    'Out'       => 'warning',
    'Completed' => 'primary',
    'Closed'    => 'dark',
    'Archived'  => 'info',
);
$statusColors = array(
    'In'        => 'success',
    'Out'       => 'warning',
    'Completed' => 'primary',
    'Closed'    => 'dark',
    'Archived'  => 'info',
);
?>
<style>
#scanner-wrap { position:relative; width:100%; max-width:420px; }
#scanner-wrap video { width:100%; border-radius:.5rem; background:#000; display:block; }
#scan-overlay { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; pointer-events:none; }
#scan-overlay::before { content:''; display:block; width:60%; aspect-ratio:1; border:3px solid rgba(99,179,237,.85); border-radius:8px; box-shadow:0 0 0 9999px rgba(0,0,0,.45); }
.tracer-label { font-size:.7rem; text-transform:uppercase; letter-spacing:.06em; color:#6c757d; margin-bottom:2px; }
.tracer-value { font-weight:600; word-break:break-word; }
</style>

<div class="pt-5">
<div class="card shadow-sm mb-4">
  <div class="card-header bg-white d-flex align-items-center gap-2">
    <i class="fas fa-search-location text-primary"></i>
    <h5 class="mb-0">Document Tracer</h5>
  </div>
  <div class="card-body">

    <!-- Search Form -->
    <form method="get" id="tracer-form" autocomplete="off">
      <div class="input-group input-group-lg mb-2">
        <span class="input-group-text bg-white"><i class="fas fa-barcode text-secondary"></i></span>
        <input type="text" class="form-control font-monospace"
               id="tracer-input" name="q"
               placeholder="Scan or type Document Control Number"
               value="<?php echo htmlspecialchars($searchQuery); ?>"
               autofocus>
        <button class="btn btn-primary px-4" type="submit"><i class="fas fa-search me-1"></i>Search</button>
        <button class="btn btn-outline-secondary" type="button" id="scan-btn" title="Open Camera Scanner">
          <i class="fas fa-camera"></i>
        </button>
      </div>
      <div class="form-text text-center">Enter the Document Control Number (e.g. <span class="font-monospace">2026-08-22-000001</span>) or scan the QR / Barcode.</div>
    </form>

    <!-- Camera Scanner -->
    <div id="scanner-container" class="d-none mt-3 d-flex flex-column align-items-center gap-2">
      <div id="scanner-wrap">
        <video id="scanner-video" autoplay playsinline muted></video>
        <div id="scan-overlay"></div>
      </div>
      <button class="btn btn-sm btn-outline-danger" id="stop-scan-btn"><i class="fas fa-times me-1"></i>Stop Scanner</button>
    </div>

  </div>
</div>

<?php if ($searchError): ?>
<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i><?php echo $searchError; ?></div>
<?php endif; ?>

<?php if ($document): ?>
<!-- Document Details Card -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <span class="font-monospace fw-bold fs-6"><?php echo htmlspecialchars($document['document_control_number']); ?></span>
      <small class="text-muted ms-2"><?php echo htmlspecialchars($document['document_type']); ?></small>
    </div>
    <div class="d-flex align-items-center gap-2">
      <?php $sc = $statusColors[$document['current_status']] ?? 'secondary'; ?>
      <span class="badge text-bg-<?php echo $sc; ?> fs-6 px-3 py-2"><?php echo htmlspecialchars($document['current_status']); ?></span>
      <button class="btn btn-outline-secondary btn-sm" onclick="printTracerDetail()"><i class="fas fa-print me-1"></i>Print</button>
    </div>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6 col-lg-4">
        <div class="tracer-label">Document Title</div>
        <div class="tracer-value"><?php echo htmlspecialchars($document['document_title'] ?: '—'); ?></div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="tracer-label">Document Number</div>
        <div class="tracer-value"><?php echo htmlspecialchars($document['document_number'] ?: '—'); ?></div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="tracer-label">Source Office</div>
        <div class="tracer-value"><?php echo htmlspecialchars($document['source_office'] ?: '—'); ?></div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="tracer-label">Source Personnel</div>
        <div class="tracer-value"><?php echo htmlspecialchars($document['source_personnel'] ?: '—'); ?></div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="tracer-label">Employee ID</div>
        <div class="tracer-value"><?php echo htmlspecialchars($document['source_employee_id'] ?: '—'); ?></div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="tracer-label">Current Recipient Office</div>
        <div class="tracer-value"><?php echo htmlspecialchars($document['recipient_office_name'] ?: '—'); ?></div>
      </div>
      <?php if (!empty($document['document_description'])): ?>
      <div class="col-12">
        <div class="tracer-label">Description</div>
        <div class="tracer-value"><?php echo nl2br(htmlspecialchars($document['document_description'])); ?></div>
      </div>
      <?php endif; ?>
      <div class="col-md-6 col-lg-4">
        <div class="tracer-label">Date Created</div>
        <div class="tracer-value"><?php echo date('M j, Y g:i A', strtotime($document['created_at'])); ?></div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="tracer-label">Last Updated</div>
        <div class="tracer-value"><?php echo date('M j, Y g:i A', strtotime($document['modified_at'])); ?></div>
      </div>
    </div>
  </div>
</div>

<!-- Recipient / Trace History Card -->
<div class="card shadow-sm">
  <div class="card-header bg-white d-flex align-items-center gap-2">
    <i class="fas fa-route text-primary"></i>
    <h6 class="mb-0">Recipient History</h6>
    <span class="badge text-bg-secondary ms-auto"><?php echo count($traces); ?> record<?php echo count($traces) !== 1 ? 's' : ''; ?></span>
  </div>
  <?php if (empty($traces)): ?>
  <div class="card-body text-muted">No trace history available.</div>
  <?php else: ?>
  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Action</th>
          <th>Office</th>
          <th>Employee</th>
          <th>User</th>
          <th>Remarks</th>
          <th>Date &amp; Time</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($traces as $i => $trace):
          $ta        = trim((string) ($trace['trace_action'] ?? ''));
          $taColor   = $actionColors[$ta] ?? 'secondary';
          $taLabel   = $ta ?: $trace['trace_status'];
          $officeName = trim((string) ($trace['office_name_lookup'] ?? ''));
          if (!$officeName) $officeName = trim((string) ($trace['office_title_lookup'] ?? ''));
          if (!$officeName) $officeName = trim((string) ($trace['employee_office'] ?? ''));
        ?>
        <tr>
          <td class="text-muted small"><?php echo $i + 1; ?></td>
          <td><span class="badge text-bg-<?php echo $taColor; ?>"><?php echo htmlspecialchars($taLabel); ?></span></td>
          <td>
            <?php echo htmlspecialchars($officeName ?: '—'); ?>
            <?php if (!empty($trace['office_id'])): ?>
            <small class="d-block text-muted">ID: <?php echo htmlspecialchars($trace['office_id']); ?></small>
            <?php endif; ?>
          </td>
          <td>
            <?php echo htmlspecialchars($trace['employee_name'] ?: '—'); ?>
            <?php if (!empty($trace['employee_id'])): ?>
            <small class="d-block text-muted"><?php echo htmlspecialchars($trace['employee_id']); ?></small>
            <?php endif; ?>
          </td>
          <td><?php echo htmlspecialchars($trace['user_name'] ?: '—'); ?></td>
          <td><?php echo !empty($trace['remarks']) ? nl2br(htmlspecialchars($trace['remarks'])) : '<span class="text-muted">—</span>'; ?></td>
          <td class="text-nowrap"><?php echo date('M j, Y g:i A', strtotime($trace['created_at'])); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>
</div>

<script>
(function () {
    var input       = document.getElementById('tracer-input');
    var form        = document.getElementById('tracer-form');
    var scanBtn     = document.getElementById('scan-btn');
    var stopBtn     = document.getElementById('stop-scan-btn');
    var container   = document.getElementById('scanner-container');
    var video       = document.getElementById('scanner-video');
    var stream      = null;
    var scanning    = false;
    var lastResult  = '';

    /* ── BarcodeDetector (built-in Chromium API) ── */
    var detector = null;
    if ('BarcodeDetector' in window) {
        try { detector = new BarcodeDetector({ formats: ['qr_code','code_128','code_39','ean_13','ean_8','data_matrix','aztec','pdf417'] }); } catch(e) {}
    }

    function startScan() {
        container.classList.remove('d-none');
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false })
            .then(function (s) {
                stream   = s;
                scanning = true;
                video.srcObject = s;
                video.play();
                requestAnimationFrame(tick);
            })
            .catch(function () { alert('Camera access was denied or is unavailable.'); container.classList.add('d-none'); });
    }

    function stopScan() {
        scanning = false;
        if (stream) { stream.getTracks().forEach(function (t) { t.stop(); }); stream = null; }
        video.srcObject = null;
        container.classList.add('d-none');
    }

    function tick() {
        if (!scanning) return;
        if (!detector || video.readyState < 2) { requestAnimationFrame(tick); return; }
        detector.detect(video).then(function (results) {
            if (results.length && results[0].rawValue !== lastResult) {
                lastResult = results[0].rawValue;
                input.value = lastResult;
                stopScan();
                form.submit();
            }
        }).catch(function () {}).finally(function () { if (scanning) requestAnimationFrame(tick); });
    }

    scanBtn.addEventListener('click', startScan);
    stopBtn.addEventListener('click', stopScan);

    /* Auto-submit when a barcode reader types fast (ends with Enter) */
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); form.submit(); }
    });
}());

<?php if ($document): ?>
function printTracerDetail() {
  var doc = <?php echo json_encode(array(
    'control'    => $document['document_control_number'],
    'number'     => $document['document_number'] ?: '—',
    'type'       => $document['document_type'] ?: '—',
    'title'      => $document['document_title'] ?: '—',
    'status'     => $document['current_status'],
    'office'     => $document['source_office'] ?: '—',
    'personnel'  => $document['source_personnel'] ?: '—',
    'emp_id'     => $document['source_employee_id'] ?: '—',
    'recipient'  => $document['recipient_office_name'] ?: '—',
    'desc'       => $document['document_description'] ?: '—',
    'created'    => date('M j, Y g:i A', strtotime($document['created_at'])),
    'modified'   => date('M j, Y g:i A', strtotime($document['modified_at'])),
  )); ?>;
  var traces = <?php echo json_encode(array_map(function($trace) use ($actionColors) {
    $ta = trim((string)($trace['trace_action'] ?? ''));
    $officeName = trim((string)($trace['office_name_lookup'] ?? ''));
    if (!$officeName) $officeName = trim((string)($trace['office_title_lookup'] ?? ''));
    if (!$officeName) $officeName = trim((string)($trace['employee_office'] ?? ''));
    return array(
      'action'   => $ta ?: $trace['trace_status'],
      'office'   => $officeName ?: '—',
      'emp'      => $trace['employee_name'] ?: '—',
      'emp_id'   => $trace['employee_id'] ?? '',
      'user'     => $trace['user_name'] ?: '—',
      'remarks'  => $trace['remarks'] ?? '',
      'datetime' => date('M j, Y g:i A', strtotime($trace['created_at'])),
    );
  }, $traces)); ?>;
  function e(s){ var d=document.createElement('div');d.textContent=String(s);return d.innerHTML; }
  var traceRows = traces.map(function(t,i){
    return '<tr><td>'+(i+1)+'</td><td>'+e(t.action)+'</td><td>'+e(t.office)+'</td><td>'+e(t.emp)+(t.emp_id?'<br><small>'+e(t.emp_id)+'</small>':'')+'</td><td>'+e(t.user)+'</td><td>'+(t.remarks?e(t.remarks):'—')+'</td><td>'+e(t.datetime)+'</td></tr>';
  }).join('');
  var w = window.open('', '_blank');
  w.document.write('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Document Tracer - '+e(doc.control)+'</title><style>@page{size:legal portrait;margin:15mm}*{margin:0;padding:0;box-sizing:border-box}body{font-family:Arial,sans-serif;font-size:9pt;color:#000}.title{text-align:center;font-size:15pt;font-weight:700;letter-spacing:1px;margin-bottom:3px}.subtitle{text-align:center;font-size:9pt;color:#555;margin-bottom:10px}hr{border:none;border-top:1px solid #ccc;margin:8px 0}.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:4px 16px;margin-bottom:10px}.info-item label{font-size:7.5pt;color:#666;display:block}.info-item span{font-weight:600;font-size:8.5pt}h3{font-size:10pt;margin:10px 0 6px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:3px 5px;font-size:8pt;text-align:left;vertical-align:top}th{background:#f0f0f0;font-weight:700}tr:nth-child(even){background:#f9f9f9}.badge{display:inline-block;padding:1px 6px;border-radius:3px;background:#0d6efd;color:#fff;font-size:7.5pt}</style></head><body>'
    +'<div class="title">Document Tracer Report</div>'
    +'<hr>'
    +'<div class="info-grid">'
    +'<div class="info-item"><label>Control Number</label><span>'+e(doc.control)+'</span></div>'
    +'<div class="info-item"><label>Document Number</label><span>'+e(doc.number)+'</span></div>'
    +'<div class="info-item"><label>Document Type</label><span>'+e(doc.type)+'</span></div>'
    +'<div class="info-item"><label>Status</label><span><span class="badge">'+e(doc.status)+'</span></span></div>'
    +'<div class="info-item"><label>Title</label><span>'+e(doc.title)+'</span></div>'
    +'<div class="info-item"><label>Current Recipient Office</label><span>'+e(doc.recipient)+'</span></div>'
    +'<div class="info-item"><label>Source Office</label><span>'+e(doc.office)+'</span></div>'
    +'<div class="info-item"><label>Source Personnel</label><span>'+e(doc.personnel)+'</span></div>'
    +'<div class="info-item"><label>Employee ID</label><span>'+e(doc.emp_id)+'</span></div>'
    +'<div class="info-item"><label>Date Created</label><span>'+e(doc.created)+'</span></div>'
    +'<div class="info-item"><label>Description</label><span>'+e(doc.desc)+'</span></div>'
    +'<div class="info-item"><label>Last Updated</label><span>'+e(doc.modified)+'</span></div>'
    +'</div>'
    +'<hr>'
    +'<h3>Recipient History ('+traces.length+' record'+(traces.length!==1?'s':'')+')</h3>'
    +'<table><thead><tr><th>#</th><th>Action</th><th>Office</th><th>Employee</th><th>User</th><th>Remarks</th><th>Date &amp; Time</th></tr></thead><tbody>'+traceRows+'</tbody></table>'
    +'<script>window.onload=function(){window.print();};<\/script></body></html>');
  w.document.close();
}
<?php endif; ?>
</script>

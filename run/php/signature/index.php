<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');

		$body = json_decode(file_get_contents('php://input'), true);

		if (!$body || empty($body['imageData'])) {
				echo json_encode(['success' => false, 'error' => 'No image data received']);
				exit;
		}

		$imageData = $body['imageData'];
		$filename  = isset($body['filename']) ? basename($body['filename']) : 'signature_' . time() . '.png';

		if (pathinfo($filename, PATHINFO_EXTENSION) !== 'png') {
				$filename = pathinfo($filename, PATHINFO_FILENAME) . '.png';
		}

		$uploadDir = __DIR__ . '/uploads/signatures/';
		if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

		$base64Data = preg_replace('/^data:image\/(png|jpeg|jpg|gif);base64,/', '', $imageData);
		$decoded    = base64_decode($base64Data);

		if ($decoded === false) {
				echo json_encode(['success' => false, 'error' => 'Invalid base64 data']);
				exit;
		}

		$filePath = $uploadDir . $filename;

		if (file_put_contents($filePath, $decoded) !== false) {
				echo json_encode(['success' => true, 'filename' => $filename, 'path' => $filePath]);
		} else {
				echo json_encode(['success' => false, 'error' => 'Failed to write file. Check folder permissions.']);
		}
		exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<title>Signature Studio</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<style>
		/* Mobile-first canvas */
		.canvas-wrap {
			position: relative;
			background-image:
				linear-gradient(45deg, #e9ecef 25%, transparent 25%),
				linear-gradient(-45deg, #e9ecef 25%, transparent 25%),
				linear-gradient(45deg, transparent 75%, #e9ecef 75%),
				linear-gradient(-45deg, transparent 75%, #e9ecef 75%);
			background-size: 16px 16px;
			background-position: 0 0, 0 8px, 8px -8px, -8px 0px;
			border-radius: 0.375rem;
			overflow: hidden;
			/* Prevent pull-to-refresh interfering with drawing */
			overscroll-behavior: none;
			
			/* Responsive & Centered Fixes */
			width: 100%;
			max-width: 700px;
			aspect-ratio: 2 / 1;
			margin: 0 auto;
		}
		.canvas-placeholder {
			position: absolute; inset: 0;
			display: flex; align-items: center; justify-content: center;
			pointer-events: none; z-index: 1;
		}
		canvas {
			display: block;
			width: 100%;
			height: 100%;
			touch-action: none;
			cursor: crosshair;
			-webkit-user-select: none;
			user-select: none;
			background: transparent;
		}

		/* Full-screen modal on mobile */
		@media (max-width: 575.98px) {
			.modal-dialog {
				margin: 0;
				max-width: 100%;
				height: 100%;
			}
			.modal-content {
				height: 100%;
				border-radius: 0;
				border: none;
				display: flex;
				flex-direction: column;
			}
			.modal-body {
				flex: 1;
				overflow-y: auto;
				-webkit-overflow-scrolling: touch;
			}
		}

		/* Camera */
		#cameraVideo {
			width: 100%; max-height: 200px;
			object-fit: cover; background: #000;
			border-radius: 0.375rem;
		}

		/* Color swatches */
		.swatch {
			width: 32px; height: 32px;
			border-radius: 50%;
			border: 2px solid #dee2e6;
			cursor: pointer;
			transition: transform .15s;
			padding: 0;
			/* Larger tap target on mobile */
			min-width: 32px;
		}
		.swatch.active { border: 3px solid #0d6efd; transform: scale(1.15); }

		/* Nav tabs touch friendly */
		.nav-tabs .nav-link {
			color: #6c757d;
			padding: 0.6rem 0.9rem;
			font-size: 0.875rem;
		}
		.nav-tabs .nav-link.active { font-weight: 600; color: #212529; }

		/* Upload drop zone */
		.upload-drop {
			cursor: pointer;
			border: 2px dashed #ced4da !important;
			transition: background .2s;
			min-height: 100px;
		}
		.upload-drop:hover, .upload-drop:active { background-color: #f8f9fa !important; }

		/* BG removal card compact on mobile */
		.bg-card .card-body { padding: 0.5rem 0.75rem; }
		.bg-card .form-range { min-width: 60px; }

		/* Bigger buttons on mobile */
		@media (max-width: 575.98px) {
			.modal-footer .btn { padding: 0.5rem 0.85rem; font-size: 0.85rem; }
			.btn-cam { padding: 0.5rem 1rem; }
		}

		/* Signature line */
		.sig-label {
			font-size: 10px;
			letter-spacing: 2px;
			text-transform: uppercase;
			max-width: 700px;
			margin: 0 auto;
		}
	</style>
</head>
<body class="bg-light">

<div class="d-flex align-items-center justify-content-center vh-100 px-3">
	<div class="text-center">
		<i class="bi bi-pen display-4 text-dark d-block mb-3"></i>
		<h4 class="mb-1 fw-semibold">Document Signing</h4>
		<p class="text-muted mb-4 small">Tap below to add your signature</p>
		<button class="btn btn-dark px-4 py-2" data-bs-toggle="modal" data-bs-target="#signatureModal">
			<i class="bi bi-vector-pen me-2"></i>Open Signature Pad
		</button>
	</div>
</div>

<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="sigModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">

			<div class="modal-header bg-dark text-white py-2 py-sm-3">
				<div>
					<p class="mb-0 text-warning" style="font-size:10px; letter-spacing:3px; text-transform:uppercase">Electronic</p>
					<h6 class="modal-title mb-0 fw-normal" id="sigModalLabel">
						<i class="bi bi-vector-pen me-2"></i>Signature Studio
					</h6>
				</div>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body px-3 px-sm-4 py-3">

				<ul class="nav nav-tabs mb-3" id="modeTabs">
					<li class="nav-item flex-fill text-center">
						<button class="nav-link active w-100" onclick="setMode('draw')">
							<i class="bi bi-pencil me-1"></i>Draw
						</button>
					</li>
					<li class="nav-item flex-fill text-center">
						<button class="nav-link w-100" onclick="setMode('upload')">
							<i class="bi bi-upload me-1"></i>Upload
						</button>
					</li>
					<li class="nav-item flex-fill text-center">
						<button class="nav-link w-100" onclick="setMode('camera')">
							<i class="bi bi-camera me-1"></i>Camera
						</button>
					</li>
				</ul>

				<div id="drawControls" class="mb-3">
					<div class="d-flex align-items-center gap-2 flex-wrap row-gap-2">
						<div class="d-flex gap-2 flex-wrap" id="swatches"></div>
						<div class="d-flex align-items-center gap-2 ms-auto">
							<label class="form-label mb-0 small text-muted">Size</label>
							<input type="range" class="form-range" id="penSize" min="1" max="12" step="0.5" value="3.5"
										 style="width:70px" oninput="document.getElementById('sizeVal').textContent=this.value">
							<span class="small text-muted" id="sizeVal" style="min-width:20px">3.5</span>
							<label class="form-label mb-0 small text-muted ms-2" title="Higher = smoother strokes for shaky hands">
								<i class="bi bi-hand-index me-1"></i>Stabilizer
							</label>
							<input type="range" class="form-range" id="stabilizerSlider" min="0" max="0.97" step="0.01" value="0.82"
										 style="width:70px" oninput="updateStabilizer(this.value)">
							<span class="small text-muted" id="stabVal" style="min-width:24px">82%</span>
						</div>
					</div>
				</div>

				<div id="uploadArea" class="mb-3 d-none">
					<div class="rounded p-4 text-center bg-white upload-drop"
							 onclick="document.getElementById('fileInput').click()">
						<input type="file" id="fileInput" accept="image/*" capture="environment" class="d-none" onchange="handleUpload(event)">
						<i class="bi bi-image fs-1 text-secondary d-block mb-2"></i>
						<p class="mb-0 text-secondary small">Tap to upload or take a photo</p>
						<p class="mb-0 text-muted" style="font-size:11px">PNG, JPG, GIF supported</p>
					</div>
				</div>

				<div id="cameraArea" class="mb-3 d-none">
					<video id="cameraVideo" class="mb-2 d-block border" autoplay playsinline muted></video>
					<div class="d-flex gap-2 justify-content-center flex-wrap">
						<button class="btn btn-dark btn-sm btn-cam" id="btnStart" onclick="startCamera()">
							<i class="bi bi-play-fill me-1"></i>Start Camera
						</button>
						<button class="btn btn-warning btn-sm btn-cam d-none" id="btnSnap" onclick="snapPhoto()">
							<i class="bi bi-camera-fill me-1"></i>Capture
						</button>
						<button class="btn btn-secondary btn-sm btn-cam d-none" id="btnStop" onclick="stopCamera()">
							<i class="bi bi-stop-fill me-1"></i>Stop
						</button>
					</div>
					<p class="text-muted text-center mt-2 mb-0" style="font-size:11px" id="camStatus">Camera is off</p>
				</div>

				<div class="canvas-wrap border mb-1" id="canvasWrap">
					<div class="canvas-placeholder" id="placeholder">
						<span class="text-muted fst-italic small" id="placeholderText">Sign here…</span>
					</div>
					<canvas id="sigCanvas"></canvas>
				</div>
				<p class="text-warning border-top border-warning pt-1 mb-3 sig-label">Authorized Signature</p>

				<div class="card bg-light border bg-card d-none" id="bgRemovalCard">
					<div class="card-body">
						<div class="d-flex align-items-center flex-wrap gap-2 row-gap-2">
							<div class="form-check mb-0">
								<input class="form-check-input" type="checkbox" id="chkAutoBg">
								<label class="form-check-label small" for="chkAutoBg">Auto-remove BG</label>
							</div>
							<div class="d-flex align-items-center gap-2 ms-auto">
								<span class="small text-muted">Tolerance</span>
								<input type="range" class="form-range" id="toleranceSlider" min="10" max="120" value="40"
											 style="width:65px" oninput="document.getElementById('tolVal').textContent=this.value">
								<span class="small text-muted" id="tolVal" style="min-width:24px">40</span>
							</div>
							<button class="btn btn-dark btn-sm" id="btnRemoveBg" onclick="removeBg()" disabled>
								<i class="bi bi-scissors me-1"></i>Remove BG
							</button>
							<button class="btn btn-outline-secondary btn-sm d-none" id="btnUndoBg" onclick="undoBg()">
								<i class="bi bi-arrow-counterclockwise"></i>
							</button>
						</div>
					</div>
				</div>

				<div id="statusSuccess" class="alert alert-success mt-3 mb-0 py-2 d-none small">
					<i class="bi bi-check-circle-fill me-1"></i><strong>Saved!</strong>
					<div class="mt-1 text-break" id="statusPath" style="font-size:11px"></div>
				</div>
				<div id="statusError" class="alert alert-danger mt-3 mb-0 py-2 d-none small">
					<i class="bi bi-exclamation-triangle-fill me-1"></i>
					<span id="statusErrorMsg">Could not save. Check server or folder permissions.</span>
				</div>

			</div><div class="modal-footer bg-light py-2 gap-2 flex-nowrap">
				<div class="d-flex gap-2">
					<button class="btn btn-outline-secondary btn-sm" onclick="clearCanvas()">
						<i class="bi bi-trash"></i>
						<span class="d-none d-sm-inline ms-1">Clear</span>
					</button>
					<button class="btn btn-outline-warning btn-sm" id="btnUndoStroke" onclick="undoStroke()" disabled>
						<i class="bi bi-arrow-counterclockwise"></i>
						<span class="d-none d-sm-inline ms-1">Undo</span>
					</button>
					<button class="btn btn-outline-primary btn-sm" id="btnDownload" disabled onclick="downloadSignature()">
						<i class="bi bi-download"></i>
						<span class="d-none d-sm-inline ms-1">Download</span>
					</button>
				</div>
				<div class="d-flex gap-2 ms-auto">
					<button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
						<i class="bi bi-x"></i>
						<span class="d-none d-sm-inline ms-1">Cancel</span>
					</button>
					<button class="btn btn-dark btn-sm" id="btnSave" disabled onclick="saveSignature()">
						<i class="bi bi-floppy"></i>
						<span class="ms-1">Save</span>
					</button>
				</div>
			</div>

		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
	const canvas = document.getElementById('sigCanvas');
	const ctx    = canvas.getContext('2d', { willReadFrequently: true });
	let isDrawing = false, isEmpty = true, mode = 'draw';
	let penColor = '#000000', penSize = 3.5;
	let cameraStream = null, beforeBgSnap = null;

	// Smooth drawing with shake correction
	let points = [];
	let allStrokes = []; // stores all completed smoothed strokes for full redraw
	// Stabilizer strength: higher = smoother but more lag (0.0 - 1.0)
	// 0.85 feels natural for shaky hands
	let STABILIZER = 0.82;

	function updateStabilizer(v) {
		STABILIZER = parseFloat(v);
		document.getElementById('stabVal').textContent = Math.round(v * 100) + '%';
	}
	// Rolling average window for shake correction
	const SMOOTH_WINDOW = 6;

	function midPoint(p1, p2) {
		return { x: (p1.x + p2.x) / 2, y: (p1.y + p2.y) / 2 };
	}

	// Average the last N points to iron out shakiness
	function smoothedPoint(pts) {
		const window = pts.slice(-SMOOTH_WINDOW);
		const avg = window.reduce((acc, p) => ({ x: acc.x + p.x, y: acc.y + p.y }), { x: 0, y: 0 });
		return { x: avg.x / window.length, y: avg.y / window.length };
	}

	// Lazy pen: interpolate current cursor toward target for trailing smoothness
	let lazyX = 0, lazyY = 0;

	const colors = ['#000000','#1a1a2e','#198754','#0d6efd','#dc3545','#6f42c1'];

	// Init canvas on modal open
	const sigModal = document.getElementById('signatureModal');
	sigModal.addEventListener('shown.bs.modal', () => {
		initCanvas();
		// Prevent body scroll behind modal on iOS
		document.body.style.overflow = 'hidden';
	});
	sigModal.addEventListener('hidden.bs.modal', () => {
		if (mode === 'camera') stopCamera();
		document.body.style.overflow = '';
	});

	function initCanvas() {
		// Fixed internal resolution guarantees the signature scales properly on resize
		// and keeps coordinate mapping completely consistent on all devices.
		canvas.width = 800;
		canvas.height = 400;
		// Scale by 2 maps it to a logical 400x200 space so penSize feels correct
		ctx.scale(2, 2);
		ctx.lineCap  = 'round';
		ctx.lineJoin = 'round';
	}

	// Color swatches
	const swatchContainer = document.getElementById('swatches');
	colors.forEach(c => {
		const btn = document.createElement('button');
		btn.className = 'swatch' + (c === penColor ? ' active' : '');
		btn.style.background = c;
		btn.type = 'button';
		btn.setAttribute('aria-label', 'Pen color ' + c);
		btn.onclick = () => {
			penColor = c;
			document.querySelectorAll('.swatch').forEach(s => s.classList.remove('active'));
			btn.classList.add('active');
		};
		swatchContainer.appendChild(btn);
	});

	// Drawing — unified mouse + touch
	function getPos(e) {
		const rect = canvas.getBoundingClientRect();
		const src  = e.touches ? e.touches[0] : e;
		// Scale physical CSS pixels to the logical (400x200) context scale
		const scaleX = 400 / rect.width;
		const scaleY = 200 / rect.height;
		
		return {
			x: (src.clientX - rect.left) * scaleX,
			y: (src.clientY - rect.top) * scaleY
		};
	}

	function startDraw(e) {
		if (mode !== 'draw') return;
		e.preventDefault();
		isDrawing = true;
		points = [];
		const p = getPos(e);
		// Seed the lazy position at start
		lazyX = p.x; lazyY = p.y;
		points.push({ x: lazyX, y: lazyY });
		// Draw a dot for tap/click
		ctx.beginPath();
		ctx.arc(p.x, p.y, penSize / 2, 0, Math.PI * 2);
		ctx.fillStyle = penColor; ctx.fill();
		markUsed();
	}

	function draw(e) {
		if (!isDrawing || mode !== 'draw') return;
		e.preventDefault();
		const raw = getPos(e);

		// Step 1: Lazy pen — move lazyX/Y toward the real cursor by (1 - STABILIZER)
		lazyX += (raw.x - lazyX) * (1 - STABILIZER);
		lazyY += (raw.y - lazyY) * (1 - STABILIZER);

		points.push({ x: lazyX, y: lazyY });

		// Step 2: Rolling average over last N points for extra smoothing
		const smoothed = smoothedPoint(points);

		// Need at least 3 points for a curve
		if (points.length < 3) return;

		ctx.strokeStyle = penColor;
		ctx.lineWidth   = penSize;
		ctx.lineCap     = 'round';
		ctx.lineJoin    = 'round';

		// Step 3: Quadratic Bezier through midpoints of smoothed points
		const prev = smoothedPoint(points.slice(0, -1));
		const curr = smoothed;
		const mid  = midPoint(prev, curr);

		ctx.beginPath();
		ctx.moveTo(midPoint(smoothedPoint(points.slice(0, -2)), prev).x,
							 midPoint(smoothedPoint(points.slice(0, -2)), prev).y);
		ctx.quadraticCurveTo(prev.x, prev.y, mid.x, mid.y);
		ctx.stroke();
	}

	function stopDraw(e) {
		if (!isDrawing) return;
		isDrawing = false;

		if (points.length >= 1) {
			// Apply Chaikin smoothing to this stroke's points
			const smoothed = chaikinSmooth(points, 3);
			// Save completed stroke
			allStrokes.push({ pts: smoothed, color: penColor, size: penSize });
			// Redraw ALL strokes cleanly
			redrawAllStrokes();
			document.getElementById('btnUndoStroke').disabled = false;
		}
		points = [];
	}

	// Chaikin corner-cutting: smooth a path by iteratively cutting corners
	function chaikinSmooth(rawPts, passes) {
		let pts = rawPts.slice();
		for (let pass = 0; pass < passes; pass++) {
			const s = [pts[0]];
			for (let i = 0; i < pts.length - 1; i++) {
				const p0 = pts[i], p1 = pts[i + 1];
				s.push({ x: 0.75 * p0.x + 0.25 * p1.x, y: 0.75 * p0.y + 0.25 * p1.y });
				s.push({ x: 0.25 * p0.x + 0.75 * p1.x, y: 0.25 * p0.y + 0.75 * p1.y });
			}
			s.push(pts[pts.length - 1]);
			pts = s;
		}
		return pts;
	}

	// Undo the last stroke
	function undoStroke() {
		if (allStrokes.length === 0) return;
		allStrokes.pop();
		if (allStrokes.length === 0) {
			ctx.clearRect(0, 0, canvas.width, canvas.height);
			isEmpty = true;
			document.getElementById('placeholder').style.display = 'flex';
			document.getElementById('btnDownload').disabled   = true;
			document.getElementById('btnSave').disabled       = true;
			document.getElementById('btnUndoStroke').disabled = true;
		} else {
			redrawAllStrokes();
			document.getElementById('btnUndoStroke').disabled = false;
		}
	}

	// Redraw every saved stroke onto a clean canvas
	function redrawAllStrokes() {
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		allStrokes.forEach(stroke => drawStroke(stroke.pts, stroke.color, stroke.size));
	}

	// Draw a single stroke as a smooth Bezier spline
	function drawStroke(pts, color, size) {
		if (pts.length === 0) return;
		ctx.beginPath();
		ctx.strokeStyle = color;
		ctx.lineWidth   = size;
		ctx.lineCap     = 'round';
		ctx.lineJoin    = 'round';
		if (pts.length === 1) {
			// Single dot
			ctx.arc(pts[0].x, pts[0].y, size / 2, 0, Math.PI * 2);
			ctx.fillStyle = color;
			ctx.fill();
			return;
		}
		ctx.moveTo(pts[0].x, pts[0].y);
		for (let i = 1; i < pts.length - 1; i++) {
			const mx = (pts[i].x + pts[i + 1].x) / 2;
			const my = (pts[i].y + pts[i + 1].y) / 2;
			ctx.quadraticCurveTo(pts[i].x, pts[i].y, mx, my);
		}
		ctx.lineTo(pts[pts.length - 1].x, pts[pts.length - 1].y);
		ctx.stroke();
	}

	canvas.addEventListener('mousedown',  startDraw);
	canvas.addEventListener('mousemove',  draw);
	canvas.addEventListener('mouseup',    stopDraw);
	canvas.addEventListener('mouseleave', stopDraw);
	canvas.addEventListener('touchstart', startDraw, { passive: false });
	canvas.addEventListener('touchmove',  draw,      { passive: false });
	canvas.addEventListener('touchend',   stopDraw);
	canvas.addEventListener('touchcancel',stopDraw);

	function markUsed() {
		isEmpty = false;
		document.getElementById('placeholder').style.display = 'none';
		document.getElementById('btnDownload').disabled    = false;
		document.getElementById('btnSave').disabled        = false;
		document.getElementById('btnRemoveBg').disabled    = (mode === 'draw');
		document.getElementById('btnUndoStroke').disabled  = (allStrokes.length === 0);
	}

	function clearCanvas() {
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		allStrokes = [];
		isEmpty = true; beforeBgSnap = null;
		document.getElementById('placeholder').style.display = 'flex';
		document.getElementById('btnDownload').disabled    = true;
		document.getElementById('btnSave').disabled        = true;
		document.getElementById('btnRemoveBg').disabled    = true;
		document.getElementById('btnUndoStroke').disabled  = true;
		document.getElementById('btnUndoBg').classList.add('d-none');
		document.getElementById('statusSuccess').classList.add('d-none');
		document.getElementById('statusError').classList.add('d-none');
	}

	function setMode(m) {
		if (mode === 'camera') stopCamera();
		mode = m;
		clearCanvas();

		document.querySelectorAll('#modeTabs .nav-link').forEach((btn, i) => {
			btn.classList.toggle('active', ['draw','upload','camera'][i] === m);
		});

		document.getElementById('drawControls').classList.toggle('d-none', m !== 'draw');
		document.getElementById('uploadArea').classList.toggle('d-none',   m !== 'upload');
		document.getElementById('cameraArea').classList.toggle('d-none',   m !== 'camera');

		// BG removal only makes sense for upload/camera
		document.getElementById('bgRemovalCard').classList.toggle('d-none', m === 'draw');

		const hints = { draw: 'Sign here…', upload: 'Tap to upload or take a photo', camera: 'Capture from camera…' };
		document.getElementById('placeholderText').textContent = hints[m];
		canvas.style.cursor = m === 'draw' ? 'crosshair' : 'default';
	}

	function drawImageToCanvas(img) {
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		const logicalW = 400;
		const logicalH = 200;
		const scale = Math.min(logicalW / img.width, logicalH / img.height) * 0.85;
		ctx.drawImage(img,
			(logicalW  - img.width  * scale) / 2,
			(logicalH - img.height * scale) / 2,
			img.width * scale, img.height * scale);
		markUsed();
		if (document.getElementById('chkAutoBg').checked) removeBg();
	}

	function handleUpload(e) {
		const file = e.target.files[0];
		if (!file) return;
		const reader = new FileReader();
		reader.onload = ev => {
			const img = new Image();
			img.onload = () => drawImageToCanvas(img);
			img.src = ev.target.result;
		};
		reader.readAsDataURL(file);
		e.target.value = '';
	}

	async function startCamera() {
		try {
			cameraStream = await navigator.mediaDevices.getUserMedia({
				video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 } },
				audio: false
			});
			document.getElementById('cameraVideo').srcObject = cameraStream;
			document.getElementById('btnStart').classList.add('d-none');
			document.getElementById('btnSnap').classList.remove('d-none');
			document.getElementById('btnStop').classList.remove('d-none');
			document.getElementById('camStatus').textContent = 'Camera active — tap Capture when ready';
		} catch (err) {
			document.getElementById('camStatus').textContent = '⚠ Camera access denied: ' + err.message;
		}
	}

	function snapPhoto() {
		const video = document.getElementById('cameraVideo');
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		const logicalW = 400;
		const logicalH = 200;
		const scale = Math.min(logicalW / video.videoWidth, logicalH / video.videoHeight) * 0.92;
		ctx.drawImage(video,
			(logicalW  - video.videoWidth  * scale) / 2,
			(logicalH - video.videoHeight * scale) / 2,
			video.videoWidth * scale, video.videoHeight * scale);
		markUsed();
		stopCamera();
		document.getElementById('camStatus').textContent = '✓ Photo captured';
		if (document.getElementById('chkAutoBg').checked) removeBg();
	}

	function stopCamera() {
		if (cameraStream) { cameraStream.getTracks().forEach(t => t.stop()); cameraStream = null; }
		document.getElementById('cameraVideo').srcObject = null;
		document.getElementById('btnStart').classList.remove('d-none');
		document.getElementById('btnSnap').classList.add('d-none');
		document.getElementById('btnStop').classList.add('d-none');
		if (document.getElementById('camStatus').textContent !== '✓ Photo captured')
			document.getElementById('camStatus').textContent = 'Camera is off';
	}

	function removeBg() {
		if (isEmpty) return;
		beforeBgSnap = ctx.getImageData(0, 0, canvas.width, canvas.height);
		document.getElementById('btnUndoBg').classList.remove('d-none');

		const tolerance = parseInt(document.getElementById('toleranceSlider').value);
		const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
		const data      = imageData.data;

		function getPixel(x, y) {
			const i = (y * canvas.width + x) * 4;
			return [data[i], data[i+1], data[i+2]];
		}
		const corners = [
			getPixel(0, 0), getPixel(canvas.width - 1, 0),
			getPixel(0, canvas.height - 1), getPixel(canvas.width - 1, canvas.height - 1)
		];
		const bgR = corners.reduce((s,c) => s + c[0], 0) / 4;
		const bgG = corners.reduce((s,c) => s + c[1], 0) / 4;
		const bgB = corners.reduce((s,c) => s + c[2], 0) / 4;

		for (let i = 0; i < data.length; i += 4) {
			const dist = Math.sqrt(
				Math.pow(data[i]   - bgR, 2) +
				Math.pow(data[i+1] - bgG, 2) +
				Math.pow(data[i+2] - bgB, 2)
			);
			if (dist < tolerance) {
				data[i + 3] = Math.round(Math.min(1, (dist / tolerance) * 3) * 255);
			}
		}
		ctx.putImageData(imageData, 0, 0);
	}

	function undoBg() {
		if (!beforeBgSnap) return;
		ctx.putImageData(beforeBgSnap, 0, 0);
		beforeBgSnap = null;
		document.getElementById('btnUndoBg').classList.add('d-none');
	}

	function getFlattenedDataURL() {
		const flat = document.createElement('canvas');
		flat.width  = canvas.width;
		flat.height = canvas.height;
		const fCtx = flat.getContext('2d');
		fCtx.drawImage(canvas, 0, 0);
		return flat.toDataURL('image/png');
	}

	function downloadSignature() {
		const link = document.createElement('a');
		link.download = 'signature_' + Date.now() + '.png';
		link.href = getFlattenedDataURL();
		link.click();
	}

	async function saveSignature() {
		const imageData = getFlattenedDataURL();
		const filename  = 'signature_' + Date.now() + '.png';
		const btn = document.getElementById('btnSave');
		btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
		btn.disabled = true;

		try {
			const res  = await fetch('', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ imageData, filename })
			});
			const data = await res.json();
			if (data.success) {
				document.getElementById('statusSuccess').classList.remove('d-none');
				document.getElementById('statusError').classList.add('d-none');
				document.getElementById('statusPath').textContent = '📁 ' + data.path;
			} else {
				document.getElementById('statusError').classList.remove('d-none');
				document.getElementById('statusSuccess').classList.add('d-none');
				document.getElementById('statusErrorMsg').textContent = data.error;
			}
		} catch {
			document.getElementById('statusError').classList.remove('d-none');
			document.getElementById('statusSuccess').classList.add('d-none');
			document.getElementById('statusErrorMsg').textContent = 'Could not save. Check server or folder permissions.';
		}

		btn.innerHTML = '<i class="bi bi-floppy"></i> Save';
		btn.disabled = false;
	}
</script>
</body>
</html>
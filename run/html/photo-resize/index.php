<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional ID Studio Pro</title>
    
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#0d6efd">
    <link rel="apple-touch-icon" href="https://cdn-icons-png.flaticon.com/512/1042/1042339.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/selfie_segmentation"></script>

    <style>
        :root { --primary-color: #0d6efd; }
        body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        
        /* The Bond Paper Canvas */
        .page-preview {
            background: white !important;
            margin: 20px auto;
            border: 1px solid #333;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            line-height: 0; 
            display: block; 
            overflow: hidden;
            position: relative;
            padding: 5mm; 
            box-sizing: border-box;
            text-align: left;
        }

        .page-preview.grid-active {
            background-image: radial-gradient(#ccc 0.5px, transparent 0.5px);
            background-size: 5mm 5mm;
        }
        
        /* Photos stick together with NO margin */
        .id-item {
            display: inline-block;
            margin: 0 !important; 
            padding: 0 !important;
            position: relative;
            vertical-align: top;
            border: 0.3mm solid #000000 !important; 
            box-sizing: border-box;
        }

        /* High-Res Print View */
        #finalPrintFrame {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: white;
            z-index: 9999;
        }

        @media print {
            .no-print { display: none !important; }
            body, html { margin: 0; padding: 0; background: white; }
            #finalPrintFrame { display: block !important; }
            #finalPrintImage { width: 100%; height: auto; display: block; }
        }

        /* UI Sidebar styling */
        .card { border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        #crop-area { background: #222; border-radius: 8px; overflow: hidden; margin-bottom: 15px; height: 250px; width: 100%; position: relative; }
        #sig-canvas { border: 1px solid #ced4da; background: #fff; cursor: crosshair; width: 100%; height: 100px; border-radius: 5px; }

        .remove-btn { 
            position: absolute; top: 0; right: 0; background: rgba(255, 0, 0, 0.9); 
            color: white; border: none; font-size: 12px; width: 20px; height: 20px;
            cursor: pointer; z-index: 10; line-height: 1;
        }
        
        .sig-overlay {
            position: absolute; bottom: 0; left: 0; width: 100%;
            background: rgba(255, 255, 255, 0.98) !important;
            text-align: center; display: flex; flex-direction: column; align-items: center; padding: 1px 0;
            border-top: 0.2mm solid #000;
        }
        .name-text { font-size: 7pt; font-weight: bold; color: #000; text-transform: uppercase; line-height: 1; margin: 0; }
        .sig-img-result { max-height: 14px; width: auto; margin-bottom: -1px; }

        /* Camera & AI UI */
        #camera-panel { display: none; background: #000; border-radius: 8px; overflow: hidden; margin-bottom: 10px; position: relative; }
        #webcam-view { width: 100%; transform: scaleX(-1); }
        .ai-loader {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.7); color: white; display: none;
            flex-direction: column; align-items: center; justify-content: center; z-index: 20;
        }
    </style>
</head>
<body>

<div id="finalPrintFrame">
    <img id="finalPrintImage">
</div>

<div class="container-fluid py-4 no-print">
    <div class="row">
        <div class="col-lg-4 col-xl-3">
            <div class="card p-3 mb-3">
                <h6 class="fw-bold text-primary mb-3">1. Photo & Custom Size</h6>
                
                <div class="d-flex gap-1 mb-2">
                    <button class="btn btn-sm btn-dark flex-fill" onclick="toggleCamera()">📸 Open Camera</button>
                    <button class="btn btn-sm btn-primary d-none" id="capture-btn" onclick="capturePhoto()">Capture</button>
                </div>

                <div id="camera-panel">
                    <video id="webcam-view" autoplay playsinline></video>
                </div>

                <div class="btn-group w-100 mb-3" role="group">
                    <button class="btn btn-sm btn-outline-primary" onclick="setPreset(1,1)">1x1</button>
                    <button class="btn btn-sm btn-outline-primary" onclick="setPreset(2,2)">2x2</button>
                    <button class="btn btn-sm btn-outline-primary" onclick="setPreset(35,45,'mm')">Passport</button>
                </div>

                <input type="file" id="imageInput" class="form-control form-control-sm mb-3" accept="image/*">
                
                <button class="btn btn-sm btn-info w-100 mb-3 text-white fw-bold d-none" id="ai-erase-btn" onclick="runAIEraser()">✨ AI Background Eraser (White)</button>

                <div id="crop-area" class="d-none">
                    <div class="ai-loader" id="ai-loading">
                        <div class="spinner-border spinner-border-sm mb-2"></div>
                        <small>AI Processing...</small>
                    </div>
                    <img id="imageToCrop">
                </div>

                <div class="row g-1 mb-3">
                    <div class="col-4">
                        <label class="tiny fw-bold" style="font-size: 11px;">Width</label>
                        <input type="number" id="custW" class="form-control form-control-sm" value="2">
                    </div>
                    <div class="col-4">
                        <label class="tiny fw-bold" style="font-size: 11px;">Height</label>
                        <input type="number" id="custH" class="form-control form-control-sm" value="2">
                    </div>
                    <div class="col-4">
                        <label class="tiny fw-bold" style="font-size: 11px;">Unit</label>
                        <select id="unit" class="form-select form-select-sm">
                            <option value="in">Inch</option>
                            <option value="mm">mm</option>
                            <option value="cm">cm</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="text" id="idName" class="form-control form-control-sm" placeholder="Name (Optional)">
                </div>

                <div class="mb-3">
                    <label class="small fw-bold d-flex justify-content-between">
                        Signature (Optional)
                        <a href="#" class="text-danger text-decoration-none small" id="clear-sig">Clear</a>
                    </label>
                    <input type="file" id="sigImageUpload" class="form-control form-control-sm mb-1" accept="image/png">
                    <canvas id="sig-canvas"></canvas>
                </div>
                
                <div class="row g-1">
                    <div class="col-8">
                        <button id="addBtn" class="btn btn-primary w-100 fw-bold disabled shadow-sm">ADD TO SHEET</button>
                    </div>
                    <div class="col-4">
                        <input type="number" id="qty" class="form-control" value="1" min="1">
                    </div>
                </div>
            </div>

            <div class="card p-3">
                <h6 class="fw-bold text-success mb-3">2. Bondpaper Settings</h6>
                <div class="row g-1 mb-2">
                    <div class="col-6"><label class="small fw-bold">Paper W (in)</label><input type="number" id="papW" class="form-control form-control-sm" value="8.27"></div>
                    <div class="col-6"><label class="small fw-bold">Paper H (in)</label><input type="number" id="papH" class="form-control form-control-sm" value="11.69"></div>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="toggleGrid">
                    <label class="form-check-label small fw-bold" for="toggleGrid">Grid Guides</label>
                </div>
                <button id="printBtn" class="btn btn-dark w-100 fw-bold shadow">PRINT NOW</button>
                <button onclick="location.reload()" class="btn btn-link text-danger w-100 mt-1 small text-decoration-none">Clear Sheet</button>
            </div>
        </div>

        <div class="col-lg-8 col-xl-9">
            <div id="printCanvas" class="page-preview shadow"></div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    // --- SERVICE WORKER ---
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('sw.js').catch(err => console.log('SW Error', err));
        });
    }

    // --- AI ERASER ENGINE ---
    const selfieSegmentation = new SelfieSegmentation({
        locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/selfie_segmentation/${file}`
    });

    selfieSegmentation.setOptions({ modelSelection: 1 });

    selfieSegmentation.onResults((results) => {
        const canvasAi = document.createElement('canvas');
        const ctxAi = canvasAi.getContext('2d');
        canvasAi.width = results.image.width;
        canvasAi.height = results.image.height;

        ctxAi.drawImage(results.segmentationMask, 0, 0, canvasAi.width, canvasAi.height);
        ctxAi.globalCompositeOperation = 'source-in';
        ctxAi.drawImage(results.image, 0, 0, canvasAi.width, canvasAi.height);
        ctxAi.globalCompositeOperation = 'destination-over';
        ctxAi.fillStyle = 'white';
        ctxAi.fillRect(0, 0, canvasAi.width, canvasAi.height);

        updateCropperSource(canvasAi.toDataURL('image/jpeg'));
        document.getElementById('ai-loading').style.display = 'none';
    });

    async function runAIEraser() {
        if (!cropper) return;
        document.getElementById('ai-loading').style.display = 'flex';
        const imgElement = document.getElementById('imageToCrop');
        await selfieSegmentation.send({ image: imgElement });
    }

    // --- CAMERA LOGIC ---
    let videoStream = null;
    async function toggleCamera() {
        const panel = document.getElementById('camera-panel');
        const video = document.getElementById('webcam-view');
        const capBtn = document.getElementById('capture-btn');

        if (!videoStream) {
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: "user" } 
                });
                video.srcObject = videoStream;
                panel.style.display = 'block';
                capBtn.classList.remove('d-none');
            } catch (err) {
                alert("Camera Access Denied or Unavailable. Ensure you are using HTTPS or Localhost.");
            }
        } else {
            stopCamera();
        }
    }

    function stopCamera() {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            videoStream = null;
            document.getElementById('camera-panel').style.display = 'none';
            document.getElementById('capture-btn').classList.add('d-none');
        }
    }

    function capturePhoto() {
        const video = document.getElementById('webcam-view');
        const captureCanvas = document.createElement('canvas');
        captureCanvas.width = video.videoWidth;
        captureCanvas.height = video.videoHeight;
        const ctxCap = captureCanvas.getContext('2d');
        
        ctxCap.translate(captureCanvas.width, 0);
        ctxCap.scale(-1, 1);
        ctxCap.drawImage(video, 0, 0);
        
        updateCropperSource(captureCanvas.toDataURL('image/jpeg'));
        stopCamera();
    }

    // --- CROPPER & UI ---
    let cropper;
    const canvasPreview = document.getElementById('printCanvas');

    function updateCropperSource(src) {
        const img = document.getElementById('imageToCrop');
        img.src = src;
        document.getElementById('crop-area').classList.remove('d-none');
        document.getElementById('ai-erase-btn').classList.remove('d-none');
        if (cropper) cropper.destroy();
        cropper = new Cropper(img, {
            aspectRatio: document.getElementById('custW').value / document.getElementById('custH').value,
            viewMode: 1, dragMode: 'move', autoCropArea: 1,
            guides: true, cropBoxMovable: true, cropBoxResizable: true
        });
        document.getElementById('addBtn').classList.remove('disabled');
    }

    function setPreset(w, h, unit = 'in') {
        document.getElementById('custW').value = w;
        document.getElementById('custH').value = h;
        document.getElementById('unit').value = unit;
        if (cropper) cropper.setAspectRatio(w / h);
    }

    // Signature Pad
    const sigCanvas = document.getElementById('sig-canvas');
    const ctxSig = sigCanvas.getContext('2d', { willReadFrequently: true });
    let drawing = false;
    sigCanvas.width = sigCanvas.offsetWidth; sigCanvas.height = sigCanvas.offsetHeight;
    ctxSig.lineWidth = 2.5; ctxSig.strokeStyle = "#000";

    function getPos(e) {
        const rect = sigCanvas.getBoundingClientRect();
        const clientX = e.clientX || (e.touches ? e.touches[0].clientX : 0);
        const clientY = e.clientY || (e.touches ? e.touches[0].clientY : 0);
        return { x: clientX - rect.left, y: clientY - rect.top };
    }
    const startDraw = (e) => { drawing = true; ctxSig.beginPath(); const p = getPos(e); ctxSig.moveTo(p.x, p.y); };
    const moveDraw = (e) => { if(!drawing) return; const p = getPos(e); ctxSig.lineTo(p.x, p.y); ctxSig.stroke(); };
    sigCanvas.addEventListener('mousedown', startDraw);
    sigCanvas.addEventListener('mousemove', moveDraw);
    sigCanvas.addEventListener('touchstart', (e) => { startDraw(e); e.preventDefault(); });
    sigCanvas.addEventListener('touchmove', (e) => { moveDraw(e); e.preventDefault(); });
    window.addEventListener('mouseup', () => drawing = false);
    window.addEventListener('touchend', () => drawing = false);

    document.getElementById('clear-sig').addEventListener('click', (e) => {
        e.preventDefault(); ctxSig.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    });

    document.getElementById('sigImageUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => { 
                    ctxSig.clearRect(0, 0, sigCanvas.width, sigCanvas.height); 
                    ctxSig.drawImage(img, 0, 0, sigCanvas.width, sigCanvas.height); 
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    function toMM(val, unit) { return unit === 'in' ? val * 25.4 : (unit === 'cm' ? val * 10 : val); }
    
    function updatePaperSize() {
        const wMM = toMM(document.getElementById('papW').value, 'in');
        const hMM = toMM(document.getElementById('papH').value, 'in');
        canvasPreview.style.width = wMM + 'mm';
        canvasPreview.style.height = hMM + 'mm';
    }
    document.getElementById('papW').addEventListener('input', updatePaperSize);
    document.getElementById('papH').addEventListener('input', updatePaperSize);
    document.getElementById('toggleGrid').addEventListener('change', () => canvasPreview.classList.toggle('grid-active'));
    updatePaperSize();

    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => updateCropperSource(event.target.result);
            reader.readAsDataURL(file);
        }
    });

    function isCanvasBlank(can) {
        const pix = can.getContext('2d').getImageData(0, 0, can.width, can.height).data;
        for (let i = 0; i < pix.length; i += 4) { if (pix[i+3] > 30) return false; }
        return true;
    }

    document.getElementById('addBtn').addEventListener('click', () => {
        if (!cropper) return;
        const w = document.getElementById('custW').value, h = document.getElementById('custH').value, unit = document.getElementById('unit').value;
        const name = document.getElementById('idName').value.trim(), isSigEmpty = isCanvasBlank(sigCanvas);
        const sigData = sigCanvas.toDataURL('image/png'), croppedData = cropper.getCroppedCanvas({ width: 800 }).toDataURL('image/jpeg', 0.9);

        for (let i = 0; i < document.getElementById('qty').value; i++) {
            const div = document.createElement('div');
            div.className = 'id-item';
            div.style.width = toMM(w, unit) + 'mm'; div.style.height = toMM(h, unit) + 'mm';
            let overlay = (name !== "" || !isSigEmpty) ? `<div class="sig-overlay">${!isSigEmpty ? `<img src="${sigData}" class="sig-img-result">` : ''}${name !== "" ? `<p class="name-text">${name}</p>` : ''}</div>` : '';
            div.innerHTML = `<button class="remove-btn no-print" onclick="this.parentElement.remove()">×</button><img src="${croppedData}" style="width:100%; height:100%; object-fit:cover; display:block;">${overlay}`;
            canvasPreview.appendChild(div);
        }
    });

    document.getElementById('printBtn').addEventListener('click', function() {
        if (canvasPreview.children.length === 0) return alert("Add photos first!");
        document.querySelectorAll('.remove-btn').forEach(b => b.style.display = 'none');
        canvasPreview.classList.remove('grid-active');
        html2canvas(canvasPreview, { scale: 3, useCORS: true, backgroundColor: "#ffffff" }).then(outputCanvas => {
            document.getElementById('finalPrintImage').src = outputCanvas.toDataURL("image/png");
            setTimeout(() => {
                window.print();
                document.querySelectorAll('.remove-btn').forEach(b => b.style.display = '');
            }, 500);
        });
    });
</script>
</body>
</html>
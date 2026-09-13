(() => {
    'use strict';
    if (window.location.protocol !== 'https:') return;
    const root = document.getElementById('attendance-kiosk');
    const el = id => document.getElementById(id);
    const idInput = el('attendance-id'), pinInput = el('attendance-pin');
    const buttons = [...root.querySelectorAll('[data-action]')];
    const video = el('attendance-camera');
    const started = performance.now(), serverTime = Number(root.dataset.now);
    let verified = false, busy = false, stream = null, modelPromise = null, expiry = 0;
    let capturePreview = null;
    let deleteToken = null;
    function renderTimes(attendance) {
        for (const [field, label] of Object.entries({amtimein: 'AM-In', amtimeout: 'AM-Out', pmtimein: 'PM-In', pmtimeout: 'PM-Out'})) {
            const cell = el('attendance-' + field);
            cell.replaceChildren();
            if (attendance[field]) {
                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'btn btn-sm text-danger d-block ms-auto py-0 px-1';
                remove.textContent = '×';
                remove.setAttribute('aria-label', 'Delete ' + label + ' time');
                remove.title = 'Delete ' + label + ' time';
                remove.dataset.deleteTime = field;
                remove.addEventListener('click', async () => {
                    if (busy || !deleteToken) return;
                    if (!window.confirm(`Warning: Delete ${label} (${attendance[field]}) for ${el('attendance-name').textContent}?\n\nThis will remove the recorded time. Press OK to delete or Cancel to keep it.`)) return;
                    busy = true; updateClock();
                    try {
                        const data = new FormData();
                        data.append('action', 'delete_time');
                        data.append('field', field);
                        data.append('delete_token', deleteToken);
                        const result = await request(data);
                        renderTimes(result.attendance);
                        message(result.message);
                    } catch (error) { message(error.message, true); }
                    finally { busy = false; updateClock(); }
                });
                cell.append(remove);
            }
            const time = document.createElement('span');
            time.textContent = attendance[field] || '—';
            cell.append(time);
        }
    }
    function message(text, error = false) {
        el('attendance-message').className = `alert mt-3 alert-${error ? 'warning' : 'success'}`;
        el('attendance-message').textContent = text;
    }
    function stopCamera() {
        if (stream) stream.getTracks().forEach(track => track.stop());
        stream = null;
        video.srcObject = null;
    }
    function reset(keepProfile = false) {
        verified = false;
        clearTimeout(expiry);
        stopCamera();
        if (keepProfile !== true) {
            deleteToken = null;
            if (capturePreview) URL.revokeObjectURL(capturePreview);
            capturePreview = null;
            el('attendance-profile').hidden = true;
            el('attendance-timetable').hidden = true;
        }
        pinInput.value = '';
        updateClock();
    }
    function updateClock() {
        const now = new Date(serverTime + performance.now() - started);
        const zone = {timeZone: 'Asia/Manila'};
        el('current-date').textContent = now.toLocaleDateString('en-US', {...zone, year: 'numeric', month: 'long', day: 'numeric'});
        el('current-time').textContent = now.toLocaleTimeString('en-US', {...zone, hour12: true});
        const hour = Number(new Intl.DateTimeFormat('en-GB', {...zone, hour: '2-digit', hourCycle: 'h23'}).format(now));
        el('attendance-am').hidden = hour >= 12;
        el('attendance-pm').hidden = hour < 12;
        buttons.forEach(button => { button.disabled = busy; });
        root.querySelectorAll('[data-delete-time]').forEach(button => { button.disabled = busy || !deleteToken; });
        idInput.disabled = pinInput.disabled = busy;
    }
    async function request(data) {
        data.append('csrf', root.dataset.csrf);
        const response = await fetch(root.dataset.api, {method: 'POST', body: data, credentials: 'same-origin'});
        let result;
        try { result = await response.json(); } catch (_) { throw new Error('The server returned an invalid response. Please contact your administrator.'); }
        if (!response.ok || result.status !== 'success') {
            if (response.status === 401 || response.status === 403) reset();
            throw new Error(result.message || 'Attendance request failed.');
        }
        return result;
    }
    for (const input of [idInput, pinInput]) {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '');
            deleteToken = null;
            el('attendance-profile').hidden = true;
            el('attendance-timetable').hidden = true;
            if (verified) reset();
        });
    }
    el('attendance-credentials').addEventListener('submit', event => event.preventDefault());
    async function verifyEmployee(action) {
        const data = new FormData();
        data.append('action', 'verify'); data.append('id', idInput.value); data.append('pin', pinInput.value);
        data.append('attendance_action', action);
        reset();
            message('Verifying employee…');
            const result = await request(data);
            verified = true;
            el('attendance-name').textContent = result.employee.name;
            el('attendance-office').textContent = result.employee.office;
            el('attendance-landmark').textContent = result.employee.landmark || 'Not specified';
            el('attendance-distance').textContent = 'Distance from geofence center: waiting for GPS…';
            const photo = el('attendance-photo');
            photo.hidden = !result.employee.photo;
            el('attendance-no-photo').hidden = !!result.employee.photo;
            photo.onerror = () => { photo.hidden = true; el('attendance-no-photo').hidden = false; };
            if (result.employee.photo) photo.src = result.employee.photo;
            else photo.removeAttribute('src');
            el('attendance-profile').hidden = false;
            deleteToken = result.delete_token;
            renderTimes(result.attendance);
            el('attendance-day').textContent = 'Attendance for ' + result.attendance.date;
            el('attendance-timetable').hidden = false;
            expiry = setTimeout(() => { reset(); message('Verification expired. Enter your credentials again.', true); }, 120000);
            if (result.duplicate_message) {
                el('attendance-distance').textContent = '';
                throw new Error(result.duplicate_message);
            }
            return result.employee.geofence;
    }
    function displayDistance(location, geofence) {
        const numeric = value => value !== null && value !== undefined && String(value).trim() !== '' && Number.isFinite(Number(value));
        const latitude = Number(geofence?.latitude), longitude = Number(geofence?.longitude);
        const exempt = String(geofence?.workLocation) === '0' ? ' On-Field — exempt from geofencing.' : '';
        if (!numeric(geofence?.latitude) || !numeric(geofence?.longitude) || Math.abs(latitude) > 90 || Math.abs(longitude) > 180) {
            el('attendance-distance').textContent = 'Distance unavailable: office coordinates are not configured.' + exempt;
            return;
        }
        const radians = degrees => degrees * Math.PI / 180;
        const a = Math.sin(radians(location.latitude - latitude) / 2) ** 2 +
            Math.cos(radians(latitude)) * Math.cos(radians(location.latitude)) * Math.sin(radians(location.longitude - longitude) / 2) ** 2;
        const distance = 6371000 * 2 * Math.asin(Math.sqrt(Math.max(0, Math.min(1, a))));
        const radius = numeric(geofence.radius) && Number(geofence.radius) >= 0 && !exempt
            ? ` Allowed radius: ${Number(geofence.radius).toLocaleString('en-US')} meters.` : '';
        el('attendance-distance').textContent = `Distance from geofence center: approximately ${distance.toLocaleString('en-US', {minimumFractionDigits: 1, maximumFractionDigits: 1})} meters.` + radius + exempt;
    }
    async function captureLocation() {
        if (!window.isSecureContext || !navigator.geolocation) {
            throw new Error('Location access requires HTTPS or localhost and a supported browser.');
        }
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(position => {
                const {latitude, longitude} = position.coords;
                if (!Number.isFinite(latitude) || !Number.isFinite(longitude) || Math.abs(latitude) > 90 || Math.abs(longitude) > 180) {
                    reject(new Error('Invalid location received. Please try again.'));
                    return;
                }
                resolve({latitude, longitude});
            }, error => {
                const errors = {
                    1: 'Allow location access to record attendance.',
                    2: 'Your location is unavailable. Enable location services and try again.',
                    3: 'Location request timed out. Please try again.'
                };
                reject(new Error(errors[error.code] || 'Could not get your location. Please try again.'));
            }, {enableHighAccuracy: true, maximumAge: 0, timeout: 20000});
        });
    }
    async function captureFace() {
        if (!window.isSecureContext || !navigator.mediaDevices?.getUserMedia) {
            throw new Error('Camera access requires HTTPS or localhost and a supported browser.');
        }
        if (!window.faceapi) throw new Error('Face detection could not load. Please reload the page.');
        if (!modelPromise) {
            modelPromise = faceapi.nets.tinyFaceDetector.loadFromUri(root.dataset.models).catch(error => { modelPromise = null; throw error; });
        }
        await modelPromise;
        stream = await navigator.mediaDevices.getUserMedia({video: {width: {ideal: 640}, height: {ideal: 480}}, audio: false});
        video.srcObject = stream;
        await video.play();
        const canvas = document.createElement('canvas');
        // Detect on the exact still frame that will be uploaded.
        for (let attempt = 0; attempt < 8; attempt++) {
            if (!verified) throw new Error('Please verify your credentials again.');
            if (video.readyState >= 2 && video.videoWidth) {
                canvas.width = 640;
                canvas.height = Math.round(video.videoHeight * 640 / video.videoWidth);
                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                const face = await faceapi.detectSingleFace(canvas, new faceapi.TinyFaceDetectorOptions({inputSize: 320, scoreThreshold: 0.5}));
                if (face) return await new Promise((resolve, reject) => canvas.toBlob(blob => blob ? resolve(blob) : reject(new Error('Camera capture failed.')), 'image/jpeg', 0.9));
            }
            await new Promise(resolve => setTimeout(resolve, 250));
        }
        throw new Error('No human face detected. Please face the unit’s camera to Time-in or Time-out.');
    }
    buttons.forEach(button => button.addEventListener('click', async () => {
        if (busy) return;
        if (!el('attendance-credentials').reportValidity()) return;
        if (!/^[0-9]+$/.test(idInput.value) || !/^[0-9]+$/.test(pinInput.value)) {
            message('Employee ID and PIN must contain numbers only.', true);
            return;
        }
        busy = true; updateClock();
        try {
            const geofence = await verifyEmployee(button.dataset.action);
            message('Getting your location. Please allow location access…');
            const location = await captureLocation();
            if (!verified) throw new Error('Please verify your credentials again.');
            displayDistance(location, geofence);
            message('Checking camera. Please face the camera…');
            const blob = await captureFace();
            if (!verified) throw new Error('Please verify your credentials again.');
            const data = new FormData();
            data.append('action', button.dataset.action); data.append('capture', blob, 'capture.jpg');
            data.append('latitude', location.latitude);
            data.append('longitude', location.longitude);
            message('Saving attendance…');
            const result = await request(data);
            capturePreview = URL.createObjectURL(blob);
            const photo = el('attendance-photo');
            const showCapture = () => {
                photo.onerror = null;
                photo.src = capturePreview;
                photo.hidden = false;
                el('attendance-no-photo').hidden = true;
            };
            photo.onerror = showCapture;
            if (photo.hidden) showCapture();
            deleteToken = result.delete_token;
            renderTimes(result.attendance);
            el('attendance-day').textContent = 'Attendance for ' + result.attendance.date;
            el('attendance-timetable').hidden = false;
            el('attendance-profile').hidden = false;
            reset(true); idInput.value = ''; message(result.message);
        } catch (error) {
            if (el('attendance-distance').textContent === 'Distance from geofence center: waiting for GPS…') {
                el('attendance-distance').textContent = 'Distance unavailable: GPS location was not obtained.';
            }
            const cameraErrors = {NotAllowedError: 'Allow camera access to record attendance.', NotFoundError: 'No camera found. Connect the unit’s primary camera.', NotReadableError: 'The camera is busy or unavailable. Close other apps using it.'};
            message(cameraErrors[error.name] || error.message || 'Camera detection failed. Please try again.', true);
        } finally { verified = false; clearTimeout(expiry); stopCamera(); busy = false; updateClock(); }
    }));
    window.addEventListener('pagehide', reset);
    document.addEventListener('visibilitychange', () => { if (document.hidden) reset(); });
    updateClock(); setInterval(updateClock, 1000);
})();

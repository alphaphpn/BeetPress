// ─── App State ────────────────────────────────────────────────────────────────
const State = {
  view: "dashboard",       // 'dashboard' | 'server' | 'rooms' | 'room-detail'
  selectedServerId: null,
  selectedRoomId: null,
  cameraFilter: "all",
  cameraSearch: "",
  modalCamera: null,
  addCameraServerId: null,
  sidebarOpen: true,
  // Viewer
  viewerCameraId: null,
  viewerStream: null,
  viewerClockInterval: null,
  gridStreams: {},
  gridLayout: 4,
  // Network scan
  scanning: false,
};

// ─── Init ─────────────────────────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", async () => {
  localStorage.removeItem("cctv_db_v1");
  await initDB();
  document.getElementById("app-loader").style.display = "none";
  renderSidebar();
  renderDashboard();
  setupGlobalListeners();
  autoStartRelays();
});

// ─── Sidebar ──────────────────────────────────────────────────────────────────
function renderSidebar() {
  const list = document.getElementById("sidebar-server-list");
  list.innerHTML = DB.servers
    .map((srv) => {
      const stats = serverStats(srv.id);
      return `
      <li class="sidebar-server-item ${State.selectedServerId === srv.id ? "active" : ""}"
          onclick="navigateToServer('${srv.id}')">
        <span class="status-dot ${srv.status}"></span>
        <span class="srv-label">
          <span class="srv-name">${srv.name}</span>
          <span class="srv-meta">${stats.total} cam${stats.total !== 1 ? "s" : ""} &bull; ${srv.ip}</span>
        </span>
      </li>`;
    })
    .join("");

  // update total counts in header
  const totalCams = DB.cameras.length;
  const activeCams = DB.cameras.filter((c) => c.status === "active").length;
  document.getElementById("sidebar-total-cams").textContent = totalCams;
  document.getElementById("sidebar-active-cams").textContent = activeCams;
}

// ─── Dashboard View ───────────────────────────────────────────────────────────
function showView(name) {
  ["view-dashboard","view-server","view-rooms","view-room-detail","view-network"].forEach(id =>
    document.getElementById(id).classList.add("hidden")
  );
  document.getElementById("view-" + name).classList.remove("hidden");
}

function renderDashboard() {
  State.view = "dashboard";
  State.selectedServerId = null;
  State.selectedRoomId   = null;

  showView("dashboard");
  document.getElementById("breadcrumb-server").classList.add("hidden");
  document.getElementById("breadcrumb-room").classList.add("hidden");
  document.getElementById("page-title").textContent = "Server Dashboard";

  renderServerCards();
  renderSidebar();
  updateGlobalStats();
}

function renderServerCards() {
  const container = document.getElementById("server-cards");
  if (DB.servers.length === 0) {
    container.innerHTML = `
      <div class="empty-state" style="grid-column:1/-1;padding:60px 20px">
        <div class="empty-icon">🖥️</div>
        <div style="font-size:16px;font-weight:600;margin-bottom:6px">No servers yet</div>
        <div style="color:var(--text3);margin-bottom:16px">Add your first server to get started.</div>
        <button class="btn btn-primary" onclick="openAddServerModal()">➕ Add Server</button>
      </div>`;
    return;
  }
  container.innerHTML = DB.servers
    .map((srv) => {
      const stats = serverStats(srv.id);
      const statusLabel = { online: "Online", offline: "Offline", warning: "Warning" }[srv.status];
      const cpuBar = srv.status === "offline" ? "" : `
        <div class="resource-row"><span>CPU</span>
          <div class="bar-wrap"><div class="bar ${barClass(srv.cpu)}" style="width:${srv.cpu}%"></div></div>
          <span>${srv.cpu}%</span></div>`;
      const ramBar = srv.status === "offline" ? "" : `
        <div class="resource-row"><span>RAM</span>
          <div class="bar-wrap"><div class="bar ${barClass(srv.ram)}" style="width:${srv.ram}%"></div></div>
          <span>${srv.ram}%</span></div>`;
      const storBar = `
        <div class="resource-row"><span>HDD</span>
          <div class="bar-wrap"><div class="bar ${barClass(srv.storage)}" style="width:${srv.storage}%"></div></div>
          <span>${srv.storage}%</span></div>`;

      return `
      <div class="server-card ${srv.status}" onclick="navigateToServer('${srv.id}')">
        <div class="card-header">
          <div class="card-title-row">
            <div class="srv-icon-wrap"><i class="icon-server"></i></div>
            <div>
              <div class="card-title">${srv.name}</div>
              <div class="card-subtitle">${srv.ip} &bull; ${srv.location}</div>
            </div>
          </div>
          <span class="badge badge-${srv.status}">${statusLabel}</span>
        </div>

        <div class="cam-stats">
          <div class="cam-stat"><span class="num green">${stats.active}</span><span class="lbl">Active</span></div>
          <div class="cam-stat"><span class="num red">${stats.inactive + stats.offline}</span><span class="lbl">Inactive</span></div>
          <div class="cam-stat"><span class="num yellow">${stats.warning}</span><span class="lbl">Warning</span></div>
          <div class="cam-stat"><span class="num blue">${stats.assigned}</span><span class="lbl">Assigned</span></div>
          <div class="cam-stat"><span class="num gray">${stats.unassigned}</span><span class="lbl">Free</span></div>
          <div class="cam-stat"><span class="num">${stats.total}</span><span class="lbl">Total</span></div>
        </div>

        <div class="resources">
          ${cpuBar}${ramBar}${storBar}
        </div>

        <div class="card-footer">
          <span class="os-label">${srv.os}</span>
          <span class="uptime-label">Uptime: ${srv.uptime}</span>
          <button class="btn btn-sm btn-danger-ghost" style="margin-left:auto"
            onclick="confirmDeleteServer('${srv.id}', event)">🗑 Delete</button>
        </div>
      </div>`;
    })
    .join("");
}

function barClass(val) {
  if (val >= 85) return "danger";
  if (val >= 65) return "warn";
  return "ok";
}

function updateGlobalStats() {
  const all = DB.cameras;
  document.getElementById("stat-servers").textContent = DB.servers.length;
  document.getElementById("stat-cameras").textContent = all.length;
  document.getElementById("stat-active").textContent = all.filter((c) => c.status === "active").length;
  document.getElementById("stat-assigned").textContent = all.filter((c) => c.assignedRoomId !== null).length;
  document.getElementById("stat-unassigned").textContent = all.filter((c) => c.assignedRoomId === null).length;
  document.getElementById("stat-rooms").textContent = DB.rooms.length;
}

// ─── Server Detail View ───────────────────────────────────────────────────────
function navigateToServer(serverId) {
  State.selectedServerId = serverId;
  State.view = "server";
  State.cameraFilter = "all";
  State.cameraSearch = "";

  showView("server");
  document.getElementById("breadcrumb-server").classList.remove("hidden");
  document.getElementById("breadcrumb-room").classList.add("hidden");

  const srv = getServer(serverId);
  document.getElementById("page-title").textContent = srv.name;
  document.getElementById("breadcrumb-server-name").textContent = srv.name;
  document.getElementById("search-cameras").value = "";

  renderSidebar();
  renderServerDetail();
}

function renderServerDetail() {
  const srv = getServer(State.selectedServerId);
  const stats = serverStats(srv.id);

  // Server info panel
  document.getElementById("server-info-name").textContent = srv.name;
  document.getElementById("server-info-ip").textContent = srv.ip;
  document.getElementById("server-info-location").textContent = srv.location;
  document.getElementById("server-info-os").textContent = srv.os;
  document.getElementById("server-info-uptime").textContent = srv.uptime;
  document.getElementById("server-info-status").className = "badge badge-" + srv.status;
  document.getElementById("server-info-status").textContent =
    { online: "Online", offline: "Offline", warning: "Warning" }[srv.status];

  // Filter tabs counts
  const cams = getCamerasByServer(srv.id);
  document.getElementById("ftab-all").textContent = `All (${cams.length})`;
  document.getElementById("ftab-active").textContent = `Active (${cams.filter((c) => c.status === "active").length})`;
  document.getElementById("ftab-inactive").textContent = `Inactive (${cams.filter((c) => c.status !== "active").length})`;
  document.getElementById("ftab-assigned").textContent = `Assigned (${cams.filter((c) => c.assignedRoomId).length})`;
  document.getElementById("ftab-unassigned").textContent = `Unassigned (${cams.filter((c) => !c.assignedRoomId).length})`;

  renderCameraList();
}

function renderCameraList() {
  const cams = getCamerasByServer(State.selectedServerId);
  const search = State.cameraSearch.toLowerCase();

  let filtered = cams.filter((c) => {
    if (State.cameraFilter === "active" && c.status !== "active") return false;
    if (State.cameraFilter === "inactive" && c.status === "active") return false;
    if (State.cameraFilter === "assigned" && !c.assignedRoomId) return false;
    if (State.cameraFilter === "unassigned" && c.assignedRoomId) return false;
    if (search && !c.name.toLowerCase().includes(search) && !c.model.toLowerCase().includes(search)) return false;
    return true;
  });

  const container = document.getElementById("camera-list");

  if (filtered.length === 0) {
    container.innerHTML = `<div class="empty-state">
      <div class="empty-icon">&#x1F4F7;</div>
      <div>No cameras match your filter.</div>
    </div>`;
    return;
  }

  container.innerHTML = filtered
    .map((cam) => {
      const room = cam.assignedRoomId ? getRoom(cam.assignedRoomId) : null;
      const roomLabel = room
        ? `<span class="room-tag assigned">${room.name} <small>(${room.building}, Fl. ${room.floor})</small></span>`
        : `<span class="room-tag unassigned">Unassigned</span>`;

      const connInfo = cam.type === "IP"
        ? `<span class="conn-info"><span class="type-badge ip">IP</span> ${cam.ip}:${cam.port}</span>`
        : `<span class="conn-info"><span class="type-badge usb">USB</span> Direct</span>`;

      const canView = cam.status !== "offline";
      const viewBtn = canView
        ? `<button class="btn btn-sm btn-view" onclick="openViewer('${cam.id}', event)" title="View live feed">▶ View</button>`
        : `<button class="btn btn-sm btn-view" disabled title="Camera offline" style="opacity:.4;cursor:not-allowed;">▶ View</button>`;

      const actionBtn = cam.assignedRoomId
        ? `<button class="btn btn-sm btn-outline" onclick="openAssignModal('${cam.id}', event)">Change Room</button>
           <button class="btn btn-sm btn-ghost" onclick="confirmUnassign('${cam.id}', event)">Unassign</button>`
        : `<button class="btn btn-sm btn-primary" onclick="openAssignModal('${cam.id}', event)">Assign to Room</button>`;
      const deleteBtn = `<button class="btn btn-sm btn-danger-ghost" onclick="confirmDeleteCamera('${cam.id}', event)" title="Delete camera">🗑</button>`;

      return `
      <div class="camera-row ${cam.status}">
        <div class="cam-icon-col">
          <div class="cam-icon ${cam.type === 'USB' ? 'usb' : 'ip'}">
            ${cam.type === "USB" ? "&#x1F4BB;" : "&#x1F4F9;"}
          </div>
        </div>
        <div class="cam-main">
          <div class="cam-name-row">
            <span class="cam-name">${cam.name}</span>
            <span class="status-badge ${cam.status}">${capitalize(cam.status)}</span>
          </div>
          <div class="cam-details">
            ${connInfo}
            <span class="cam-model">${cam.model}</span>
            <span class="cam-res">${cam.resolution} &bull; ${cam.fps}fps</span>
          </div>
          <div class="cam-assignment">${roomLabel}</div>
        </div>
        <div class="cam-actions">${viewBtn}${actionBtn}${deleteBtn}</div>
      </div>`;
    })
    .join("");
}

// ─── Assign Modal ─────────────────────────────────────────────────────────────
function openAssignModal(cameraId, evt) {
  if (evt) evt.stopPropagation();
  State.modalCamera = cameraId;
  const cam  = getCamera(cameraId);

  document.getElementById("modal-cam-name").textContent  = cam.name;
  document.getElementById("modal-cam-model").textContent = cam.model;
  document.getElementById("modal-cam-type").textContent  = cam.type;

  const roomList = document.getElementById("modal-room-list");

  // ── Already assigned: block re-assignment, show unassign prompt ──
  if (cam.assignedRoomId) {
    const currentRoom = getRoom(cam.assignedRoomId);
    document.getElementById("modal-assign-footer").classList.add("hidden");
    document.getElementById("modal-unassign-footer").classList.remove("hidden");
    roomList.innerHTML = `
      <div class="assign-locked-msg">
        <div class="alm-icon">🔒</div>
        <div class="alm-body">
          <strong>${cam.name}</strong> is already assigned to
          <strong>${currentRoom ? currentRoom.name : "a room"}</strong>.
          <br>A camera can only be in <strong>one room</strong>.
          Unassign it first to move it elsewhere.
        </div>
      </div>`;
    document.getElementById("modal-selected-room").value = "";
  } else {
    // ── Not assigned: show empty rooms to pick from ──
    document.getElementById("modal-assign-footer").classList.remove("hidden");
    document.getElementById("modal-unassign-footer").classList.add("hidden");

    const emptyRooms = DB.rooms.filter(r => !getRoomCamera(r.id));

    if (emptyRooms.length === 0) {
      roomList.innerHTML = `<div class="empty-state" style="padding:24px">
        <div class="empty-icon">🚪</div>
        <div>All rooms already have a camera assigned.<br>Free up a room first.</div>
      </div>`;
    } else {
      roomList.innerHTML = emptyRooms.map(r => `
        <div class="room-option" onclick="selectRoomOption('${r.id}', this)">
          <div class="room-option-info">
            <span class="room-option-name">${r.name}</span>
            <span class="room-option-meta">${r.building} &bull; Floor ${r.floor}</span>
          </div>
          <div class="room-option-right"><span class="room-cam-count">Empty</span></div>
        </div>`).join("");
    }
    document.getElementById("modal-selected-room").value = "";
  }

  document.getElementById("assign-modal").classList.remove("hidden");
  document.getElementById("modal-overlay").classList.remove("hidden");
}

function selectRoomOption(roomId, el) {
  document.querySelectorAll(".room-option").forEach(e => e.classList.remove("selected"));
  el.classList.add("selected");
  document.getElementById("modal-selected-room").value = roomId;
}

function confirmAssign() {
  const roomId = document.getElementById("modal-selected-room").value;
  if (!roomId) { showToast("Please select a room.", "warn"); return; }
  const result = assignCameraToRoom(State.modalCamera, roomId);
  if (!result.ok) { showToast(result.msg, "warn"); return; }
  closeModal();
  if (State.view === "server") renderServerDetail();
  if (State.view === "room-detail") renderRoomDetail();
  renderSidebar();
  showToast("Camera assigned successfully.", "success");
}

function confirmUnassignFromModal() {
  unassignCamera(State.modalCamera);
  closeModal();
  if (State.view === "server") renderServerDetail();
  if (State.view === "room-detail") renderRoomDetail();
  renderSidebar();
  showToast("Camera unassigned.", "info");
}

function closeModal() {
  ["assign-modal","add-camera-modal","add-room-modal","rooms-modal","add-server-modal","room-camera-picker"].forEach((id) =>
    document.getElementById(id).classList.add("hidden")
  );
  document.getElementById("modal-overlay").classList.add("hidden");
  State.modalCamera = null;
  State.addCameraServerId = null;
}

// ─── Camera Viewer (single) ───────────────────────────────────────────────────

function openViewer(cameraId, evt) {
  if (evt) evt.stopPropagation();
  State.viewerCameraId = cameraId;
  const cam = getCamera(cameraId);

  document.getElementById("viewer-cam-name").textContent = cam.name;
  document.getElementById("viewer-cam-meta").textContent =
    `${cam.type} · ${cam.model} · ${cam.resolution} · ${cam.fps}fps`;
  document.getElementById("viewer-status-badge").className = "status-badge " + cam.status;
  document.getElementById("viewer-status-badge").textContent = capitalize(cam.status);

  // Stream URL config
  document.getElementById("viewer-stream-url").value = cam.streamUrl || "";
  document.getElementById("viewer-usb-section").classList.add("hidden");
  document.getElementById("viewer-ip-section").classList.add("hidden");
  document.getElementById("viewer-url-section").classList.toggle("hidden", cam.type === "USB");

  // Camera info panel
  const room = cam.assignedRoomId ? getRoom(cam.assignedRoomId) : null;
  const srv  = getServer(cam.serverId);
  document.getElementById("viewer-info-grid").innerHTML = [
    ["Server",     srv ? srv.name : "—"],
    ["Type",       cam.type],
    ["Address",    cam.ip ? `${cam.ip}:${cam.port}` : "USB Direct"],
    ["Model",      cam.model],
    ["Resolution", cam.resolution],
    ["FPS",        cam.fps],
    ["Room",       room ? `${room.name} (${room.building})` : "Unassigned"],
    ["Status",     capitalize(cam.status)],
  ].map(([k, v]) => `<span class="vi-key">${k}</span><span class="vi-val">${v}</span>`).join("");

  startViewerClock();
  loadViewerFeed(cam);

  document.getElementById("camera-viewer").classList.remove("hidden");
}

const isLocalHost = location.hostname === "localhost" || location.hostname === "127.0.0.1";

function loadViewerFeed(cam) {
  const screen = document.getElementById("viewer-screen");
  screen.innerHTML = "";

  if (cam.type === "USB") {
    if (isLocalHost) {
      // Host machine: capture USB camera and relay frames so remote viewers can see it
      document.getElementById("viewer-usb-section").classList.remove("hidden");
      startUSBFeed(cam, screen, "viewer-stream", "viewer-device-select");
    } else {
      // Remote viewer: show the MJPEG relay stream from the host's XAMPP
      document.getElementById("viewer-usb-section").classList.add("hidden");
      document.getElementById("viewer-url-section").classList.add("hidden");
      startPollingFeed(cam.id, screen);
    }
  } else {
    document.getElementById("viewer-ip-section").classList.remove("hidden");
    if (cam.streamUrl) {
      loadMJPEGFeed(cam, screen);
    } else {
      showNoSignal(screen, cam, "ip");
    }
  }
}

function startUSBFeed(cam, container, streamKey, selectId) {
  if (!navigator.mediaDevices?.getUserMedia) {
    showNoSignal(container, cam, "usb-unsupported");
    return;
  }

  navigator.mediaDevices.enumerateDevices().then((devices) => {
    const videoDevices = devices.filter((d) => d.kind === "videoinput");
    const sel = document.getElementById(selectId);
    if (sel) {
      sel.innerHTML = videoDevices.map((d, i) =>
        `<option value="${d.deviceId}">${d.label || "Camera " + (i + 1)}</option>`
      ).join("");
      if (cam.usbDeviceId) sel.value = cam.usbDeviceId;
    }

    // If auto-relay already has a live stream for this camera, reuse it
    const relay = USBRelays[cam.id];
    if (relay?.stream?.active) {
      const video = document.createElement("video");
      video.autoplay = true; video.muted = true; video.playsInline = true;
      video.srcObject = relay.stream;
      video.style.cssText = "width:100%;height:100%;object-fit:cover;border-radius:0;";
      container.innerHTML = "";
      container.appendChild(video);
      State[streamKey] = relay.stream;
    } else {
      const deviceId = sel?.value || videoDevices[0]?.deviceId;
      requestUSBStream(cam, container, streamKey, deviceId);
    }
  }).catch(() => requestUSBStream(cam, container, streamKey, undefined));
}

function requestUSBStream(cam, container, streamKey, deviceId) {
  if (State[streamKey] && !isRelayStream(State[streamKey])) {
    State[streamKey].getTracks().forEach((t) => t.stop());
    State[streamKey] = null;
  }

  const constraints = { video: deviceId ? { deviceId: { exact: deviceId } } : true, audio: false };

  navigator.mediaDevices.getUserMedia(constraints)
    .then((stream) => {
      State[streamKey] = stream;
      const video = document.createElement("video");
      video.autoplay = true; video.muted = true; video.playsInline = true;
      video.srcObject = stream;
      video.style.cssText = "width:100%;height:100%;object-fit:cover;border-radius:0;";
      container.innerHTML = "";
      container.appendChild(video);

      if (isLocalHost) {
        startUSBRelay(cam.id, stream, video);
        const c = getCamera(cam.id);
        if (c && c.streamUrl !== `api/stream.php?camId=${cam.id}`) {
          c.streamUrl = `api/stream.php?camId=${cam.id}`;
          saveDB();
        }
      }
    })
    .catch(() => showNoSignal(container, cam, "usb-denied"));
}

// Returns true if this stream belongs to an auto-relay (don't stop it on viewer close)
function isRelayStream(stream) {
  return Object.values(USBRelays).some(r => r.stream === stream);
}

function loadMJPEGFeed(cam, container) {
  const img = document.createElement("img");
  img.style.cssText = "width:100%;height:100%;object-fit:cover;";
  img.alt = "Camera feed";
  img.onload = () => {};
  img.onerror = () => showNoSignal(container, cam, "ip-error");
  img.src = cam.streamUrl;
  container.innerHTML = "";
  container.appendChild(img);
}

// JS-polling feed for remote USB viewers — refreshes an <img> src every ~150ms.
// Uses direct img.src (no fetch/blob) so it works on all browsers including mobile Safari.
// Returns a stop function; the poll stops automatically when container leaves the DOM.
function startPollingFeed(camId, container) {
  const img = document.createElement("img");
  img.style.cssText = "width:100%;height:100%;object-fit:cover;";
  img.alt = "";
  container.innerHTML = "";
  container.appendChild(img);

  let active = true;
  let pending = false;

  function poll() {
    if (!active || !document.contains(container)) { active = false; return; }
    if (!pending) {
      pending = true;
      img.onload  = () => { pending = false; if (active) setTimeout(poll, 100); else active = false; };
      img.onerror = () => { pending = false; if (active) setTimeout(poll, 300); else active = false; };
      img.src = `api/stream.php?single=1&camId=${encodeURIComponent(camId)}&t=${Date.now()}`;
    } else {
      setTimeout(poll, 50);
    }
  }

  poll();
  return () => { active = false; };
}

function showNoSignal(container, cam, reason) {
  const msgs = {
    "ip":                  { icon: "📡", title: "No Stream URL",     sub: "Enter the camera's MJPEG URL above and click Load." },
    "ip-error":            { icon: "⚠️", title: "Stream Error",      sub: "Could not connect to the MJPEG URL. Check the URL and network." },
    "usb-denied":          { icon: "🔒", title: "Access Denied",     sub: "Browser blocked camera access. Allow camera permission and try again." },
    "usb-unsupported":     { icon: "❌", title: "Not Supported",     sub: "This browser does not support webcam access." },
    "usb-remote-waiting":  { icon: "⏳", title: "Waiting for host",  sub: "The host must have this camera open in their dashboard for you to view it." },
    "offline":             { icon: "📵", title: "Camera Offline",    sub: "This camera is offline. Check the device connection." },
  };
  const m = msgs[reason] || msgs["ip"];
  container.innerHTML = `
    <div class="no-signal">
      <div class="ns-icon">${m.icon}</div>
      <div class="ns-title">${m.title}</div>
      <div class="ns-sub">${cam.ip ? cam.ip + ":" + cam.port : cam.name}</div>
      <div class="ns-hint">${m.sub}</div>
    </div>`;
}

function applyStreamUrl() {
  const url = document.getElementById("viewer-stream-url").value.trim();
  const cam = getCamera(State.viewerCameraId);
  cam.streamUrl = url;
  const screen = document.getElementById("viewer-screen");
  if (url) {
    loadMJPEGFeed(cam, screen);
  } else {
    showNoSignal(screen, cam, "ip");
  }
}

function changeUSBDevice() {
  const cam = getCamera(State.viewerCameraId);
  const sel = document.getElementById("viewer-device-select");
  cam.usbDeviceId = sel.value;
  const screen = document.getElementById("viewer-screen");
  requestUSBStream(cam, screen, "viewerStream", sel.value);
}

// ─── USB Relay (host machine only) ────────────────────────────────────────────
// Auto-starts on localhost so remote viewers can always see USB cameras
// without the host needing to open the viewer first.
// Maps camId → { timer, stream, video }
const USBRelays = {};

function startUSBRelay(camId, stream, videoEl) {
  stopUSBRelay(camId);
  const canvas = document.createElement("canvas");
  const ctx    = canvas.getContext("2d", { willReadFrequently: true });
  let pushing  = false; // prevents frame backlog that causes freezing

  const initSize = () => {
    if (videoEl.videoWidth) {
      canvas.width  = videoEl.videoWidth;
      canvas.height = videoEl.videoHeight;
    }
  };
  if (videoEl.readyState >= 1) initSize();
  else videoEl.addEventListener("loadedmetadata", initSize, { once: true });

  const timer = setInterval(() => {
    if (pushing || !canvas.width || !videoEl.videoWidth) return;
    ctx.drawImage(videoEl, 0, 0, canvas.width, canvas.height);
    canvas.toBlob(async (blob) => {
      if (!blob) return;
      pushing = true;
      const form = new FormData();
      form.append("camId", camId);
      form.append("frame", blob, "frame.jpg");
      try { await fetch("api/push_frame.php", { method: "POST", body: form }); } catch (_) {}
      pushing = false;
    }, "image/jpeg", 0.7);
  }, 150); // ~6-7 fps — stable, won't saturate the connection

  USBRelays[camId] = { timer, stream, video: videoEl };
}

function stopUSBRelay(camId) {
  const r = USBRelays[camId];
  if (!r) return;
  clearInterval(r.timer);
  // Only release stream if it's a background hidden video (not viewer's video)
  if (r.video?.style.position === "fixed") {
    r.stream?.getTracks().forEach(t => t.stop());
    r.video.remove();
  }
  delete USBRelays[camId];
}

// Auto-start relays for all USB cameras on the host machine (localhost only).
// This means remote viewers can see USB cameras immediately without the host
// having to open the viewer first.
async function autoStartRelays() {
  if (!isLocalHost || !navigator.mediaDevices?.getUserMedia) return;
  const usbCams = DB.cameras.filter(c => c.type === "USB" && c.status !== "offline");
  if (!usbCams.length) return;

  let devices = [];
  try {
    // Trigger permission prompt once, then enumerate
    const tmp = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
    tmp.getTracks().forEach(t => t.stop());
    devices = (await navigator.mediaDevices.enumerateDevices()).filter(d => d.kind === "videoinput");
  } catch (_) { return; }

  let dirty = false;
  for (let i = 0; i < usbCams.length; i++) {
    const cam = usbCams[i];
    if (USBRelays[cam.id]?.stream?.active) continue; // already relaying

    const target = cam.usbDeviceId
      ? devices.find(d => d.deviceId === cam.usbDeviceId)
      : devices[i % devices.length];
    if (!target) continue;

    try {
      const stream = await navigator.mediaDevices.getUserMedia({
        video: { deviceId: { exact: target.deviceId } }, audio: false,
      });
      // Hidden background video — no visual output on host
      const video = document.createElement("video");
      video.autoplay = true; video.muted = true; video.playsInline = true;
      video.srcObject = stream;
      // Positioned just off the left edge — large enough for browser to keep rendering
      video.style.cssText = "position:fixed;left:-321px;top:0;width:320px;height:240px;pointer-events:none;";
      document.body.appendChild(video);
      video.play().catch(() => {});
      // Wait for canplay (first frame available), not just loadedmetadata
      await new Promise(r => { video.oncanplay = r; setTimeout(r, 5000); });

      if (!cam.usbDeviceId) { cam.usbDeviceId = target.deviceId; dirty = true; }
      if (cam.streamUrl !== `api/stream.php?camId=${cam.id}`) {
        cam.streamUrl = `api/stream.php?camId=${cam.id}`; dirty = true;
      }
      startUSBRelay(cam.id, stream, video);
    } catch (e) {
      console.warn(`Auto-relay failed for "${cam.name}":`, e.message);
    }
  }
  if (dirty) saveDB();
}

window.addEventListener("beforeunload", () => {
  Object.keys(USBRelays).forEach(stopUSBRelay);
});

function closeViewer() {
  if (State.viewerStream) {
    // Don't stop a background relay stream — it should keep running
    if (!isRelayStream(State.viewerStream)) {
      State.viewerStream.getTracks().forEach((t) => t.stop());
    }
    State.viewerStream = null;
  }
  clearInterval(State.viewerClockInterval);
  State.viewerClockInterval = null;
  State.viewerCameraId = null;
  document.getElementById("viewer-screen").innerHTML = "";
  document.getElementById("camera-viewer").classList.add("hidden");
}

function toggleViewerFullscreen() {
  const el = document.getElementById("camera-viewer");
  if (!document.fullscreenElement) {
    el.requestFullscreen && el.requestFullscreen();
  } else {
    document.exitFullscreen && document.exitFullscreen();
  }
}

function startViewerClock() {
  clearInterval(State.viewerClockInterval);
  function tick() {
    const el = document.getElementById("viewer-clock");
    if (el) el.textContent = new Date().toLocaleString();
  }
  tick();
  State.viewerClockInterval = setInterval(tick, 1000);
}

// ─── Grid Viewer (multi-camera) ───────────────────────────────────────────────

function openGridViewer() {
  const cams = getCamerasByServer(State.selectedServerId).filter((c) => c.status !== "offline");
  State.gridLayout = cams.length <= 4 ? 4 : 9;

  renderGridCells(cams);
  document.getElementById("grid-viewer").classList.remove("hidden");
  startGridClock();
}

function renderGridCells(cams) {
  const grid = document.getElementById("grid-screen");
  const cols = State.gridLayout === 4 ? 2 : 3;
  const slots = State.gridLayout;

  grid.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;
  grid.innerHTML = "";

  for (let i = 0; i < slots; i++) {
    const cam = cams[i];
    const cell = document.createElement("div");
    cell.className = "grid-cell";
    cell.id = `grid-cell-${i}`;

    if (!cam) {
      cell.innerHTML = `<div class="no-signal" style="height:100%"><div class="ns-icon" style="font-size:28px">📷</div><div class="ns-sub">Empty slot</div></div>`;
    } else {
      cell.innerHTML = `
        <div class="grid-cell-overlay">
          <span class="grid-cam-name">${cam.name}</span>
          <span class="status-badge ${cam.status}" style="font-size:10px">${capitalize(cam.status)}</span>
        </div>
        <div class="grid-cell-screen" id="grid-screen-${i}" style="width:100%;height:100%;"></div>`;

      // Load feed after inserting into DOM
      setTimeout(() => {
        const container = document.getElementById(`grid-screen-${i}`);
        if (!container) return;
        if (cam.type === "USB") {
          if (isLocalHost) {
            const relay = USBRelays[cam.id];
            if (relay?.stream?.active) {
              // Reuse background relay stream
              const video = document.createElement("video");
              video.autoplay = true; video.muted = true; video.playsInline = true;
              video.srcObject = relay.stream;
              video.style.cssText = "width:100%;height:100%;object-fit:cover;";
              container.innerHTML = ""; container.appendChild(video);
              State[`gridStream_${cam.id}`] = relay.stream;
            } else {
              requestUSBStream(cam, container, `gridStream_${cam.id}`, cam.usbDeviceId || undefined);
            }
          } else {
            // Remote viewer: JS polling relay stream
            startPollingFeed(cam.id, container);
          }
        } else if (cam.streamUrl) {
          loadMJPEGFeed(cam, container);
        } else {
          showNoSignal(container, cam, "ip");
        }
      }, 80);

      cell.onclick    = (e) => { e.stopPropagation(); openGridDetail(cam.id, cell); };
      cell.ondblclick = (e) => { e.stopPropagation(); closeGridDetail(); openViewer(cam.id); };
    }
    grid.appendChild(cell);
  }
}

// ─── Grid Detail Panel ────────────────────────────────────────────────────────
function openGridDetail(cameraId, cellEl) {
  const cam  = getCamera(cameraId);
  const srv  = getServer(cam.serverId);
  const room = cam.assignedRoomId ? getRoom(cam.assignedRoomId) : null;

  // Highlight selected cell
  document.querySelectorAll(".grid-cell").forEach(c => c.classList.remove("grid-cell-selected"));
  if (cellEl) cellEl.classList.add("grid-cell-selected");

  document.getElementById("gdp-icon").textContent   = cam.type === "USB" ? "💻" : "📹";
  document.getElementById("gdp-name").textContent   = cam.name;
  document.getElementById("gdp-sub").textContent    = `${cam.model}  ·  ${cam.type === "IP" ? cam.ip + ":" + cam.port : "USB Direct"}`;
  const badge = document.getElementById("gdp-status");
  badge.className   = "status-badge " + cam.status;
  badge.textContent = capitalize(cam.status);

  document.getElementById("gdp-info-grid").innerHTML = [
    ["Server",     srv  ? srv.name                        : "—"],
    ["Room",       room ? `${room.name} (${room.building})` : "Unassigned"],
    ["Resolution", cam.resolution],
    ["FPS",        cam.fps],
    ["Type",       cam.type],
  ].map(([k, v]) => `<span class="vi-key">${k}</span><span class="vi-val">${v}</span>`).join("");

  const canView = cam.status !== "offline";
  document.getElementById("gdp-actions").innerHTML = `
    ${canView
      ? `<button class="btn btn-sm btn-view" onclick="closeGridDetail();openViewer('${cam.id}')">▶ Full View</button>`
      : ""}
    ${room
      ? `<button class="btn btn-sm btn-ghost" onclick="confirmUnassignFromRoom('${cam.id}')">✕ Remove from Room</button>`
      : `<button class="btn btn-sm btn-outline" onclick="closeGridDetail();openAssignModal('${cam.id}')">＋ Assign Room</button>`}
  `;

  document.getElementById("grid-detail-panel").classList.remove("hidden");
}

function closeGridDetail() {
  document.getElementById("grid-detail-panel").classList.add("hidden");
  document.querySelectorAll(".grid-cell").forEach(c => c.classList.remove("grid-cell-selected"));
}

function setGridLayout(n) {
  State.gridLayout = n;
  const cams = getCamerasByServer(State.selectedServerId).filter((c) => c.status !== "offline");
  stopAllGridStreams();
  renderGridCells(cams);
}

function stopAllGridStreams() {
  Object.keys(State.gridStreams).forEach((k) => {
    const s = State.gridStreams[k];
    if (s) s.getTracks().forEach((t) => t.stop());
  });
  State.gridStreams = {};
  // also stop streams stored under gridStream_ keys
  Object.keys(State).filter(k => k.startsWith("gridStream_")).forEach(k => {
    if (State[k]) { State[k].getTracks().forEach(t => t.stop()); State[k] = null; }
  });
}

function closeGridViewer() {
  stopAllGridStreams();
  clearInterval(State.gridClockInterval);
  State.gridClockInterval = null;
  document.getElementById("grid-screen").innerHTML = "";
  document.getElementById("grid-viewer").classList.add("hidden");
}

function startGridClock() {
  clearInterval(State.gridClockInterval);
  function tick() {
    const el = document.getElementById("grid-clock");
    if (el) el.textContent = new Date().toLocaleString();
  }
  tick();
  State.gridClockInterval = setInterval(tick, 1000);
}

function toggleGridFullscreen() {
  const el = document.getElementById("grid-viewer");
  if (!document.fullscreenElement) {
    el.requestFullscreen && el.requestFullscreen();
  } else {
    document.exitFullscreen && document.exitFullscreen();
  }
}

function confirmUnassign(cameraId, evt) {
  if (evt) evt.stopPropagation();
  if (!confirm("Remove this camera's room assignment?")) return;
  unassignCamera(cameraId);
  renderServerDetail();
  renderSidebar();
  showToast("Camera unassigned.", "info");
}

function confirmDeleteCamera(cameraId, evt) {
  if (evt) evt.stopPropagation();
  const cam = getCamera(cameraId);
  if (!confirm(`Delete camera "${cam.name}"? This cannot be undone.`)) return;
  deleteCamera(cameraId);
  renderServerDetail();
  renderSidebar();
  showToast(`Camera "${cam.name}" deleted.`, "info");
}

function confirmDeleteServer(serverId, evt) {
  if (evt) evt.stopPropagation();
  const srv = getServer(serverId);
  const camCount = getCamerasByServer(serverId).length;
  const warn = camCount > 0 ? `\n\n⚠️ This will also delete ${camCount} camera(s) connected to it.` : "";
  if (!confirm(`Delete server "${srv.name}"?${warn}`)) return;
  deleteServer(serverId);
  renderDashboard();
  showToast(`Server "${srv.name}" deleted.`, "info");
}

// ─── Rooms Management Modal ───────────────────────────────────────────────────
function openRoomsModal() {
  renderRoomsList();
  document.getElementById("rooms-modal").classList.remove("hidden");
  document.getElementById("modal-overlay").classList.remove("hidden");
}

function renderRoomsList() {
  const list = document.getElementById("rooms-manage-list");
  if (DB.rooms.length === 0) {
    list.innerHTML = `<div class="empty-state" style="padding:30px"><div class="empty-icon">🚪</div><div>No rooms added yet.</div></div>`;
    return;
  }
  list.innerHTML = DB.rooms.map((r) => {
    const camCount = DB.cameras.filter((c) => c.assignedRoomId === r.id).length;
    return `
    <div class="room-manage-row">
      <div class="rm-info">
        <span class="rm-name">${r.name}</span>
        <span class="rm-meta">${r.building} &bull; Floor ${r.floor} &bull; ${camCount} camera${camCount !== 1 ? "s" : ""}</span>
      </div>
      <button class="btn btn-sm btn-danger-ghost" onclick="confirmDeleteRoom('${r.id}')" title="Delete room">🗑 Delete</button>
    </div>`;
  }).join("");
}

function confirmDeleteRoom(roomId) {
  const room = getRoom(roomId);
  const camCount = DB.cameras.filter((c) => c.assignedRoomId === roomId).length;
  const warn = camCount > 0 ? `\n\n${camCount} camera(s) assigned to this room will be unassigned.` : "";
  if (!confirm(`Delete room "${room.name}"?${warn}`)) return;
  deleteRoom(roomId);
  renderRoomsList();
  renderSidebar();
  updateGlobalStats();
  if (State.view === "server") renderServerDetail();
  if (State.view === "rooms") renderRoomsGrid();
  if (State.view === "room-detail" && State.selectedRoomId === roomId) navigateToRooms();
  showToast(`Room "${room.name}" deleted.`, "info");
}

// ─── Add Camera Modal ─────────────────────────────────────────────────────────
function openAddCameraModal() {
  State.addCameraServerId = State.selectedServerId;
  const srv = getServer(State.selectedServerId);

  document.getElementById("add-cam-server-name").textContent = srv.name;
  document.getElementById("add-cam-type").value = "IP";
  document.getElementById("add-cam-name").value = "";
  document.getElementById("add-cam-ip").value = "";
  document.getElementById("add-cam-port").value = "554";
  document.getElementById("add-cam-model").value = "";
  document.getElementById("add-cam-res").value = "1080p";
  document.getElementById("add-cam-fps").value = "25";
  toggleIpFields();

  document.getElementById("add-camera-modal").classList.remove("hidden");
  document.getElementById("modal-overlay").classList.remove("hidden");
}

function toggleIpFields() {
  const type = document.getElementById("add-cam-type").value;
  const ipFields = document.getElementById("ip-fields");
  ipFields.style.display = type === "IP" ? "grid" : "none";
}

function submitAddCamera() {
  const name = document.getElementById("add-cam-name").value.trim();
  const type = document.getElementById("add-cam-type").value;
  const ip = document.getElementById("add-cam-ip").value.trim();
  const port = parseInt(document.getElementById("add-cam-port").value) || 554;
  const model = document.getElementById("add-cam-model").value.trim();
  const resolution = document.getElementById("add-cam-res").value;
  const fps = parseInt(document.getElementById("add-cam-fps").value) || 25;

  if (!name) { showToast("Camera name is required.", "warn"); return; }
  if (type === "IP" && !ip) { showToast("IP address is required.", "warn"); return; }

  const newId = "cam-" + Date.now();
  addCamera({
    id: newId,
    serverId: State.addCameraServerId,
    name,
    type,
    ip: type === "IP" ? ip : null,
    port: type === "IP" ? port : null,
    model: model || "Unknown Model",
    status: "active",
    assignedRoomId: null,
    resolution,
    fps,
  });

  closeModal();
  renderServerDetail();
  renderSidebar();
  showToast(`Camera "${name}" added.`, "success");
}

// ─── Add Room Modal ───────────────────────────────────────────────────────────
function openAddRoomModal() {
  document.getElementById("add-room-name").value = "";
  document.getElementById("add-room-building").value = "";
  document.getElementById("add-room-floor").value = "";
  document.getElementById("add-room-modal").classList.remove("hidden");
  document.getElementById("modal-overlay").classList.remove("hidden");
}

function submitAddRoom() {
  const name = document.getElementById("add-room-name").value.trim();
  const building = document.getElementById("add-room-building").value.trim();
  const floor = document.getElementById("add-room-floor").value.trim();

  if (!name || !building) { showToast("Room name and building are required.", "warn"); return; }

  addRoom({
    id: "room-" + Date.now(),
    name,
    building,
    floor: floor || "G",
  });

  closeModal();
  showToast(`Room "${name}" added.`, "success");
}

// ─── Filters & Search ─────────────────────────────────────────────────────────
function setFilter(filter) {
  State.cameraFilter = filter;
  document.querySelectorAll(".filter-tab").forEach((el) => el.classList.remove("active"));
  document.getElementById("ftab-wrap-" + filter).classList.add("active");
  renderCameraList();
}

function setupGlobalListeners() {
  document.getElementById("search-cameras").addEventListener("input", (e) => {
    State.cameraSearch = e.target.value;
    if (State.view === "server") renderCameraList();
  });

  document.getElementById("modal-overlay").addEventListener("click", closeModal);
}

// ─── Toast ────────────────────────────────────────────────────────────────────
function showToast(msg, type = "info") {
  const container = document.getElementById("toast-container");
  const toast = document.createElement("div");
  toast.className = `toast toast-${type}`;
  toast.textContent = msg;
  container.appendChild(toast);
  setTimeout(() => toast.classList.add("show"), 10);
  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// ─── Util ─────────────────────────────────────────────────────────────────────
function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

function toggleSidebar() {
  const isMobile = window.innerWidth <= 480;
  const sidebar  = document.getElementById("sidebar");
  const backdrop = document.getElementById("sidebar-backdrop");

  if (isMobile) {
    const open = sidebar.classList.toggle("mobile-open");
    backdrop.classList.toggle("visible", open);
  } else {
    State.sidebarOpen = !State.sidebarOpen;
    sidebar.classList.toggle("collapsed", !State.sidebarOpen);
    document.getElementById("main-content").classList.toggle("sidebar-collapsed", !State.sidebarOpen);
  }
}

function closeMobileSidebar() {
  document.getElementById("sidebar").classList.remove("mobile-open");
  document.getElementById("sidebar-backdrop").classList.remove("visible");
}

// ─── Add Server Modal ─────────────────────────────────────────────────────────
function openAddServerModal(prefill) {
  const f = prefill || {};
  document.getElementById("add-srv-name").value     = f.name     || "";
  document.getElementById("add-srv-ip").value       = f.ip       || "";
  document.getElementById("add-srv-location").value = f.location || "";
  document.getElementById("add-srv-os").value       = f.os       || "Ubuntu 22.04 LTS";
  document.getElementById("add-server-modal").classList.remove("hidden");
  document.getElementById("modal-overlay").classList.remove("hidden");
}

function submitAddServer() {
  const name     = document.getElementById("add-srv-name").value.trim();
  const ip       = document.getElementById("add-srv-ip").value.trim();
  const location = document.getElementById("add-srv-location").value.trim();
  const os       = document.getElementById("add-srv-os").value;

  if (!name) { showToast("Server name is required.", "warn"); return; }
  if (!ip)   { showToast("IP address is required.", "warn"); return; }

  addServer({
    id:       "srv-" + Date.now(),
    name,
    ip,
    location: location || "—",
    status:   "online",
    os,
    uptime:   "0d 0h 0m",
    cpu:      0,
    ram:      0,
    storage:  0,
  });

  closeModal();
  renderDashboard();
  showToast(`Server "${name}" added.`, "success");
}

// ─── Rooms View ───────────────────────────────────────────────────────────────
function navigateToRooms() {
  State.view = "rooms";
  State.selectedRoomId = null;
  showView("rooms");
  document.getElementById("breadcrumb-server").classList.add("hidden");
  document.getElementById("breadcrumb-room").classList.remove("hidden");
  document.getElementById("breadcrumb-room-name-wrap").classList.add("hidden");
  document.getElementById("page-title").textContent = "Rooms";
  renderRoomsGrid();
  renderSidebar();
}

function renderRoomsGrid() {
  const container = document.getElementById("rooms-grid");
  if (DB.rooms.length === 0) {
    container.innerHTML = `<div class="empty-state"><div class="empty-icon">🚪</div><div>No rooms yet. Add one from the sidebar.</div></div>`;
    return;
  }
  container.innerHTML = DB.rooms.map(r => {
    const cams = DB.cameras.filter(c => c.assignedRoomId === r.id);
    const active = cams.filter(c => c.status === "active").length;
    return `
    <div class="room-card" onclick="navigateToRoom('${r.id}')">
      <div class="room-card-icon">🚪</div>
      <div class="room-card-body">
        <div class="room-card-name">${r.name}</div>
        <div class="room-card-meta">${r.building} · Floor ${r.floor}</div>
        <div class="room-card-stats">
          <span class="num green">${active}</span> active &nbsp;/&nbsp;
          <span class="num">${cams.length}</span> total cams
        </div>
      </div>
      <button class="btn btn-sm btn-danger-ghost room-del-btn"
        onclick="confirmDeleteRoom('${r.id}');event.stopPropagation()">🗑</button>
    </div>`;
  }).join("");
}

// ─── Room Detail View ─────────────────────────────────────────────────────────
function navigateToRoom(roomId) {
  State.selectedRoomId = roomId;
  State.view = "room-detail";
  const room = getRoom(roomId);
  showView("room-detail");
  document.getElementById("breadcrumb-room").classList.remove("hidden");
  document.getElementById("breadcrumb-server").classList.add("hidden");
  document.getElementById("breadcrumb-room-name-wrap").classList.remove("hidden");
  document.getElementById("breadcrumb-room-name").textContent = room.name;
  document.getElementById("page-title").textContent = room.name;
  renderRoomDetail();
}

function renderRoomDetail() {
  const room = getRoom(State.selectedRoomId);
  document.getElementById("room-detail-name").textContent     = room.name;
  document.getElementById("room-detail-building").textContent = room.building;
  document.getElementById("room-detail-floor").textContent    = room.floor;

  const cams = DB.cameras.filter(c => c.assignedRoomId === room.id);
  const container = document.getElementById("room-camera-list");

  if (cams.length === 0) {
    container.innerHTML = `<div class="empty-state"><div class="empty-icon">📷</div><div>No cameras assigned to this room yet.</div></div>`;
    return;
  }

  container.innerHTML = cams.map(cam => {
    const srv = getServer(cam.serverId);
    const canView = cam.status !== "offline";
    return `
    <div class="camera-row ${cam.status}">
      <div class="cam-icon-col">
        <div class="cam-icon ${cam.type === 'USB' ? 'usb' : 'ip'}">${cam.type === "USB" ? "💻" : "📹"}</div>
      </div>
      <div class="cam-main">
        <div class="cam-name-row">
          <span class="cam-name">${cam.name}</span>
          <span class="status-badge ${cam.status}">${capitalize(cam.status)}</span>
        </div>
        <div class="cam-details">
          ${cam.type === "IP"
            ? `<span class="conn-info"><span class="type-badge ip">IP</span> ${cam.ip}:${cam.port}</span>`
            : `<span class="conn-info"><span class="type-badge usb">USB</span> Direct</span>`}
          <span class="cam-model">${cam.model}</span>
          <span class="cam-res">${cam.resolution} · ${cam.fps}fps</span>
          <span class="cam-model" style="color:var(--text3)">📡 ${srv ? srv.name : "?"}</span>
        </div>
      </div>
      <div class="cam-actions">
        ${canView ? `<button class="btn btn-sm btn-view" onclick="openViewer('${cam.id}', event)">▶ View</button>` : ""}
        <button class="btn btn-sm btn-ghost" onclick="confirmUnassignFromRoom('${cam.id}', event)">✕ Remove</button>
      </div>
    </div>`;
  }).join("");
}

function confirmUnassignFromRoom(cameraId, evt) {
  if (evt) evt.stopPropagation();
  const cam = getCamera(cameraId);
  if (!confirm(`Remove "${cam.name}" from this room?`)) return;
  unassignCamera(cameraId);
  renderRoomDetail();
  showToast("Camera removed from room.", "info");
}

// ─── Room Camera Picker ───────────────────────────────────────────────────────
function openRoomCameraPicker() {
  const room = getRoom(State.selectedRoomId);
  document.getElementById("picker-room-name").textContent = room.name;
  renderCameraPicker();
  document.getElementById("room-camera-picker").classList.remove("hidden");
  document.getElementById("modal-overlay").classList.remove("hidden");
}

function renderCameraPicker() {
  const roomId = State.selectedRoomId;
  const list    = document.getElementById("picker-camera-list");
  const occupant = getRoomCamera(roomId); // camera already in this room (or null)

  if (DB.cameras.length === 0) {
    list.innerHTML = `<div class="empty-state" style="padding:20px">No cameras in any server yet.</div>`;
    return;
  }

  // Show a banner if the room is already full
  const banner = occupant
    ? `<div class="picker-full-banner">
         🔒 This room already has <strong>${occupant.name}</strong>.
         Remove it below before adding a different camera.
       </div>`
    : `<div class="picker-empty-banner">This room has no camera yet. Pick one below.</div>`;

  // Group cameras by server
  const byServer = {};
  DB.cameras.forEach(cam => {
    if (!byServer[cam.serverId]) byServer[cam.serverId] = [];
    byServer[cam.serverId].push(cam);
  });

  const groups = Object.entries(byServer).map(([srvId, cams]) => {
    const srv = getServer(srvId);
    const rows = cams.map(cam => {
      const inThisRoom  = cam.assignedRoomId === roomId;
      const inOtherRoom = cam.assignedRoomId && cam.assignedRoomId !== roomId;
      const otherRoom   = inOtherRoom ? getRoom(cam.assignedRoomId) : null;
      const typeBadge   = `<span class="type-badge ${cam.type.toLowerCase()}">${cam.type}</span>`;

      if (inThisRoom) {
        return `
        <div class="picker-row in-this-room">
          <div class="picker-cam-info">
            ${typeBadge} <span class="picker-cam-name">${cam.name}</span>
            <span class="picker-cam-meta">${cam.model} · ${cam.resolution}</span>
          </div>
          <span class="picker-tag already">✔ In this room</span>
        </div>`;
      }

      if (inOtherRoom) {
        // Only allow reassign if destination room is currently empty
        const canReassign = !occupant;
        return `
        <div class="picker-row in-other-room">
          <div class="picker-cam-info">
            ${typeBadge} <span class="picker-cam-name">${cam.name}</span>
            <span class="picker-cam-meta">${cam.model} · ${cam.resolution}</span>
          </div>
          <div class="picker-actions">
            <span class="picker-tag inuse">🔒 ${otherRoom ? otherRoom.name : "other room"}</span>
            ${canReassign
              ? `<button class="btn btn-sm btn-outline" onclick="reassignCameraToRoom('${cam.id}')">Reassign here</button>`
              : `<span style="font-size:11px;color:var(--text3)">Room full</span>`}
          </div>
        </div>`;
      }

      // Available — only addable if room is empty
      if (occupant) {
        return `
        <div class="picker-row" style="opacity:.45;cursor:not-allowed">
          <div class="picker-cam-info">
            ${typeBadge} <span class="picker-cam-name">${cam.name}</span>
            <span class="picker-cam-meta">${cam.model} · ${cam.resolution}</span>
          </div>
          <span style="font-size:11px;color:var(--text3)">Room full</span>
        </div>`;
      }

      return `
      <div class="picker-row available">
        <div class="picker-cam-info">
          ${typeBadge} <span class="picker-cam-name">${cam.name}</span>
          <span class="picker-cam-meta">${cam.model} · ${cam.resolution}</span>
        </div>
        <button class="btn btn-sm btn-success" onclick="addCameraToRoom('${cam.id}')">➕ Add</button>
      </div>`;
    }).join("");

    return `
    <div class="picker-server-group">
      <div class="picker-server-label">📡 ${srv ? srv.name : srvId} <span style="color:var(--text3);font-size:11px">${srv ? srv.ip : ""}</span></div>
      ${rows}
    </div>`;
  }).join("");

  list.innerHTML = banner + groups;
}

function addCameraToRoom(cameraId) {
  const occupant = getRoomCamera(State.selectedRoomId);
  if (occupant) {
    showToast(`Room already has "${occupant.name}". Remove it first.`, "warn");
    return;
  }
  const result = assignCameraToRoom(cameraId, State.selectedRoomId);
  if (!result.ok) { showToast(result.msg, "warn"); return; }
  renderCameraPicker();
  renderRoomDetail();
  showToast("Camera added to room.", "success");
}

function reassignCameraToRoom(cameraId) {
  const cam      = getCamera(cameraId);
  const fromRoom = cam.assignedRoomId ? getRoom(cam.assignedRoomId) : null;
  const toRoom   = getRoom(State.selectedRoomId);
  const occupant = getRoomCamera(State.selectedRoomId);

  // If the destination room is also occupied, block (user must remove that camera first)
  if (occupant) {
    showToast(`"${toRoom.name}" already has "${occupant.name}". Remove it first.`, "warn");
    return;
  }

  if (!confirm(`Move "${cam.name}" from "${fromRoom ? fromRoom.name : "?"}" to "${toRoom.name}"?`)) return;
  const result = assignCameraToRoom(cameraId, State.selectedRoomId);
  if (!result.ok) { showToast(result.msg, "warn"); return; }
  renderCameraPicker();
  renderRoomDetail();
  showToast("Camera reassigned.", "success");
}

// ─── Network Discovery ────────────────────────────────────────────────────────
// ─── Network View (full-page) ─────────────────────────────────────────────────
const SCAN_KEY = "cctv_last_scan";

function openNetworkScan() {
  State.view = "network";
  showView("network");
  document.getElementById("breadcrumb-server").classList.add("hidden");
  document.getElementById("breadcrumb-room").classList.add("hidden");
  document.getElementById("page-title").textContent = "Network Devices";
  renderSidebar();

  // Restore last scan from localStorage
  try {
    const saved = localStorage.getItem(SCAN_KEY);
    if (saved) {
      const { hosts, subnet, scannedAt } = JSON.parse(saved);
      document.getElementById("net-scan-status").textContent =
        `Last scan: ${subnet}.x — ${hosts.length} device(s) found on ${new Date(scannedAt).toLocaleString()}`;
      renderScanResults(hosts);
      return;
    }
  } catch (e) {}
  document.getElementById("net-scan-status").textContent = "No scan yet. Click Scan to discover devices.";
  document.getElementById("net-scan-results").innerHTML = "";
}

function runNetworkScan() {
  if (State.scanning) return;
  State.scanning = true;

  const btn    = document.getElementById("scan-btn");
  const manual = (document.getElementById("manual-subnet")?.value || "").trim();
  btn.disabled = true;
  btn.textContent = "⏳ Scanning…";
  document.getElementById("net-scan-results").innerHTML = `
    <div class="scan-spinner">
      <div class="spinner"></div>
      <span id="scan-progress-text">Detecting local IP via WebRTC…</span>
      <div class="scan-progress-bar-wrap"><div id="scan-progress-bar" class="scan-progress-bar"></div></div>
    </div>`;

  // If user typed a manual subnet, override WebRTC detection
  if (manual) {
    BrowserScan._manualSubnet = manual;
  }

  BrowserScan.run(
    // onProgress
    ({ phase, subnet, pct, found }) => {
      const txt = document.getElementById("scan-progress-text");
      const bar = document.getElementById("scan-progress-bar");
      if (txt) txt.textContent = phase === "scanning"
        ? `Scanning ${subnet}.x — ${found} device(s) found… (${Math.round(pct * 100)}%)`
        : `Probing ports on ${found} discovered device(s)…`;
      if (bar) bar.style.width = Math.round(pct * 100) + "%";
      // Patch in manual subnet override
      if (manual && phase === "scanning") BrowserScan._manualSubnet = manual;
    },
    // onHostFound (live rendering as each host is confirmed)
    null
  ).then(({ subnet, hosts, error }) => {
    State.scanning = false;
    btn.disabled   = false;
    btn.textContent = "🔍 Scan Again";

    if (error) {
      // Subnet detection failed — show manual input
      document.getElementById("net-scan-status").textContent = error;
      document.getElementById("net-scan-results").innerHTML = `
        <div class="scan-manual-wrap">
          <p class="scan-error" style="margin-bottom:12px">⚠️ ${error}</p>
          <label style="font-size:12px;color:var(--text2)">Enter your subnet manually:</label>
          <div style="display:flex;gap:8px;margin-top:6px">
            <input id="manual-subnet" class="form-input" placeholder="e.g. 192.168.1" style="width:180px" />
            <button class="btn btn-primary" onclick="runNetworkScan()">Scan</button>
          </div>
        </div>`;
      return;
    }

    const now = Date.now();
    localStorage.setItem(SCAN_KEY, JSON.stringify({ hosts, subnet, scannedAt: now }));
    document.getElementById("net-scan-status").textContent =
      `${subnet}.x · ${hosts.length} device(s) found · ${new Date(now).toLocaleString()}`;
    renderScanResults(hosts);
  });

  // Patch getLocalIP to use manual subnet if provided
  if (manual) {
    const origRun = BrowserScan.run.bind(BrowserScan);
    // Override via monkey-patch on the result promise chain
    BrowserScan._manualSubnet = manual;
  }
}

function showScanError(msg) {
  document.getElementById("net-scan-status").textContent = "Error";
  document.getElementById("net-scan-results").innerHTML = `<div class="scan-error">⚠️ ${msg}</div>`;
}

function renderScanResults(hosts) {
  const container = document.getElementById("net-scan-results");
  if (!hosts.length) {
    container.innerHTML = `<div class="empty-state"><div class="empty-icon">🔎</div><div>No devices found.</div></div>`;
    return;
  }

  const PORT_LABELS = {80:"HTTP", 8080:"HTTP-Alt", 443:"HTTPS", 554:"RTSP", 8554:"RTSP-Alt", 8000:"HTTP-Alt", 9000:"HTTP-Alt"};

  container.innerHTML = hosts.map(h => {
    const isCam    = h.type === "camera";
    const matchSrv = DB.servers.find(s => s.ip === h.ip);
    const portList = h.ports.length
      ? h.ports.map(p =>
          `<span class="port-badge ${[554,8554].includes(p)?'rtsp':'http'}">${PORT_LABELS[p]||p}</span>`
        ).join("")
      : `<span style="color:var(--text3);font-size:11px">No open ports detected</span>`;

    const brandBadge = h.brand ? `<span class="brand-badge">${h.brand}</span>` : "";
    const icon = matchSrv ? "🖥️" : isCam ? "📹" : "💻";

    let actions;
    if (matchSrv) {
      actions = `
        <span class="picker-tag already">✔ ${matchSrv.name}</span>
        <button class="btn btn-sm btn-outline" onclick="navigateToServer('${matchSrv.id}')">View →</button>`;
    } else {
      actions = `<button class="btn btn-sm btn-primary"
        onclick="addDiscoveredServer('${h.ip}','${(h.brand||"").replace(/'/g,"\\'")}','${(h.hostname||"").replace(/'/g,"\\'")}')">
        ➕ Add as Server</button>`;
    }

    const webUI = h.ports.includes(80) || h.ports.includes(8080)
      ? `<a href="http://${h.ip}${h.ports.includes(80)?'':':8080'}" target="_blank"
            class="btn btn-sm btn-view" style="text-decoration:none">🌐 Web UI</a>` : "";

    return `
    <div class="scan-device ${matchSrv ? 'is-configured' : isCam ? 'is-camera' : ''}">
      <div class="scan-device-icon">${icon}</div>
      <div class="scan-device-info">
        <div class="scan-device-name">
          ${h.hostname || h.ip} ${brandBadge}
          ${isCam ? `<span class="scan-type-badge">📹 Camera Device</span>` : ""}
          ${matchSrv ? `<span class="scan-type-badge" style="background:var(--green-bg);color:var(--green-l);border-color:var(--green)">✔ Configured</span>` : ""}
        </div>
        <div class="scan-device-meta">
          <span class="net-ip">${h.ip}</span>
          <span style="color:var(--text3)">${h.mac}</span>
        </div>
        <div class="scan-port-list">${portList}</div>
      </div>
      <div class="scan-device-actions">${webUI}${actions}</div>
    </div>`;
  }).join("");
}

function addDiscoveredServer(ip, brand, hostname) {
  openAddServerModal({
    name:     brand ? `${brand} Server (${ip})` : (hostname || `Server ${ip}`),
    ip:       ip,
    location: "Discovered via network scan",
    os:       "Unknown",
  });
}

// (closeModal defined earlier — covers all modals including share-modal)


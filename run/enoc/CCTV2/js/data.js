// ─── Default seed data (used only on very first load) ─────────────────────────

const DB_DEFAULTS = {
  servers: [],
  cameras: [],
  rooms:   [],
};

// ─── Persistence — server-side JSON file via api/db.php ───────────────────────
// All browsers see the same data because it's stored on the XAMPP server,
// not in each browser's localStorage.

let DB = JSON.parse(JSON.stringify(DB_DEFAULTS)); // default until loaded

async function initDB() {
  for (let attempt = 1; attempt <= 3; attempt++) {
    try {
      const resp = await fetch("api/db.php");
      if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
      const data = await resp.json();
      if (data && Array.isArray(data.servers)) {
        DB.servers = data.servers;
        DB.cameras = data.cameras;
        DB.rooms   = data.rooms;
      } else {
        // First ever load — save empty DB to server
        await saveDB();
      }
      return; // success
    } catch (e) {
      if (attempt === 3) {
        console.warn("Could not load DB from server after 3 attempts.", e);
        // DB stays as empty defaults — user will see empty dashboard
      } else {
        await new Promise(r => setTimeout(r, 600 * attempt));
      }
    }
  }
}

async function saveDB() {
  try {
    await fetch("api/db.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(DB),
    });
  } catch (e) {
    console.warn("Could not save DB to server.", e);
  }
}

// ─── Helpers ─────────────────────────────────────────────────────────────────

function getCamerasByServer(serverId) {
  return DB.cameras.filter((c) => c.serverId === serverId);
}

function getServer(serverId) {
  return DB.servers.find((s) => s.id === serverId);
}

function getRoom(roomId) {
  return DB.rooms.find((r) => r.id === roomId);
}

function getCamera(cameraId) {
  return DB.cameras.find((c) => c.id === cameraId);
}

function getAvailableRoomsForCamera(cameraId) {
  return DB.rooms;
}

// Returns the camera currently assigned to a room, or null.
function getRoomCamera(roomId) {
  return DB.cameras.find((c) => c.assignedRoomId === roomId) || null;
}

function assignCameraToRoom(cameraId, roomId) {
  const cam = getCamera(cameraId);
  if (!cam) return { ok: false, msg: "Camera not found." };
  const existing = getRoomCamera(roomId);
  if (existing && existing.id !== cameraId)
    return { ok: false, msg: `Room already has camera "${existing.name}". Remove it first.` };
  cam.assignedRoomId = roomId;
  saveDB();
  return { ok: true };
}

function unassignCamera(cameraId) {
  const cam = getCamera(cameraId);
  if (!cam) return { ok: false, msg: "Camera not found." };
  cam.assignedRoomId = null;
  saveDB();
  return { ok: true };
}

function addCamera(camera) {
  DB.cameras.push(camera);
  saveDB();
}

function deleteCamera(cameraId) {
  const idx = DB.cameras.findIndex((c) => c.id === cameraId);
  if (idx === -1) return { ok: false, msg: "Camera not found." };
  DB.cameras.splice(idx, 1);
  saveDB();
  return { ok: true };
}

function addRoom(room) {
  DB.rooms.push(room);
  saveDB();
}

function deleteRoom(roomId) {
  const idx = DB.rooms.findIndex((r) => r.id === roomId);
  if (idx === -1) return { ok: false, msg: "Room not found." };
  DB.cameras.forEach((c) => { if (c.assignedRoomId === roomId) c.assignedRoomId = null; });
  DB.rooms.splice(idx, 1);
  saveDB();
  return { ok: true };
}

function addServer(server) {
  DB.servers.push(server);
  saveDB();
}

function deleteServer(serverId) {
  const idx = DB.servers.findIndex((s) => s.id === serverId);
  if (idx === -1) return { ok: false, msg: "Server not found." };
  DB.cameras = DB.cameras.filter((c) => c.serverId !== serverId);
  DB.servers.splice(idx, 1);
  saveDB();
  return { ok: true };
}

function serverStats(serverId) {
  const cams = getCamerasByServer(serverId);
  return {
    total:      cams.length,
    active:     cams.filter((c) => c.status === "active").length,
    inactive:   cams.filter((c) => c.status === "inactive").length,
    warning:    cams.filter((c) => c.status === "warning").length,
    offline:    cams.filter((c) => c.status === "offline").length,
    assigned:   cams.filter((c) => c.assignedRoomId !== null).length,
    unassigned: cams.filter((c) => c.assignedRoomId === null).length,
  };
}

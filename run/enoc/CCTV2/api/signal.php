<?php
/**
 * WebRTC Signaling — file-based, no database needed.
 *
 * Actions (GET or POST with ?action=xxx&camId=yyy[&viewerId=zzz]):
 *   request       POST  Viewer announces it wants to watch camId
 *   requests      GET   Host polls for pending viewer IDs for camId
 *   offer         POST  Host sends SDP offer for a specific viewerId
 *   offer         GET   Viewer retrieves offer
 *   answer        POST  Viewer sends SDP answer
 *   answer        GET   Host retrieves answer
 *   cleanup       POST  Remove signal files for one viewer (on disconnect)
 *   cleanup_all   POST  Remove all signal files for camId (on broadcast stop)
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

$dir    = __DIR__ . '/signals';
$action = preg_replace('/[^a-z_]/', '', strtolower($_GET['action'] ?? ''));
$camId  = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['camId']   ?? '');
$viewId = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['viewerId'] ?? '');

if (!$action || !$camId) { http_response_code(400); echo json_encode(['error'=>'missing params']); exit; }
if (!is_dir($dir)) mkdir($dir, 0755, true);

$TTL = 120; // seconds — signal files older than this are stale

function readFile($path, $ttl) {
    if (!file_exists($path)) return null;
    if (time() - filemtime($path) > $ttl) { @unlink($path); return null; }
    return file_get_contents($path);
}

function writeFile($path, $body) {
    if (!$body || !json_decode($body)) { http_response_code(400); echo json_encode(['error'=>'invalid json']); exit; }
    file_put_contents($path, $body);
    echo json_encode(['ok' => true]);
}

// ── Viewer announces itself ───────────────────────────────────────────────────
if ($action === 'request' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$viewId) { http_response_code(400); echo json_encode(['error'=>'missing viewerId']); exit; }
    $f = "$dir/{$camId}_req_{$viewId}.json";
    file_put_contents($f, json_encode(['viewerId' => $viewId, 'ts' => time()]));
    echo json_encode(['ok' => true]);
    exit;
}

// ── Host gets list of pending viewer IDs ─────────────────────────────────────
if ($action === 'requests' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $pattern = "$dir/{$camId}_req_*.json";
    $files   = glob($pattern) ?: [];
    $ids     = [];
    foreach ($files as $f) {
        $content = readFile($f, $TTL);
        if ($content) {
            $data = json_decode($content, true);
            if (isset($data['viewerId'])) $ids[] = $data['viewerId'];
        }
    }
    echo json_encode($ids);
    exit;
}

// ── Offer (host → viewer) ─────────────────────────────────────────────────────
if ($action === 'offer') {
    if (!$viewId) { http_response_code(400); echo json_encode(['error'=>'missing viewerId']); exit; }
    $f = "$dir/{$camId}_offer_{$viewId}.json";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        writeFile($f, file_get_contents('php://input'));
    } else {
        $content = readFile($f, $TTL);
        echo $content !== null ? $content : 'null';
    }
    exit;
}

// ── Answer (viewer → host) ────────────────────────────────────────────────────
if ($action === 'answer') {
    if (!$viewId) { http_response_code(400); echo json_encode(['error'=>'missing viewerId']); exit; }
    $f = "$dir/{$camId}_answer_{$viewId}.json";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        writeFile($f, file_get_contents('php://input'));
    } else {
        $content = readFile($f, $TTL);
        echo $content !== null ? $content : 'null';
    }
    exit;
}

// ── Cleanup one viewer's files ────────────────────────────────────────────────
if ($action === 'cleanup' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($viewId) {
        foreach (["req","offer","answer"] as $type) {
            @unlink("$dir/{$camId}_{$type}_{$viewId}.json");
        }
    }
    echo json_encode(['ok' => true]);
    exit;
}

// ── Cleanup all files for a camId (broadcast stopped) ────────────────────────
if ($action === 'cleanup_all' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (glob("$dir/{$camId}_*.json") ?: [] as $f) { @unlink($f); }
    echo json_encode(['ok' => true]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'unknown action']);

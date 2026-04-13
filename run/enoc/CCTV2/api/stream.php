<?php
@ini_set('display_errors', 0);
error_reporting(0);
/**
 * Serves a live MJPEG stream for a broadcasting USB camera.
 * Remote viewers open this URL in an <img> tag or video viewer.
 *
 * Usage: stream.php?camId=cam-xxx
 *        stream.php?camId=cam-xxx&single=1   (returns one JPEG frame, for JS polling)
 */

$camId = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_GET['camId'] ?? '');
if (!$camId) {
    http_response_code(400);
    echo 'Missing camId';
    exit;
}

$frameFile = __DIR__ . '/frames/' . $camId . '.jpg';
$metaFile  = __DIR__ . '/frames/' . $camId . '.meta';

// ── Single frame mode (for JS polling fallback) ───────────────────────────────
if (!empty($_GET['single'])) {
    if (!file_exists($frameFile)) {
        http_response_code(404);
        exit;
    }
    header('Content-Type: image/jpeg');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    readfile($frameFile);
    exit;
}

// ── Status check ──────────────────────────────────────────────────────────────
if (!empty($_GET['status'])) {
    header('Content-Type: application/json');
    $alive = false;
    if (file_exists($metaFile)) {
        $meta  = json_decode(file_get_contents($metaFile), true);
        $alive = (time() - ($meta['updated'] ?? 0)) < 5; // stale after 5 seconds
    }
    echo json_encode(['broadcasting' => $alive, 'hasFrame' => file_exists($frameFile)]);
    exit;
}

// ── MJPEG stream ──────────────────────────────────────────────────────────────
set_time_limit(0);
ignore_user_abort(false);

header('Content-Type: multipart/x-mixed-replace; boundary=mjpeg_frame');
header('Cache-Control: no-cache, no-store');
header('Connection: keep-alive');
header('Pragma: no-cache');

$maxFrames   = 18000;  // ~30 min at 10fps before PHP forces a reconnect
$frameCount  = 0;
$sleepUs     = 100000; // 10 fps target
$noFrameSleep = 250000; // wait longer if no frame yet

while ($frameCount < $maxFrames && connection_status() === CONNECTION_NORMAL) {
    if (!file_exists($frameFile)) {
        usleep($noFrameSleep);
        continue;
    }

    // Check if broadcaster is still alive (meta updated within 5s)
    if (file_exists($metaFile)) {
        $meta = json_decode(@file_get_contents($metaFile), true);
        if ((time() - ($meta['updated'] ?? 0)) > 5) {
            // Broadcaster went away — send a final "no signal" JPEG if available
            break;
        }
    }

    $data = @file_get_contents($frameFile);
    if ($data && strlen($data) > 100) {
        echo "--mjpeg_frame\r\n";
        echo "Content-Type: image/jpeg\r\n";
        echo "Content-Length: " . strlen($data) . "\r\n\r\n";
        echo $data . "\r\n";

        if (ob_get_level() > 0) ob_flush();
        flush();
        $frameCount++;
    }

    usleep($sleepUs);
}

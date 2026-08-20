<?php

header('Content-Type: application/json; charset=utf-8');

if (file_exists('../../lib/core.php')) {
    require_once '../../lib/core.php';
} elseif (file_exists('../../../lib/core.php')) {
    require_once '../../../lib/core.php';
}

if (file_exists('../../lib/dashboard-theme.php')) {
    require_once '../../lib/dashboard-theme.php';
} elseif (file_exists('../../../lib/dashboard-theme.php')) {
    require_once '../../../lib/dashboard-theme.php';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    exit;
}

if (
    !isset($_SESSION['d2s8wu_uid'], $_SESSION['d2s8wu_ustat'], $_SESSION['d2s8wu_verified'], $_SESSION['d2s8wu_xdel'], $_SESSION['d2s8wu_ulevel']) ||
    (int) $_SESSION['d2s8wu_ustat'] !== 1 ||
    (int) $_SESSION['d2s8wu_verified'] !== 1 ||
    (int) $_SESSION['d2s8wu_xdel'] !== 0
) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Authentication is required.']);
    exit;
}

if (!isset($_POST['dashboard_theme']) || !in_array((string) $_POST['dashboard_theme'], ['0', '1'], true)) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Invalid dashboard theme.']);
    exit;
}

try {
    $database = new myDatabase();
    $cnn = $database->getConnection();

    if (!$cnn) {
        throw new RuntimeException('Database connection is unavailable.');
    }

    ensureUserDashboardThemeColumn($cnn);
    $userId = trim((string) $_SESSION['d2s8wu_uid']);

    $statement = $cnn->prepare('UPDATE `user_tbl` SET `dashboard_theme` = :dashboard_theme WHERE `uid` = :uid');
    $statement->execute([
        ':dashboard_theme' => (int) $_POST['dashboard_theme'],
        ':uid' => $userId,
    ]);

    $_SESSION['d2s8wu_dashboard_theme'] = (int) $_POST['dashboard_theme'];

    echo json_encode(['status' => 'success']);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Unable to save the dashboard theme.']);
}

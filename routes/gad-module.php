<?php

include_once "lib/core.php";
include_once "lib/env.php";
include_once "lib/function.php";

if (!isset($_SESSION['d2s8wu_ustat'], $_SESSION['d2s8wu_verified'], $_SESSION['d2s8wu_xdel'], $_SESSION['d2s8wu_ulevel']) ||
    $_SESSION['d2s8wu_ustat'] != 1 || $_SESSION['d2s8wu_verified'] != 1 || $_SESSION['d2s8wu_xdel'] != 0 ||
    !in_array((int)$_SESSION['d2s8wu_ulevel'], [1, 2, 15, 16, 17], true)) {
    header("location:" . $domainhome);
    exit;
}

include_once "model/gad/index.php";
$gadHealthData = new gadHealthData();
$gadHealthData->ensureTableAndSeed();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_SESSION['d2s8wu_uname'] ?? 'system');
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $gadHealthData->delete((int)($_POST['id'] ?? 0), $user);
    } elseif ($action === 'save') {
        $category = $_POST['data_category'] ?? '';
        $year = (int)($_POST['calendar_year'] ?? 0);
        $diagnosis = trim($_POST['diagnosis'] ?? '');
        $male = (int)($_POST['male_count'] ?? -1);
        $female = (int)($_POST['female_count'] ?? -1);
        if ($year >= 2000 && $year <= 2100 && in_array($category, ['discharged', 'emergency', 'opd'], true) && $diagnosis !== '' && $male >= 0 && $female >= 0) {
            $gadHealthData->save((int)($_POST['id'] ?? 0), $year, $category, $diagnosis, $male, $female, $user);
        }
    }
    header("location:" . $domainhome . "/gad-module");
    exit;
}

$the_htitle = "DashPanel: GAD Module";
$the_refresh = null;
$the_expires = null;
$page_title = "GAD Module";
$breadcrumb = "Gender and Development Health Data";
include_once "app/theme/{$theme}/dpanel/header.php";
include_once "app/theme/{$theme}/dpanel/navbar.php";
include_once "app/theme/{$theme}/dpanel/sidebar.php";
include_once "app/views/gad-module/index.php";
include_once "app/theme/{$theme}/dpanel/footer.php";
?>

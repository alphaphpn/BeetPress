<?php

include_once "lib/core.php";
include_once "lib/env.php";
include_once "lib/function.php";

$allowedLevels = array(1, 2, 15, 16, 17);
$isAuthenticated = !empty($_SESSION['d2s8wu_ustat'])
    && !empty($_SESSION['d2s8wu_verified'])
    && (int) ($_SESSION['d2s8wu_xdel'] ?? 1) === 0
    && in_array((int) ($_SESSION['d2s8wu_ulevel'] ?? 0), $allowedLevels, true);

if (!$isAuthenticated) {
    header("location:" . $domainhome);
    exit;
}

$the_htitle = "DashPanel: Time Request - Employee";
$the_refresh = null;
$the_expires = null;
$page_title = "DashPanel";
$breadcrumb = "Electronic Provincial Local Government Unit System";
include_once "app/theme/{$theme}/dpanel/header.php";
include_once "app/theme/{$theme}/dpanel/navbar.php";
include_once "app/theme/{$theme}/dpanel/sidebar.php";
include_once "app/views/time-request-employee/index.php";
include_once "app/theme/{$theme}/dpanel/footer-datatables.php";

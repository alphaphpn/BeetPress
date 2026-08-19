<?php

require_once __DIR__ . '/cnn.php';

/** Adds the dashboard theme setting to existing installations on first use. */
function ensureDashboardThemeColumn(PDO $cnn): void
{
    $column = $cnn->query("SHOW COLUMNS FROM `0a_conf` LIKE 'dashboard_theme'");

    if ($column->fetch() === false) {
        $cnn->exec("ALTER TABLE `0a_conf` ADD COLUMN `dashboard_theme` TINYINT(1) NOT NULL DEFAULT 0 AFTER `account_enabled`");
    }
}

/** Returns 0 for dark (the default) or 1 for light. */
function getDashboardTheme(): int
{
    try {
        $database = new myDatabase();
        $cnn = $database->getConnection();

        if (!$cnn) {
            return 0;
        }

        ensureDashboardThemeColumn($cnn);
        $theme = $cnn->query("SELECT `dashboard_theme` FROM `0a_conf` ORDER BY `agency_id` ASC LIMIT 1")->fetchColumn();

        return ((int) $theme === 1) ? 1 : 0;
    } catch (Throwable $exception) {
        return 0;
    }
}

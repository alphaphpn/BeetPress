<?php

require_once __DIR__ . '/cnn.php';

/**
 * Moves the dashboard theme from the global configuration to each user.
 *
 * Existing installations receive the previous global value as every user's
 * initial preference. Once the values have been copied, the obsolete global
 * column is removed.
 */
function ensureUserDashboardThemeColumn(PDO $cnn): void
{
    $userThemeColumn = $cnn->query("SHOW COLUMNS FROM `user_tbl` LIKE 'dashboard_theme'");
    $userThemeExists = $userThemeColumn->fetch() !== false;

    $globalThemeColumn = $cnn->query("SHOW COLUMNS FROM `0a_conf` LIKE 'dashboard_theme'");
    $globalThemeExists = $globalThemeColumn->fetch() !== false;

    if (!$userThemeExists) {
        $initialTheme = 0;

        if ($globalThemeExists) {
            $globalTheme = $cnn->query("SELECT `dashboard_theme` FROM `0a_conf` ORDER BY `agency_id` ASC LIMIT 1")->fetchColumn();
            $initialTheme = ((int) $globalTheme === 1) ? 1 : 0;
        }

        $cnn->exec("ALTER TABLE `user_tbl` ADD COLUMN `dashboard_theme` TINYINT(1) NOT NULL DEFAULT {$initialTheme} AFTER `onoffline`");

        // The default only applies to new rows, so copy the former global
        // setting to all users already in the table.
        if ($globalThemeExists) {
            $statement = $cnn->prepare('UPDATE `user_tbl` SET `dashboard_theme` = :dashboard_theme');
            $statement->execute([':dashboard_theme' => $initialTheme]);
        }
    }

    if ($globalThemeExists) {
        $cnn->exec("ALTER TABLE `0a_conf` DROP COLUMN `dashboard_theme`");
    }
}

/** Returns the signed-in user's theme: 0 for dark (default), 1 for light. */
function getDashboardTheme(): int
{
    try {
        $userId = trim((string) ($_SESSION['d2s8wu_uid'] ?? ''));

        if ($userId === '') {
            return 0;
        }

        $database = new myDatabase();
        $cnn = $database->getConnection();

        if (!$cnn) {
            return 0;
        }

        ensureUserDashboardThemeColumn($cnn);

        $statement = $cnn->prepare('SELECT `dashboard_theme` FROM `user_tbl` WHERE `uid` = :uid LIMIT 1');
        $statement->execute([':uid' => $userId]);
        $theme = $statement->fetchColumn();

        return ((int) $theme === 1) ? 1 : 0;
    } catch (Throwable $exception) {
        return 0;
    }
}

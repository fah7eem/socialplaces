<?php
foreach (glob(__DIR__ . '/../dashboards/*/models/' . "*.php") as $filename) {
    require_once $filename;
}
// Classes
require_once __DIR__ . '/../src/system/Migrate.php';
require_once __DIR__ . '/../src/system/DBLogger.php';
require_once __DIR__ . '/../src/system/Utils.php';
require_once __DIR__ . '/../src/system/Database.php';
require_once __DIR__ . '/../src/system/Email.php';

// Include rest of files (exclude system files)
foreach (glob(__DIR__ . '/../src/' . "*/*.php") as $filename) {
    if (strpos($filename, "/src/system") == 0) {
        require_once $filename;
    }
}

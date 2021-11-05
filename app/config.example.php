<?php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_USER', 'DB USERNAME'); /** Required for install */
define('DB_PASSWORD', 'DB PASSWORD'); /** Required for install */
define('DB_NAME', 'DB NAME'); /** Required  for install */
define('CHARSET', 'utf8mb4');
define('JWT_APP_SECRET', 'JWT SECRET RANDOM STRING'.date('Y'));
define('JWT_SECURE', false);
define('PROJECT_NAME', 'Contact Page');
define('PROJECT_LINK', 'https://localhost:8081'); /** Required for install example http://locahost */
define('SHOW_ERRORS', true);
define('MIGRATE_PATH', 'Path  to folder'); /** Required for install (full path to database folder) must have forward slash at end */
define('MIGRATE_PATH_DELETE', false);

define('MAIL_NAME',''); /** Required */
define('MAIL_FROM',''); /** Required */
define('MAIL_PASS',''); /** Required */
define('MAIL_SMTP',''); /** Required */
define('MAIL_PORT',''); /** Required */
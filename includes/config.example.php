<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'feedbackflow');
define('DB_USER', 'root');
define('DB_PASS', 'password');

define('BASE_URL', 'http://yourdomain.com/feedbackflow');

define('MAIL_FROM', 'alerts@yourdomain.com');
define('ENABLE_EMAIL_ALERTS', false);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'feedbackflow');
define('DB_USER', 'root');
define('DB_PASS', 'password');
define('BASE_URL', 'http://localhost/feedbackflow');
define('ENABLE_EMAIL_ALERTS', false);
define('MAIL_FROM', 'noreply@yourdomain.com');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
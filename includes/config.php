<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'feedbackflow');
define('DB_USER', 'root');
define('DB_PASS', 'YourStrongPassword123!');
define('BASE_URL', 'http://localhost/feedbackflow');
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
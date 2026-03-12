<?php
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function is_logged_in() { return !empty($_SESSION['company_id']); }
function require_login() { if (!is_logged_in()) { header('Location: login.php'); exit; } }
function generate_site_key($length = 24) {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $key = 'SITE_';
    for ($i = 0; $i < $length; $i++) { $key .= $chars[random_int(0, strlen($chars) - 1)]; }
    return $key;
}
?>
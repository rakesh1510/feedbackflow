<?php
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function json_response($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function is_logged_in() {
    return !empty($_SESSION['company_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function generate_site_key($length = 24) {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $key = 'SITE_';
    for ($i = 0; $i < $length; $i++) {
        $key .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $key;
}

function analyze_feedback($text) {
    $text = strtolower((string)$text);
    $sentiment = 'neutral';

    if (str_contains($text, 'slow') || str_contains($text, 'bad') || str_contains($text, 'bug') || str_contains($text, 'error')) {
        $sentiment = 'negative';
    }

    if (str_contains($text, 'great') || str_contains($text, 'love') || str_contains($text, 'good')) {
        $sentiment = 'positive';
    }

    return $sentiment;
}

function send_feedback_alert($email, $message) {
    if (!defined('ENABLE_EMAIL_ALERTS') || ENABLE_EMAIL_ALERTS !== true) {
        return false;
    }

    if (!$email) {
        return false;
    }

    $subject = 'New Feedback Received';
    $headers = 'From: ' . MAIL_FROM;
    return @mail($email, $subject, $message, $headers);
}
?>
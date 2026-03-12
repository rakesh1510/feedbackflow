<?php
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
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

function allowed_request_for_domain($domain) {
    $domain = strtolower(trim((string)$domain));
    if ($domain === '') {
        return false;
    }

    $hosts = [];

    if (!empty($_SERVER['HTTP_ORIGIN'])) {
        $host = parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST);
        if ($host) $hosts[] = strtolower($host);
    }

    if (!empty($_SERVER['HTTP_REFERER'])) {
        $host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        if ($host) $hosts[] = strtolower($host);
    }

    if (empty($hosts)) {
        return true;
    }

    foreach ($hosts as $host) {
        if ($host === $domain || str_ends_with($host, '.' . $domain)) {
            return true;
        }
    }
    return false;
}

function send_feedback_alert($toEmail, $projectName, $rating, $message, $pageUrl) {
    if (!defined('ENABLE_EMAIL_ALERTS') || !ENABLE_EMAIL_ALERTS || !$toEmail) {
        return false;
    }

    $from = defined('MAIL_FROM') ? MAIL_FROM : 'noreply@localhost';
    $subject = 'New feedback received - ' . $projectName;
    $body = "Project: {$projectName}\nRating: {$rating}\nPage: {$pageUrl}\n\nMessage:\n{$message}";
    $headers = 'From: ' . $from;

    return @mail($toEmail, $subject, $body, $headers);
}

function get_project_widget_settings(PDO $pdo, int $projectId): array {
    $stmt = $pdo->prepare("SELECT * FROM widget_settings WHERE project_id = ?");
    $stmt->execute([$projectId]);
    $settings = $stmt->fetch();

    return $settings ?: [
        'button_text' => 'Feedback',
        'button_color' => '#0b1730',
        'position' => 'right'
    ];
}
?>

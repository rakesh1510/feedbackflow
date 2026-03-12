<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$data = json_decode(file_get_contents("php://input"), true);

$message = trim($data['message'] ?? '');
$rating = (int)($data['rating'] ?? 5);
$siteKey = trim($data['site_key'] ?? '');

if ($message === '') {
    json_response(['status' => 'error', 'message' => 'Message required']);
}

$sentiment = analyze_feedback($message);

$projectId = null;
$notifyEmail = null;

if ($siteKey !== '') {
    $stmt = $pdo->prepare("SELECT id, notify_email FROM projects WHERE site_key=?");
    $stmt->execute([$siteKey]);
    $project = $stmt->fetch();
    if ($project) {
        $projectId = $project['id'];
        $notifyEmail = $project['notify_email'];
    }
}

$stmt = $pdo->prepare("INSERT INTO feedback (project_id,message,rating,sentiment) VALUES (?,?,?,?)");
$stmt->execute([$projectId, $message, $rating, $sentiment]);

if ($notifyEmail) {
    send_feedback_alert($notifyEmail, "Message: {$message}\nRating: {$rating}\nSentiment: {$sentiment}");
}

json_response(['status' => 'success', 'sentiment' => $sentiment]);
?>
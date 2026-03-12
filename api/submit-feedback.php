<?php
require_once '../includes/db.php';

$data = json_decode(file_get_contents("php://input"), true);

$site_key = $data['site_key'] ?? '';
$rating = $data['rating'] ?? 0;
$message = $data['message'] ?? '';
$page_url = $data['page_url'] ?? '';

$stmt = $pdo->prepare("SELECT id FROM projects WHERE site_key=?");
$stmt->execute([$site_key]);
$project = $stmt->fetch();

if (!$project) {
    echo json_encode(["status"=>"error","message"=>"Invalid site key"]);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO feedback (project_id,rating,message,page_url) VALUES (?,?,?,?)");
$stmt->execute([$project['id'],$rating,$message,$page_url]);

echo json_encode(["status"=>"success"]);
?>
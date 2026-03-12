<?php
require_once __DIR__ . '/../includes/db.php'; header('Content-Type: application/json');
$data=json_decode(file_get_contents("php://input"),true);
$site_key=trim($data['site_key']??''); $rating=(int)($data['rating']??5); $message=trim($data['message']??''); $page_url=trim($data['page_url']??''); $user_name=trim($data['user_name']??''); $user_email=trim($data['user_email']??'');
if ($site_key==='' || $message==='') { http_response_code(422); echo json_encode(['status'=>'error','message'=>'Site key and message are required.']); exit; }
$stmt=$pdo->prepare("SELECT id,domain FROM projects WHERE site_key=?"); $stmt->execute([$site_key]); $project=$stmt->fetch();
if (!$project) { http_response_code(404); echo json_encode(['status'=>'error','message'=>'Invalid site key.']); exit; }
$stmt=$pdo->prepare("INSERT INTO feedback (project_id,rating,message,page_url,user_name,user_email) VALUES (?,?,?,?,?,?)");
$stmt->execute([$project['id'], max(1,min(5,$rating)), $message, $page_url, $user_name, $user_email]);
echo json_encode(['status'=>'success','message'=>'Feedback saved.']);
?>
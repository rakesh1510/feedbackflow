<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$companyId = $_SESSION['company_id'];
$stmt = $pdo->prepare("SELECT f.*, p.project_name FROM feedback f LEFT JOIN projects p ON p.id=f.project_id WHERE p.company_id=? OR f.project_id IS NULL ORDER BY f.id DESC");
$stmt->execute([$companyId]);
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Feedback</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><div class="nav"><div class="container">
<a href="dashboard.php"><strong>FeedbackFlow</strong></a>
<a href="projects.php">Projects</a>
<a href="feedback-list.php">Feedback</a>
<a href="analytics.php">Analytics</a>
<a href="logout.php">Logout</a>
</div></div>
<div class="container"><div class="card">
<h1>Feedback list</h1>
<table class="table">
<tr><th>Project</th><th>Message</th><th>Rating</th><th>Sentiment</th><th>Date</th></tr>
<?php foreach ($rows as $row): ?>
<tr>
<td><?php echo e($row['project_name'] ?: 'General'); ?></td>
<td><?php echo e($row['message']); ?></td>
<td><?php echo e($row['rating']); ?></td>
<td><?php echo e($row['sentiment']); ?></td>
<td><?php echo e($row['created_at']); ?></td>
</tr>
<?php endforeach; ?>
</table>
</div></div></body></html>
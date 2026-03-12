<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$companyId = $_SESSION['company_id'];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE company_id=?");
$stmt->execute([$companyId]);
$totalProjects = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM feedback f LEFT JOIN projects p ON p.id=f.project_id WHERE p.company_id=? OR f.project_id IS NULL");
$stmt->execute([$companyId]);
$totalFeedback = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT ROUND(AVG(rating),1) FROM feedback f LEFT JOIN projects p ON p.id=f.project_id WHERE p.company_id=? OR f.project_id IS NULL");
$stmt->execute([$companyId]);
$avg = $stmt->fetchColumn() ?: 0;
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Dashboard</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><div class="nav"><div class="container">
<a href="dashboard.php"><strong>FeedbackFlow</strong></a>
<a href="projects.php">Projects</a>
<a href="feedback-list.php">Feedback</a>
<a href="analytics.php">Analytics</a>
<a href="logout.php">Logout</a>
</div></div>
<div class="container">
  <h1>Welcome, <?php echo e($_SESSION['company_name']); ?></h1>
  <div class="grid">
    <div class="card"><h3>Projects</h3><div class="stat"><?php echo e($totalProjects); ?></div></div>
    <div class="card"><h3>Total Feedback</h3><div class="stat"><?php echo e($totalFeedback); ?></div></div>
    <div class="card"><h3>Average Rating</h3><div class="stat"><?php echo e($avg); ?></div></div>
  </div>
  <div class="card">
    <h2>Quick Links</h2>
    <p><a class="btn" href="projects.php">Manage Projects</a> <a class="btn btn-secondary" href="feedback-list.php">View Feedback</a></p>
  </div>
</div>
</body></html>
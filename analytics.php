<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$data = $pdo->query("SELECT DATE(created_at) AS day, COUNT(*) AS total FROM feedback GROUP BY DATE(created_at) ORDER BY day DESC LIMIT 30")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Analytics</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><div class="nav"><div class="container">
<a href="dashboard.php"><strong>FeedbackFlow</strong></a>
<a href="projects.php">Projects</a>
<a href="feedback-list.php">Feedback</a>
<a href="analytics.php">Analytics</a>
<a href="logout.php">Logout</a>
</div></div>
<div class="container">
<div class="card">
<h1>Analytics</h1>
<table class="table">
<tr><th>Date</th><th>Feedback Count</th></tr>
<?php foreach ($data as $row): ?>
<tr><td><?php echo e($row['day']); ?></td><td><?php echo e($row['total']); ?></td></tr>
<?php endforeach; ?>
</table>
<p><a class="btn" href="charts/chart_example.html">Open Chart Example</a></p>
</div>
</div></body></html>
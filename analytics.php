<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$companyId = $_SESSION['company_id'];

$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM feedback f JOIN projects p ON p.id = f.project_id WHERE p.company_id = ?");
$stmt->execute([$companyId]);
$total = (int)($stmt->fetch()['total'] ?? 0);

$stmt = $pdo->prepare("SELECT ROUND(AVG(f.rating),1) AS avg_rating FROM feedback f JOIN projects p ON p.id = f.project_id WHERE p.company_id = ?");
$stmt->execute([$companyId]);
$avg = (float)($stmt->fetch()['avg_rating'] ?? 0);

$ratings = [];
for ($i = 1; $i <= 5; $i++) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM feedback f JOIN projects p ON p.id = f.project_id WHERE p.company_id = ? AND f.rating = ?");
    $stmt->execute([$companyId, $i]);
    $ratings[$i] = (int)($stmt->fetch()['cnt'] ?? 0);
}

$stmt = $pdo->prepare("SELECT p.project_name, COUNT(f.id) AS cnt, ROUND(AVG(f.rating),1) AS avg_rating
    FROM projects p
    LEFT JOIN feedback f ON f.project_id = p.id
    WHERE p.company_id = ?
    GROUP BY p.id, p.project_name
    ORDER BY cnt DESC");
$stmt->execute([$companyId]);
$projectStats = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT DATE(f.created_at) AS day, COUNT(*) AS cnt
    FROM feedback f
    JOIN projects p ON p.id = f.project_id
    WHERE p.company_id = ?
    GROUP BY DATE(f.created_at)
    ORDER BY day DESC
    LIMIT 7");
$stmt->execute([$companyId]);
$trend = $stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Analytics</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="nav"><div class="container">
<a href="dashboard.php"><strong>FeedbackFlow</strong></a>
<a href="projects.php">Projects</a>
<a href="feedback-list.php">Feedback</a>
<a href="analytics.php">Analytics</a>
<a href="logout.php">Logout</a>
</div></div>
<div class="container">
  <div class="grid">
    <div class="card"><h3>Total feedback</h3><div class="stat"><?php echo e($total); ?></div></div>
    <div class="card"><h3>Average rating</h3><div class="stat"><?php echo e($avg); ?></div></div>
    <div class="card"><h3>5-star feedback</h3><div class="stat"><?php echo e($ratings[5]); ?></div></div>
  </div>

  <div class="grid-2">
    <div class="card">
      <h2>Rating breakdown</h2>
      <?php for ($i = 5; $i >= 1; $i--): $pct = $total > 0 ? round(($ratings[$i] / $total) * 100) : 0; ?>
        <div style="display:grid;grid-template-columns:80px 1fr 60px;gap:12px;align-items:center;margin-bottom:14px;">
          <div><?php echo e($i); ?> star</div>
          <div class="progress"><div class="progress-bar" style="width:<?php echo e($pct); ?>%;"></div></div>
          <div><?php echo e($ratings[$i]); ?></div>
        </div>
      <?php endfor; ?>
    </div>

    <div class="card">
      <h2>Last 7 feedback days</h2>
      <?php if (!$trend): ?>
        <p>No trend data yet.</p>
      <?php else: ?>
        <table class="table">
          <tr><th>Date</th><th>Count</th></tr>
          <?php foreach ($trend as $row): ?>
          <tr><td><?php echo e($row['day']); ?></td><td><?php echo e($row['cnt']); ?></td></tr>
          <?php endforeach; ?>
        </table>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <h2>Project summary</h2>
    <table class="table">
      <tr><th>Project</th><th>Total feedback</th><th>Average rating</th></tr>
      <?php foreach ($projectStats as $row): ?>
      <tr>
        <td><?php echo e($row['project_name']); ?></td>
        <td><?php echo e($row['cnt']); ?></td>
        <td><?php echo e($row['avg_rating'] ?? 0); ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
</body></html>

<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$companyId = $_SESSION['company_id'];
$where = ["p.company_id = ?"];
$params = [$companyId];

$rating = trim($_GET['rating'] ?? '');
$projectId = trim($_GET['project_id'] ?? '');
$q = trim($_GET['q'] ?? '');

if ($rating !== '') {
    $where[] = "f.rating = ?";
    $params[] = (int)$rating;
}
if ($projectId !== '') {
    $where[] = "p.id = ?";
    $params[] = (int)$projectId;
}
if ($q !== '') {
    $where[] = "(f.message LIKE ? OR f.user_name LIKE ? OR f.user_email LIKE ?)";
    $like = '%' . $q . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$sql = "SELECT f.*, p.project_name
    FROM feedback f
    JOIN projects p ON p.id = f.project_id
    WHERE " . implode(' AND ', $where) . "
    ORDER BY f.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT id, project_name FROM projects WHERE company_id = ? ORDER BY project_name ASC");
$stmt->execute([$companyId]);
$projects = $stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Feedback</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="nav"><div class="container">
<a href="dashboard.php"><strong>FeedbackFlow</strong></a>
<a href="projects.php">Projects</a>
<a href="feedback-list.php">Feedback</a>
<a href="analytics.php">Analytics</a>
<a href="logout.php">Logout</a>
</div></div>
<div class="container">
  <div class="card">
    <h1>Feedback list</h1>
    <form method="get" class="grid-4">
      <div>
        <select name="project_id">
          <option value="">All projects</option>
          <?php foreach ($projects as $p): ?>
            <option value="<?php echo e($p['id']); ?>" <?php echo ($projectId !== '' && (int)$projectId === (int)$p['id']) ? 'selected' : ''; ?>><?php echo e($p['project_name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <select name="rating">
          <option value="">All ratings</option>
          <?php for ($i = 5; $i >= 1; $i--): ?>
            <option value="<?php echo $i; ?>" <?php echo ($rating !== '' && (int)$rating === $i) ? 'selected' : ''; ?>><?php echo $i; ?> stars</option>
          <?php endfor; ?>
        </select>
      </div>
      <div>
        <input type="text" name="q" placeholder="Search message, name, email" value="<?php echo e($q); ?>">
      </div>
      <div class="flex">
        <button type="submit">Filter</button>
        <a class="btn btn-secondary" href="feedback-list.php">Reset</a>
      </div>
    </form>

    <table class="table">
      <tr><th>Project</th><th>Rating</th><th>Message</th><th>Name</th><th>Email</th><th>Page URL</th><th>Date</th></tr>
      <?php foreach ($items as $row): ?>
      <tr>
        <td><?php echo e($row['project_name']); ?></td>
        <td><?php echo e($row['rating']); ?></td>
        <td><?php echo e($row['message']); ?></td>
        <td><?php echo e($row['user_name']); ?></td>
        <td><?php echo e($row['user_email']); ?></td>
        <td><?php echo e($row['page_url']); ?></td>
        <td><?php echo e($row['created_at']); ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
</body></html>

<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$companyId = $_SESSION['company_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project = trim($_POST['project_name'] ?? '');
    $domain = trim($_POST['domain'] ?? '');
    $notify = trim($_POST['notify_email'] ?? '');

    if ($project === '' || $domain === '') {
        $error = 'Please fill project and domain.';
    } else {
        $siteKey = generate_site_key();
        $stmt = $pdo->prepare("INSERT INTO projects (company_id,project_name,domain,site_key,notify_email) VALUES (?,?,?,?,?)");
        $stmt->execute([$companyId, $project, $domain, $siteKey, $notify ?: null]);
        $success = 'Project created.';
    }
}

$stmt = $pdo->prepare("SELECT * FROM projects WHERE company_id=? ORDER BY id DESC");
$stmt->execute([$companyId]);
$projects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Projects</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><div class="nav"><div class="container">
<a href="dashboard.php"><strong>FeedbackFlow</strong></a>
<a href="projects.php">Projects</a>
<a href="feedback-list.php">Feedback</a>
<a href="analytics.php">Analytics</a>
<a href="logout.php">Logout</a>
</div></div>
<div class="container">
  <div class="grid-2">
    <div class="card">
      <h2>Create project</h2>
      <?php if ($success): ?><div class="notice notice-success"><?php echo e($success); ?></div><?php endif; ?>
      <?php if ($error): ?><div class="notice notice-error"><?php echo e($error); ?></div><?php endif; ?>
      <form method="post">
        <input type="text" name="project_name" placeholder="Project name">
        <input type="text" name="domain" placeholder="example.com">
        <input type="email" name="notify_email" placeholder="Alert email (optional)">
        <button type="submit">Create project</button>
      </form>
    </div>
    <div class="card">
      <h2>Widget install</h2>
      <?php if (!empty($projects)): ?>
      <div class="codebox">&lt;script src="<?php echo e(BASE_URL); ?>/widget/widget.js" data-site-key="<?php echo e($projects[0]['site_key']); ?>"&gt;&lt;/script&gt;</div>
      <?php else: ?>
      <p>No project yet.</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <h2>Your projects</h2>
    <table class="table">
      <tr><th>Name</th><th>Domain</th><th>Alert Email</th><th>Site Key</th></tr>
      <?php foreach ($projects as $p): ?>
      <tr>
        <td><?php echo e($p['project_name']); ?></td>
        <td><?php echo e($p['domain']); ?></td>
        <td><?php echo e($p['notify_email']); ?></td>
        <td><?php echo e($p['site_key']); ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
</body></html>
<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$companyId = $_SESSION['company_id'];
$success = '';
$error = '';
$editProject = null;

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT p.*, ws.button_text, ws.button_color, ws.position
        FROM projects p
        LEFT JOIN widget_settings ws ON ws.project_id = p.id
        WHERE p.id = ? AND p.company_id = ?");
    $stmt->execute([(int)$_GET['edit'], $companyId]);
    $editProject = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_project'])) {
    $project_name = trim($_POST['project_name'] ?? '');
    $domain = strtolower(trim($_POST['domain'] ?? ''));
    $notify_email = trim($_POST['notify_email'] ?? '');
    $button_text = trim($_POST['button_text'] ?? 'Feedback');
    $button_color = trim($_POST['button_color'] ?? '#0b1730');
    $position = trim($_POST['position'] ?? 'right');

    if ($project_name === '' || $domain === '') {
        $error = 'Please enter project name and allowed domain.';
    } else {
        $siteKey = generate_site_key();
        $stmt = $pdo->prepare("INSERT INTO projects (company_id, project_name, domain, site_key, notify_email) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$companyId, $project_name, $domain, $siteKey, $notify_email ?: null]);

        $projectId = (int)$pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO widget_settings (project_id, button_text, button_color, position) VALUES (?, ?, ?, ?)");
        $stmt->execute([$projectId, $button_text ?: 'Feedback', $button_color ?: '#0b1730', $position ?: 'right']);

        $success = 'Project created successfully.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_project'])) {
    $project_id = (int)($_POST['project_id'] ?? 0);
    $project_name = trim($_POST['project_name'] ?? '');
    $domain = strtolower(trim($_POST['domain'] ?? ''));
    $notify_email = trim($_POST['notify_email'] ?? '');
    $button_text = trim($_POST['button_text'] ?? 'Feedback');
    $button_color = trim($_POST['button_color'] ?? '#0b1730');
    $position = trim($_POST['position'] ?? 'right');

    if ($project_id <= 0 || $project_name === '' || $domain === '') {
        $error = 'Please enter project name and allowed domain.';
    } else {
        $stmt = $pdo->prepare("UPDATE projects SET project_name = ?, domain = ?, notify_email = ? WHERE id = ? AND company_id = ?");
        $stmt->execute([$project_name, $domain, $notify_email ?: null, $project_id, $companyId]);

        $stmt = $pdo->prepare("INSERT INTO widget_settings (project_id, button_text, button_color, position)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE button_text = VALUES(button_text), button_color = VALUES(button_color), position = VALUES(position)");
        $stmt->execute([$project_id, $button_text ?: 'Feedback', $button_color ?: '#0b1730', $position ?: 'right']);

        $success = 'Project updated successfully.';
        $editProject = null;
    }
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ? AND company_id = ?");
    $stmt->execute([(int)$_GET['delete'], $companyId]);
    header('Location: projects.php?deleted=1');
    exit;
}

$stmt = $pdo->prepare("SELECT p.*, ws.button_text, ws.button_color, ws.position
    FROM projects p
    LEFT JOIN widget_settings ws ON ws.project_id = p.id
    WHERE p.company_id = ?
    ORDER BY p.created_at DESC");
$stmt->execute([$companyId]);
$projects = $stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Projects</title><link rel="stylesheet" href="assets/css/style.css"><script defer src="assets/js/app.js"></script></head>
<body>
<div class="nav"><div class="container">
<a href="dashboard.php"><strong>FeedbackFlow</strong></a>
<a href="projects.php">Projects</a>
<a href="feedback-list.php">Feedback</a>
<a href="analytics.php">Analytics</a>
<a href="logout.php">Logout</a>
</div></div>
<div class="container">
  <?php if (!empty($_GET['deleted'])): ?><div class="notice notice-success">Project deleted successfully.</div><?php endif; ?>
  <?php if ($success): ?><div class="notice notice-success"><?php echo e($success); ?></div><?php endif; ?>
  <?php if ($error): ?><div class="notice notice-error"><?php echo e($error); ?></div><?php endif; ?>

  <div class="grid-2">
    <div class="card">
      <h2><?php echo $editProject ? 'Edit project' : 'Create project'; ?></h2>
      <form method="post">
        <?php if ($editProject): ?><input type="hidden" name="project_id" value="<?php echo e($editProject['id']); ?>"><?php endif; ?>
        <input type="text" name="project_name" placeholder="Project name" value="<?php echo e($editProject['project_name'] ?? ''); ?>">
        <input type="text" name="domain" placeholder="example.com or demo.example.com" value="<?php echo e($editProject['domain'] ?? ''); ?>">
        <input type="email" name="notify_email" placeholder="Alert email (optional)" value="<?php echo e($editProject['notify_email'] ?? ''); ?>">
        <input type="text" name="button_text" placeholder="Button text" value="<?php echo e($editProject['button_text'] ?? 'Feedback'); ?>">
        <input type="color" name="button_color" value="<?php echo e($editProject['button_color'] ?? '#0b1730'); ?>">
        <select name="position">
          <option value="right" <?php echo (($editProject['position'] ?? 'right') === 'right') ? 'selected' : ''; ?>>Right</option>
          <option value="left" <?php echo (($editProject['position'] ?? 'right') === 'left') ? 'selected' : ''; ?>>Left</option>
        </select>
        <button type="submit" name="<?php echo $editProject ? 'update_project' : 'create_project'; ?>"><?php echo $editProject ? 'Update project' : 'Create project'; ?></button>
        <?php if ($editProject): ?><a class="btn btn-secondary" href="projects.php">Cancel</a><?php endif; ?>
      </form>
      <p class="muted small">Domain verification is enabled. Only the allowed domain or its subdomains can submit feedback.</p>
    </div>

    <div class="card">
      <h2>Widget install</h2>
      <p>Copy this snippet after creating a project.</p>
      <?php if (!empty($projects)): $first = $projects[0]; $snippetId = 'snippet-main'; ?>
      <div class="codebox" id="<?php echo $snippetId; ?>">&lt;script src="<?php echo e(BASE_URL); ?>/widget/widget.js" data-site-key="<?php echo e($first['site_key']); ?>"&gt;&lt;/script&gt;</div>
      <button type="button" class="btn copy-snippet copy-btn" data-target="<?php echo $snippetId; ?>">Copy snippet</button>
      <p><span class="badge">Example uses latest project</span></p>
      <?php else: ?>
      <p>No projects yet.</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <h2>Your projects</h2>
    <table class="table">
      <tr><th>Name</th><th>Allowed domain</th><th>Alert email</th><th>Widget</th><th>Site key</th><th>Snippet</th><th>Actions</th></tr>
      <?php foreach ($projects as $p): ?>
      <?php $sid = 'snippet-' . $p['id']; ?>
      <tr>
        <td><?php echo e($p['project_name']); ?></td>
        <td><?php echo e($p['domain']); ?></td>
        <td><?php echo e($p['notify_email']); ?></td>
        <td>
          <div class="small">Text: <?php echo e($p['button_text'] ?: 'Feedback'); ?></div>
          <div class="small">Color: <?php echo e($p['button_color'] ?: '#0b1730'); ?></div>
          <div class="small">Position: <?php echo e($p['position'] ?: 'right'); ?></div>
        </td>
        <td><?php echo e($p['site_key']); ?></td>
        <td>
          <div class="codebox small" id="<?php echo $sid; ?>">&lt;script src="<?php echo e(BASE_URL); ?>/widget/widget.js" data-site-key="<?php echo e($p['site_key']); ?>"&gt;&lt;/script&gt;</div>
          <button type="button" class="btn copy-snippet copy-btn" data-target="<?php echo $sid; ?>">Copy</button>
        </td>
        <td>
          <a class="btn btn-secondary" href="projects.php?edit=<?php echo e($p['id']); ?>">Edit</a>
          <a class="btn btn-danger" href="projects.php?delete=<?php echo e($p['id']); ?>" onclick="return confirm('Delete this project?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
</body></html>

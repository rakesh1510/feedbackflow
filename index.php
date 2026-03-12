<?php require_once 'includes/config.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>FeedbackFlow</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="nav">
    <div class="container">
      <a href="index.php"><strong>FeedbackFlow</strong></a>
      <a href="pricing.php">Pricing</a>
      <?php if (!empty($_SESSION['company_id'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Sign up</a>
      <?php endif; ?>
    </div>
  </div>
  <div class="container hero">
    <div class="grid-2">
      <div class="card">
        <h1>Collect customer feedback in minutes</h1>
        <p>FeedbackFlow helps small businesses add a feedback widget to any website, collect ratings and comments, and review everything in one simple dashboard.</p>
        <p>
          <a class="btn" href="register.php">Start free</a>
          <a class="btn btn-secondary" href="pricing.php">See pricing</a>
        </p>
      </div>
      <div class="card">
        <h3>How it works</h3>
        <ol>
          <li>Create an account</li>
          <li>Add a project and allowed domain</li>
          <li>Copy your widget snippet</li>
          <li>Install it on your website</li>
          <li>View feedback, analytics, and alerts</li>
        </ol>
      </div>
    </div>
  </div>
</body>
</html>
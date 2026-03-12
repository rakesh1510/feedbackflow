<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>FeedbackFlow</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="nav"><div class="container">
<a href="index.php"><strong>FeedbackFlow</strong></a>
<a href="pricing.php">Pricing</a>
<?php if (!empty($_SESSION['company_id'])): ?>
<a href="dashboard.php">Dashboard</a>
<a href="logout.php">Logout</a>
<?php else: ?>
<a href="login.php">Login</a>
<a href="register.php">Register</a>
<?php endif; ?>
</div></div>
<div class="container">
  <div class="grid-2">
    <div class="card">
      <h1>Collect customer feedback</h1>
      <p>FeedbackFlow helps businesses collect website feedback, track ratings, and view analytics in one dashboard.</p>
      <a class="btn" href="register.php">Start free</a>
    </div>
    <div class="card">
      <h3>Included pages</h3>
      <p>Login, logout, register, pricing, projects, dashboard, feedback list, analytics, test widget, and roadmap.</p>
    </div>
  </div>
</div>
</body>
</html>
<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM companies WHERE email = ?");
    $stmt->execute([$email]);
    $company = $stmt->fetch();

    if ($company && password_verify($password, $company['password_hash'])) {
        $_SESSION['company_id'] = $company['id'];
        $_SESSION['company_name'] = $company['company_name'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid login credentials.';
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><div class="nav"><div class="container"><a href="index.php"><strong>FeedbackFlow</strong></a></div></div>
<div class="container"><div class="card" style="max-width:520px;margin:40px auto;">
<h1>Login</h1>
<?php if ($error): ?><div class="notice notice-error"><?php echo e($error); ?></div><?php endif; ?>
<form method="post">
<input type="email" name="email" placeholder="Email">
<input type="password" name="password" placeholder="Password">
<button type="submit">Login</button>
</form></div></div></body></html>
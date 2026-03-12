<?php
require_once 'includes/db.php'; require_once 'includes/functions.php'; $error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $company_name=trim($_POST['company_name']??''); $email=trim($_POST['email']??''); $password=$_POST['password']??'';
  if ($company_name===''||$email===''||$password==='') { $error='Please fill all fields.'; }
  else {
    $stmt=$pdo->prepare("SELECT id FROM companies WHERE email=?"); $stmt->execute([$email]);
    if ($stmt->fetch()) { $error='Email already registered.'; }
    else { $hash=password_hash($password,PASSWORD_DEFAULT); $stmt=$pdo->prepare("INSERT INTO companies (company_name,email,password_hash) VALUES (?,?,?)"); $stmt->execute([$company_name,$email,$hash]); $_SESSION['company_id']=$pdo->lastInsertId(); $_SESSION['company_name']=$company_name; header('Location: dashboard.php'); exit; }
  }
}
?><!doctype html><html><head><meta charset="utf-8"><title>Register</title><link rel="stylesheet" href="assets/css/style.css"></head><body><div class="nav"><div class="container"><a href="index.php"><strong>FeedbackFlow</strong></a></div></div><div class="container"><div class="card" style="max-width:520px;margin:40px auto;"><h1>Create account</h1><?php if ($error): ?><div class="notice notice-error"><?php echo e($error); ?></div><?php endif; ?><form method="post"><input type="text" name="company_name" placeholder="Company name"><input type="email" name="email" placeholder="Email"><input type="password" name="password" placeholder="Password"><button type="submit">Create account</button></form></div></div></body></html>
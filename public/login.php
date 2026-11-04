<?php
require dirname(__DIR__).'/config/bootstrap.php';
if (!empty($_SESSION['user_id'])) redirect('/index.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 checkCsrf();
 $q=db()->prepare('SELECT id,password_hash FROM users WHERE username=?');$q->execute([trim((string)($_POST['username']??''))]);$user=$q->fetch();
 if($user && password_verify((string)($_POST['password']??''),$user['password_hash'])) {
  session_regenerate_id(true);$_SESSION['user_id']=(int)$user['id'];redirect('/index.php');
 }
 $error='Invalid username or password.';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Sign in · Monthly Checklist</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="/assets/style.css"></head><body class="d-flex align-items-center min-vh-100"><main class="container" class="login-container"><div class="card shadow-sm border-0 p-4"><div class="fs-1">☑</div><h1 class="h3 mt-2">Monthly Checklist</h1><p class="text-secondary">Sign in to your private checklists.</p><?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf())?>"><label class="form-label">Username<input name="username" required autocomplete="username" class="form-control mt-1"></label><label class="form-label d-block mt-3">Password<input type="password" name="password" required autocomplete="current-password" class="form-control mt-1"></label><button class="btn btn-dark w-100 mt-4">Sign in</button></form></div></main></body></html>
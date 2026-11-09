<?php
declare(strict_types=1);
require dirname(__DIR__,2) . '/app/bootstrap.php';
if (admin_user()) redirect('/admin/dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 csrf_check(); $email=trim($_POST['email']??''); $password=$_POST['password']??'';
 $stmt=db()->prepare('SELECT id,name,email,password_hash,role,is_active FROM admins WHERE email=? LIMIT 1'); $stmt->execute([$email]); $admin=$stmt->fetch();
 if ($admin && (int)$admin['is_active']===1 && password_verify($password,$admin['password_hash'])) {
   session_regenerate_id(true); unset($admin['password_hash']); $_SESSION['admin']=$admin;
   db()->prepare('UPDATE admins SET last_login_at=NOW() WHERE id=?')->execute([$admin['id']]);
   redirect('/admin/dashboard.php');
 }
 $error='اطلاعات ورود صحیح نیست.';
}
?><!doctype html><html lang="fa" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>ورود مدیریت</title><link rel="stylesheet" href="/assets/style.css"><body><main class="auth-wrap"><form class="panel" method="post"><a class="brand" href="/">CRIVIT</a><h1>ورود به مدیریت</h1><?php if(isset($_GET['created'])): ?><p class="success">مدیر اصلی ساخته شد؛ وارد شوید.</p><?php endif ?><?php if($error): ?><p class="error"><?=e($error)?></p><?php endif ?><input type="hidden" name="_csrf" value="<?=e(csrf_token())?>"><label>ایمیل<input type="email" name="email" required autocomplete="username"></label><label>رمز عبور<input type="password" name="password" required autocomplete="current-password"></label><button class="primary">ورود</button><a href="/">بازگشت به فروشگاه</a></form></main></body></html>

<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
$count = (int)db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
if ($count > 0) { http_response_code(404); exit('راه‌اندازی اولیه قبلاً انجام شده است.'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    if ($name === '' || !$email || strlen($password) < 12) $error = 'نام و ایمیل معتبر و رمز عبور حداقل ۱۲ کاراکتری وارد کنید.';
    else {
        $stmt = db()->prepare('INSERT INTO admins(name,email,password_hash,role) VALUES(?,?,?,?)');
        $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),'owner']);
        redirect('/admin/login.php?created=1');
    }
}
?><!doctype html><html lang="fa" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>ساخت مدیر اصلی</title><link rel="stylesheet" href="/assets/style.css"><body><main class="auth-wrap"><form class="panel" method="post"><h1>ساخت مدیر اصلی فروشگاه</h1><p>این صفحه فقط تا پیش از ساخت اولین مدیر فعال است.</p><?php if($error): ?><p class="error"><?=e($error)?></p><?php endif ?><input type="hidden" name="_csrf" value="<?=e(csrf_token())?>"><label>نام<input name="name" required></label><label>ایمیل<input type="email" name="email" required></label><label>رمز عبور (حداقل ۱۲ کاراکتر)<input type="password" name="password" minlength="12" required></label><button class="primary">ایجاد مدیر اصلی</button></form></main></body></html>

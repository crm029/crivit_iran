<?php
declare(strict_types=1);
require dirname(__DIR__,2) . '/app/bootstrap.php';
require_roles(['manager']);
$pdo=db(); $error=''; $saved=false;
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();
 $siteName=trim($_POST['site_name']??'');
 if($siteName==='') $error='نام فروشگاه را وارد کنید.';
 else {
  $logoPath=setting('site_logo','');
  if(isset($_FILES['logo']) && $_FILES['logo']['error']!==UPLOAD_ERR_NO_FILE){
   if($_FILES['logo']['error']!==UPLOAD_ERR_OK || $_FILES['logo']['size']>5242880) $error='فایل لوگو معتبر نیست یا حجم آن بیشتر از ۵ مگابایت است.';
   else {
    $mime=(new finfo(FILEINFO_MIME_TYPE))->file($_FILES['logo']['tmp_name']);
    $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if(!isset($allowed[$mime])) $error='فرمت لوگو باید JPG، PNG یا WebP باشد.';
    else {
     $dir=dirname(__DIR__).'/uploads/branding'; if(!is_dir($dir)) mkdir($dir,0755,true);
     $filename='logo-'.bin2hex(random_bytes(12)).'.'.$allowed[$mime];
     if(move_uploaded_file($_FILES['logo']['tmp_name'],$dir.'/'.$filename)) $logoPath='/uploads/branding/'.$filename;
     else $error='بارگذاری لوگو انجام نشد.';
    }
   }
  }
  if(!$error){
   $q=$pdo->prepare('INSERT INTO site_settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)');
   $q->execute(['site_name',$siteName]); $q->execute(['site_logo',$logoPath]);
   $saved=true;
  }
 }
}
?><!doctype html><html lang="fa" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>تنظیمات فروشگاه</title><link rel="stylesheet" href="/assets/style.css"><body><main class="admin-wrap"><a href="/admin/dashboard.php">← داشبورد</a><h1>تنظیمات فروشگاه</h1><?php if($error):?><p class="error"><?=e($error)?></p><?php endif?><?php if($saved):?><p class="success">تنظیمات ذخیره شد.</p><?php endif?><section class="panel"><form method="post" enctype="multipart/form-data" class="admin-form"><input type="hidden" name="_csrf" value="<?=e(csrf_token())?>"><label>نام فروشگاه<input name="site_name" value="<?=e(setting('site_name','CRIVIT IRAN'))?>" required></label><label>بارگذاری لوگوی فروشگاه<input type="file" name="logo" accept="image/jpeg,image/png,image/webp"><small>فرمت JPG، PNG یا WebP؛ حداکثر ۵ مگابایت. لوگو در هدر سایت نمایش داده می‌شود.</small></label><?php if(setting('site_logo')):?><div><img src="<?=e(setting('site_logo'))?>" alt="لوگوی فعلی" style="max-width:180px;max-height:90px;object-fit:contain"></div><?php endif?><button class="primary">ذخیره تنظیمات</button></form></section></main></body></html>
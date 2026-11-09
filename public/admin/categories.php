<?php
declare(strict_types=1);
require dirname(__DIR__,2) . '/app/bootstrap.php'; require_admin();
$pdo=db(); $error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 csrf_check(); $action=$_POST['action']??'';
 if ($action==='save') {
   $name=trim($_POST['name']??''); $parent=($_POST['parent_id']??'')!==''?(int)$_POST['parent_id']:null;
   $description=trim($_POST['description']??''); $slug=slugify($_POST['slug']??$name);
   if ($name==='') $error='نام دسته‌بندی الزامی است.';
   else {
     try { $q=$pdo->prepare('INSERT INTO categories(parent_id,name,slug,description,sort_order,is_active) VALUES(?,?,?,?,?,?)');
       $q->execute([$parent,$name,$slug,$description,(int)($_POST['sort_order']??0),isset($_POST['is_active'])?1:0]);
       redirect('/admin/categories.php?saved=1');
     } catch(PDOException $e) { $error='ذخیره انجام نشد؛ احتمالاً نامک تکراری است.'; }
   }
 } elseif ($action==='delete') {
   $id=(int)($_POST['id']??0); $pdo->prepare('DELETE FROM categories WHERE id=?')->execute([$id]); redirect('/admin/categories.php?deleted=1');
 }
}
$categories=$pdo->query('SELECT c.*,p.name AS parent_name FROM categories c LEFT JOIN categories p ON p.id=c.parent_id ORDER BY COALESCE(c.parent_id,c.id),c.sort_order,c.name')->fetchAll();
?><!doctype html><html lang="fa" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>دسته‌بندی‌ها</title><link rel="stylesheet" href="/assets/style.css"><body><main class="admin-wrap"><a href="/admin/dashboard.php">← داشبورد</a><h1>مدیریت دسته‌بندی‌ها</h1><?php if($error): ?><p class="error"><?=e($error)?></p><?php endif ?><?php if(isset($_GET['saved'])): ?><p class="success">دسته‌بندی ذخیره شد.</p><?php endif ?><section class="panel"><h2>افزودن دسته‌بندی</h2><form method="post" class="admin-form"><input type="hidden" name="_csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="action" value="save"><label>نام دسته‌بندی<input name="name" required></label><label>نامک (اختیاری)<input name="slug" placeholder="به‌صورت خودکار ساخته می‌شود"></label><label>دسته‌بندی والد<select name="parent_id"><option value="">بدون والد (اصلی)</option><?php foreach($categories as $c): ?><option value="<?=$c['id']?>"><?=e($c['name'])?></option><?php endforeach ?></select></label><label>توضیحات<textarea name="description"></textarea></label><label>ترتیب نمایش<input type="number" name="sort_order" value="0"></label><label class="check"><input type="checkbox" name="is_active" checked> فعال باشد</label><button class="primary">ذخیره دسته‌بندی</button></form></section><section class="panel"><h2>دسته‌بندی‌های ثبت‌شده (<?=count($categories)?>)</h2><div class="table-wrap"><table><thead><tr><th>نام</th><th>والد</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody><?php foreach($categories as $c): ?><tr><td><?=e($c['name'])?></td><td><?=e($c['parent_name']??'—')?></td><td><?=$c['is_active']?'فعال':'غیرفعال'?></td><td><form method="post" onsubmit="return confirm('این دسته‌بندی حذف شود؟')"><input type="hidden" name="_csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$c['id']?>"><button class="danger">حذف</button></form></td></tr><?php endforeach ?></tbody></table></div></section></main></body></html>

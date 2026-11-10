<?php
declare(strict_types=1);
require dirname(__DIR__,2) . '/app/bootstrap.php';
require_roles(['manager','warehouse']);
$pdo=db();$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();$productId=(int)($_POST['product_id']??0);$type=$_POST['movement_type']??'';$qty=max(0,(int)($_POST['quantity']??0));$note=trim($_POST['note']??'');
 if(!$productId||!in_array($type,['in','out','adjustment'],true)||$qty<1)$error='اطلاعات عملیات انبار معتبر نیست.';
 else{try{$pdo->beginTransaction();$q=$pdo->prepare('SELECT stock_quantity FROM products WHERE id=? FOR UPDATE');$q->execute([$productId]);$current=$q->fetchColumn();if($current===false)throw new RuntimeException();$current=(int)$current;
  if($type==='in')$next=$current+$qty;elseif($type==='out')$next=$current-$qty;else $next=$qty;
  if($next<0)throw new RuntimeException();
  $pdo->prepare('UPDATE products SET stock_quantity=?,stock_status=? WHERE id=?')->execute([$next,$next>0?'in_stock':'out_of_stock',$productId]);
  $pdo->prepare('INSERT INTO inventory_movements(product_id,admin_id,movement_type,quantity,note) VALUES(?,?,?,?,?)')->execute([$productId,admin_user()['id'],$type,$type==='adjustment'?abs($next-$current):$qty,$note]);
  $pdo->commit();redirect('/admin/inventory.php?saved=1');
 }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$error='عملیات انجام نشد؛ موجودی یا محصول را بررسی کنید.';}}
}
$products=$pdo->query('SELECT id,name,sku,stock_quantity FROM products ORDER BY name')->fetchAll();
$logs=$pdo->query('SELECT m.*,p.name AS product_name,a.name AS admin_name FROM inventory_movements m JOIN products p ON p.id=m.product_id JOIN admins a ON a.id=m.admin_id ORDER BY m.id DESC LIMIT 50')->fetchAll();
?><!doctype html><html lang="fa" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>مدیریت انبار</title><link rel="stylesheet" href="/assets/style.css"><body><main class="admin-wrap"><a href="/admin/dashboard.php">← داشبورد</a><h1>مدیریت انبار</h1><?php if($error):?><p class="error"><?=e($error)?></p><?php endif?><?php if(isset($_GET['saved'])):?><p class="success">موجودی به‌روزرسانی شد.</p><?php endif?><section class="panel"><h2>ثبت عملیات انبار</h2><form method="post" class="admin-form"><input type="hidden" name="_csrf" value="<?=e(csrf_token())?>"><label>محصول<select name="product_id" required><?php foreach($products as $p):?><option value="<?=$p['id']?>"><?=e($p['name'])?> — موجودی فعلی: <?=number_format((int)$p['stock_quantity'])?></option><?php endforeach?></select></label><label>نوع عملیات<select name="movement_type"><option value="in">ورود کالا</option><option value="out">خروج کالا</option><option value="adjustment">اصلاح موجودی (عدد نهایی)</option></select></label><label>تعداد<input type="number" name="quantity" min="1" required></label><label>توضیحات<input name="note" maxlength="500"></label><button class="primary">ثبت عملیات</button></form></section><section class="panel"><h2>سوابق اخیر انبار</h2><div class="table-wrap"><table><thead><tr><th>محصول</th><th>نوع عملیات</th><th>تعداد</th><th>کاربر</th><th>تاریخ</th></tr></thead><tbody><?php foreach($logs as $l):?><tr><td><?=e($l['product_name'])?></td><td><?=$l['movement_type']==='in'?'ورود':($l['movement_type']==='out'?'خروج':'اصلاح')?></td><td><?=number_format((int)$l['quantity'])?></td><td><?=e($l['admin_name'])?></td><td><?=e($l['created_at'])?></td></tr><?php endforeach?></tbody></table></div></section></main></body></html>
<?php
declare(strict_types=1);
require dirname(__DIR__,2) . '/app/bootstrap.php'; require_admin();
$stats=[
 'products'=>(int)db()->query('SELECT COUNT(*) FROM products')->fetchColumn(),
 'categories'=>(int)db()->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
 'orders'=>(int)db()->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
 'pending'=>(int)db()->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn()
];
?><!doctype html><html lang="fa" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>مدیریت فروشگاه</title><link rel="stylesheet" href="/assets/style.css"><body><main class="admin-wrap"><header class="admin-head"><div><b>پنل مدیریت CRIVIT</b><small>سلام <?=e(admin_user()['name'])?></small></div><form method="post" action="/admin/logout.php"><input type="hidden" name="_csrf" value="<?=e(csrf_token())?>"><button class="quiet">خروج</button></form></header><h1>نمای کلی فروشگاه</h1><div class="stats"><?php foreach(['products'=>'محصولات','categories'=>'دسته‌بندی‌ها','orders'=>'سفارش‌ها','pending'=>'سفارش‌های در انتظار'] as $key=>$label): ?><div class="stat"><strong><?=number_format($stats[$key])?></strong><span><?=e($label)?></span></div><?php endforeach ?></div><div class="admin-links"><a href="/admin/categories.php">مدیریت دسته‌بندی‌ها</a><a href="/admin/products.php">مدیریت محصولات</a><a href="/">مشاهده فروشگاه</a></div></main></body></html>

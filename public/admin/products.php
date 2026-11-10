<?php
declare(strict_types=1);
require dirname(__DIR__,2) . '/app/bootstrap.php'; require_roles(['manager']);
$pdo=db(); $error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 csrf_check(); $name=trim($_POST['name']??''); $slug=slugify($_POST['slug']??$name);
 $price=(float)($_POST['price']??0); $sale=($_POST['sale_price']??'')!==''?(float)$_POST['sale_price']:null;
 $stock=max(0,(int)($_POST['stock_quantity']??0)); $status=in_array($_POST['status']??'draft',['draft','published','archived'],true)?$_POST['status']:'draft';
 $categoryIds=array_values(array_unique(array_filter(array_map('intval',$_POST['categories']??[]))));
 if ($name==='' || $price<0 || ($sale!==null && ($sale<0 || $sale>$price))) $error='نام و قیمت معتبر وارد کنید؛ قیمت تخفیف نباید از قیمت اصلی بیشتر باشد.';
 else {
  try {
   $pdo->beginTransaction();
   $stmt=$pdo->prepare('INSERT INTO products(name,slug,summary,description,price,sale_price,stock_quantity,stock_status,status,published_at) VALUES(?,?,?,?,?,?,?,?,?,?)');
   $stmt->execute([$name,$slug,trim($_POST['summary']??''),trim($_POST['description']??''),$price,$sale,$stock,$stock>0?'in_stock':'out_of_stock',$status,$status==='published'?date('Y-m-d H:i:s'):null]);
   $id=(int)$pdo->lastInsertId();
   $link=$pdo->prepare('INSERT INTO product_categories(product_id,category_id) VALUES(?,?)');
   foreach($categoryIds as $categoryId) $link->execute([$id,$categoryId]);
   if (!empty($_FILES['images']['name'][0])) {
    $dir=dirname(__DIR__).'/uploads/products'; if(!is_dir($dir)) mkdir($dir,0755,true);
    $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    foreach($_FILES['images']['tmp_name'] as $i=>$tmp) {
     if($_FILES['images']['error'][$i]!==UPLOAD_ERR_OK) continue;
     if($_FILES['images']['size'][$i]>(int)envv('UPLOAD_MAX_BYTES','5242880')) continue;
     $mime=(new finfo(FILEINFO_MIME_TYPE))->file($tmp); if(!isset($allowed[$mime])) continue;
     $filename=bin2hex(random_bytes(16)).'.'.$allowed[$mime];
     if(move_uploaded_file($tmp,$dir.'/'.$filename)) {
      $pdo->prepare('INSERT INTO product_images(product_id,image_path,is_primary,sort_order) VALUES(?,?,?,?)')->execute([$id,'/uploads/products/'.$filename,$i===0?1:0,$i]);
     }
    }
   }
   $pdo->commit(); redirect('/admin/products.php?saved=1');
  } catch(Throwable $e) { if($pdo->inTransaction())$pdo->rollBack(); $error='ثبت محصول انجام نشد؛ نامک را بررسی کنید.'; }
 }
}
$categories=$pdo->query('SELECT id,name,parent_id FROM categories WHERE is_active=1 ORDER BY sort_order,name')->fetchAll();
$products=$pdo->query('SELECT p.id,p.name,p.price,p.sale_price,p.stock_quantity,p.status,GROUP_CONCAT(c.name SEPARATOR "، ") AS category_names FROM products p LEFT JOIN product_categories pc ON pc.product_id=p.id LEFT JOIN categories c ON c.id=pc.category_id GROUP BY p.id ORDER BY p.id DESC LIMIT 100')->fetchAll();
?><!doctype html><html lang="fa" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>محصولات</title><link rel="stylesheet" href="/assets/style.css"><body><main class="admin-wrap"><a href="/admin/dashboard.php">← داشبورد</a><h1>مدیریت محصولات</h1><?php if($error): ?><p class="error"><?=e($error)?></p><?php endif ?><?php if(isset($_GET['saved'])): ?><p class="success">محصول ثبت شد.</p><?php endif ?><section class="panel"><h2>افزودن محصول</h2><form method="post" enctype="multipart/form-data" class="admin-form"><input type="hidden" name="_csrf" value="<?=e(csrf_token())?>"><label>نام محصول<input name="name" required></label><label>نامک (اختیاری)<input name="slug"></label><label>خلاصه<input name="summary"></label><label>توضیحات<textarea name="description" rows="5"></textarea></label><div class="two-col"><label>قیمت اصلی (تومان)<input type="number" name="price" min="0" step="1" required></label><label>قیمت با تخفیف<input type="number" name="sale_price" min="0" step="1"></label><label>موجودی<input type="number" name="stock_quantity" min="0" value="0"></label><label>وضعیت<select name="status"><option value="draft">پیش‌نویس</option><option value="published">انتشار</option><option value="archived">بایگانی</option></select></label></div><label>دسته‌بندی‌ها<select name="categories[]" multiple size="6"><?php foreach($categories as $c): ?><option value="<?=$c['id']?>"><?=str_repeat('— ',(int)($c['parent_id']?1:0)).e($c['name'])?></option><?php endforeach ?></select><small>برای انتخاب چند دسته، کلید Ctrl را نگه دارید.</small></label><label>تصاویر محصول<input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple><small>فرمت مجاز: JPG، PNG، WebP؛ حداکثر حجم هر تصویر طبق تنظیمات سرور.</small></label><button class="primary">ثبت محصول</button></form></section><section class="panel"><h2>محصولات ثبت‌شده</h2><div class="table-wrap"><table><thead><tr><th>محصول</th><th>قیمت</th><th>موجودی</th><th>دسته‌بندی</th><th>وضعیت</th></tr></thead><tbody><?php foreach($products as $p): ?><tr><td><?=e($p['name'])?></td><td><?=number_format((float)$p['price'])?></td><td><?=$p['stock_quantity']?></td><td><?=e($p['category_names']??'—')?></td><td><?=e($p['status'])?></td></tr><?php endforeach ?></tbody></table></div></section></main></body></html>

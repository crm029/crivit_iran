<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

$pdo = db();
$categories = $pdo->query("SELECT id,name,slug,image_path FROM categories WHERE is_active=1 AND parent_id IS NULL ORDER BY sort_order,name")->fetchAll();
$products = $pdo->query("SELECT p.id,p.name,p.slug,p.summary,p.price,p.sale_price,p.stock_status,
    (SELECT pi.image_path FROM product_images pi WHERE pi.product_id=p.id ORDER BY pi.is_primary DESC,pi.sort_order ASC LIMIT 1) AS image_path
    FROM products p WHERE p.status='published' ORDER BY p.is_featured DESC,p.published_at DESC,p.id DESC LIMIT 12")->fetchAll();
$siteName = setting('site_name', 'Crivit Iran');
?><!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($siteName) ?> | فروشگاه لوازم ورزشی</title>
<meta name="description" content="<?= e(setting('seo_home_description','فروشگاه آنلاین لوازم و تجهیزات ورزشی')) ?>">
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="site-header">
  <a class="brand" href="/"><?= e($siteName) ?></a>
  <form class="search" action="/" method="get"><input name="q" placeholder="جستجوی محصولات..." value="<?= e($_GET['q'] ?? '') ?>"><button>جستجو</button></form>
  <nav><a href="/">خانه</a><a href="/cart.php">سبد خرید</a><a href="/admin/login.php">مدیریت</a></nav>
</header>
<main class="container">
  <section class="hero"><div><span class="eyebrow">قدرت حرکت، انتخاب هوشمند</span><h1><?= e(setting('hero_title','برای هر هدفی، تجهیزات درست را انتخاب کن')) ?></h1><p><?= e(setting('hero_subtitle','محصولات ورزشی مورد نیازت را در یک تجربه خرید ساده پیدا کن.')) ?></p></div></section>
  <?php if ($categories): ?><section><div class="section-head"><h2>دسته‌بندی‌ها</h2></div><div class="category-grid">
    <?php foreach ($categories as $category): ?><a class="category-card" href="/category.php?slug=<?= rawurlencode($category['slug']) ?>">
      <?php if ($category['image_path']): ?><img src="<?= e($category['image_path']) ?>" alt="<?= e($category['name']) ?>"><?php endif ?>
      <strong><?= e($category['name']) ?></strong></a><?php endforeach ?>
  </div></section><?php endif ?>
  <section><div class="section-head"><h2>محصولات منتخب</h2></div>
    <?php if (!$products): ?><p class="empty">هنوز محصولی منتشر نشده است. مدیر فروشگاه می‌تواند از پنل مدیریت محصولات را اضافه کند.</p><?php else: ?>
    <div class="product-grid"><?php foreach ($products as $product): ?><article class="product-card">
      <a href="/product.php?slug=<?= rawurlencode($product['slug']) ?>">
      <?php if ($product['image_path']): ?><img src="<?= e($product['image_path']) ?>" alt="<?= e($product['name']) ?>"><?php else: ?><div class="image-placeholder">CRIVIT</div><?php endif ?>
      <h3><?= e($product['name']) ?></h3></a>
      <?php if ($product['sale_price'] !== null): ?><p class="old-price"><?= number_format((float)$product['price']) ?> تومان</p><strong><?= number_format((float)$product['sale_price']) ?> تومان</strong>
      <?php else: ?><strong><?= number_format((float)$product['price']) ?> تومان</strong><?php endif ?>
    </article><?php endforeach ?></div><?php endif ?>
  </section>
</main>
<footer><span><?= e($siteName) ?></span><span><?= e(setting('footer_text','خرید مطمئن، انتخاب حرفه‌ای')) ?></span></footer>
</body></html>

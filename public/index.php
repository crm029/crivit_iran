<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

$pdo = db();
$categories = $pdo->query("SELECT id,name,slug,image_path FROM categories WHERE is_active=1 AND parent_id IS NULL ORDER BY sort_order,name")->fetchAll();
$products = $pdo->query("SELECT p.id,p.name,p.slug,p.summary,p.price,p.sale_price,p.stock_status,p.is_featured,
    (SELECT pi.image_path FROM product_images pi WHERE pi.product_id=p.id ORDER BY pi.is_primary DESC,pi.sort_order ASC LIMIT 1) AS image_path
    FROM products p WHERE p.status='published' ORDER BY p.is_featured DESC,p.published_at DESC,p.id DESC LIMIT 20")->fetchAll();
$siteName = setting('site_name', 'Crivit Iran');\n$siteLogo = setting('site_logo', '');
$searchTerm = trim((string)($_GET['q'] ?? ''));
if ($searchTerm !== '') {
    $stmt = $pdo->prepare("SELECT p.id,p.name,p.slug,p.summary,p.price,p.sale_price,p.stock_status,p.is_featured,
        (SELECT pi.image_path FROM product_images pi WHERE pi.product_id=p.id ORDER BY pi.is_primary DESC,pi.sort_order ASC LIMIT 1) AS image_path
        FROM products p WHERE p.status='published' AND (p.name LIKE :q OR p.summary LIKE :q)
        ORDER BY p.is_featured DESC,p.id DESC LIMIT 32");
    $stmt->execute(['q' => '%' . $searchTerm . '%']);
    $products = $stmt->fetchAll();
}
function renderProducts(array $items): void { ?>
  <?php if (!$items): ?><div class="empty-state">فعلاً محصولی برای نمایش وجود ندارد.</div><?php else: ?>
  <div class="product-grid"><?php foreach ($items as $product): ?>
    <article class="product-card">
      <a class="product-image" href="/product.php?slug=<?= rawurlencode($product['slug']) ?>">
        <?php if (!empty($product['image_path'])): ?><img loading="lazy" src="<?= e($product['image_path']) ?>" alt="<?= e($product['name']) ?>"><?php else: ?><div class="image-placeholder"><span>CRIVIT</span></div><?php endif ?>
        <?php if (!empty($product['sale_price'])): ?><span class="sale-badge">پیشنهاد ویژه</span><?php endif ?>
      </a>
      <div class="product-info">
        <a href="/product.php?slug=<?= rawurlencode($product['slug']) ?>" class="product-name"><?= e($product['name']) ?></a>
        <div class="product-bottom">
          <span class="stock-label <?= ($product['stock_status'] ?? '') === 'in_stock' ? 'available' : 'unavailable' ?>"><?= ($product['stock_status'] ?? '') === 'in_stock' ? 'موجود' : 'استعلام موجودی' ?></span>
          <div class="price"><?php if (!empty($product['sale_price'])): ?><del><?= number_format((float)$product['price']) ?></del><strong><?= number_format((float)$product['sale_price']) ?> <small>تومان</small></strong><?php else: ?><strong><?= number_format((float)$product['price']) ?> <small>تومان</small></strong><?php endif ?></div>
        </div>
      </div>
    </article>
  <?php endforeach ?></div><?php endif ?>
<?php } ?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($siteName) ?> | فروشگاه لوازم ورزشی</title>
<meta name="description" content="<?= e(setting('seo_home_description','فروشگاه آنلاین لوازم و تجهیزات ورزشی')) ?>">
<link rel="stylesheet" href="/assets/style.css">
</head>
<body class="storefront">
<div class="topline"><div class="topline-inner"><span>تجربه‌ای تازه برای خرید تجهیزات ورزشی</span><span>پشتیبانی و پاسخگویی به مشتریان</span></div></div>
<header class="store-header">
  <a class="brand" href="/"><?php if ($siteLogo): ?><img class="brand-logo" src="<?= e($siteLogo) ?>" alt="<?= e($siteName) ?>"><?php else: ?><span class="brand-mark">C</span><?php endif ?><span><?= e($siteName) ?><small>SPORT & OUTDOOR</small></span></a>
  <form class="search" action="/" method="get"><button aria-label="جستجو">⌕</button><input name="q" placeholder="دنبال چه محصولی می‌گردید؟" value="<?= e($searchTerm) ?>"></form>
  <div class="header-actions"><a href="/admin/login.php" class="account-link"><span class="action-icon">♙</span><span>ورود / عضویت</span></a><a href="/cart.php" class="cart-link"><span class="action-icon">🛒</span><span>سبد خرید</span><b>۰</b></a></div>
</header>
<nav class="main-nav"><div class="nav-inner"><a href="/" class="nav-home">خانه</a><a href="#products">فروشگاه</a><?php foreach ($categories as $category): ?><a href="/category.php?slug=<?= rawurlencode($category['slug']) ?>"><?= e($category['name']) ?></a><?php endforeach ?><a href="#offers" class="nav-offer">پیشنهادهای ویژه</a></div></nav>
<main class="store-main">
  <?php if ($searchTerm !== ''): ?>
    <section class="listing-heading"><span>نتایج جستجو</span><h1>نتایج برای «<?= e($searchTerm) ?>»</h1></section>
    <?php renderProducts($products); ?>
  <?php else: ?>
    <section class="hero-banner">
      <div class="hero-copy"><span class="hero-kicker">MOVE WITH CONFIDENCE</span><h1><?= e(setting('hero_title','برای ماجراجویی بعدی آماده‌ای؟')) ?></h1><p><?= e(setting('hero_subtitle','تجهیزات مناسب، شروع یک تجربه ورزشی متفاوت است.')) ?></p><a href="#products" class="hero-button">مشاهده محصولات <span>←</span></a></div>
      <div class="hero-art" aria-hidden="true"><div class="hero-circle"></div><div class="hero-word">ACTIVE<br>EVERYDAY</div><div class="hero-orbit"></div><div class="hero-chip">SPORT / OUTDOOR</div></div>
      <div class="hero-pagination"><i class="active"></i><i></i><i></i></div>
    </section>
    <section class="service-strip"><div><b>✓</b><span><strong>خرید آسان</strong><small>تجربه خرید سریع و مطمئن</small></span></div><div><b>↗</b><span><strong>تنوع محصولات</strong><small>انتخاب‌های متنوع ورزشی</small></span></div><div><b>◈</b><span><strong>پشتیبانی مشتریان</strong><small>همراه شما در مسیر خرید</small></span></div><div><b>⌂</b><span><strong>ارسال سفارش</strong><small>پیگیری آسان سفارش‌ها</small></span></div></section>
    <?php if ($categories): ?><section class="store-section"><div class="section-title"><div><span class="section-kicker">DISCOVER YOUR STYLE</span><h2>دسته‌بندی محصولات</h2></div></div><div class="category-showcase"><?php foreach ($categories as $i=>$category): ?><a class="category-tile tile-<?= $i%4 ?>" href="/category.php?slug=<?= rawurlencode($category['slug']) ?>"><?php if ($category['image_path']): ?><img loading="lazy" src="<?= e($category['image_path']) ?>" alt="<?= e($category['name']) ?>"><?php else: ?><span class="category-symbol"><?= ['◉','↗','✳','◇'][$i%4] ?></span><?php endif ?><span class="category-tile-name"><?= e($category['name']) ?></span><span class="category-arrow">←</span></a><?php endforeach ?></div></section><?php endif ?>
    <section class="store-section" id="products"><div class="section-title"><div><span class="section-kicker">LATEST ARRIVALS</span><h2>جدیدترین محصولات</h2></div><a href="#products" class="view-all">مشاهده همه <span>←</span></a></div><?php renderProducts($products); ?></section>
    <section class="promo-banner" id="offers"><div><span>READY FOR YOUR NEXT MOVE?</span><h2>هر روز، یک قدم جلوتر</h2><p>تجهیزات مناسب برای مسیر ورزشی تو</p><a href="#products">شروع انتخاب <b>←</b></a></div><div class="promo-graphic">CRIVIT<span>SPORT</span></div></section>
    <section class="store-section"><div class="section-title"><div><span class="section-kicker">PICK YOUR FAVORITE</span><h2>محصولات منتخب</h2></div><a href="#products" class="view-all">مشاهده همه <span>←</span></a></div><?php renderProducts(array_slice($products,0,8)); ?></section>
  <?php endif ?>
</main>
<footer class="store-footer"><div class="footer-brand"><strong><?= e($siteName) ?></strong><p><?= e(setting('footer_text','همراه تو برای انتخاب بهتر و حرکت بیشتر.')) ?></p></div><div><strong>دسترسی سریع</strong><a href="/">صفحه اصلی</a><a href="#products">محصولات</a></div><div><strong>راهنمای خرید</strong><a href="/cart.php">سبد خرید</a><a href="/admin/login.php">حساب کاربری</a></div><div class="footer-copy">© تمامی حقوق برای <?= e($siteName) ?> محفوظ است.</div></footer>
</body></html>

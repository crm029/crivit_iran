# Crivit Iran — Dynamic Sports Store

A database-driven ecommerce platform. Store owners manage the catalog, categories, navigation, homepage content, products, inventory, and orders from the admin dashboard. Product and category catalogs are not hard-coded in templates.

## Stack
- PHP 8.2+
- MySQL 8+
- Responsive RTL storefront
- PDO prepared statements
- Session-based admin authentication, password hashing and CSRF tokens

## Current implementation
- Database schema for administrators, nested categories, products, product images, admin-defined attributes, customers, carts, orders, banners, navigation and site settings
- One-time first-admin setup
- Administrator login/logout and dashboard
- Create/list/delete categories; nested parent categories
- Create/list products, assign multiple categories, upload multiple JPG/PNG/WebP images
- Database-driven homepage, category listings and product detail pages

## Local setup
1. Create a MySQL database and import `database/schema.sql`.
2. Copy `.env.example` to `.env` and set database credentials.
3. Point the web server document root to `public/`.
4. Visit `/setup.php` once to create the first administrator.
5. Sign in at `/admin/login.php`.
6. Do not expose `.env`, `app/`, or `database/` from the web server. In production, configure the web server so only `public/` is document root.

## Important
This is an active foundation, not a production-ready finished store yet. Product editing/deletion, category editing, customer accounts, cart and checkout, payment gateway, order management, search/filtering, dynamic homepage/banner/navigation settings, automated tests, and deployment hardening remain to be completed and verified. Configure HTTPS, backups, rate limiting, upload limits, and production error logging before launch.

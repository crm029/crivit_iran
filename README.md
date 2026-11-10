# Crivit Iran — Dynamic Sports Store

PHP 8.2+ / MySQL 8+ RTL ecommerce project with a responsive storefront and role-based admin area.

## Storefront
- Reference-inspired white/blue design, responsive header, search, category tiles, hero and product cards
- Store logo and store name are controlled from the admin panel
- Product and category data are database-driven
- Standalone static preview: `public/preview.html`

## Admin roles
- `owner`: private full-access account; not listed in the ordinary admin user directory
- `manager`: store-wide admin for catalog, categories and store branding
- `warehouse`: stock operations and inventory history only
- Permissions are enforced server-side, not only hidden in the interface

## Catalog
- Upload products with multiple JPG/PNG/WebP images
- Admin-managed categories
- Optional demo catalog: import `database/demo-seed.sql` once after `database/schema.sql` on a fresh database to add 6 categories and 20 sample products

## Local setup
1. For a fresh database, import `database/schema.sql`. For an existing database created from the earlier schema, apply `database/migrations/001_warehouse_role_and_inventory.sql`.
2. Copy `.env.example` to `.env` and configure MySQL credentials.
3. Configure the web server document root as `public/`.
4. Open `/setup.php` once to create the private initial owner account.
5. Import `database/demo-seed.sql` if you want the sample catalog.
6. Sign in at `/admin/login.php`; use the dashboard to create the manager and warehouse accounts.

## Security / current status
- PDO prepared statements, password hashing, CSRF checks, session regeneration and server-side role checks are in place.
- This is an active build, not yet production-ready. Cart/checkout, payments, customer accounts, full order management, product editing, category image uploads, additional SEO controls, automated tests, and production deployment hardening still need implementation and verification.
- Before launch: enforce HTTPS, restrict the web root to `public/`, disable public access to setup after first run, configure backups, upload limits, rate limiting and production error logging.

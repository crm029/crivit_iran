# Crivit Iran — Dynamic Sports Store

A database-driven ecommerce platform. Store owners manage the catalog, categories, navigation, homepage content, products, inventory, and orders from the admin dashboard. No product/category catalog is hard-coded in templates.

## Planned stack
- PHP 8.2+
- MySQL 8+
- Server-rendered responsive storefront
- Session-based admin authentication with password hashing and CSRF protection
- PDO prepared statements
- Local development with PHP built-in server or XAMPP

## Core data model
- Categories: unlimited nested categories, image, description, slug, sort order, active state
- Products: title, slug, description, SKU, price, sale price, stock, status, multiple images
- Product-category relation: many-to-many
- Product attributes and attribute values: admin-defined
- Users, addresses, carts, orders, order items
- Site settings, navigation items, homepage sections and banners

## Local setup
1. Create a MySQL database and import `database/schema.sql`.
2. Copy `.env.example` to `.env` and set database credentials.
3. Configure the web server document root to `public/`.
4. Open the site and complete the first-admin setup flow (to be implemented before production).
5. Never commit real secrets or production credentials.

## Project status
Foundation stage. Authentication, admin CRUD, storefront, checkout, uploads, tests, and deployment hardening are to be implemented and verified in subsequent commits.

## Security notes
Do not expose `.env`, `database/`, or private upload files through the web server. Use HTTPS in production, restrict upload MIME types and file sizes, rotate secrets, and configure automated database backups.

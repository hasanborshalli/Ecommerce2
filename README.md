# E-Commerce Template #2 — Vaulted

**Sharp Modern Commerce** · Laravel 11 · MySQL · No build step required

A fully-featured e-commerce template with storefront, cart, checkout, and a complete admin panel. Designed by [brndng.](https://brndnglb.com) — customise the brand colours and logo, and it ships.

---

## Stack

| Layer        | Technology                                 |
| ------------ | ------------------------------------------ |
| Framework    | Laravel 11 (PHP 8.2+)                      |
| Database     | MySQL 8+ (via phpMyAdmin or CLI)           |
| Frontend     | Vanilla JS + CSS custom properties         |
| Charts       | Chart.js 4 (CDN, no npm needed)            |
| Images       | Laravel Storage + `storage:link`           |
| Auth (admin) | Custom session-based (no Breeze/Jetstream) |

---

## Quick Start

```bash
# 1. Install dependencies
composer install

# 2. Copy environment file and set your values
cp .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Create the MySQL database
#    In phpMyAdmin: create a database named `ecom_template_2`
#    Then set DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env

# 5. Run migrations + seed demo data
php artisan migrate --seed

# 6. Create the storage symlink (for product images / logo)
php artisan storage:link

# 7. Serve
php artisan serve
```

Visit **http://localhost:8000** for the storefront.
Visit **http://localhost:8000/admin** for the admin panel.

---

## Admin Credentials

Set in `.env`:

```
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=changeme123
```

**Change both before going live.**

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php          ← storefront pages
│   │   ├── ProductController.php       ← shop + product detail
│   │   ├── CartController.php          ← session cart (AJAX)
│   │   ├── CheckoutController.php      ← order placement + stock deduction
│   │   ├── ContactController.php
│   │   └── Admin/
│   │       ├── AdminAuthController.php
│   │       ├── AdminDashboardController.php
│   │       ├── AdminProductController.php
│   │       ├── AdminCategoryController.php
│   │       ├── AdminOrderController.php
│   │       ├── AdminStockController.php
│   │       ├── AdminPurchaseOrderController.php
│   │       ├── AdminSupplierController.php
│   │       ├── AdminReportController.php
│   │       ├── AdminMessageController.php
│   │       └── AdminSettingController.php
│   └── Middleware/
│       └── AdminAuth.php
├── Models/
│   ├── Product.php                     ← addStock(), deductStock(), scopeLowStock()
│   ├── Category.php
│   ├── Order.php                       ← profit accessors, order number generation
│   ├── OrderItem.php
│   ├── PurchaseOrder.php               ← markReceived() atomically updates stock + cost
│   ├── PurchaseOrderItem.php
│   ├── StockMovement.php               ← full audit log
│   ├── Supplier.php
│   ├── SiteSetting.php                 ← cached key/value store
│   ├── HeroSlide.php
│   └── ContactMessage.php
└── Providers/
    ├── AppServiceProvider.php
    └── ViewServiceProvider.php         ← shares settings + cart count to all views

public/css/
├── variables.css   ← all design tokens (colours, spacing, typography)
├── base.css        ← reset + utility classes
├── layout.css      ← nav, footer, breadcrumb
├── components.css  ← buttons, badges, forms, tables, cards
├── shop.css        ← hero, product grid, product detail, cart
├── cart.css        ← cart sidebar, checkout, order confirmation
└── admin.css       ← full admin panel styles

resources/views/
├── layouts/app.blade.php               ← storefront layout
├── home / shop / product / cart / checkout / about / contact
└── admin/
    ├── layout.blade.php                ← admin sidebar + topbar
    ├── dashboard / products / categories / orders
    ├── stock / purchase-orders / suppliers
    ├── reports / messages / settings
```

---

## Key Design Decisions

### Stock Management

- Every stock change (purchase, sale, manual adjust) is logged in `stock_movements` with before/after snapshots — full audit trail.
- `products.cost_price` is updated every time a purchase order is received, keeping the cost accurate for margin calculations.
- `order_items.line_cost` and `line_profit` are **frozen at sale time** so historical profit reports stay accurate even if cost changes later.

### Cart

- Stored in the PHP session as an array keyed by `md5(product_id + serialised variant)`.
- AJAX endpoints return `cart_count` so the nav badge updates without page reload.

### Admin Auth

- Simple session-based login (no packages). Credentials live in `.env` → `config/admin.php`.
- Protected by `AdminAuth` middleware aliased as `admin.auth`.

### CSS Architecture

- **No Vite, no npm.** All CSS lives in `public/css/` and is linked directly.
- Design tokens are CSS custom properties in `variables.css` — swap colours there to re-brand.
- No `<style>` blocks inside Blade views.

---

## Customising for a Client

1. **Brand colours** — edit CSS variables in `public/css/variables.css`:
    ```css
    --navy: #0b1629; /* primary dark */
    --accent: #2563eb; /* electric blue */
    ```
2. **Logo** — upload via Admin → Settings → Store Logo, or drop a file in `public/images/`.
3. **Store name, tagline, social links** — Admin → Settings.
4. **Hero slides** — Admin → (seed data includes 2 slides; add/edit via `hero_slides` table or a future admin UI).
5. **Admin credentials** — set `ADMIN_EMAIL` + `ADMIN_PASSWORD` in `.env`.

---

## Reports & Exports

All reports support a custom date range and can be exported:

| Export | Format        | How                                                            |
| ------ | ------------- | -------------------------------------------------------------- |
| CSV    | `.csv`        | Direct download, opens in Excel / Google Sheets                |
| PDF    | Browser print | Opens a print-optimised HTML page → File → Print → Save as PDF |

Available reports: **Sales**, **Profit**, **By Product**, **By Category**.

---

## Migrations

Run in order (timestamps ensure this automatically):

```
000001 create_categories_table
000002 create_products_table
000003 create_suppliers_table
000004 create_purchase_orders_tables       ← creates both purchase_orders + purchase_order_items
000005 create_stock_movements_table
000006 create_orders_table
000007 create_order_items_table
000008 create_support_tables               ← contact_messages, site_settings, hero_slides
000009 add_expected_date_to_purchase_orders
```

---

## Seeder

`DatabaseSeeder` ships with:

- Store: **Vaulted** with 2 hero slides
- 6 categories, 12 active products
- 2 suppliers, 1 received purchase order
- 7 sample orders (mixed statuses/payments)
- 2 unread contact messages

Re-seed at any time:

```bash
php artisan migrate:fresh --seed
```

---

## Powered by [brndng.](https://brndnglb.com)

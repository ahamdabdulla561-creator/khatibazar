# খাঁটি বাজার (Khati Bajar) — Production Documentation & Deployment Guide

Welcome to **Khati Bajar**, a full production-ready E-Commerce platform for agricultural, veterinary, fish medicine, and organic farm products built with **PHP 8.2+**, **Laravel 11**, **MySQL**, **Blade Templates**, **Tailwind CSS**, and **Alpine.js**.

---

## 1. Environment & Requirements

- **PHP Version**: 8.2+ (with `gd`, `pdo_mysql`, `mbstring`, `fileinfo`, `zip`, `openssl` extensions)
- **Framework**: Laravel 11.x
- **Database**: MySQL / MariaDB (`khati_bajar`)
- **Composer**: Composer 2.x
- **Node.js**: v18+ (Optional for asset compilation, project uses Tailwind CDN & Alpine.js for instant rendering)

---

## 2. Default Seeded Credentials

### Admin Account
- **URL**: `http://localhost:8000/admin/login`
- **Email / Phone**: `admin@khatibajar.com` or `01711112222`
- **Password**: `admin123456`
- **Role**: `admin`

### Customer Account
- **URL**: `http://localhost:8000/login`
- **Phone / Email**: `01700000000` or `customer@khatibajar.com`
- **Password**: `123456`
- **Role**: `customer`

---

## 3. Database Configuration (`.env`)

```env
APP_NAME="Khati Bajar"
APP_ENV=local
APP_KEY=base64:B6W4pT5t0WjBW9Rf0NI8GT+Gwn6n7zlbJR9hTpcZu+8=
APP_DEBUG=true
APP_TIMEZONE=Asia/Dhaka
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=khati_bajar
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

---

## 4. Local Development Commands

To run the application locally:

```bash
# 1. Install dependencies
php composer.phar install --no-security-blocking

# 2. Run Database Migrations & Seeders
php artisan migrate:fresh --seed

# 3. Create Storage Link for Uploads
php artisan storage:link

# 4. Clear Caches
php artisan optimize:clear

# 5. Start Local Development Server
php artisan serve
```

---

## 5. Key System Architecture & Security Features

1. **Order Stock Logic & DB Transactions**:
   - Order placement wraps price verification and stock deduction inside strict database locks (`DB::transaction` with `lockForUpdate()`).
   - Price sent from frontend forms is ignored; actual database prices are computed on the server side.
   - Restores variant & product stock automatically when an admin cancels an order.
2. **Variant / Size Pricing System**:
   - Products (like **CFC Plus**) support multi-weight variants (1 KG, 2 KG, 3 KG, 4 KG, 6 KG, 12 KG, 24 KG).
   - Dynamic price, SKU, and stock switcher on product detail and shop views.
3. **Double Submission Shielding**:
   - Form submission buttons disabled during API call with active loading states.
4. **Role-Based Authorization & Security**:
   - `admin` middleware guards `/admin/*` routes.
   - `customer` middleware protects `/account/*` routes.
   - Password hashing via Bcrypt, XSS escaping, SQL Injection prevention via Eloquent ORM.
5. **Dynamic Site Settings**:
   - All phone numbers, emails, addresses, inside/outside Dhaka shipping fees, currency symbol `৳`, and social links are managed dynamically in the database via `/admin/settings`.
6. **Audit Logs**:
   - Tracks important admin actions (product creation, order status changes, customer suspension) with IP and timestamp.

---

## 6. Route Summary

### Customer Routes:
- `/` — Homepage (Hero, Featured Categories, Super Deals, Popular Products)
- `/products` — Products Catalog & Filters (Category, Brand, Price Range, Super Offer, Sorting)
- `/products/{slug}` — Product Details Page (Gallery, Dynamic Variant Switcher, Quantity)
- `/cart` — Shopping Cart
- `/checkout` — Simplified Checkout (Delivery Area Selector, Cash on Delivery)
- `/track-order` — Order Tracking (Progress Timeline: Order Placed -> Confirmed -> Processing -> Shipped -> Delivered)
- `/login` / `/register` / `/account` — Customer Auth & Dashboard

### Admin Routes (`/admin`):
- `/admin/login` — Admin Login
- `/admin/dashboard` — Sales Overview & Metrics, Low Stock Alerts
- `/admin/products` — Product CRUD & Variant Builder
- `/admin/categories` — Category CRUD
- `/admin/brands` — Brand CRUD
- `/admin/orders` — Order Processing & Status Switcher
- `/admin/customers` — Customer List & Suspend/Activate Toggle
- `/admin/settings` — Dynamic Site Settings Manager
- `/admin/audit-logs` — Admin Audit Trail

<div align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-red?style=for-the-badge&logo=laravel" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.3-777bb4?style=for-the-badge&logo=php" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js" alt="Alpine.js">
  <br>
  <img src="https://img.shields.io/badge/Tests-7_Passed-success?style=for-the-badge&logo=pestphp" alt="Tests">
  <img src="https://img.shields.io/badge/Migrations-12_Ran-success?style=for-the-badge&logo=laravel" alt="Migrations">
  <img src="https://img.shields.io/badge/PHPStan-Level_5-blue?style=for-the-badge" alt="PHPStan">
  <img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="License">
</div>

<br>

<div align="center">
  <img src="https://raw.githubusercontent.com/tabler/tabler-icons/main/icons/outline/shopping-cart.svg" width="80" alt="Stitch POS">
</div>

<h1 align="center">Stitch POS</h1>

<p align="center">
  <strong>Sistem Point-of-Sale Modern untuk Retail & Kuliner</strong><br>
  <sup>Transaksi cepat · Stok akurat · Laporan real-time · Siap produksi</sup>
</p>

<p align="center">
  <a href="#-quick-start"><strong>Quick Start</strong></a> ·
  <a href="#-fitur"><strong>Fitur</strong></a> ·
  <a href="#-keamanan"><strong>Keamanan</strong></a> ·
  <a href="#-testing"><strong>Testing</strong></a> ·
  <a href="#-struktur-proyek"><strong>Struktur</strong></a> ·
  <a href="#-skema-database"><strong>Database</strong></a>
</p>

<br>

---

## 📖 Daftar Isi

- [✨ Fitur](#-fitur)
- [🚀 Quick Start](#-quick-start)
- [🔑 Akun Demo](#-akun-demo)
- [🔄 Alur Kerja Kasir](#-alur-kerja-kasir)
- [🧪 Testing](#-testing)
- [📁 Struktur Proyek](#-struktur-proyek)
- [🗄️ Skema Database](#-skema-database)
- [🛡️ Keamanan](#-keamanan)
- [🤝 Kontribusi](#-kontribusi)
- [📜 Lisensi](#-lisensi)

---

## ✨ Fitur

<table>
<tr>
<td width="50%">

### 🧑‍💼 Panel Admin

| Modul | Kemampuan |
|---|---|
| **Dashboard** | Omzet harian/bulanan, tren ±% vs kemarin, jumlah transaksi, rata-rata nilai, Cash vs QRIS, produk terlaris, performa kasir, stok rendah, feed aktivitas |
| **Produk** | CRUD lengkap, upload gambar, **barcode EAN-13 auto-generate**, scan input barcode |
| **Kategori** | CRUD dengan jumlah produk |
| **Pengguna** | CRUD admin/kasir, role-based access control |
| **Laporan** | Tabel transaksi, filter bulan, pagination |
| **Landing Page** | Editor konten dinamis — hero, fitur, pricing, FAQ, testimoni, clients |

</td>
<td width="50%">

### 🛒 Panel Kasir

| Modul | Kemampuan |
|---|---|
| **Dashboard** | Omzet harian/bulanan, rata-rata transaksi, Cash vs QRIS, produk terlaris harian |
| **Transaksi Baru** | Katalog kiri + keranjang kanan, **scan barcode otomatis**, Cash/QRIS, diskon, quick cash button, receipt modal, **animasi scan glow** |
| **Riwayat** | Tabel riwayat transaksi pribadi dengan filter |

### 🔐 Autentikasi

| Fitur | Detail |
|---|---|
| **Login** | Rate limiting, account lockout, countdown timer, loading spinner, demo toggle |
| **Lupa Password** | Form reset via email |
| **Hubungi Admin** | Form kontak + info admin |

</td>
</tr>
</table>

> **💡 Barcode System**: Setiap produk punya barcode EAN-13 unik. Auto-generate saat produk dibuat tanpa barcode. Scanner fisik langsung dikenali — produk otomatis masuk keranjang tanpa klik.

---

## 🚀 Quick Start

### Prasyarat

| Dependency | Versi |
|---|---|
| PHP | 8.2+ |
| Composer | 2.x |
| MySQL / MariaDB | 8.0+ / 10.6+ |
| Node.js | 18+ |

### Langkah 1 — Clone & Install

```bash
git clone https://github.com/username/pos-toko.git
cd pos-toko

composer install
npm install
```

### Langkah 2 — Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_toko
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 3 — Migrasi & Seed

```bash
php artisan migrate --seed
```

> **📦 Seed Data**: 2 user (admin + kasir), 5 kategori, 18 produk dengan barcode EAN-13, 60+ landing settings.

### Langkah 4 — Jalankan

```bash
# Terminal 1 — Vite dev server
npm run dev

# Terminal 2 — Laravel
php artisan serve
```

Buka **[http://localhost:8000](http://localhost:8000)**

---

## 🔑 Akun Demo

| Role | Email | Password | Akses |
|---|---|---|---|
| **Admin** | `admin@stitchpos.com` | `password` | `/admin/dashboard` |
| **Kasir** | `kasir@stitchpos.com` | `password` | `/kasir/dashboard` |

> ⚠️ **Production**: Ganti password setelah instalasi. Akun dikunci 15 menit setelah 5× gagal login.

---

## 🔄 Alur Kerja Kasir

```
┌─────────────┐    ┌──────────────────┐    ┌─────────────┐    ┌──────────┐
│ Scan Barcode │──▶│ Produk masuk     │──▶│ Atur qty,    │──▶│ Pilih    │
│ (atau klik)  │    │ keranjang (beep)│    │ diskon, note │    │ metode   │
└─────────────┘    └──────────────────┘    └─────────────┘    └──────────┘
                                                                   │
                                                              ┌────┴────┐
                                                              │  TunaI  │
                                                              │  QRIS   │
                                                              └────┬────┘
                                                                   │
┌──────────┐    ┌──────────────────┐    ┌─────────────┐    ┌──────┴────┐
│  Transaksi │◀──│ Struk modal     │◀──│ Masukkan     │◀──│ Proses    │
│  Baru      │    │ (Cetak / Baru) │    │ uang bayar   │    │ Bayar     │
└──────────┘    └──────────────────┘    └─────────────┘    └──────────┘
```

| Step | Aksi |
|---|---|
| 1 | Buka `/kasir/transaksi` |
| 2 | **Scan barcode** pakai scanner fisik → ketik otomatis + Enter → produk masuk keranjang |
| 3 | Atau **klik produk** di grid katalog kiri (bisa filter kategori / search) |
| 4 | Sesuaikan **qty** (+/−), tambahkan **diskon**, **catatan** |
| 5 | Pilih **Tunai** (isi uang bayar + quick cash 20K/50K/100K) atau **QRIS** |
| 6 | Klik **"Proses Bayar"** |
| 7 | Struk modal muncul → **Cetak** atau **Transaksi Baru** |

---

## 🧪 Testing

```bash
php artisan test
```

```
PASS  Tests\Feature\TransactionStoreTest
✓ it processes a valid cash transaction
✓ it rejects payment less than total
✓ it processes a QRIS transaction
✓ it applies discount correctly
✓ it rejects empty cart
✓ it rejects when stock insufficient
✓ it redirects unauthenticated user to login

Tests:    7 passed  (22 assertions)
Duration: 0.73s
```

> **Test Suite**: Pest PHP + RefreshDatabase. Factory untuk User, Category, Product. Setiap test isolasi database.

---

## 📁 Struktur Proyek

```
pos-toko/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Dashboard, Product, Category, User, Report, Landing Setting
│   │   │   ├── Auth/               # Login, Logout, Forgot Password, Contact Admin
│   │   │   └── Kasir/              # Dashboard, Transaction
│   │   └── Middleware/
│   │       ├── CheckRole.php       # RBAC: admin vs kasir
│   │       └── ForceHttps.php      # HTTPS redirect (production)
│   ├── Models/
│   │   ├── ActivityLog.php         # Audit trail
│   │   ├── Category.php            # Kategori produk
│   │   ├── LandingSetting.php      # Key-value store landing page
│   │   ├── Product.php             # Produk + auto-generate EAN-13 barcode
│   │   ├── Transaction.php         # Transaksi + auto-generate invoice
│   │   ├── TransactionItem.php     # Item per transaksi
│   │   └── User.php                # Admin & Kasir
│   └── Traits/
│       └── LogsActivity.php        # Auto-log create/update/delete ke activity_logs
├── bootstrap/
│   └── app.php                     # Middleware alias + ForceHttps
├── database/
│   ├── factories/                  # UserFactory, CategoryFactory, ProductFactory
│   ├── migrations/                 # 12 migrasi (semua Ran)
│   └── seeders/
│       └── DatabaseSeeder.php      # 18 produk + barcode + users + landing
├── resources/
│   ├── css/app.css                 # Tailwind 3 + Material Design 3 tokens
│   └── views/
│       ├── admin/                  # Dashboard, Products, Categories, Users, Reports, Landing
│       ├── auth/                   # Login, Forgot Password, Contact Admin
│       ├── kasir/                  # Dashboard, Transaksi (Create, History, Show)
│       ├── layouts/app.blade.php   # Sidebar + role-based navigation
│       └── landing.blade.php       # Public website dinamis
├── routes/
│   └── web.php                     # 30 routes
├── tests/
│   ├── Feature/TransactionStoreTest.php  # 7 tests
│   └── Pest.php
└── tailwind.config.js              # Material Design 3 color system
```

---

## 🗄️ Skema Database

```
users
├─ id                               # Primary
├─ name, email, password            # Auth
├─ role (admin|kasir)               # RBAC
├─ login_attempts                   # Brute-force counter (max 5)
├─ locked_until                     # Lockout timestamp
└─ hasMany → transactions

categories
├─ id, name, slug
└─ hasMany → products

products
├─ id, category_id → categories     # FK
├─ name, slug, barcode (UNIQUE)     # EAN-13
├─ description, price, stock
├─ image, is_active
└─ hasMany → transaction_items

transactions
├─ id, user_id → users              # FK
├─ invoice_number                   # INV-YYYYMMDD-###
├─ total_amount, discount_amount
├─ paid_amount, change_amount
├─ payment_method (cash|qris)
├─ notes
└─ hasMany → transaction_items

transaction_items
├─ id, transaction_id → transactions  # FK
├─ product_id → products              # FK
├─ product_name, price, quantity, subtotal
└─ (no timestamps — denormalized)

activity_logs
├─ id, user_id → users              # FK (nullable)
├─ action (created|updated|deleted)
├─ model_type, model_id             # Polymorphic reference
├─ label, changes (JSON)
└─ created_at, updated_at

landing_settings
├─ id, key, value, type, label
└─ group (meta|hero|features|pricing|faq|cta|footer|...)
```

---

## 🛡️ Keamanan

| Lapis | Mekanisme | Implementasi |
|---|---|---|
| **Rate Limiting** | 5 percobaan/menit per email+IP | `RateLimiter` Laravel |
| **Account Lockout** | 5× gagal → terkunci 15 menit | Kolom `login_attempts` + `locked_until` |
| **Countdown Timer** | MM:SS real-time di error message | Alpine.js `setInterval` |
| **HTTPS** | Force redirect di production | Middleware `ForceHttps` |
| **Session** | Regenerate setiap login/logout | `session()->regenerate()` |
| **CSRF** | Token per request | Laravel built-in |
| **Password** | Bcrypt hashing | `Hash::make()` |
| **Error Messages** | Generik, tidak bocorkan info | OWASP compliant |
| **Audit Trail** | Setiap create/update/delete | Trait `LogsActivity` |
| **Role Access** | Admin vs Kasir | Middleware `CheckRole` |
| **CORS** | Handle cross-origin requests | Middleware built-in |

---

## 🤝 Kontribusi

Pull request sangat diterima. Untuk perubahan besar, buka issue dulu untuk diskusi.

```bash
# Setup development
git clone https://github.com/username/pos-toko.git
cd pos-toko
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Jalankan tests sebelum commit
php artisan test
```

---

## 📜 Lisensi

MIT © Stitch POS — bebas menggunakan, memodifikasi, dan mendistribusikan.

---

<br>

<div align="center">
  <sub>Built with ❤️ using Laravel · Tailwind CSS · Alpine.js · Material Design 3</sub><br>
  <sub><strong>Stitch POS</strong> — Precision for Retail & Catering</sub>
</div>

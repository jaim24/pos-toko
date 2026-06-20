# POS Toko — Implementation Plan

**Goal:** Sistem POS lengkap Laravel 11 + Blade + Tailwind + MySQL sesuai BLUEPRINT.md

**Stack:** Laravel 11, Blade, Tailwind CSS, Alpine.js, MySQL

---

## Phase 1: Setup & Struktur Dasar

### Task 1: Buat proyek Laravel baru
**Files:**
- Create: `C:\Aplikasi\POS kasir\pos-toko\` (hasil `composer create-project`)

**Step 1: Buat proyek**
```bash
composer create-project laravel/laravel "pos-toko"
cd pos-toko
```

### Task 2: Install & konfigurasi Tailwind CSS
**Files:**
- Modify: `tailwind.config.js`
- Modify: `resources/css/app.css`

**Step 1: Install Tailwind**
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

**Step 2: Konfigurasi tailwind.config.js**
```js
export default {
  content: ["./resources/**/*.blade.php"],
  theme: { extend: {} },
  plugins: [],
}
```

**Step 3: Update resources/css/app.css**
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

### Task 3: Install Alpine.js
**Files:**
- Modify: `resources/js/app.js`

**Step 1: Install**
```bash
npm install alpinejs
```

**Step 2: Update resources/js/app.js**
```js
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()
```

### Task 4: Setup .env & buat database
**Files:**
- Modify: `.env`

**Step 1: Setup .env**
```env
DB_DATABASE=pos_toko
DB_USERNAME=root
DB_PASSWORD=
```

**Step 2: Buat database**
```bash
mysql -u root -e "CREATE DATABASE pos_toko;"
```

---

## Phase 2: Database (Migrations)

### Task 5: Modifikasi users migration (tambah role)
**Files:**
- Modify: `database/migrations/xxxx_create_users_table.php`

**Step 1: Edit migration — tambahkan kolom role**
```php
$table->enum('role', ['admin', 'kasir'])->default('kasir');
```

### Task 6: Buat categories migration
**Files:**
- Create: `database/migrations/xxxx_create_categories_table.php`

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->string('slug', 100)->unique();
    $table->timestamps();
});
```

### Task 7: Buat products migration
**Files:**
- Create: `database/migrations/xxxx_create_products_table.php`

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained()->cascadeOnDelete();
    $table->string('name', 150);
    $table->string('slug', 150)->unique();
    $table->text('description')->nullable();
    $table->decimal('price', 12, 2);
    $table->integer('stock')->default(0);
    $table->string('image')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Task 8: Buat transactions migration
**Files:**
- Create: `database/migrations/xxxx_create_transactions_table.php`

```php
Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('invoice_number', 30)->unique();
    $table->decimal('total_amount', 12, 2);
    $table->decimal('paid_amount', 12, 2);
    $table->decimal('change_amount', 12, 2);
    $table->enum('payment_method', ['cash', 'qris'])->default('cash');
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

### Task 9: Buat transaction_items migration
**Files:**
- Create: `database/migrations/xxxx_create_transaction_items_table.php`

```php
Schema::create('transaction_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained()->restrictOnDelete();
    $table->string('product_name', 150);
    $table->decimal('price', 12, 2);
    $table->integer('quantity');
    $table->decimal('subtotal', 12, 2);
});
```

### Task 10: Jalankan migration & seeder
**Files:**
- Modify: `database/seeders/DatabaseSeeder.php`

**Step 1: Update DatabaseSeeder.php** (isi dari blueprint section 10)

**Step 2: Jalankan**
```bash
php artisan migrate:fresh --seed
```

---

## Phase 3: Models

### Task 11: Buat semua Model
**Files:**
- Modify: `app/Models/User.php`
- Create: `app/Models/Category.php`
- Create: `app/Models/Product.php`
- Create: `app/Models/Transaction.php`
- Create: `app/Models/TransactionItem.php`

Kode lengkap ada di BLUEPRINT.md section 7.

---

## Phase 4: Middleware

### Task 12: Buat CheckRole middleware
**Files:**
- Create: `app/Http/Middleware/CheckRole.php`

**Step 1: Buat middleware**
```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403);
        }
        return $next($request);
    }
}
```

**Step 2: Register di bootstrap/app.php**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

---

## Phase 5: Auth

### Task 13: Buat AuthController & routes
**Files:**
- Create: `app/Http/Controllers/Auth/AuthController.php`
- Modify: `routes/web.php`

Kode lengkap di BLUEPRINT.md section 5 (routes) dan section 9 (AuthController).

### Task 14: Buat view login
**Files:**
- Create: `resources/views/auth/login.blade.php`
- Create: `resources/views/layouts/auth.blade.php`

---

## Phase 6: Admin Panel

### Task 15: Layout utama admin
**Files:**
- Create: `resources/views/layouts/app.blade.php`
- Create: `resources/views/admin/dashboard.blade.php`

### Task 16: CRUD Kategori
**Files:**
- Create: `app/Http/Controllers/Admin/CategoryController.php`
- Create: `resources/views/admin/categories/index.blade.php`
- Create: `resources/views/admin/categories/form.blade.php`

### Task 17: CRUD Produk
**Files:**
- Create: `app/Http/Controllers/Admin/ProductController.php`
- Create: `resources/views/admin/products/index.blade.php`
- Create: `resources/views/admin/products/form.blade.php`

### Task 18: Manajemen User
**Files:**
- Create: `app/Http/Controllers/Admin/UserController.php`
- Create: `resources/views/admin/users/index.blade.php`

### Task 19: Laporan
**Files:**
- Create: `app/Http/Controllers/Admin/ReportController.php`
- Create: `resources/views/admin/reports/index.blade.php`

---

## Phase 7: Kasir Panel

### Task 20: Dashboard kasir
**Files:**
- Create: `app/Http/Controllers/Kasir/DashboardController.php`
- Create: `resources/views/kasir/dashboard.blade.php`

### Task 21: Halaman kasir POS (keranjang)
**Files:**
- Create: `app/Http/Controllers/Kasir/TransactionController.php`
- Create: `resources/views/kasir/transactions/create.blade.php`

### Task 22: Struk transaksi
**Files:**
- Create: `resources/views/kasir/transactions/show.blade.php`

### Task 23: History transaksi
**Files:**
- Create: `resources/views/kasir/transactions/history.blade.php`

---

## Phase 8: Final

### Task 24: Setup storage link
```bash
php artisan storage:link
```

### Task 25: Run & verify
```bash
npm run dev &
php artisan serve
```

---

## Commit Strategy
Setiap task = 1 commit. Contoh:
```bash
git add .
git commit -m "feat: add categories migration & model"
```

# Blueprint: Sistem Manajemen Toko (POS)
**Stack:** Laravel 11 · Blade · Tailwind CSS · MySQL  
**Estimasi pengerjaan:** 1–2 minggu  
**Tujuan:** Portofolio web developer — menunjukkan CRUD, auth, relasi DB, role-based access

---

## 1. Fitur Lengkap

| Modul | Fitur |
|---|---|
| Auth | Login, Register, Logout, Role (Admin/Kasir) |
| Produk | CRUD produk, kategori, upload foto, stok |
| Transaksi | Tambah item ke keranjang, checkout, struk |
| Laporan | Penjualan harian/bulanan, grafik |
| Dashboard | Ringkasan omzet, produk terjual, stok rendah |

---

## 2. Struktur Database (ERD)

### Tabel: `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | varchar(100) | |
| email | varchar(100) unique | |
| password | varchar | hashed bcrypt |
| role | enum('admin','kasir') | default: kasir |
| created_at / updated_at | timestamp | |

### Tabel: `categories`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | varchar(100) | |
| slug | varchar(100) unique | |
| created_at / updated_at | timestamp | |

### Tabel: `products`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| category_id | bigint FK → categories.id | |
| name | varchar(150) | |
| slug | varchar(150) unique | |
| description | text nullable | |
| price | decimal(12,2) | harga jual |
| stock | int | stok tersedia |
| image | varchar nullable | path gambar |
| is_active | boolean | default: true |
| created_at / updated_at | timestamp | |

### Tabel: `transactions`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK → users.id | kasir yang melayani |
| invoice_number | varchar(30) unique | ex: INV-20250620-001 |
| total_amount | decimal(12,2) | total bayar |
| paid_amount | decimal(12,2) | uang diterima |
| change_amount | decimal(12,2) | kembalian |
| payment_method | enum('cash','qris') | default: cash |
| notes | text nullable | |
| created_at / updated_at | timestamp | |

### Tabel: `transaction_items`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| transaction_id | bigint FK → transactions.id | |
| product_id | bigint FK → products.id | |
| product_name | varchar(150) | snapshot nama saat transaksi |
| price | decimal(12,2) | snapshot harga saat transaksi |
| quantity | int | |
| subtotal | decimal(12,2) | price × quantity |

---

## 3. Relasi Antar Tabel

```
users ──< transactions ──< transaction_items >── products
                                                      │
categories ──────────────────────────────────────────<┘
```

- User (kasir) bisa punya banyak transaksi
- Transaksi punya banyak item
- Setiap item merujuk ke satu produk
- Produk masuk ke satu kategori

---

## 4. Struktur Folder Laravel

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── AuthController.php
│   │   ├── Admin/
│   │   │   ├── CategoryController.php
│   │   │   ├── ProductController.php
│   │   │   ├── ReportController.php
│   │   │   └── UserController.php
│   │   └── Kasir/
│   │       ├── DashboardController.php
│   │       └── TransactionController.php
│   └── Middleware/
│       ├── IsAdmin.php
│       └── IsKasir.php
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Product.php
│   ├── Transaction.php
│   └── TransactionItem.php
resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php       ← layout utama
│   │   └── auth.blade.php      ← layout login
│   ├── auth/
│   │   └── login.blade.php
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── categories/
│   │   │   ├── index.blade.php
│   │   │   └── form.blade.php
│   │   ├── products/
│   │   │   ├── index.blade.php
│   │   │   └── form.blade.php
│   │   ├── users/
│   │   │   └── index.blade.php
│   │   └── reports/
│   │       └── index.blade.php
│   └── kasir/
│       ├── dashboard.blade.php
│       └── transactions/
│           ├── create.blade.php  ← halaman kasir POS
│           └── show.blade.php    ← struk
routes/
└── web.php
```

---

## 5. Routes (web.php)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Kasir\TransactionController;
use App\Http\Controllers\Kasir\DashboardController;

// ── Auth ──────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Admin ─────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

// ── Kasir ─────────────────────────────────────────
Route::prefix('kasir')->name('kasir.')->middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transaksi', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transaksi/{transaction}/struk', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transaksi/history', [TransactionController::class, 'history'])->name('transactions.history');
});

// ── Redirect root ──────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('kasir.dashboard');
    }
    return redirect()->route('login');
});
```

---

## 6. Migration Files

### users (modifikasi default)
```php
// tambahkan di migration users yang sudah ada:
$table->enum('role', ['admin', 'kasir'])->default('kasir');
```

### create_categories_table
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->string('slug', 100)->unique();
    $table->timestamps();
});
```

### create_products_table
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

### create_transactions_table
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

### create_transaction_items_table
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

---

## 7. Models

### User.php
```php
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }
}
```

### Category.php
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($cat) => $cat->slug = Str::slug($cat->name));
        static::updating(fn($cat) => $cat->slug = Str::slug($cat->name));
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
```

### Product.php
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'stock', 'image', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($p) => $p->slug = Str::slug($p->name));
        static::updating(fn($p) => $p->slug = Str::slug($p->name));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function isLowStock(int $threshold = 5): bool
    {
        return $this->stock <= $threshold;
    }
}
```

### Transaction.php
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'invoice_number', 'total_amount',
        'paid_amount', 'change_amount', 'payment_method', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public static function generateInvoice(): string
    {
        $today = now()->format('Ymd');
        $last = static::whereDate('created_at', today())
            ->latest('id')->first();
        $seq = $last ? (int) substr($last->invoice_number, -3) + 1 : 1;
        return 'INV-' . $today . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }
}
```

### TransactionItem.php
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'transaction_id', 'product_id',
        'product_name', 'price', 'quantity', 'subtotal'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
```

---

## 8. Middleware

### IsAdmin.php (app/Http/Middleware/)
```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
        return $next($request);
    }
}
```

### Role Middleware (pakai satu middleware fleksibel)
```php
// bootstrap/app.php — daftarkan alias middleware:
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})

// app/Http/Middleware/CheckRole.php
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

---

## 9. Controller Utama

### TransactionController.php (logika terpenting)
```php
<?php
namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function create()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get();
        return view('kasir.transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'paid_amount'    => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,qris',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $lines = [];

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak cukup.");
                }

                $subtotal = $product->price * $item['qty'];
                $total   += $subtotal;

                $lines[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $product->price,
                    'quantity'     => $item['qty'],
                    'subtotal'     => $subtotal,
                ];

                $product->decrement('stock', $item['qty']);
            }

            if ($request->paid_amount < $total) {
                throw new \Exception('Uang bayar kurang dari total.');
            }

            $transaction = Transaction::create([
                'user_id'        => auth()->id(),
                'invoice_number' => Transaction::generateInvoice(),
                'total_amount'   => $total,
                'paid_amount'    => $request->paid_amount,
                'change_amount'  => $request->paid_amount - $total,
                'payment_method' => $request->payment_method,
                'notes'          => $request->notes,
            ]);

            $transaction->items()->createMany($lines);

            DB::commit();

            return redirect()
                ->route('kasir.transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('items', 'user');
        return view('kasir.transactions.show', compact('transaction'));
    }

    public function history()
    {
        $transactions = Transaction::with('user')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(20);
        return view('kasir.transactions.history', compact('transactions'));
    }
}
```

---

## 10. Seeder (Data Awal)

### DatabaseSeeder.php
```php
<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin Toko',
            'email'    => 'admin@toko.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Kasir
        User::create([
            'name'     => 'Budi Kasir',
            'email'    => 'kasir@toko.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
        ]);

        // Kategori
        $minuman = Category::create(['name' => 'Minuman']);
        $makanan = Category::create(['name' => 'Makanan']);
        $snack   = Category::create(['name' => 'Snack']);

        // Produk
        $products = [
            ['category_id' => $minuman->id, 'name' => 'Es Teh Manis',  'price' => 5000,  'stock' => 100],
            ['category_id' => $minuman->id, 'name' => 'Air Mineral',   'price' => 3000,  'stock' => 200],
            ['category_id' => $minuman->id, 'name' => 'Kopi Hitam',    'price' => 8000,  'stock' => 80],
            ['category_id' => $makanan->id, 'name' => 'Nasi Goreng',   'price' => 15000, 'stock' => 50],
            ['category_id' => $makanan->id, 'name' => 'Mie Goreng',    'price' => 12000, 'stock' => 50],
            ['category_id' => $snack->id,   'name' => 'Keripik Singkong','price' => 7000,'stock' => 60],
            ['category_id' => $snack->id,   'name' => 'Biskuit Regal', 'price' => 6000,  'stock' => 70],
        ];

        foreach ($products as $p) {
            Product::create($p + ['description' => null, 'is_active' => true]);
        }
    }
}
```

---

## 11. Langkah Setup Proyek

```bash
# 1. Buat proyek Laravel baru
composer create-project laravel/laravel pos-toko
cd pos-toko

# 2. Install Tailwind CSS
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p

# 3. Konfigurasi tailwind.config.js
#    content: ["./resources/**/*.blade.php"]

# 4. Tambahkan di resources/css/app.css:
#    @tailwind base;
#    @tailwind components;
#    @tailwind utilities;

# 5. Install Alpine.js (untuk interaktivitas keranjang)
npm install alpinejs

# 6. Setup .env
#    DB_DATABASE=pos_toko
#    DB_USERNAME=root
#    DB_PASSWORD=

# 7. Buat database
mysql -u root -e "CREATE DATABASE pos_toko;"

# 8. Jalankan migration & seeder
php artisan migrate --seed

# 9. Link storage (untuk upload gambar)
php artisan storage:link

# 10. Jalankan
npm run dev
php artisan serve
```

---

## 12. Checklist Pengerjaan

### Minggu 1
- [ ] Setup proyek & install dependency
- [ ] Buat semua migration & jalankan seeder
- [ ] Buat semua Model dengan relasi
- [ ] Auth: login, logout, redirect sesuai role
- [ ] Layout utama (sidebar, navbar) dengan Tailwind
- [ ] CRUD Kategori (admin)
- [ ] CRUD Produk dengan upload gambar (admin)

### Minggu 2
- [ ] Halaman kasir POS (keranjang belanja interaktif)
- [ ] Proses checkout dengan validasi stok
- [ ] Halaman struk transaksi
- [ ] Dashboard admin (statistik + grafik)
- [ ] Laporan penjualan dengan filter tanggal
- [ ] Manajemen user (admin)
- [ ] Polish UI + testing semua fitur
- [ ] Deploy ke Railway/Render
- [ ] Buat README lengkap di GitHub

---

## 13. Tips untuk Portofolio

1. **Screenshot semua halaman** dan buat GIF demo 30 detik untuk README
2. **Tulis di README**: masalah apa yang diselesaikan proyek ini
3. **Sertakan ERD** sebagai gambar di folder `/docs`
4. **Commit secara bertahap** — bukan satu commit besar di akhir
5. **Nama commit deskriptif**: `feat: add product CRUD`, `fix: fix stock validation`
6. **Akun demo** di README: email & password untuk recruiter mencoba langsung

---

*Blueprint ini dibuat sebagai panduan pengerjaan proyek portofolio. Semua kode di atas adalah starter — kembangkan sesuai kebutuhan.*

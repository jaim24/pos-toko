<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\LandingSetting;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Users ────────────────────────────────────
        User::create([
            'name' => 'Admin Stitch',
            'email' => 'admin@stitchpos.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Stitch',
            'email' => 'kasir@stitchpos.com',
            'password' => 'password',
            'role' => 'kasir',
        ]);

        // ── Categories ───────────────────────────────
        $categories = [
            ['name' => 'Elektronik', 'slug' => 'elektronik'],
            ['name' => 'Kebutuhan Pokok', 'slug' => 'kebutuhan-pokok'],
            ['name' => 'Minuman', 'slug' => 'minuman'],
            ['name' => 'Makanan Ringan', 'slug' => 'makanan-ringan'],
            ['name' => 'Perlengkapan Rumah', 'slug' => 'perlengkapan-rumah'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // ── Products ──────────────────────────────────
        $products = [
            ['category_id' => 1, 'name' => 'Charger USB-C 65W', 'price' => 150000, 'stock' => 25, 'is_active' => true],
            ['category_id' => 1, 'name' => 'Kabel Data 1M', 'price' => 25000, 'stock' => 50, 'is_active' => true],
            ['category_id' => 1, 'name' => 'Mouse Wireless', 'price' => 85000, 'stock' => 30, 'is_active' => true],
            ['category_id' => 1, 'name' => 'Keyboard Mechanical', 'price' => 450000, 'stock' => 10, 'is_active' => true],
            ['category_id' => 2, 'name' => 'Beras 5kg', 'price' => 65000, 'stock' => 40, 'is_active' => true],
            ['category_id' => 2, 'name' => 'Minyak Goreng 2L', 'price' => 38000, 'stock' => 35, 'is_active' => true],
            ['category_id' => 2, 'name' => 'Gula Pasir 1kg', 'price' => 16000, 'stock' => 60, 'is_active' => true],
            ['category_id' => 2, 'name' => 'Telur 1kg', 'price' => 28000, 'stock' => 20, 'is_active' => true],
            ['category_id' => 3, 'name' => 'Air Mineral 600ml', 'price' => 4000, 'stock' => 100, 'is_active' => true],
            ['category_id' => 3, 'name' => 'Teh Botol 350ml', 'price' => 7000, 'stock' => 80, 'is_active' => true],
            ['category_id' => 3, 'name' => 'Kopi Sachet', 'price' => 3500, 'stock' => 120, 'is_active' => true],
            ['category_id' => 4, 'name' => 'Keripik Kentang 60g', 'price' => 12000, 'stock' => 45, 'is_active' => true],
            ['category_id' => 4, 'name' => 'Coklat Batang 100g', 'price' => 18000, 'stock' => 30, 'is_active' => true],
            ['category_id' => 4, 'name' => 'Biskuit Kaleng', 'price' => 35000, 'stock' => 15, 'is_active' => true],
            ['category_id' => 5, 'name' => 'Sabun Cuci Piring 500ml', 'price' => 12000, 'stock' => 40, 'is_active' => true],
            ['category_id' => 5, 'name' => 'Deterjen 1kg', 'price' => 22000, 'stock' => 30, 'is_active' => true],
            ['category_id' => 5, 'name' => 'Pengharum Ruangan', 'price' => 15000, 'stock' => 22, 'is_active' => true],
            ['category_id' => 1, 'name' => 'Speaker Bluetooth', 'price' => 250000, 'stock' => 3, 'is_active' => true],
        ];

        foreach ($products as $p) {
            Product::create(array_merge($p, [
                'slug' => \Illuminate\Support\Str::slug($p['name']),
                'description' => null,
                'image' => null,
            ]));
        }

        // ── Landing Settings ──────────────────────────
        $landing = [
            ['key' => 'brand_name', 'value' => 'Stitch POS', 'type' => 'text', 'label' => 'Nama Brand', 'group' => 'meta'],
            ['key' => 'meta_title', 'value' => 'Stitch POS', 'type' => 'text', 'label' => 'Meta Title', 'group' => 'meta'],
            ['key' => 'meta_desc', 'value' => 'Sistem POS Terbaik untuk Bisnis Kuliner & Retail', 'type' => 'textarea', 'label' => 'Meta Description', 'group' => 'meta'],
            ['key' => 'meta_og_image', 'value' => '', 'type' => 'image', 'label' => 'OG Image URL', 'group' => 'meta'],
            ['key' => 'accent_color', 'value' => '#10b981', 'type' => 'color', 'label' => 'Warna Akses', 'group' => 'meta'],
            ['key' => 'hero_badge', 'value' => 'Precision for Retail & Catering', 'type' => 'text', 'label' => 'Hero Badge', 'group' => 'hero'],
            ['key' => 'hero_title', 'value' => "Sistem POS Terbaik untuk\nBisnis Kuliner & Retail", 'type' => 'textarea', 'label' => 'Hero Judul', 'group' => 'hero'],
            ['key' => 'hero_subtitle', 'value' => 'Optimalkan operasional bisnis Anda dengan kecepatan dan akurasi tinggi. Kelola transaksi, stok, hingga laporan keuangan dalam satu platform yang intuitif dan reliabel.', 'type' => 'textarea', 'label' => 'Hero Subjudul', 'group' => 'hero'],
            ['key' => 'hero_btn_primary', 'value' => 'Mulai Sekarang', 'type' => 'text', 'label' => 'Tombol Utama', 'group' => 'hero'],
            ['key' => 'hero_btn_secondary', 'value' => 'Lihat Demo', 'type' => 'text', 'label' => 'Tombol Kedua', 'group' => 'hero'],
            ['key' => 'hero_image', 'value' => 'https://placehold.co/600x500/e0f2fe/1e293b?text=Stitch+POS', 'type' => 'image', 'label' => 'Hero Image', 'group' => 'hero'],
            ['key' => 'hero_social_proof', 'value' => 'Dipercaya oleh 2,500+ Bisnis di Indonesia', 'type' => 'text', 'label' => 'Social Proof', 'group' => 'hero'],
            ['key' => 'hero_stat_label', 'value' => 'Efisiensi Transaksi', 'type' => 'text', 'label' => 'Stat Label', 'group' => 'hero'],
            ['key' => 'hero_stat_value', 'value' => '+45% Lebih Cepat', 'type' => 'text', 'label' => 'Stat Value', 'group' => 'hero'],
            ['key' => 'features_title', 'value' => 'Fitur Unggulan Untuk Performa Maksimal', 'type' => 'text', 'label' => 'Fitur Judul', 'group' => 'features'],
            ['key' => 'features_subtitle', 'value' => 'Dirancang untuk memudahkan setiap aspek manajemen bisnis Anda.', 'type' => 'textarea', 'label' => 'Fitur Subjudul', 'group' => 'features'],
            ['key' => 'features_data', 'value' => json_encode([
                ['icon' => 'inventory_2', 'title' => 'Manajemen Stok Real-time', 'desc' => 'Pantau persediaan secara otomatis setiap ada transaksi.'],
                ['icon' => 'analytics', 'title' => 'Laporan Penjualan Otomatis', 'desc' => 'Dapatkan insight mendalam tentang tren penjualan harian.'],
                ['icon' => 'payments', 'title' => 'Integrasi Pembayaran Digital', 'desc' => 'QRIS, e-wallet, kartu debit — semua dalam satu sistem.'],
                ['icon' => 'storefront', 'title' => 'Dukungan Multi-outlet', 'desc' => 'Kelola puluhan cabang dalam satu dashboard terpusat.'],
                ['icon' => 'groups', 'title' => 'Manajemen Karyawan', 'desc' => 'Atur shift, performa, dan otorisasi akses kasir.'],
                ['icon' => 'loyalty', 'title' => 'Program Loyalitas', 'desc' => 'Bangun database pelanggan dan buat promo khusus member.'],
                ['icon' => 'kitchen', 'title' => 'Kitchen Display System', 'desc' => 'Kirim pesanan langsung ke layar dapur tanpa kertas.'],
                ['icon' => 'cloud_sync', 'title' => 'Akses Cloud Anywhere', 'desc' => 'Pantau bisnis kapan saja, di mana saja.'],
            ]), 'type' => 'textarea', 'label' => 'Data Fitur (JSON)', 'group' => 'features'],
            ['key' => 'testimonials_title', 'value' => 'Apa Kata Mereka', 'type' => 'text', 'label' => 'Testi Judul', 'group' => 'testimonials'],
            ['key' => 'testimonials_subtitle', 'value' => 'Kesuksesan bisnis mereka berawal dari sistem yang tepat.', 'type' => 'text', 'label' => 'Testi Subjudul', 'group' => 'testimonials'],
            ['key' => 'testimonials_data', 'value' => json_encode([
                ['name' => 'Maya Sartika', 'role' => 'Owner, Green Bites Cafe', 'text' => 'Stitch POS benar-benar mengubah cara kami mengelola stok. Dulu sering sekali bahan habis tanpa terdeteksi, sekarang semuanya terpantau rapi.', 'avatar' => 'https://placehold.co/80/e0f2fe/1e293b?text=MS'],
                ['name' => 'Budi Santoso', 'role' => 'Founder, Urban Retail', 'text' => 'Proses checkout yang cepat sangat membantu saat jam sibuk. Pelanggan tidak perlu mengantri lama, dan pembayaran digitalnya sangat lancar.', 'avatar' => 'https://placehold.co/80/f0f4f8/1e293b?text=BS'],
                ['name' => 'Rizky Ramadhan', 'role' => 'CEO, Delice Catering', 'text' => 'Fitur multi-outletnya juaranya. Saya bisa cek performa 5 cabang saya sekaligus dari rumah tanpa harus keliling satu-satu.', 'avatar' => 'https://placehold.co/80/eaeef2/1e293b?text=RR'],
            ]), 'type' => 'textarea', 'label' => 'Data Testimoni (JSON)', 'group' => 'testimonials'],
            ['key' => 'benefits_title', 'value' => 'Biarkan Kami Menangani Kerumitan Operasional', 'type' => 'text', 'label' => 'Benefits Judul', 'group' => 'cta'],
            ['key' => 'benefits_image', 'value' => 'https://placehold.co/600x500/e0f2fe/1e293b?text=Benefits', 'type' => 'image', 'label' => 'Benefits Image', 'group' => 'cta'],
            ['key' => 'benefits_data', 'value' => json_encode([
                ['title' => 'Fokus Pada Pelayanan', 'desc' => 'Stitch POS menangani semua pencatatan, sehingga Anda dan tim bisa fokus memberikan pelayanan terbaik ke pelanggan.'],
                ['title' => 'Keputusan Berbasis Data', 'desc' => 'Jangan menebak-nebak. Gunakan laporan analitik kami untuk menentukan menu mana yang harus dipertahankan atau dipromosikan.'],
                ['title' => 'Skalabilitas Tanpa Batas', 'desc' => 'Siap buka cabang baru? Tambahkan outlet baru hanya dalam hitungan menit tanpa setup infrastruktur yang rumit.'],
            ]), 'type' => 'textarea', 'label' => 'Data Benefits (JSON)', 'group' => 'cta'],
            ['key' => 'benefits_btn', 'value' => 'Pelajari Lebih Lanjut', 'type' => 'text', 'label' => 'Tombol Benefits', 'group' => 'cta'],
            ['key' => 'cta_title', 'value' => 'Siap Membawa Bisnis Anda ke Level Berikutnya?', 'type' => 'text', 'label' => 'CTA Judul', 'group' => 'cta'],
            ['key' => 'cta_subtitle', 'value' => 'Dapatkan akses penuh ke semua fitur premium selama 14 hari. Tanpa perlu kartu kredit, tanpa komitmen.', 'type' => 'textarea', 'label' => 'CTA Subjudul', 'group' => 'cta'],
            ['key' => 'cta_btn_primary', 'value' => 'Coba Gratis 14 Hari', 'type' => 'text', 'label' => 'CTA Tombol Utama', 'group' => 'cta'],
            ['key' => 'cta_btn_secondary', 'value' => 'Hubungi Sales', 'type' => 'text', 'label' => 'CTA Tombol Kedua', 'group' => 'cta'],
            ['key' => 'cta_disclaimer', 'value' => 'Tidak puas? Batalkan kapan saja.', 'type' => 'text', 'label' => 'CTA Disclaimer', 'group' => 'cta'],
            ['key' => 'footer_about', 'value' => 'Solusi point-of-sale terpadu untuk efisiensi bisnis modern. Memberikan presisi dalam setiap transaksi dan kemudahan dalam setiap laporan.', 'type' => 'textarea', 'label' => 'Footer About', 'group' => 'footer'],
            ['key' => 'footer_copyright', 'value' => '© ' . date('Y') . ' Stitch POS. All rights reserved.', 'type' => 'text', 'label' => 'Footer Copyright', 'group' => 'footer'],

            // ── New sections ──────────────────────
            ['key' => 'statistics_data', 'value' => json_encode([
                ['value' => '2500+', 'label' => 'Bisnis Aktif'],
                ['value' => '50.000+', 'label' => 'Transaksi/Hari'],
                ['value' => '99.9%', 'label' => 'Uptime Server'],
                ['value' => '15+', 'label' => 'Industri'],
            ]), 'type' => 'textarea', 'label' => 'Data Statistik (JSON)', 'group' => 'statistics'],

            ['key' => 'how_title', 'value' => 'Mudah & Cepat', 'type' => 'text', 'label' => 'How Badge', 'group' => 'how-it-works'],
            ['key' => 'how_heading', 'value' => 'Mulai dalam Hitungan Menit', 'type' => 'text', 'label' => 'How Judul', 'group' => 'how-it-works'],
            ['key' => 'how_subtitle', 'value' => 'Tiga langkah sederhana untuk transformasi bisnis Anda.', 'type' => 'textarea', 'label' => 'How Subjudul', 'group' => 'how-it-works'],
            ['key' => 'how_steps', 'value' => json_encode([
                ['step' => '01', 'icon' => 'person_add', 'title' => 'Daftar Akun', 'desc' => 'Buat akun gratis dalam 2 menit. Tidak perlu kartu kredit.'],
                ['step' => '02', 'icon' => 'settings', 'title' => 'Setup Toko', 'desc' => 'Tambahkan produk, harga, dan atur pajak sesuai kebutuhan bisnis Anda.'],
                ['step' => '03', 'icon' => 'rocket_launch', 'title' => 'Mulai Jualan', 'desc' => 'Langsung proses transaksi dan pantau performa dari dashboard.'],
            ]), 'type' => 'textarea', 'label' => 'Data Langkah (JSON)', 'group' => 'how-it-works'],

            ['key' => 'pricing_title', 'value' => 'Harga Transparan', 'type' => 'text', 'label' => 'Pricing Judul', 'group' => 'pricing'],
            ['key' => 'pricing_subtitle', 'value' => 'Pilih paket yang sesuai dengan skala bisnis Anda.', 'type' => 'textarea', 'label' => 'Pricing Subjudul', 'group' => 'pricing'],
            ['key' => 'pricing_plans', 'value' => json_encode([
                ['name' => 'Basic', 'price' => 'Rp 0', 'period' => '/bulan', 'desc' => 'Cocok untuk bisnis baru', 'features' => ['5 produk aktif', '1 user kasir', 'Laporan harian', 'Support via email']],
                ['name' => 'Pro', 'price' => 'Rp 199K', 'period' => '/bulan', 'desc' => 'Untuk bisnis bertumbuh', 'features' => ['100 produk', '3 user kasir', 'Laporan mingguan & bulanan', 'Multi-outlet dasar', 'Support prioritas'], 'featured' => true],
                ['name' => 'Enterprise', 'price' => 'Custom', 'period' => '', 'desc' => 'Untuk skala besar', 'features' => ['Unlimited produk', 'Unlimited user', 'Multi-outlet penuh', 'API akses', 'Dedicated support 24/7', 'Custom integrasi']],
            ]), 'type' => 'textarea', 'label' => 'Data Paket (JSON)', 'group' => 'pricing'],

            ['key' => 'faq_title', 'value' => 'Pertanyaan Umum', 'type' => 'text', 'label' => 'FAQ Judul', 'group' => 'faq'],
            ['key' => 'faq_subtitle', 'value' => 'Semua yang perlu Anda ketahui tentang Stitch POS.', 'type' => 'textarea', 'label' => 'FAQ Subjudul', 'group' => 'faq'],
            ['key' => 'faq_data', 'value' => json_encode([
                ['q' => 'Apa itu Stitch POS?', 'a' => 'Stitch POS adalah sistem point-of-sale berbasis cloud yang dirancang untuk bisnis retail dan kuliner. Kelola transaksi, stok, laporan, dan karyawan dalam satu platform.'],
                ['q' => 'Apakah ada biaya setup?', 'a' => 'Tidak. Anda bisa daftar dan mulai menggunakan sistem secara gratis. Biaya hanya berlaku saat Anda upgrade ke paket berbayar.'],
                ['q' => 'Apakah bisa dipakai untuk banyak cabang?', 'a' => 'Ya! Paket Enterprise mendukung multi-outlet dengan dashboard terpusat. Anda bisa mengelola puluhan cabang dari satu akun.'],
                ['q' => 'Bagaimana keamanan data saya?', 'a' => 'Kami menggunakan enkripsi end-to-end, backup otomatis setiap jam, dan server tersertifikasi. Data Anda aman dan selalu tersedia.'],
                ['q' => 'Metode pembayaran apa yang didukung?', 'a' => 'Stitch POS mendukung pembayaran tunai dan QRIS. Integrasi dengan e-wallet dan kartu debit tersedia di paket Pro+.'],
                ['q' => 'Apakah bisa dicoba gratis?', 'a' => 'Tentu. Paket Basic gratis selamanya untuk 5 produk dan 1 user. Anda bisa upgrade kapan saja saat bisnis bertumbuh.'],
                ['q' => 'Apakah ada pelatihan untuk tim?', 'a' => 'Kami menyediakan video tutorial, panduan lengkap, dan sesi onboarding gratis untuk paket Pro dan Enterprise.'],
            ]), 'type' => 'textarea', 'label' => 'Data FAQ (JSON)', 'group' => 'faq'],

            ['key' => 'clients_title', 'value' => 'Dipercaya oleh Ratusan Bisnis di Indonesia', 'type' => 'text', 'label' => 'Clients Judul', 'group' => 'clients'],
            ['key' => 'clients_logos', 'value' => json_encode([
                ['name' => 'Green Cafe', 'color' => 'bg-emerald-100 text-emerald-700'],
                ['name' => 'Urban Retail', 'color' => 'bg-blue-100 text-blue-700'],
                ['name' => 'Delice Food', 'color' => 'bg-amber-100 text-amber-700'],
                ['name' => 'Prima Mart', 'color' => 'bg-purple-100 text-purple-700'],
                ['name' => 'Bakery Elite', 'color' => 'bg-rose-100 text-rose-700'],
                ['name' => 'Fresh Corner', 'color' => 'bg-teal-100 text-teal-700'],
                ['name' => 'Max Store', 'color' => 'bg-indigo-100 text-indigo-700'],
                ['name' => 'Quick Shop', 'color' => 'bg-orange-100 text-orange-700'],
            ]), 'type' => 'textarea', 'label' => 'Data Logo Klien (JSON)', 'group' => 'clients'],
        ];

        foreach ($landing as $row) {
            LandingSetting::create($row);
        }
    }
}

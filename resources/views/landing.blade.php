<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        use App\Models\LandingSetting;
        $s = LandingSetting::allAsArray();
        $title    = $s['meta_title'] ?? 'Stitch POS';
        $desc     = $s['meta_desc'] ?? 'Sistem POS Terbaik untuk Bisnis Kuliner & Retail';
        $ogImage  = $s['meta_og_image'] ?? '';
        $heroBadge = $s['hero_badge'] ?? 'Precision for Retail & Catering';
        $heroTitle = $s['hero_title'] ?? 'Sistem POS Terbaik untuk Bisnis Kuliner & Retail';
        $heroSub   = $s['hero_subtitle'] ?? 'Optimalkan operasional bisnis Anda dengan kecepatan dan akurasi tinggi.';
        $heroBtn1  = $s['hero_btn_primary'] ?? 'Mulai Sekarang';
        $heroBtn2  = $s['hero_btn_secondary'] ?? 'Lihat Demo';
        $ctaTitle  = $s['cta_title'] ?? 'Siap Membawa Bisnis Anda ke Level Berikutnya?';
        $ctaSub    = $s['cta_subtitle'] ?? 'Dapatkan akses penuh ke semua fitur premium selama 14 hari.';
        $ctaBtn1   = $s['cta_btn_primary'] ?? 'Coba Gratis 14 Hari';
        $ctaBtn2   = $s['cta_btn_secondary'] ?? 'Hubungi Sales';
        $footerAbout = $s['footer_about'] ?? 'Solusi point-of-sale terpadu untuk efisiensi bisnis modern.';
        $footerCopy  = $s['footer_copyright'] ?? '© ' . date('Y') . ' Stitch POS. All rights reserved.';
        $accent = $s['accent_color'] ?? '#10b981';
    @endphp
    <title>{{ $title }} - {{ $desc }}</title>
    <meta name="description" content="{{ $desc }}">
    @if($ogImage)<meta property="og:image" content="{{ $ogImage }}">@endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-gradient { background: linear-gradient(135deg, #f6fafe 0%, #e0f2fe 100%); }
        .bento-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem; }
        @media (min-width: 640px) { .bento-grid { gap: 1.5rem; } }
        .glass-card { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.3); }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        html { scroll-behavior: smooth; }

        /* ── Keyframes ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            50% { box-shadow: 0 0 0 16px rgba(16, 185, 129, 0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes countUp {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* ── Utility classes ── */
        .reveal { opacity: 0; }
        .reveal.visible { animation: fadeInUp 0.8s cubic-bezier(0.17, 0.84, 0.44, 1) forwards; }
        .reveal-left { opacity: 0; }
        .reveal-left.visible { animation: fadeInLeft 0.8s cubic-bezier(0.17, 0.84, 0.44, 1) forwards; }
        .reveal-right { opacity: 0; }
        .reveal-right.visible { animation: fadeInRight 0.8s cubic-bezier(0.17, 0.84, 0.44, 1) forwards; }
        .reveal-scale { opacity: 0; }
        .reveal-scale.visible { animation: scaleIn 0.7s cubic-bezier(0.17, 0.84, 0.44, 1) forwards; }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }
        .stagger-6 { animation-delay: 0.6s; }
        .stagger-7 { animation-delay: 0.7s; }
        .stagger-8 { animation-delay: 0.8s; }

        .float-anim { animation: float 4s ease-in-out infinite; }
        .pulse-glow { animation: pulseGlow 2.5s ease-in-out infinite; }
        .shimmer-text {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            background-size: 200% 100%;
            animation: shimmer 2.5s infinite;
        }
        .slide-down { animation: slideDown 0.6s cubic-bezier(0.17, 0.84, 0.44, 1) forwards; }
    </style>
</head>
<body class="bg-background text-on-background antialiased overflow-x-hidden" x-data="{ mobileOpen: false }">

{{-- NAVBAR --}}
<header class="bg-surface/90 backdrop-blur-md sticky top-0 z-50 border-b border-outline-variant shadow-sm slide-down">
    <nav class="flex justify-between items-center w-full px-4 sm:px-6 py-3 sm:py-4 max-w-7xl mx-auto">
        <div class="flex items-center gap-2">
            <span class="text-lg sm:text-2xl font-black text-secondary leading-none">{{ $s['brand_name'] ?? 'Stitch POS' }}</span>
        </div>
        <div class="hidden lg:flex items-center gap-6 sm:gap-8">
            @foreach(['features' => 'Fitur', 'stats' => 'Statistik', 'how-it-works' => 'Cara Kerja', 'solutions' => 'Testimoni', 'pricing' => 'Harga', 'faq' => 'FAQ', 'contact' => 'Kontak'] as $key => $label)
            <a href="#{{ $key }}" class="text-on-surface-variant hover:text-secondary transition-colors text-[10px] sm:text-xs font-semibold uppercase tracking-wider">{{ $label }}</a>
            @endforeach
        </div>
        <div class="flex items-center gap-3 sm:gap-4">
            <a href="{{ route('login') }}" class="hidden sm:inline-flex px-4 sm:px-6 py-2 bg-secondary text-white rounded-full text-xs font-bold hover:bg-secondary/90 transition-all active:scale-95">
                {{ $s['hero_btn_primary'] ?? 'Mulai' }}
            </a>
            {{-- Mobile hamburger --}}
            <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-2xl" x-text="mobileOpen ? 'close' : 'menu'">menu</span>
            </button>
        </div>
    </nav>
    {{-- Mobile menu --}}
    <nav x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         class="lg:hidden px-4 sm:px-6 pb-4 space-y-1 sm:space-y-2">
        @foreach(['features' => 'Fitur', 'stats' => 'Statistik', 'how-it-works' => 'Cara Kerja', 'solutions' => 'Testimoni', 'pricing' => 'Harga', 'faq' => 'FAQ', 'contact' => 'Kontak'] as $key => $label)
        <a href="#{{ $key }}" @click="mobileOpen = false" class="block px-3 py-2 text-on-surface-variant hover:text-secondary transition-colors text-sm font-medium">{{ $label }}</a>
        @endforeach
        <a href="{{ route('login') }}" class="block sm:hidden px-5 py-2.5 mt-2 bg-secondary text-white rounded-full text-center text-sm font-bold">Mulai</a>
    </nav>
</header>

{{-- HERO --}}
<section class="hero-gradient relative py-12 sm:py-20 md:py-24 overflow-hidden" id="hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col lg:flex-row items-center gap-8 sm:gap-12">
        <div class="flex-1 space-y-4 sm:space-y-6 z-10 reveal-left">
            <div class="inline-block px-3 sm:px-4 py-1 sm:py-1.5 bg-secondary/10 text-secondary rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-wider pulse-glow">
                {{ $heroBadge }}
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-primary leading-tight">
                {!! nl2br(e($heroTitle)) !!}
            </h1>
            <p class="text-sm sm:text-base text-on-surface-variant max-w-xl">
                {{ $heroSub }}
            </p>
            <div class="flex flex-wrap gap-3 sm:gap-4 pt-2 sm:pt-4">
                <a href="{{ route('login') }}" class="px-6 sm:px-8 py-3 sm:py-4 bg-secondary text-white rounded-xl font-bold text-sm sm:text-base hover:shadow-lg hover:shadow-secondary/20 transition-all flex items-center gap-2">
                    {{ $heroBtn1 }}
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
                <a href="#features" class="px-6 sm:px-8 py-3 sm:py-4 bg-white border border-outline-variant text-primary rounded-xl font-bold text-sm sm:text-base hover:bg-surface-container-low transition-all">
                    {{ $heroBtn2 }}
                </a>
            </div>
            <div class="flex items-center gap-3 sm:gap-4 pt-4 sm:pt-6 text-on-surface-variant">
                <div class="flex -space-x-2">
                    @for($i=1;$i<=3;$i++)
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-slate-200 border-2 border-white shadow-sm"></div>
                    @endfor
                </div>
                <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider">{{ $s['hero_social_proof'] ?? 'Dipercaya oleh 2,500+ Bisnis di Indonesia' }}</span>
            </div>
        </div>
        <div class="flex-1 relative reveal-right w-full">
            <div class="relative w-full max-w-lg mx-auto float-anim">
                <div class="rounded-[1.5rem] sm:rounded-[2rem] overflow-hidden shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500 border-[6px] sm:border-8 border-white">
                    <img class="w-full h-[280px] sm:h-[360px] md:h-[420px] object-cover" src="{{ $s['hero_image'] ?? 'https://placehold.co/600x500/e0f2fe/1e293b?text=Stitch+POS' }}" alt="Stitch POS Dashboard">
                </div>
                <div class="absolute -top-8 sm:-top-12 -right-8 sm:-right-12 w-36 sm:w-48 h-36 sm:h-48 bg-secondary/20 rounded-full blur-3xl -z-10"></div>
                <div class="absolute -bottom-6 sm:-bottom-8 -left-6 sm:-left-8 w-48 sm:w-64 h-48 sm:h-64 bg-primary/10 rounded-full blur-3xl -z-10"></div>
                <div class="absolute bottom-6 sm:bottom-10 -left-6 sm:-left-12 glass-card p-3 sm:p-4 rounded-2xl shadow-xl hidden lg:block reveal-scale">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="p-2 bg-secondary/20 text-secondary rounded-lg"><span class="material-symbols-outlined">trending_up</span></div>
                        <div>
                            <p class="text-[10px] text-on-surface-variant font-semibold uppercase">{{ $s['hero_stat_label'] ?? 'Efisiensi Transaksi' }}</p>
                            <p class="text-base sm:text-lg font-bold text-primary">{{ $s['hero_stat_value'] ?? '+45% Lebih Cepat' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATISTICS COUNTERS --}}
<section class="py-14 sm:py-16 md:py-20 bg-primary text-white" id="stats">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 text-center reveal">
            @php
                $statistics = json_decode($s['statistics_data'] ?? '[]', true) ?: [
                    ['value' => '2500+', 'label' => 'Bisnis Aktif'],
                    ['value' => '50.000+', 'label' => 'Transaksi/Hari'],
                    ['value' => '99.9%', 'label' => 'Uptime Server'],
                    ['value' => '15+', 'label' => 'Industri'],
                ];
            @endphp
            @foreach($statistics as $i => $stat)
            <div class="reveal stagger-{{ min($i+1, 4) }}">
                <p class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-secondary-fixed mb-2">{{ $stat['value'] }}</p>
                <p class="text-on-primary/70 text-xs sm:text-sm font-semibold uppercase tracking-wider">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
{{-- FEATURES --}}
<section class="py-16 sm:py-20 md:py-24 bg-white" id="features">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-16 space-y-3 sm:space-y-4 reveal">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-primary">{{ $s['features_title'] ?? 'Fitur Unggulan' }}</h2>
            <p class="text-sm sm:text-base text-on-surface-variant">{{ $s['features_subtitle'] ?? 'Dirancang untuk memudahkan setiap aspek manajemen bisnis Anda.' }}</p>
        </div>
        <div class="bento-grid">
            @php
                $featuresData = json_decode($s['features_data'] ?? '[]', true) ?: [];
            @endphp
            @foreach($featuresData as $i => $feat)
            <div class="p-5 sm:p-8 rounded-2xl sm:rounded-3xl bg-surface-container-low border border-slate-100 hover:border-secondary/30 transition-all hover:shadow-md group reveal stagger-{{ min($i+1, 8) }}">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-xl sm:rounded-2xl flex items-center justify-center text-secondary mb-4 sm:mb-6 shadow-sm group-hover:bg-secondary group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-lg sm:text-2xl">{{ $feat['icon'] ?? 'rocket_launch' }}</span>
                </div>
                <h3 class="text-base sm:text-xl font-semibold text-primary mb-1 sm:mb-2">{{ $feat['title'] ?? '' }}</h3>
                <p class="text-xs sm:text-sm text-on-surface-variant">{{ $feat['desc'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="py-16 sm:py-20 md:py-24 bg-surface-container-lowest" id="how-it-works">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16 reveal">
            <div class="inline-block px-3 sm:px-4 py-1 sm:py-1.5 bg-secondary/10 text-secondary rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-wider mb-3 sm:mb-4">
                {{ $s['how_title'] ?? 'Mudah & Cepat' }}
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-primary">{{ $s['how_heading'] ?? 'Mulai dalam Hitungan Menit' }}</h2>
            <p class="text-sm sm:text-base text-on-surface-variant mt-3 sm:mt-4">{{ $s['how_subtitle'] ?? 'Tiga langkah sederhana untuk transformasi bisnis Anda.' }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-8 relative">
            @php
                $steps = json_decode($s['how_steps'] ?? '[]', true) ?: [
                    ['step' => '01', 'icon' => 'person_add', 'title' => 'Daftar Akun', 'desc' => 'Buat akun gratis dalam 2 menit. Tidak perlu kartu kredit.'],
                    ['step' => '02', 'icon' => 'settings', 'title' => 'Setup Toko', 'desc' => 'Tambahkan produk, harga, dan atur pajak sesuai kebutuhan bisnis Anda.'],
                    ['step' => '03', 'icon' => 'rocket_launch', 'title' => 'Mulai Jualan', 'desc' => 'Langsung proses transaksi dan pantau performa dari dashboard.'],
                ];
            @endphp
            {{-- Connector line (tablet+) --}}
            <div class="hidden sm:block absolute top-12 left-[15%] right-[15%] h-0.5 bg-secondary/20 -z-10"></div>
            @foreach($steps as $i => $step)
            <div class="text-center reveal stagger-{{ min($i+1, 3) }}">
                <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-secondary text-white text-lg sm:text-xl font-black mb-4 sm:mb-6 shadow-lg shadow-secondary/20">
                    <span class="material-symbols-outlined text-xl sm:text-2xl">{{ $step['icon'] }}</span>
                </div>
                <p class="text-[10px] sm:text-xs font-black text-secondary uppercase tracking-widest mb-1 sm:mb-2">Langkah {{ $step['step'] }}</p>
                <h3 class="text-lg sm:text-xl font-bold text-primary mb-1 sm:mb-2">{{ $step['title'] }}</h3>
                <p class="text-xs sm:text-sm text-on-surface-variant">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TESTIMONIALS --}}
<section class="py-16 sm:py-20 md:py-24 bg-surface-container-lowest" id="solutions">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12 sm:mb-16 reveal">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-primary">{{ $s['testimonials_title'] ?? 'Apa Kata Mereka' }}</h2>
            <p class="text-sm sm:text-base text-on-surface-variant mt-3 sm:mt-4">{{ $s['testimonials_subtitle'] ?? 'Kesuksesan bisnis mereka berawal dari sistem yang tepat.' }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @php
                $testis = json_decode($s['testimonials_data'] ?? '[]', true) ?: [
                    ['name' => 'Maya Sartika', 'role' => 'Owner, Green Bites Cafe', 'text' => 'Stitch POS benar-benar mengubah cara kami mengelola stok. Dulu sering sekali bahan habis tanpa terdeteksi, sekarang semuanya terpantau rapi.', 'avatar' => 'https://placehold.co/80/e0f2fe/1e293b?text=MS'],
                    ['name' => 'Budi Santoso', 'role' => 'Founder, Urban Retail', 'text' => 'Proses checkout yang cepat sangat membantu saat jam sibuk. Pelanggan tidak perlu mengantri lama.', 'avatar' => 'https://placehold.co/80/f0f4f8/1e293b?text=BS'],
                    ['name' => 'Rizky Ramadhan', 'role' => 'CEO, Delice Catering', 'text' => 'Fitur multi-outletnya juaranya. Saya bisa cek performa 5 cabang sekaligus dari rumah.', 'avatar' => 'https://placehold.co/80/eaeef2/1e293b?text=RR'],
                ];
            @endphp
            @foreach($testis as $i => $t)
            <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl shadow-sm border border-outline-variant/30 flex flex-col h-full reveal stagger-{{ min($i+1, 3) }} hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="mb-4 sm:mb-6">
                    <img class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-secondary/20 shadow-sm" src="{{ $t['avatar'] ?? 'https://placehold.co/80' }}" alt="{{ $t['name'] }}">
                </div>
                <p class="italic text-on-surface-variant text-xs sm:text-sm flex-1 mb-4 sm:mb-6">"{{ $t['text'] }}"</p>
                <div>
                    <p class="font-bold text-primary text-sm sm:text-base">{{ $t['name'] }}</p>
                    <p class="text-[10px] sm:text-xs text-secondary font-semibold uppercase">{{ $t['role'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRICING PLANS --}}
<section class="py-16 sm:py-20 md:py-24 bg-white" id="pricing">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16 reveal">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-primary">{{ $s['pricing_title'] ?? 'Harga Transparan' }}</h2>
            <p class="text-sm sm:text-base text-on-surface-variant mt-3 sm:mt-4">{{ $s['pricing_subtitle'] ?? 'Pilih paket yang sesuai dengan skala bisnis Anda.' }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 max-w-5xl mx-auto">
            @php
                $plans = json_decode($s['pricing_plans'] ?? '[]', true) ?: [
                    ['name' => 'Basic', 'price' => 'Rp 0', 'period' => '/bulan', 'desc' => 'Cocok untuk bisnis baru', 'features' => ['5 produk aktif', '1 user kasir', 'Laporan harian', 'Support via email']],
                    ['name' => 'Pro', 'price' => 'Rp 199K', 'period' => '/bulan', 'desc' => 'Untuk bisnis bertumbuh', 'features' => ['100 produk', '3 user kasir', 'Laporan mingguan & bulanan', 'Multi-outlet dasar', 'Support prioritas'], 'featured' => true],
                    ['name' => 'Enterprise', 'price' => 'Custom', 'period' => '', 'desc' => 'Untuk skala besar', 'features' => ['Unlimited produk', 'Unlimited user', 'Multi-outlet penuh', 'API akses', 'Dedicated support 24/7', 'Custom integrasi']],
                ];
            @endphp
            @foreach($plans as $i => $plan)
            <div class="relative rounded-2xl sm:rounded-3xl p-6 sm:p-8 flex flex-col reveal stagger-{{ min($i+1, 3) }} {{ $plan['featured'] ?? false ? 'bg-primary text-white shadow-2xl lg:scale-105 z-10' : 'bg-surface-container-low border border-outline-variant/30' }}">
                @if($plan['featured'] ?? false)
                <div class="absolute -top-3 sm:-top-4 left-1/2 -translate-x-1/2 px-3 sm:px-4 py-1 bg-secondary text-white text-[10px] sm:text-xs font-bold rounded-full uppercase">Paling Populer</div>
                @endif
                <p class="text-base sm:text-lg font-bold mb-1 {{ ($plan['featured'] ?? false) ? 'text-secondary-fixed' : 'text-primary' }}">{{ $plan['name'] }}</p>
                <p class="text-[10px] sm:text-xs {{ ($plan['featured'] ?? false) ? 'text-on-primary/70' : 'text-on-surface-variant' }} mb-3 sm:mb-4">{{ $plan['desc'] }}</p>
                <div class="mb-4 sm:mb-6">
                    <span class="text-3xl sm:text-4xl font-extrabold">{{ $plan['price'] }}</span>
                    @if($plan['period'])<span class="text-xs sm:text-sm {{ ($plan['featured'] ?? false) ? 'text-on-primary/60' : 'text-on-surface-variant' }}">{{ $plan['period'] }}</span>@endif
                </div>
                <ul class="space-y-2 sm:space-y-3 flex-1 mb-6 sm:mb-8">
                    @foreach($plan['features'] as $feat)
                    <li class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm">
                        <span class="material-symbols-outlined text-secondary text-[14px] sm:text-[16px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        <span class="{{ ($plan['featured'] ?? false) ? 'text-on-primary/80' : 'text-on-surface-variant' }}">{{ $feat }}</span>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('login') }}" class="block text-center py-2.5 sm:py-3 rounded-xl font-bold text-sm sm:text-base transition-all {{ ($plan['featured'] ?? false) ? 'bg-secondary text-white hover:bg-secondary/90' : 'bg-white border border-outline-variant text-primary hover:bg-surface-container-low' }}">
                    {{ ($plan['featured'] ?? false) ? 'Mulai Gratis' : 'Pilih Paket' }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- BENEFITS --}}
<section class="py-16 sm:py-20 md:py-24 bg-white overflow-hidden" id="pricing">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 sm:gap-16 items-center">
        <div class="relative group reveal-left">
            <div class="absolute -inset-4 bg-secondary/10 rounded-[2rem] sm:rounded-[3rem] -rotate-3 transition-transform group-hover:rotate-0"></div>
            <img class="relative w-full rounded-[1.5rem] sm:rounded-[2.5rem] shadow-xl z-10" src="{{ $s['benefits_image'] ?? 'https://placehold.co/600x500/e0f2fe/1e293b?text=Benefits' }}" alt="Benefits">
        </div>
        <div class="space-y-6 sm:space-y-8 reveal-right">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-primary leading-tight">{{ $s['benefits_title'] ?? 'Biarkan Kami Menangani Kerumitan Operasional' }}</h2>
            <div class="space-y-4 sm:space-y-6">
                @php
                    $benefits = json_decode($s['benefits_data'] ?? '[]', true) ?: [
                        ['title' => 'Fokus Pada Pelayanan', 'desc' => 'Stitch POS menangani semua pencatatan, sehingga Anda dan tim bisa fokus memberikan pelayanan terbaik.'],
                        ['title' => 'Keputusan Berbasis Data', 'desc' => 'Gunakan laporan analitik kami untuk menentukan menu mana yang harus dipertahankan atau dipromosikan.'],
                        ['title' => 'Skalabilitas Tanpa Batas', 'desc' => 'Tambahkan outlet baru hanya dalam hitungan menit tanpa setup infrastruktur yang rumit.'],
                    ];
                @endphp
                @foreach($benefits as $b)
                <div class="flex gap-3 sm:gap-4">
                    <div class="mt-1 w-7 h-7 sm:w-8 sm:h-8 bg-secondary/10 text-secondary flex items-center justify-center rounded-full flex-shrink-0">
                        <span class="material-symbols-outlined text-xs sm:text-sm" style="font-variation-settings: 'FILL' 1;">check</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary text-base sm:text-lg">{{ $b['title'] }}</h4>
                        <p class="text-on-surface-variant text-xs sm:text-sm mt-1">{{ $b['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('login') }}" class="inline-flex px-6 sm:px-8 py-3 sm:py-4 bg-primary text-white rounded-xl font-bold text-sm sm:text-base hover:bg-slate-700 transition-all items-center gap-2 mt-4">
                {{ $s['benefits_btn'] ?? 'Pelajari Lebih Lanjut' }}
            </a>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-16 sm:py-20 md:py-24 bg-surface-container-lowest" id="faq">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12 sm:mb-16 reveal">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-primary">{{ $s['faq_title'] ?? 'Pertanyaan Umum' }}</h2>
            <p class="text-sm sm:text-base text-on-surface-variant mt-3 sm:mt-4">{{ $s['faq_subtitle'] ?? 'Semua yang perlu Anda ketahui tentang Stitch POS.' }}</p>
        </div>
        <div class="space-y-3 sm:space-y-4" x-data="{ activeFaq: null }">
            @php
                $faqs = json_decode($s['faq_data'] ?? '[]', true) ?: [
                    ['q' => 'Apa itu Stitch POS?', 'a' => 'Stitch POS adalah sistem point-of-sale berbasis cloud yang dirancang untuk bisnis retail dan kuliner. Kelola transaksi, stok, laporan, dan karyawan dalam satu platform.']
                    ,['q' => 'Apakah ada biaya setup?', 'a' => 'Tidak. Anda bisa daftar dan mulai menggunakan sistem secara gratis. Biaya hanya berlaku saat Anda upgrade ke paket berbayar.']
                    ,['q' => 'Apakah bisa dipakai untuk banyak cabang?', 'a' => 'Ya! Paket Enterprise mendukung multi-outlet dengan dashboard terpusat. Anda bisa mengelola puluhan cabang dari satu akun.']
                    ,['q' => 'Bagaimana keamanan data saya?', 'a' => 'Kami menggunakan enkripsi end-to-end, backup otomatis setiap jam, dan server tersertifikasi. Data Anda aman dan selalu tersedia.']
                    ,['q' => 'Metode pembayaran apa yang didukung?', 'a' => 'Stitch POS mendukung pembayaran tunai dan QRIS. Integrasi dengan e-wallet dan kartu debit tersedia di paket Pro+.']
                    ,['q' => 'Apakah bisa dicoba gratis?', 'a' => 'Tentu. Paket Basic gratis selamanya untuk 5 produk dan 1 user. Anda bisa upgrade kapan saja saat bisnis bertumbuh.']
                    ,['q' => 'Apakah ada pelatihan untuk tim?', 'a' => 'Kami menyediakan video tutorial, panduan lengkap, dan sesi onboarding gratis untuk paket Pro dan Enterprise.'],
                ];
            @endphp
            @foreach($faqs as $i => $faq)
            <div class="bg-white rounded-xl sm:rounded-2xl border border-outline-variant/30 overflow-hidden reveal stagger-{{ min($i+1, 7) }}">
                <button @click="activeFaq === {{ $i }} ? activeFaq = null : activeFaq = {{ $i }}"
                        class="w-full flex justify-between items-center px-4 sm:px-6 py-4 sm:py-5 text-left hover:bg-surface-container-low/50 transition-colors">
                    <span class="font-semibold text-primary text-xs sm:text-sm">{{ $faq['q'] }}</span>
                    <span class="material-symbols-outlined text-on-surface-variant transition-transform duration-200 text-lg sm:text-xl"
                          :class="activeFaq === {{ $i }} ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="activeFaq === {{ $i }}" x-collapse class="px-4 sm:px-6 pb-4 sm:pb-5 text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CLIENT LOGOS --}}
<section class="py-12 sm:py-16 bg-white border-t border-outline-variant/20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 mb-6 sm:mb-8 text-center reveal">
        <p class="text-[10px] sm:text-xs font-semibold text-on-surface-variant uppercase tracking-wider">{{ $s['clients_title'] ?? 'Dipercaya oleh Ratusan Bisnis di Indonesia' }}</p>
    </div>
    <div class="relative">
        @php
            $logos = json_decode($s['clients_logos'] ?? '[]', true) ?: [
                ['name' => 'Green Cafe', 'color' => 'bg-emerald-100 text-emerald-700'],
                ['name' => 'Urban Retail', 'color' => 'bg-blue-100 text-blue-700'],
                ['name' => 'Delice Food', 'color' => 'bg-amber-100 text-amber-700'],
                ['name' => 'Prima Mart', 'color' => 'bg-purple-100 text-purple-700'],
                ['name' => 'Bakery Elite', 'color' => 'bg-rose-100 text-rose-700'],
                ['name' => 'Fresh Corner', 'color' => 'bg-teal-100 text-teal-700'],
                ['name' => 'Max Store', 'color' => 'bg-indigo-100 text-indigo-700'],
                ['name' => 'Quick Shop', 'color' => 'bg-orange-100 text-orange-700'],
            ];
        @endphp
        {{-- Duplicate for infinite scroll illusion --}}
        <div class="flex gap-8 sm:gap-12 items-center whitespace-nowrap" style="animation: marquee 30s linear infinite;">
            @foreach(array_merge($logos, $logos) as $logo)
            <div class="inline-flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-3 sm:py-4 rounded-xl sm:rounded-2xl {{ $logo['color'] ?? 'bg-slate-100 text-slate-700' }}">
                <span class="material-symbols-outlined opacity-70">store</span>
                <span class="font-bold text-sm">{{ $logo['name'] }}</span>
            </div>
            @endforeach
        </div>
        <style>
            @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        </style>
    </div>
</section>

{{-- CTA --}}
<section class="py-14 sm:py-16 md:py-20 bg-primary text-white text-center px-4 sm:px-6" id="contact">
    <div class="max-w-4xl mx-auto space-y-6 sm:space-y-8 reveal">
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold">{{ $ctaTitle }}</h2>
        <p class="text-base sm:text-lg opacity-80 max-w-2xl mx-auto">{{ $ctaSub }}</p>
        <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 pt-2 sm:pt-4">
            <a href="{{ route('login') }}" class="px-8 sm:px-10 py-4 sm:py-5 bg-secondary text-white rounded-xl font-black text-base sm:text-lg hover:scale-105 transition-all shadow-xl shadow-secondary/20">
                {{ $ctaBtn1 }}
            </a>
            <a href="#" class="px-8 sm:px-10 py-4 sm:py-5 bg-transparent border-2 border-white/30 text-white rounded-xl font-black text-base sm:text-lg hover:bg-white/10 transition-all">
                {{ $ctaBtn2 }}
            </a>
        </div>
        <p class="text-xs sm:text-sm opacity-60">{{ $s['cta_disclaimer'] ?? 'Tidak puas? Batalkan kapan saja.' }}</p>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-primary text-on-primary">
    <div class="flex flex-col lg:flex-row justify-between items-start w-full px-4 sm:px-6 py-12 sm:py-16 gap-8 max-w-7xl mx-auto border-b border-white/10">
        <div class="space-y-4 sm:space-y-6 max-w-sm">
            <span class="text-xl sm:text-2xl font-bold text-secondary-fixed">{{ $s['brand_name'] ?? 'Stitch POS' }}</span>
            <p class="text-on-primary/70 text-xs sm:text-sm">{{ $footerAbout }}</p>
            <div class="flex gap-3 sm:gap-4">
                <a class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-secondary transition-colors" href="#"><span class="material-symbols-outlined text-sm">face_nod</span></a>
                <a class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-secondary transition-colors" href="#"><span class="material-symbols-outlined text-sm">alternate_email</span></a>
                <a class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-secondary transition-colors" href="#"><span class="material-symbols-outlined text-sm">smart_display</span></a>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-8 sm:gap-12">
            <div class="space-y-3 sm:space-y-4">
                <p class="font-bold text-white text-[10px] sm:text-xs uppercase tracking-widest">Produk</p>
                <ul class="space-y-1.5 sm:space-y-2 text-on-primary/70 text-xs sm:text-sm">
                    <li><a class="hover:text-secondary-fixed transition-colors" href="#">Fitur POS</a></li>
                    <li><a class="hover:text-secondary-fixed transition-colors" href="#">Integrasi Stok</a></li>
                    <li><a class="hover:text-secondary-fixed transition-colors" href="#">Laporan Keuangan</a></li>
                    <li><a class="hover:text-secondary-fixed transition-colors" href="#">Update Versi</a></li>
                </ul>
            </div>
            <div class="space-y-3 sm:space-y-4">
                <p class="font-bold text-white text-[10px] sm:text-xs uppercase tracking-widest">Dukungan</p>
                <ul class="space-y-1.5 sm:space-y-2 text-on-primary/70 text-xs sm:text-sm">
                    <li><a class="hover:text-secondary-fixed transition-colors" href="#">Pusat Bantuan</a></li>
                    <li><a class="hover:text-secondary-fixed transition-colors" href="#">Video Tutorial</a></li>
                    <li><a class="hover:text-secondary-fixed transition-colors" href="#">API Docs</a></li>
                    <li><a class="hover:text-secondary-fixed transition-colors" href="#">Chat Support</a></li>
                </ul>
            </div>
            <div class="space-y-3 sm:space-y-4">
                <p class="font-bold text-white text-[10px] sm:text-xs uppercase tracking-widest">Legal</p>
                <ul class="space-y-1.5 sm:space-y-2 text-on-primary/70 text-xs sm:text-sm">
                    <li><a class="hover:text-secondary-fixed transition-colors" href="#">Privacy Policy</a></li>
                    <li><a class="hover:text-secondary-fixed transition-colors" href="#">Terms of Service</a></li>
                    <li><a class="hover:text-secondary-fixed transition-colors" href="#">Cookies</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="flex flex-col sm:flex-row justify-between items-center w-full px-4 sm:px-6 py-6 sm:py-8 gap-3 sm:gap-4 max-w-7xl mx-auto opacity-60 text-[10px] sm:text-xs">
        <p>{{ $footerCopy }}</p>
        <div class="flex gap-4 sm:gap-6">
            <span>Indonesia</span>
            <span>English</span>
        </div>
    </div>
</footer>

{{-- Back to Top --}}
<button @click="window.scrollTo({top:0, behavior:'smooth'})" x-data x-show="false" x-init="window.addEventListener('scroll', () => { $el.style.display = window.scrollY > 600 ? 'flex' : 'none' })"
        class="fixed bottom-6 right-6 z-50 w-12 h-12 bg-secondary text-white rounded-full shadow-lg shadow-secondary/30 flex items-center justify-center hover:bg-secondary/90 transition-all"
        style="display:none;">
    <span class="material-symbols-outlined">arrow_upward</span>
</button>

{{-- Scroll Reveal Observer --}}
<script>
(function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
        observer.observe(el);
    });
})();
</script>
</body>
</html>

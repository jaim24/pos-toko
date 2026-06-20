@extends('layouts.app')
@section('title', 'Pengaturan Halaman Utama')

@php
use App\Models\LandingSetting;

// Prepare decoded data
$decoded = [];
foreach ($settings as $group => $items) {
    foreach ($items as $s) {
        if (in_array($s->key, ['statistics_data','how_steps','testimonials_data','pricing_plans','benefits_data','faq_data','clients_logos','features_data'])) {
            $decoded[$s->key] = json_decode($s->value, true) ?: [];
        }
    }
}
$stats     = $decoded['statistics_data'] ?? [];
$steps     = $decoded['how_steps'] ?? [];
$testis    = $decoded['testimonials_data'] ?? [];
$plans     = $decoded['pricing_plans'] ?? [];
$benefits  = $decoded['benefits_data'] ?? [];
$faqs      = $decoded['faq_data'] ?? [];
$logos     = $decoded['clients_logos'] ?? [];
$features  = $decoded['features_data'] ?? [];

// Tab definitions
$tabs = [
    ['id' => 'general',    'label' => 'Umum',          'icon' => 'branding_watermark'],
    ['id' => 'hero',       'label' => 'Pembuka',       'icon' => 'campaign'],
    ['id' => 'statistics', 'label' => 'Angka',         'icon' => 'bar_chart'],
    ['id' => 'features',   'label' => 'Fitur',         'icon' => 'dashboard'],
    ['id' => 'howitworks', 'label' => 'Cara Kerja',    'icon' => 'account_tree'],
    ['id' => 'testimonials','label' => 'Testimoni',    'icon' => 'groups'],
    ['id' => 'pricing',    'label' => 'Harga',         'icon' => 'payments'],
    ['id' => 'benefits',   'label' => 'Keunggulan',    'icon' => 'star'],
    ['id' => 'faq',        'label' => 'FAQ',           'icon' => 'contact_support'],
    ['id' => 'clients',    'label' => 'Klien',         'icon' => 'domain'],
    ['id' => 'cta',        'label' => 'Ajakan',        'icon' => 'rocket_launch'],
    ['id' => 'footer',     'label' => 'Footer',        'icon' => 'corporate_fare'],
];
@endphp

@section('content')
<div class="max-w-5xl mx-auto px-2 sm:px-4 lg:px-0" x-data="{ activeTab: 'general' }">

    {{-- HEADER — stacks on mobile, row on sm+ --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4 sm:mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-on-surface tracking-tight mb-1">Pengaturan Halaman Utama</h2>
            <p class="text-on-surface-variant text-xs sm:text-sm">Atur isi halaman depan website melalui tab-tab di bawah.</p>
        </div>
        <a href="{{ url('/') }}" target="_blank"
           class="self-start sm:self-auto inline-flex items-center gap-2 px-3 sm:px-4 py-2 border border-outline-variant text-on-surface-variant rounded-full text-xs sm:text-sm font-medium hover:bg-surface-container-low transition-colors shrink-0">
            <span class="material-symbols-outlined text-base sm:text-lg">open_in_new</span>
            <span>Lihat Hasil</span>
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 text-green-700 rounded-xl text-xs sm:text-sm font-medium flex items-center gap-2">
        <span class="material-symbols-outlined text-base sm:text-lg shrink-0">check_circle</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- TAB BAR — scrollable horizontal on mobile via mouse wheel --}}
    <div class="mb-4 sm:mb-6 bg-white rounded-2xl p-1.5 sm:p-2 shadow-sm border border-outline-variant/20 overflow-x-auto scrollbar-hide"
         x-ref="tabBar"
         @wheel.prevent="$refs.tabBar.scrollLeft += $event.deltaY">
        <div class="flex flex-nowrap gap-1 min-w-max">
            @foreach($tabs as $tab)
            <button type="button"
                    @click="activeTab = '{{ $tab['id'] }}'"
                    :class="activeTab === '{{ $tab['id'] }}'
                        ? 'bg-emerald-600 text-white shadow-sm'
                        : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100'"
                    class="flex items-center gap-1 sm:gap-1.5 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all shrink-0">
                <span class="material-symbols-outlined text-base sm:text-lg">{{ $tab['icon'] }}</span>
                <span class="hidden sm:inline">{{ $tab['label'] }}</span>
            </button>
            @endforeach
        </div>
    </div>

    {{-- FORM — all 12 tabs --}}
    <form method="POST" action="{{ route('admin.landing-settings.update') }}">
        @csrf

        {{-- ======== TAB 1: UMUM ======== --}}
        <div x-show="activeTab === 'general'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">branding_watermark</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Informasi Umum</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Nama Usaha / Brand</label>
                        <input type="text" name="set[brand_name]" value="{{ old('set.brand_name', LandingSetting::get('brand_name')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Judul Browser</label>
                        <input type="text" name="set[meta_title]" value="{{ old('set.meta_title', LandingSetting::get('meta_title')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Deskripsi Singkat (untuk Google)</label>
                    <textarea name="set[meta_desc]" rows="2" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">{{ old('set.meta_desc', LandingSetting::get('meta_desc')) }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Gambar Banner (URL)</label>
                        <input type="text" name="set[meta_og_image]" value="{{ old('set.meta_og_image', LandingSetting::get('meta_og_image')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all" placeholder="https://...">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Warna Utama</label>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <input type="color" name="set[accent_color]" value="{{ old('set.accent_color', LandingSetting::get('accent_color', '#10b981')) }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg border cursor-pointer shrink-0">
                            <input type="text" value="{{ old('set.accent_color', LandingSetting::get('accent_color', '#10b981')) }}" class="flex-1 border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-mono" oninput="this.previousElementSibling.value = this.value">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======== TAB 2: PEMBUKA (HERO) ======== --}}
        <div x-show="activeTab === 'hero'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">campaign</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Bagian Atas (Pembuka)</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Label Kecil Atas</label>
                        <input type="text" name="set[hero_badge]" value="{{ old('set.hero_badge', LandingSetting::get('hero_badge')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Gambar Samping Kanan (URL)</label>
                        <input type="text" name="set[hero_image]" value="{{ old('set.hero_image', LandingSetting::get('hero_image')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Judul Besar</label>
                    <textarea name="set[hero_title]" rows="2" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">{{ old('set.hero_title', LandingSetting::get('hero_title')) }}</textarea>
                </div>
                <div>
                    <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Deskripsi Singkat</label>
                    <textarea name="set[hero_subtitle]" rows="3" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">{{ old('set.hero_subtitle', LandingSetting::get('hero_subtitle')) }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Teks Tombol Utama</label>
                        <input type="text" name="set[hero_btn_primary]" value="{{ old('set.hero_btn_primary', LandingSetting::get('hero_btn_primary')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Teks Tombol Kedua</label>
                        <input type="text" name="set[hero_btn_secondary]" value="{{ old('set.hero_btn_secondary', LandingSetting::get('hero_btn_secondary')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Keterangan Dipercaya</label>
                        <input type="text" name="set[hero_social_proof]" value="{{ old('set.hero_social_proof', LandingSetting::get('hero_social_proof')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Label Statistik</label>
                        <input type="text" name="set[hero_stat_label]" value="{{ old('set.hero_stat_label', LandingSetting::get('hero_stat_label')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Nilai Statistik</label>
                        <input type="text" name="set[hero_stat_value]" value="{{ old('set.hero_stat_value', LandingSetting::get('hero_stat_value')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- ======== TAB 3: ANGKA STATISTIK ======== --}}
        <div x-show="activeTab === 'statistics'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">bar_chart</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Angka Statistik (maks. 4)</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                @for($i = 0; $i < 4; $i++)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Angka ke-{{ $i+1 }}</label>
                        <input type="text" name="dec[statistics][{{ $i }}][value]" value="{{ old('dec.statistics.'.$i.'.value', $stats[$i]['value'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400" placeholder="contoh: 2500+">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Keterangan ke-{{ $i+1 }}</label>
                        <input type="text" name="dec[statistics][{{ $i }}][label]" value="{{ old('dec.statistics.'.$i.'.label', $stats[$i]['label'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400" placeholder="contoh: Bisnis Aktif">
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- ======== TAB 4: FITUR UNGGULAN ======== --}}
        <div x-show="activeTab === 'features'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">dashboard</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Fitur Unggulan (maks. 8)</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Judul Bagian</label>
                        <input type="text" name="set[features_title]" value="{{ old('set.features_title', LandingSetting::get('features_title')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Deskripsi Bagian</label>
                        <input type="text" name="set[features_subtitle]" value="{{ old('set.features_subtitle', LandingSetting::get('features_subtitle')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                </div>
                @for($i = 0; $i < 8; $i++)
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Ikon {{ $i+1 }}</label>
                        <input type="text" name="dec[features][{{ $i }}][icon]" value="{{ old('dec.features.'.$i.'.icon', $features[$i]['icon'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400" placeholder="inventory_2">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Nama Fitur {{ $i+1 }}</label>
                        <input type="text" name="dec[features][{{ $i }}][title]" value="{{ old('dec.features.'.$i.'.title', $features[$i]['title'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Penjelasan {{ $i+1 }}</label>
                        <input type="text" name="dec[features][{{ $i }}][desc]" value="{{ old('dec.features.'.$i.'.desc', $features[$i]['desc'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- ======== TAB 5: CARA KERJA ======== --}}
        <div x-show="activeTab === 'howitworks'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">account_tree</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Cara Kerja / Langkah</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Label Kecil</label>
                        <input type="text" name="set[how_title]" value="{{ old('set.how_title', LandingSetting::get('how_title')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Judul Besar</label>
                        <input type="text" name="set[how_heading]" value="{{ old('set.how_heading', LandingSetting::get('how_heading')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Deskripsi Singkat</label>
                        <input type="text" name="set[how_subtitle]" value="{{ old('set.how_subtitle', LandingSetting::get('how_subtitle')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                </div>
                @for($i = 0; $i < 3; $i++)
                <div class="p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl">
                    <p class="text-[11px] sm:text-xs font-bold text-gray-400 uppercase mb-2 sm:mb-3">Langkah {{ $i+1 }}</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Nomor</label>
                            <input type="text" name="dec[how_steps][{{ $i }}][step]" value="{{ old('dec.how_steps.'.$i.'.step', $steps[$i]['step'] ?? '0'.($i+1)) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                        </div>
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Ikon</label>
                            <input type="text" name="dec[how_steps][{{ $i }}][icon]" value="{{ old('dec.how_steps.'.$i.'.icon', $steps[$i]['icon'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400" placeholder="person_add">
                        </div>
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Judul</label>
                            <input type="text" name="dec[how_steps][{{ $i }}][title]" value="{{ old('dec.how_steps.'.$i.'.title', $steps[$i]['title'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                        </div>
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Penjelasan</label>
                            <input type="text" name="dec[how_steps][{{ $i }}][desc]" value="{{ old('dec.how_steps.'.$i.'.desc', $steps[$i]['desc'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- ======== TAB 6: TESTIMONI ======== --}}
        <div x-show="activeTab === 'testimonials'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">groups</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Testimoni Pelanggan</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Judul Bagian</label>
                        <input type="text" name="set[testimonials_title]" value="{{ old('set.testimonials_title', LandingSetting::get('testimonials_title')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Deskripsi Bagian</label>
                        <input type="text" name="set[testimonials_subtitle]" value="{{ old('set.testimonials_subtitle', LandingSetting::get('testimonials_subtitle')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                </div>
                @for($i = 0; $i < 3; $i++)
                <div class="p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl">
                    <p class="text-[11px] sm:text-xs font-bold text-gray-400 uppercase mb-2 sm:mb-3">Testimoni {{ $i+1 }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Nama</label>
                            <input type="text" name="dec[testimonials][{{ $i }}][name]" value="{{ old('dec.testimonials.'.$i.'.name', $testis[$i]['name'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                        </div>
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Jabatan / Usaha</label>
                            <input type="text" name="dec[testimonials][{{ $i }}][role]" value="{{ old('dec.testimonials.'.$i.'.role', $testis[$i]['role'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                        </div>
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Foto (URL)</label>
                            <input type="text" name="dec[testimonials][{{ $i }}][avatar]" value="{{ old('dec.testimonials.'.$i.'.avatar', $testis[$i]['avatar'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400" placeholder="https://...">
                        </div>
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Isi Testimoni</label>
                            <textarea name="dec[testimonials][{{ $i }}][text]" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">{{ old('dec.testimonials.'.$i.'.text', $testis[$i]['text'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- ======== TAB 7: PAKET HARGA ======== --}}
        <div x-show="activeTab === 'pricing'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">payments</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Paket Harga</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Judul Bagian</label>
                        <input type="text" name="set[pricing_title]" value="{{ old('set.pricing_title', LandingSetting::get('pricing_title')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Deskripsi Bagian</label>
                        <input type="text" name="set[pricing_subtitle]" value="{{ old('set.pricing_subtitle', LandingSetting::get('pricing_subtitle')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                </div>
                @for($i = 0; $i < 3; $i++)
                <div class="p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl">
                    <p class="text-[11px] sm:text-xs font-bold text-gray-400 uppercase mb-2 sm:mb-3">Paket {{ $i+1 }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-3">
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Nama Paket</label>
                            <input type="text" name="dec[pricing][{{ $i }}][name]" value="{{ old('dec.pricing.'.$i.'.name', $plans[$i]['name'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                        </div>
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Harga</label>
                            <input type="text" name="dec[pricing][{{ $i }}][price]" value="{{ old('dec.pricing.'.$i.'.price', $plans[$i]['price'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                        </div>
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Periode</label>
                            <input type="text" name="dec[pricing][{{ $i }}][period]" value="{{ old('dec.pricing.'.$i.'.period', $plans[$i]['period'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400" placeholder="/bulan">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Deskripsi Singkat</label>
                            <input type="text" name="dec[pricing][{{ $i }}][desc]" value="{{ old('dec.pricing.'.$i.'.desc', $plans[$i]['desc'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                        </div>
                        <div>
                            <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Fitur (pisahkan dengan koma)</label>
                            <input type="text" name="dec[pricing][{{ $i }}][features_text]" value="{{ old('dec.pricing.'.$i.'.features_text', isset($plans[$i]['features']) ? implode(', ', $plans[$i]['features']) : '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400" placeholder="Fitur A, Fitur B">
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="dec[pricing][{{ $i }}][featured]" value="1" {{ old('dec.pricing.'.$i.'.featured', ($plans[$i]['featured'] ?? false) ? '1' : '') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-[11px] sm:text-xs font-semibold text-gray-500">Tandai sebagai paket unggulan</span>
                        </label>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- ======== TAB 8: KEUNGGULAN (BENEFITS) ======== --}}
        <div x-show="activeTab === 'benefits'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">star</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Keunggulan / Manfaat</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Judul Bagian</label>
                        <input type="text" name="set[benefits_title]" value="{{ old('set.benefits_title', LandingSetting::get('benefits_title')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Gambar Samping (URL)</label>
                        <input type="text" name="set[benefits_image]" value="{{ old('set.benefits_image', LandingSetting::get('benefits_image')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Teks Tombol</label>
                        <input type="text" name="set[benefits_btn]" value="{{ old('set.benefits_btn', LandingSetting::get('benefits_btn')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                </div>
                @for($i = 0; $i < 3; $i++)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Judul Manfaat {{ $i+1 }}</label>
                        <input type="text" name="dec[benefits][{{ $i }}][title]" value="{{ old('dec.benefits.'.$i.'.title', $benefits[$i]['title'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Penjelasan {{ $i+1 }}</label>
                        <input type="text" name="dec[benefits][{{ $i }}][desc]" value="{{ old('dec.benefits.'.$i.'.desc', $benefits[$i]['desc'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- ======== TAB 9: FAQ ======== --}}
        <div x-show="activeTab === 'faq'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">contact_support</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Pertanyaan Umum (FAQ)</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Judul Bagian</label>
                        <input type="text" name="set[faq_title]" value="{{ old('set.faq_title', LandingSetting::get('faq_title')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Deskripsi Bagian</label>
                        <input type="text" name="set[faq_subtitle]" value="{{ old('set.faq_subtitle', LandingSetting::get('faq_subtitle')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                </div>
                @for($i = 0; $i < 7; $i++)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Pertanyaan ke-{{ $i+1 }}</label>
                        <input type="text" name="dec[faq][{{ $i }}][q]" value="{{ old('dec.faq.'.$i.'.q', $faqs[$i]['q'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Jawaban ke-{{ $i+1 }}</label>
                        <textarea name="dec[faq][{{ $i }}][a]" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">{{ old('dec.faq.'.$i.'.a', $faqs[$i]['a'] ?? '') }}</textarea>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- ======== TAB 10: LOGO KLIEN ======== --}}
        <div x-show="activeTab === 'clients'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">domain</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Logo Klien / Partner</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                <div>
                    <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Judul Bagian</label>
                    <input type="text" name="set[clients_title]" value="{{ old('set.clients_title', LandingSetting::get('clients_title')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                </div>
                @for($i = 0; $i < 8; $i++)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Nama Klien ke-{{ $i+1 }}</label>
                        <input type="text" name="dec[clients][{{ $i }}][name]" value="{{ old('dec.clients.'.$i.'.name', $logos[$i]['name'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 mb-1">Warna Latar</label>
                        <input type="text" name="dec[clients][{{ $i }}][color]" value="{{ old('dec.clients.'.$i.'.color', $logos[$i]['color'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400" placeholder="bg-emerald-100 text-emerald-700">
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- ======== TAB 11: AJAKAN (CTA) ======== --}}
        <div x-show="activeTab === 'cta'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">rocket_launch</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Bagian Ajakan (CTA)</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                <div>
                    <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Judul Besar</label>
                    <input type="text" name="set[cta_title]" value="{{ old('set.cta_title', LandingSetting::get('cta_title')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                </div>
                <div>
                    <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Deskripsi</label>
                    <textarea name="set[cta_subtitle]" rows="2" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">{{ old('set.cta_subtitle', LandingSetting::get('cta_subtitle')) }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Tombol Utama</label>
                        <input type="text" name="set[cta_btn_primary]" value="{{ old('set.cta_btn_primary', LandingSetting::get('cta_btn_primary')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Tombol Kedua</label>
                        <input type="text" name="set[cta_btn_secondary]" value="{{ old('set.cta_btn_secondary', LandingSetting::get('cta_btn_secondary')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Catatan Kecil</label>
                        <input type="text" name="set[cta_disclaimer]" value="{{ old('set.cta_disclaimer', LandingSetting::get('cta_disclaimer')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- ======== TAB 12: FOOTER ======== --}}
        <div x-show="activeTab === 'footer'" x-cloak class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center gap-2 sm:gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">corporate_fare</span>
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Bagian Bawah (Footer)</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                <div>
                    <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Tentang Perusahaan</label>
                    <textarea name="set[footer_about]" rows="3" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">{{ old('set.footer_about', LandingSetting::get('footer_about')) }}</textarea>
                </div>
                <div>
                    <label class="block text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Teks Hak Cipta</label>
                    <input type="text" name="set[footer_copyright]" value="{{ old('set.footer_copyright', LandingSetting::get('footer_copyright')) }}" class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 transition-all">
                </div>
            </div>
        </div>

        {{-- SUBMIT BUTTON --}}
        <div class="mt-4 sm:mt-6">
            <button type="submit" class="w-full sm:w-auto px-6 sm:px-8 py-3 bg-emerald-600 text-white rounded-full font-semibold hover:bg-emerald-700 transition-colors shadow-sm flex items-center justify-center gap-2 text-sm sm:text-base">
                <span class="material-symbols-outlined text-lg">save</span> Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>
@stop

@extends('layouts.app')
@section('title', 'Dashboard')


@section('content')
<div class="max-w-[1400px] mx-auto">
    {{-- Welcome Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-6 sm:mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight mb-1 sm:mb-2">Dashboard</h2>
            <p class="text-on-surface-variant text-xs sm:text-sm flex items-center gap-2">
                <span>{{ now()->isoFormat('dddd, D MMMM Y') }}</span> <span class="hidden sm:inline">• Kelola operasi retail Anda dengan efisien.</span>
            </p>
        </div>
        <div class="flex gap-2 sm:gap-3">
            <a href="{{ route('kasir.transactions.create') }}" class="flex-1 sm:flex-none px-4 sm:px-5 py-2.5 bg-secondary text-white rounded-full text-xs sm:text-sm font-medium flex items-center justify-center gap-2 hover:bg-secondary/90 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[16px] sm:text-[18px]">add</span> Transaksi Baru
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex-1 sm:flex-none px-4 sm:px-5 py-2.5 bg-white text-on-surface rounded-full text-xs sm:text-sm font-medium border border-outline-variant/50 hover:bg-surface-container-low transition-colors shadow-sm flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px] sm:text-[18px]">description</span> Laporan
            </a>
        </div>
    </div>

    {{-- Summary Cards Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 mb-6 sm:mb-8">
        {{-- 1. Today Sales --}}
        <div class="bg-white rounded-2xl p-3 sm:p-4 shadow-sm border border-outline-variant/30 col-span-2">
            <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-secondary/10 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-secondary text-lg sm:text-xl">today</span>
                </div>
                <span class="text-[9px] sm:text-[10px] font-semibold text-on-surface-variant uppercase tracking-wider leading-tight">Omzet<br>Hari Ini</span>
            </div>
            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-on-surface truncate">Rp {{ number_format($todayTotal, 0, ',', '.') }}</p>
            @if(!is_null($todayVsYesterday))
            <p class="text-[10px] sm:text-xs mt-1 flex items-center gap-1 font-medium {{ $todayVsYesterday >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <span class="material-symbols-outlined text-xs">{{ $todayVsYesterday >= 0 ? 'trending_up' : 'trending_down' }}</span>
                {{ abs($todayVsYesterday) }}% vs kemarin
            </p>
            @endif
        </div>
        {{-- 2. Today Transaction Count --}}
        <div class="bg-white rounded-2xl p-3 sm:p-4 shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-primary-container/20 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-primary text-lg sm:text-xl">receipt_long</span>
                </div>
                <span class="text-[9px] sm:text-[10px] font-semibold text-on-surface-variant uppercase tracking-wider leading-tight">Transaksi<br>Hari Ini</span>
            </div>
            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-on-surface">{{ $todayCount }}</p>
        </div>
        {{-- 3. Average Transaction --}}
        <div class="bg-white rounded-2xl p-3 sm:p-4 shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-tertiary-container/20 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-tertiary-container text-lg sm:text-xl">trending_up</span>
                </div>
                <span class="text-[9px] sm:text-[10px] font-semibold text-on-surface-variant uppercase tracking-wider leading-tight">Rata-rata<br>Nilai</span>
            </div>
            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-on-surface truncate">Rp {{ number_format($todayAvg, 0, ',', '.') }}</p>
        </div>
        {{-- 4. Month Sales --}}
        <div class="bg-white rounded-2xl p-3 sm:p-4 shadow-sm border border-outline-variant/30 col-span-2">
            <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-error/10 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-error text-lg sm:text-xl">calendar_month</span>
                </div>
                <span class="text-[9px] sm:text-[10px] font-semibold text-on-surface-variant uppercase tracking-wider leading-tight">Omzet<br>Bulan Ini</span>
            </div>
            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-on-surface truncate">Rp {{ number_format($monthTotal, 0, ',', '.') }}</p>
            @if(!is_null($monthVsLastMonth))
            <p class="text-[10px] sm:text-xs mt-1 flex items-center gap-1 font-medium {{ $monthVsLastMonth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <span class="material-symbols-outlined text-xs">{{ $monthVsLastMonth >= 0 ? 'trending_up' : 'trending_down' }}</span>
                {{ abs($monthVsLastMonth) }}% vs bulan lalu
            </p>
            @endif
        </div>
    </div>

    {{-- Sub Cards --}}
    <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div class="bg-white rounded-2xl p-3 sm:p-4 shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 sm:gap-3 mb-1">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-blue-50 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-blue-700 text-lg">inventory_2</span>
                </div>
                <span class="text-[9px] sm:text-[10px] font-semibold text-on-surface-variant uppercase tracking-wider">Total Produk</span>
            </div>
            <p class="text-lg sm:text-xl font-bold text-on-surface">{{ $totalProducts }} <span class="text-xs font-normal text-on-surface-variant">sku</span></p>
        </div>
        <div class="bg-white rounded-2xl p-3 sm:p-4 shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 sm:gap-3 mb-1">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-orange-50 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-orange-600 text-lg">warning</span>
                </div>
                <span class="text-[9px] sm:text-[10px] font-semibold text-on-surface-variant uppercase tracking-wider">Stok Rendah (≤5)</span>
            </div>
            <p class="text-lg sm:text-xl font-bold text-on-surface">{{ $lowStock }} <span class="text-xs font-normal text-on-surface-variant">produk</span></p>
        </div>
    </div>

    {{-- Payment Method Breakdown --}}
    <div class="mb-6 sm:mb-8">
        <h3 class="text-sm font-semibold text-on-surface mb-3 sm:mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-lg">account_balance</span> Metode Pembayaran Hari Ini
        </h3>
        <div class="grid grid-cols-2 gap-3 sm:gap-4">
            <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-outline-variant/30 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-50 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-green-700 text-xl sm:text-2xl">payments</span>
                </div>
                <div>
                    <p class="text-[10px] sm:text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Tunai (Cash)</p>
                    <p class="text-lg sm:text-xl font-bold text-on-surface">{{ $todayCashCount }} <span class="text-xs font-normal text-on-surface-variant">transaksi</span></p>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-outline-variant/30 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-blue-700 text-xl sm:text-2xl">qr_code_2</span>
                </div>
                <div>
                    <p class="text-[10px] sm:text-xs font-semibold text-on-surface-variant uppercase tracking-wider">QRIS</p>
                    <p class="text-lg sm:text-xl font-bold text-on-surface">{{ $todayQrisCount }} <span class="text-xs font-normal text-on-surface-variant">transaksi</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Two Column: Recent Transactions + Top Products --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        {{-- Recent Transactions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 flex items-center justify-between">
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Transaksi Terbaru</h3>
                <a href="{{ route('admin.reports.index') }}" class="text-xs text-secondary font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[450px]">
                    <thead>
                        <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                            <th class="text-left px-3 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Invoice</th>
                            <th class="text-left px-3 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kasir</th>
                            <th class="text-right px-3 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total</th>
                            <th class="text-center px-3 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Metode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-3 sm:px-5 py-3 font-mono text-xs text-on-surface">{{ $trx->invoice_number }}</td>
                            <td class="px-3 sm:px-5 py-3 text-on-surface-variant text-xs sm:text-sm">{{ $trx->user->name }}</td>
                            <td class="px-3 sm:px-5 py-3 text-right font-mono text-xs sm:text-sm text-on-surface">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="px-3 sm:px-5 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-[10px] font-medium uppercase {{ $trx->payment_method === 'cash' ? 'bg-surface-container text-on-surface-variant' : 'bg-primary-container/20 text-on-primary-container' }}">
                                    {{ $trx->payment_method }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-3 sm:px-5 py-8 text-center text-on-surface-variant text-xs sm:text-sm">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Products Today --}}
        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 flex items-center justify-between">
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Produk Terlaris Hari Ini</h3>
                <span class="text-[10px] text-on-surface-variant">Top 5</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[350px]">
                    <thead>
                        <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                            <th class="text-center px-2 sm:px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider w-8">#</th>
                            <th class="text-left px-2 sm:px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Produk</th>
                            <th class="text-center px-2 sm:px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Qty</th>
                            <th class="text-right px-2 sm:px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($topProducts ?? collect() as $idx => $tp)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-2 sm:px-4 py-3 text-center">
                                <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full inline-flex items-center justify-center text-[10px] sm:text-xs font-bold
                                    {{ $idx === 0 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $idx === 1 ? 'bg-gray-100 text-gray-600' : '' }}
                                    {{ $idx === 2 ? 'bg-orange-50 text-orange-700' : '' }}
                                    {{ $idx > 2 ? 'bg-surface-container-low text-on-surface-variant' : '' }}">
                                    {{ $idx + 1 }}
                                </span>
                            </td>
                            <td class="px-2 sm:px-4 py-3">
                                <p class="font-medium text-on-surface text-xs sm:text-sm truncate max-w-[120px] sm:max-w-[180px]">{{ $tp->product_name }}</p>
                            </td>
                            <td class="px-2 sm:px-4 py-3 text-center font-mono text-xs sm:text-sm text-on-surface font-medium">{{ $tp->total_qty }}</td>
                            <td class="px-2 sm:px-4 py-3 text-right font-mono text-xs text-on-surface-variant">Rp {{ number_format($tp->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-3 sm:px-5 py-8 text-center text-on-surface-variant text-xs sm:text-sm">Belum ada transaksi hari ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Cashier Performance --}}
    <div class="mb-6 sm:mb-8">
        <h3 class="text-sm font-semibold text-on-surface mb-3 sm:mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-lg">group</span> Performa Kasir Hari Ini
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
            @forelse($cashierPerformance ?? collect() as $kasir)
            <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-outline-variant/30 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl flex items-center justify-center shrink-0 font-bold text-lg text-on-surface-variant
                    {{ $kasir->today_total > 0 ? 'bg-secondary/10 text-secondary' : 'bg-surface-container-low' }}">
                    {{ strtoupper(substr($kasir->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-on-surface text-sm sm:text-base truncate">{{ $kasir->name }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-xs text-on-surface-variant font-mono">{{ $kasir->today_count }} trx</span>
                        <span class="text-xs text-on-surface-variant">•</span>
                        <span class="text-xs font-mono font-medium text-on-surface truncate">Rp {{ number_format($kasir->today_total, 0, ',', '.') }}</span>
                    </div>
                </div>
                @if($kasir->today_count > 0)
                <span class="material-symbols-outlined text-secondary text-lg shrink-0">check_circle</span>
                @else
                <span class="material-symbols-outlined text-on-surface-variant/30 text-lg shrink-0">radio_button_unchecked</span>
                @endif
            </div>
            @empty
            <div class="col-span-full text-center text-on-surface-variant text-xs sm:text-sm py-4">Belum ada data kasir.</div>
            @endforelse
        </div>
    </div>

    {{-- Low Stock Alert --}}
    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 flex items-center justify-between">
            <h3 class="font-semibold text-on-surface text-sm sm:text-base">Peringatan Stok Rendah</h3>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-secondary font-medium hover:underline">Kelola Produk</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[400px]">
                <thead>
                    <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                        <th class="text-left px-3 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Produk</th>
                        <th class="text-right px-3 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Stok</th>
                        <th class="text-right px-3 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($lowStockProducts ?? collect() as $p)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-3 sm:px-5 py-3">
                            <p class="font-medium text-on-surface text-xs sm:text-sm">{{ $p->name }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $p->category->name }}</p>
                        </td>
                        <td class="px-3 sm:px-5 py-3 text-right">
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-medium bg-error/10 text-error">{{ $p->stock }}</span>
                        </td>
                        <td class="px-3 sm:px-5 py-3 text-right font-mono text-on-surface-variant text-xs sm:text-sm">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-3 sm:px-5 py-8 text-center text-on-surface-variant text-xs sm:text-sm">Semua stok aman ✅</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Activity Feed --}}
    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 flex items-center justify-between">
            <h3 class="font-semibold text-on-surface text-sm sm:text-base flex items-center gap-2">
                <span class="material-symbols-outlined text-on-surface-variant text-lg">history</span> Aktivitas Terbaru
            </h3>
        </div>
        <div class="divide-y divide-outline-variant/10 max-h-[360px] overflow-y-auto">
            @forelse($recentActivities ?? collect() as $log)
            <div class="flex items-start gap-3 sm:gap-4 px-4 sm:px-6 py-3 hover:bg-surface-container-low/50 transition-colors">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5
                    {{ $log->action === 'created' ? 'bg-green-50' : '' }}
                    {{ $log->action === 'updated' ? 'bg-blue-50' : '' }}
                    {{ $log->action === 'deleted' ? 'bg-red-50' : '' }}
                    {{ $log->action === 'checkout' ? 'bg-amber-50' : '' }}">
                    <span class="material-symbols-outlined text-sm
                        {{ $log->action === 'created' ? 'text-green-600' : '' }}
                        {{ $log->action === 'updated' ? 'text-blue-600' : '' }}
                        {{ $log->action === 'deleted' ? 'text-red-500' : '' }}
                        {{ $log->action === 'checkout' ? 'text-amber-600' : '' }}">
                        {{ $log->action === 'created' ? 'add_circle' : '' }}
                        {{ $log->action === 'updated' ? 'edit' : '' }}
                        {{ $log->action === 'deleted' ? 'delete' : '' }}
                        {{ $log->action === 'checkout' ? 'shopping_cart_checkout' : '' }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                            {{ $log->action === 'created' ? 'bg-green-50 text-green-700' : '' }}
                            {{ $log->action === 'updated' ? 'bg-blue-50 text-blue-700' : '' }}
                            {{ $log->action === 'deleted' ? 'bg-red-50 text-red-600' : '' }}
                            {{ $log->action === 'checkout' ? 'bg-amber-50 text-amber-700' : '' }}">
                            {{ $log->action === 'checkout' ? 'Transaksi' : $log->action }}
                        </span>
                        <span class="text-[10px] text-on-surface-variant uppercase tracking-wider">{{ $log->model_type }}</span>
                    </div>
                    <p class="text-xs sm:text-sm text-on-surface mt-0.5">
                        <span class="font-medium">{{ $log->label }}</span>
                        @if($log->action === 'updated' && $log->changes)
                            <span class="text-on-surface-variant text-[11px]">
                                @php
                                    $dirty = $log->changes['new'] ?? [];
                                @endphp
                                @foreach(array_keys($dirty) as $i => $field)
                                    @if($i < 2)
                                        {{ ucfirst($field) }}:
                                        <span class="line-through text-red-400">{{ $log->changes['old'][$field] ?? '—' }}</span>
                                        →
                                        <span class="text-green-600">{{ $dirty[$field] }}</span>
                                        @if($i < min(1, count($dirty)-1)), @endif
                                    @endif
                                @endforeach
                                @if(count($dirty) > 2)
                                    <span class="text-on-surface-variant"> +{{ count($dirty) - 2 }} field lainnya</span>
                                @endif
                            </span>
                        @endif
                    </p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-[10px] sm:text-xs text-on-surface-variant">{{ $log->created_at->diffForHumans() }}</p>
                    <p class="text-[10px] text-on-surface-variant/60 mt-0.5">{{ $log->user?->name ?? 'System' }}</p>
                </div>
            </div>
            @empty
            <div class="px-4 sm:px-6 py-8 text-center text-on-surface-variant text-xs sm:text-sm">Belum ada aktivitas tercatat.</div>
            @endforelse
        </div>
    </div>
</div>
@stop

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
            <button class="flex-1 sm:flex-none px-4 sm:px-5 py-2.5 bg-white text-on-surface rounded-full text-xs sm:text-sm font-medium border border-outline-variant/50 hover:bg-surface-container-low transition-colors shadow-sm">
                Ekspor Data
            </button>
        </div>
    </div>

    {{-- Summary Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-outline-variant/30 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3 sm:mb-4">
                <div class="w-10 h-10 bg-secondary/10 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-secondary">today</span>
                </div>
                <span class="text-[10px] sm:text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Penjualan Hari Ini</span>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-on-surface">Rp {{ number_format($todayTotal, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-outline-variant/30 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3 sm:mb-4">
                <div class="w-10 h-10 bg-primary-container/20 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-primary">calendar_month</span>
                </div>
                <span class="text-[10px] sm:text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Penjualan Bulan Ini</span>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-on-surface">Rp {{ number_format($monthTotal, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-outline-variant/30 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3 sm:mb-4">
                <div class="w-10 h-10 bg-tertiary-container/20 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-tertiary-container">inventory_2</span>
                </div>
                <span class="text-[10px] sm:text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total Produk</span>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-on-surface">{{ $totalProducts }}</p>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-outline-variant/30 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3 sm:mb-4">
                <div class="w-10 h-10 bg-error/10 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-error">warning</span>
                </div>
                <span class="text-[10px] sm:text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Stok Rendah</span>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-on-surface">{{ $lowStock }} <span class="text-sm font-normal text-on-surface-variant">produk</span></p>
        </div>
    </div>

    {{-- Quick Stats Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        {{-- Recent Transactions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 flex items-center justify-between">
                <h3 class="font-semibold text-on-surface">Transaksi Terbaru</h3>
                <a href="#" class="text-xs text-secondary font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[500px]">
                    <thead>
                        <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                            <th class="text-left px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Invoice</th>
                            <th class="text-left px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kasir</th>
                            <th class="text-right px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total</th>
                            <th class="text-center px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Metode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-3 sm:px-6 py-3 font-mono text-xs text-on-surface">{{ $trx->invoice_number }}</td>
                            <td class="px-3 sm:px-6 py-3 text-on-surface-variant text-xs sm:text-sm">{{ $trx->user->name }}</td>
                            <td class="px-3 sm:px-6 py-3 text-right font-mono text-xs sm:text-sm text-on-surface">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="px-3 sm:px-6 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-[10px] font-medium uppercase {{ $trx->payment_method === 'cash' ? 'bg-surface-container text-on-surface-variant' : 'bg-primary-container/20 text-on-primary-container' }}">
                                    {{ $trx->payment_method }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-3 sm:px-6 py-8 text-center text-on-surface-variant text-sm">Belum ada transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Low Stock Alert --}}
        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 flex items-center justify-between">
                <h3 class="font-semibold text-on-surface">Peringatan Stok Rendah</h3>
                <a href="{{ route('admin.products.index') }}" class="text-xs text-secondary font-medium hover:underline">Kelola</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[400px]">
                    <thead>
                        <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                            <th class="text-left px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Produk</th>
                            <th class="text-right px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Stok</th>
                            <th class="text-right px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse(Product::with('category')->where('stock', '<=', 5)->where('is_active', true)->take(5)->get() as $p)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-3 sm:px-6 py-3">
                                <p class="font-medium text-on-surface text-xs sm:text-sm">{{ $p->name }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $p->category->name }}</p>
                            </td>
                            <td class="px-3 sm:px-6 py-3 text-right">
                                <span class="px-2 py-0.5 rounded-full text-xs font-mono font-medium bg-error/10 text-error">{{ $p->stock }}</span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 text-right font-mono text-on-surface-variant text-xs sm:text-sm">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-3 sm:px-6 py-8 text-center text-on-surface-variant text-sm">Semua stok aman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

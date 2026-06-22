@extends('layouts.app')
@section('title', 'Dashboard Kasir')

@section('content')
<div class="max-w-[1400px] mx-auto">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-6 sm:mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight mb-1 sm:mb-2">Dashboard Kasir</h2>
            <p class="text-on-surface-variant text-xs sm:text-sm flex items-center gap-2">
                <span>{{ now()->isoFormat('dddd, D MMMM Y') }}</span> <span class="hidden sm:inline">• Terminal #01</span>
            </p>
        </div>
        <a href="{{ route('kasir.transactions.create') }}" class="w-full sm:w-auto px-4 sm:px-5 py-2.5 bg-secondary text-white rounded-full text-xs sm:text-sm font-medium flex items-center justify-center gap-2 hover:bg-secondary/90 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[16px] sm:text-[18px]">add</span> Transaksi Baru
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        {{-- 1. Transaction Count --}}
        <div class="bg-white rounded-2xl p-3 sm:p-5 shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-secondary/10 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-secondary text-lg sm:text-xl">receipt_long</span>
                </div>
                <span class="text-[9px] sm:text-[10px] font-semibold text-on-surface-variant uppercase tracking-wider leading-tight">Transaksi<br>Hari Ini</span>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-on-surface">{{ $todayCount }}</p>
        </div>
        {{-- 2. Today Revenue --}}
        <div class="bg-white rounded-2xl p-3 sm:p-5 shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-primary-container/20 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-primary text-lg sm:text-xl">payments</span>
                </div>
                <span class="text-[9px] sm:text-[10px] font-semibold text-on-surface-variant uppercase tracking-wider leading-tight">Omzet<br>Hari Ini</span>
            </div>
            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-on-surface truncate">Rp {{ number_format($todayTotal, 0, ',', '.') }}</p>
        </div>
        {{-- 3. Average Transaction --}}
        <div class="bg-white rounded-2xl p-3 sm:p-5 shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-tertiary-container/20 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-tertiary-container text-lg sm:text-xl">trending_up</span>
                </div>
                <span class="text-[9px] sm:text-[10px] font-semibold text-on-surface-variant uppercase tracking-wider leading-tight">Rata-rata<br>Transaksi</span>
            </div>
            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-on-surface truncate">Rp {{ number_format($todayAvg, 0, ',', '.') }}</p>
        </div>
        {{-- 4. Month Revenue --}}
        <div class="bg-white rounded-2xl p-3 sm:p-5 shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-error/10 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-error text-lg sm:text-xl">calendar_month</span>
                </div>
                <span class="text-[9px] sm:text-[10px] font-semibold text-on-surface-variant uppercase tracking-wider leading-tight">Omzet<br>Bulan Ini</span>
            </div>
            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-on-surface truncate">Rp {{ number_format($monthTotal, 0, ',', '.') }}</p>
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        {{-- Recent Transactions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20 flex items-center justify-between">
                <h3 class="font-semibold text-on-surface text-sm sm:text-base">Transaksi Terbaru</h3>
                <a href="{{ route('kasir.transactions.history') }}" class="text-xs text-secondary font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[400px]">
                    <thead>
                        <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                            <th class="text-left px-3 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Invoice</th>
                            <th class="text-right px-3 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total</th>
                            <th class="text-center px-3 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Metode</th>
                            <th class="text-right px-3 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($recentTransactions ?? collect() as $trx)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-3 sm:px-5 py-3 font-mono text-xs text-on-surface">{{ $trx->invoice_number }}</td>
                            <td class="px-3 sm:px-5 py-3 text-right font-mono text-xs sm:text-sm text-on-surface">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="px-3 sm:px-5 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-[10px] font-medium uppercase {{ $trx->payment_method === 'cash' ? 'bg-surface-container text-on-surface-variant' : 'bg-primary-container/20 text-on-primary-container' }}">
                                    {{ $trx->payment_method }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-5 py-3 text-right text-on-surface-variant text-xs">{{ $trx->created_at->isoFormat('HH:mm') }}</td>
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
</div>
@stop

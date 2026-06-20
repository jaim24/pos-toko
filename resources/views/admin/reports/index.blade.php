@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<div class="max-w-[1400px] mx-auto">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 mb-6 sm:mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight mb-1 sm:mb-2">Laporan Penjualan</h2>
            <p class="text-on-surface-variant text-xs sm:text-sm">Pantau performa penjualan toko Anda.</p>
        </div>
        <button class="w-full sm:w-auto px-4 sm:px-5 py-2.5 bg-white text-on-surface rounded-full text-xs sm:text-sm font-medium border border-outline-variant/50 hover:bg-surface-container-low transition-colors shadow-sm flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[16px] sm:text-[18px]">download</span> Ekspor Laporan
        </button>
    </div>

    {{-- Month Filter --}}
    <div class="mb-4 sm:mb-6">
        <form method="GET" class="flex items-center gap-3">
            <label class="text-xs font-semibold text-on-surface-variant">Filter Bulan:</label>
            <input type="month" name="month" value="{{ $month }}"
                   onchange="this.form.submit()"
                   class="border border-outline-variant rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all">
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-outline-variant/30">
            <p class="text-[10px] sm:text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Transaksi Hari Ini</p>
            <p class="text-xl sm:text-2xl font-bold text-on-surface">{{ $todayCount }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-outline-variant/30">
            <p class="text-[10px] sm:text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Omzet Hari Ini</p>
            <p class="text-xl sm:text-2xl font-bold text-on-surface">Rp {{ number_format($todayTotal, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-outline-variant/30">
            <p class="text-[10px] sm:text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Omzet Bulan Ini</p>
            <p class="text-xl sm:text-2xl font-bold text-on-surface">Rp {{ number_format($monthTotal, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-outline-variant/30">
            <p class="text-[10px] sm:text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Total Transaksi</p>
            <p class="text-xl sm:text-2xl font-bold text-on-surface">{{ $totalTransactions }}</p>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-outline-variant/20">
            <h3 class="font-semibold text-on-surface">Semua Transaksi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[800px]">
                <thead>
                    <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                        <th class="text-left px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Invoice</th>
                        <th class="text-left px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                        <th class="text-left px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kasir</th>
                        <th class="text-right px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total</th>
                        <th class="text-right px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Bayar</th>
                        <th class="text-right px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kembali</th>
                        <th class="text-center px-3 sm:px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-3 sm:px-6 py-3 font-mono text-xs text-on-surface">{{ $trx->invoice_number }}</td>
                        <td class="px-3 sm:px-6 py-3 text-on-surface-variant text-xs">{{ $trx->created_at->isoFormat('D MMM Y HH:mm') }}</td>
                        <td class="px-3 sm:px-6 py-3 text-on-surface-variant text-xs sm:text-sm">{{ $trx->user->name }}</td>
                        <td class="px-3 sm:px-6 py-3 text-right font-mono text-xs sm:text-sm text-on-surface">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td class="px-3 sm:px-6 py-3 text-right font-mono text-xs sm:text-sm text-on-surface-variant">Rp {{ number_format($trx->paid_amount, 0, ',', '.') }}</td>
                        <td class="px-3 sm:px-6 py-3 text-right font-mono text-xs sm:text-sm text-secondary">Rp {{ number_format($trx->change_amount, 0, ',', '.') }}</td>
                        <td class="px-3 sm:px-6 py-3 text-center">
                            <span class="px-2 py-1 rounded-full text-[10px] font-medium uppercase {{ $trx->payment_method === 'cash' ? 'bg-surface-container text-on-surface-variant' : 'bg-primary-container/20 text-on-primary-container' }}">
                                {{ $trx->payment_method }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-3 sm:px-6 py-12 text-center text-on-surface-variant">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions instanceof \Illuminate\Contracts\Pagination\Paginator)
        <div class="px-4 sm:px-6 py-3 border-t border-outline-variant/20">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@stop

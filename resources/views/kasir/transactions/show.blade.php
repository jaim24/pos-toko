@extends('layouts.app')
@section('title', 'Struk')

@section('content')

<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg sm:shadow-2xl border border-outline-variant/20 overflow-hidden">
        {{-- Header --}}
        <div class="bg-primary p-6 sm:p-8 text-center text-white">
            <div class="flex items-center justify-center gap-3 mb-3">
                <span class="material-symbols-outlined text-secondary text-2xl sm:text-3xl" style="font-variation-settings: 'FILL' 1;">storefront</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold tracking-tight">Stitch POS</h1>
            <p class="text-on-primary-container text-xs sm:text-sm mt-1">Struk Transaksi</p>
        </div>

        {{-- Invoice Info --}}
        <div class="p-4 sm:p-6 border-b border-outline-variant/20">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 sm:gap-0 mb-3">
                <div>
                    <p class="text-xs text-on-surface-variant uppercase tracking-wider font-semibold mb-1">Invoice</p>
                    <p class="font-mono font-bold text-on-surface text-base sm:text-lg">{{ $transaction->invoice_number }}</p>
                </div>
                <div class="sm:text-right">
                    <p class="text-xs text-on-surface-variant uppercase tracking-wider font-semibold mb-1">Tanggal</p>
                    <p class="font-medium text-on-surface text-xs sm:text-sm">{{ $transaction->created_at->isoFormat('D MMM Y HH:mm') }}</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:justify-between gap-2 sm:gap-0">
                <div>
                    <p class="text-xs text-on-surface-variant uppercase tracking-wider font-semibold mb-1">Kasir</p>
                    <p class="font-medium text-on-surface text-xs sm:text-sm">{{ $transaction->user->name }}</p>
                </div>
                <div class="sm:text-right">
                    <p class="text-xs text-on-surface-variant uppercase tracking-wider font-semibold mb-1">Metode</p>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ $transaction->payment_method === 'cash' ? 'bg-surface-container text-on-surface-variant' : 'bg-primary-container/20 text-on-primary-container' }}">
                        {{ $transaction->payment_method }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="p-4 sm:p-6 border-b border-outline-variant/20">
            <p class="text-xs text-on-surface-variant uppercase tracking-wider font-semibold mb-3 sm:mb-4">Item</p>
            <div class="space-y-2 sm:space-y-3">
                @foreach($transaction->items as $item)
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-on-surface text-xs sm:text-sm truncate">{{ $item->product_name }}</p>
                        <p class="text-xs text-on-surface-variant font-mono">
                            {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}
                        </p>
                    </div>
                    <p class="font-mono font-medium text-on-surface text-xs sm:text-sm ml-4 shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Totals --}}
        <div class="p-4 sm:p-6 bg-surface-container-low space-y-2 sm:space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-on-surface-variant">Total</span>
                <span class="font-mono font-bold text-on-surface text-base sm:text-lg">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-on-surface-variant">Bayar</span>
                <span class="font-mono font-medium text-on-surface text-sm">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm pt-2 sm:pt-3 border-t border-outline-variant/30">
                <span class="text-secondary font-semibold">Kembalian</span>
                <span class="font-mono font-bold text-secondary text-base sm:text-lg">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-4 sm:p-6 text-center border-t border-outline-variant/20">
            <p class="text-xs text-on-surface-variant mb-1">Terima kasih telah berbelanja</p>
            <p class="text-[10px] text-on-surface-variant opacity-60">Stitch POS — Retail Management System</p>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-6">
        <a href="{{ route('kasir.transactions.create') }}" class="w-full sm:w-auto px-5 py-2.5 bg-secondary text-white rounded-full text-sm font-medium flex items-center justify-center gap-2 hover:bg-secondary/90 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span> Transaksi Baru
        </a>
        <a href="{{ route('kasir.transactions.history') }}" class="w-full sm:w-auto px-5 py-2.5 bg-white text-on-surface rounded-full text-sm font-medium border border-outline-variant/50 hover:bg-surface-container-low transition-colors flex items-center justify-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">receipt_long</span> Lihat Riwayat
        </a>
        <button onclick="window.print()" class="w-full sm:w-auto px-5 py-2.5 bg-white border border-outline-variant text-on-surface rounded-full text-sm font-medium hover:bg-surface-container-low transition-colors shadow-sm flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">print</span> Cetak
        </button>
    </div>
</div>
@stop

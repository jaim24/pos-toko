@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="max-w-[1400px] mx-auto">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 mb-6 sm:mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight mb-1 sm:mb-2">Riwayat Transaksi</h2>
            <p class="text-on-surface-variant text-xs sm:text-sm">Semua transaksi yang telah diproses.</p>
        </div>
        <a href="{{ route('kasir.transactions.create') }}" class="w-full sm:w-auto px-4 sm:px-5 py-2.5 bg-secondary text-white rounded-full text-xs sm:text-sm font-medium flex items-center justify-center gap-2 hover:bg-secondary/90 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[16px] sm:text-[18px]">add</span> Transaksi Baru
        </a>
    </div>

    @php use App\Models\Transaction; $transactions = Transaction::with('user')->latest()->paginate(20); @endphp

    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[800px]">
                <thead>
                    <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                        <th class="text-left px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Invoice</th>
                        <th class="text-left px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                        <th class="text-right px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total</th>
                        <th class="text-right px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Bayar</th>
                        <th class="text-right px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kembali</th>
                        <th class="text-center px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Metode</th>
                        <th class="text-center px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Struk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-3 sm:px-6 py-3 sm:py-4 font-mono text-xs text-on-surface">{{ $trx->invoice_number }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-on-surface-variant text-xs">{{ $trx->created_at->isoFormat('D MMM Y HH:mm') }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-right font-mono text-xs sm:text-sm text-on-surface">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-right font-mono text-xs sm:text-sm text-on-surface-variant">Rp {{ number_format($trx->paid_amount, 0, ',', '.') }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-right font-mono text-xs sm:text-sm text-secondary">Rp {{ number_format($trx->change_amount, 0, ',', '.') }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                            <span class="px-2 py-1 rounded-full text-[10px] font-medium uppercase {{ $trx->payment_method === 'cash' ? 'bg-surface-container text-on-surface-variant' : 'bg-primary-container/20 text-on-primary-container' }}">
                                {{ $trx->payment_method }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                            <a href="{{ route('kasir.transactions.show', $trx->id) }}" class="p-1.5 sm:p-2 text-on-surface-variant hover:text-secondary hover:bg-surface-container-low rounded-lg transition-colors inline-flex">
                                <span class="material-symbols-outlined text-base sm:text-lg">receipt</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-3 sm:px-6 py-8 sm:py-12 text-center text-on-surface-variant text-xs sm:text-sm">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-outline-variant/20">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@stop

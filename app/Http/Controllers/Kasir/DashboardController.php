<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $todayTotal = Transaction::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('total_amount');
        $todayCount = Transaction::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->count();
        $monthTotal = Transaction::where('user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Average transaction value today
        $todayAvg = $todayCount > 0 ? round($todayTotal / $todayCount) : 0;

        // Payment method breakdown today
        $todayCashCount = Transaction::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->where('payment_method', 'cash')
            ->count();
        $todayQrisCount = Transaction::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->where('payment_method', 'qris')
            ->count();

        // Top products today (by quantity)
        $topProducts = TransactionItem::select(
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->whereHas('transaction', function ($q) use ($userId) {
                $q->where('user_id', $userId)->whereDate('created_at', today());
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $recentTransactions = Transaction::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact(
            'todayTotal', 'todayCount', 'monthTotal', 'todayAvg',
            'todayCashCount', 'todayQrisCount',
            'topProducts', 'recentTransactions'
        ));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Summary metrics ---
        $todayCount  = Transaction::whereDate('created_at', today())->count();
        $todayTotal  = Transaction::whereDate('created_at', today())->sum('total_amount');
        $yesterdayTotal = Transaction::whereDate('created_at', today()->subDay())->sum('total_amount');
        $monthTotal  = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        $lastMonthTotal = Transaction::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');

        $todayAvg = $todayCount > 0 ? round($todayTotal / $todayCount) : 0;

        $totalProducts = Product::count();
        $lowStock      = Product::where('stock', '<=', 5)->where('is_active', true)->count();

        // --- Payment breakdown today ---
        $todayCashCount = Transaction::whereDate('created_at', today())
            ->where('payment_method', 'cash')->count();
        $todayQrisCount = Transaction::whereDate('created_at', today())
            ->where('payment_method', 'qris')->count();

        // --- Top products today ---
        $topProducts = TransactionItem::select(
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->whereHas('transaction', fn($q) => $q->whereDate('created_at', today()))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // --- Cashier performance today ---
        $cashierPerformance = User::where('role', 'kasir')
            ->withCount(['transactions as today_count' => fn($q) => $q->whereDate('created_at', today())])
            ->withSum(['transactions as today_total' => fn($q) => $q->whereDate('created_at', today())], 'total_amount')
            ->orderByDesc('today_total')
            ->get();

        // --- Recent transactions ---
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

        // --- Low stock products ---
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 5)
            ->where('is_active', true)
            ->take(5)
            ->get();

        // --- Trend percentages ---
        $todayVsYesterday = $yesterdayTotal > 0
            ? round((($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100)
            : null;
        $monthVsLastMonth = $lastMonthTotal > 0
            ? round((($monthTotal - $lastMonthTotal) / $lastMonthTotal) * 100)
            : null;

        // --- Activity feed ---
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'todayCount', 'todayTotal', 'yesterdayTotal', 'todayAvg',
            'monthTotal', 'lastMonthTotal',
            'totalProducts', 'lowStock',
            'todayCashCount', 'todayQrisCount',
            'topProducts', 'cashierPerformance',
            'recentTransactions', 'lowStockProducts',
            'todayVsYesterday', 'monthVsLastMonth',
            'recentActivities'
        ));
    }
}

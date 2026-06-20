<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayTotal = Transaction::whereDate('created_at', today())->sum('total_amount');
        $monthTotal = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        $totalProducts = Product::count();
        $lowStock = Product::where('stock', '<=', 5)->where('is_active', true)->count();
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todayTotal', 'monthTotal', 'totalProducts', 'lowStock', 'recentTransactions'
        ));
    }
}

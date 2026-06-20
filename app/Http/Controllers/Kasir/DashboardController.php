<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayTotal = Transaction::whereDate('created_at', today())->sum('total_amount');
        $todayCount = Transaction::whereDate('created_at', today())->count();
        $monthTotal = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $recentTransactions = Transaction::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact('todayTotal', 'todayCount', 'monthTotal', 'recentTransactions'));
    }
}

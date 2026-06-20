<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        $todayTotal = Transaction::whereDate('created_at', today())->sum('total_amount');
        $monthTotal = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        $totalTransactions = Transaction::count();
        $todayCount = Transaction::whereDate('created_at', today())->count();

        $transactions = Transaction::with(['user', 'items'])
            ->when($month, fn($q) => $q->whereMonth('created_at', substr($month, 5, 2))
                ->whereYear('created_at', substr($month, 0, 4)))
            ->latest()
            ->paginate(25);

        return view('admin.reports.index', compact(
            'transactions', 'todayTotal', 'monthTotal', 'totalTransactions', 'todayCount', 'month'
        ));
    }
}

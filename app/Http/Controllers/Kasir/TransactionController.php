<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function create()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get();
        $categories = \App\Models\Category::all();
        return view('kasir.transactions.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items_json'     => 'required|json',
            'paid_amount'    => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,qris',
        ]);

        $items = json_decode($request->items_json, true);

        if (empty($items)) {
            $msg = 'Keranjang kosong.';
            if ($request->expectsJson()) return response()->json(['success' => false, 'message' => $msg], 422);
            return back()->withErrors(['items_json' => $msg]);
        }

        DB::beginTransaction();
        try {
            $total = 0;
            $lines = [];

            foreach ($items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak cukup.");
                }

                $subtotal = $product->price * $item['qty'];
                $total += $subtotal;

                $lines[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $product->price,
                    'quantity'     => $item['qty'],
                    'subtotal'     => $subtotal,
                ];

                $product->decrement('stock', $item['qty']);
            }

            // QRIS: treat as exact payment
            $paid   = $request->payment_method === 'qris' ? $total : round((float) $request->paid_amount, 2);
            $change = max(0, $paid - $total);

            if ($request->payment_method === 'cash' && $paid < $total) {
                throw new \Exception('Uang bayar kurang dari total.');
            }

            $transaction = Transaction::create([
                'user_id'        => auth()->id(),
                'invoice_number' => Transaction::generateInvoice(),
                'total_amount'   => $total,
                'paid_amount'    => $paid,
                'change_amount'  => $change,
                'payment_method' => $request->payment_method,
                'notes'          => $request->notes,
            ]);

            $transaction->items()->createMany($lines);

            DB::commit();

            $data = [
                'success'     => true,
                'message'     => 'Transaksi berhasil!',
                'transaction' => [
                    'id'             => $transaction->id,
                    'invoice_number' => $transaction->invoice_number,
                    'date'           => $transaction->created_at->isoFormat('D MMM Y HH:mm'),
                    'kasir'          => $transaction->user->name,
                    'payment_method' => $transaction->payment_method,
                    'total_fmt'      => number_format($transaction->total_amount, 0, ',', '.'),
                    'paid_fmt'       => number_format($transaction->paid_amount, 0, ',', '.'),
                    'change_fmt'     => number_format($transaction->change_amount, 0, ',', '.'),
                    'items'          => $transaction->items->map(fn($i) => [
                        'product_name'  => $i->product_name,
                        'quantity'      => $i->quantity,
                        'price_fmt'     => number_format($i->price, 0, ',', '.'),
                        'subtotal_fmt'  => number_format($i->subtotal, 0, ',', '.'),
                    ])->toArray(),
                ],
            ];

            if ($request->expectsJson()) return response()->json($data);

            return redirect()->route('kasir.transactions.show', $transaction->id)
                ->with('success', 'Transaksi berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            if ($request->expectsJson()) return response()->json(['success' => false, 'message' => $msg], 422);
            return back()->withErrors(['error' => $msg]);
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with(['items', 'user'])
            ->when(auth()->user()->isKasir(), fn($q) => $q->where('user_id', auth()->id()))
            ->findOrFail($id);
        return view('kasir.transactions.show', compact('transaction'));
    }

    public function history()
    {
        return view('kasir.transactions.history');
    }
}

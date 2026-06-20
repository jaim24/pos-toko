<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'invoice_number', 'total_amount',
        'paid_amount', 'change_amount', 'payment_method', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public static function generateInvoice(): string
    {
        return DB::transaction(function () {
            $today = now()->format('Ymd');
            $last = static::whereDate('created_at', today())
                ->lockForUpdate()
                ->latest('id')->first();
            $seq = $last ? (int) substr($last->invoice_number, -3) + 1 : 1;
            return 'INV-' . $today . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
        });
    }
}

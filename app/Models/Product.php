<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'category_id', 'name', 'slug', 'barcode', 'description',
        'price', 'stock', 'image', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($p) {
            $p->slug ??= Str::slug($p->name);
            if (empty($p->barcode)) {
                $p->barcode = static::generateUniqueBarcode();
            }
        });
        static::updating(fn($p) => $p->slug = Str::slug($p->name));
    }

    /**
     * Generate a unique EAN-13 compatible barcode.
     */
    public static function generateUniqueBarcode(): string
    {
        do {
            // 12 random digits + 1 check digit = EAN-13
            $digits = '';
            for ($i = 0; $i < 12; $i++) {
                $digits .= random_int(0, 9);
            }
            $barcode = $digits . static::ean13CheckDigit($digits);
        } while (static::where('barcode', $barcode)->exists());

        return $barcode;
    }

    /**
     * Calculate EAN-13 check digit.
     */
    private static function ean13CheckDigit(string $digits12): int
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += ($i % 2 === 0) ? (int) $digits12[$i] : (int) $digits12[$i] * 3;
        }
        $mod = $sum % 10;
        return $mod === 0 ? 0 : 10 - $mod;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function isLowStock(int $threshold = 5): bool
    {
        return $this->stock <= $threshold;
    }
}

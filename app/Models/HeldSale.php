<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeldSale extends Model
{
    protected $fillable = [
        'reference',
        'customer_id',
        'user_id',
        'cart',
        'subtotal',
        'tax',
        'total',
    ];

    protected static function booted()
    {
        static::creating(function ($heldSale) {

            $heldSale->reference = 'HOLD-' . str_pad(
                (static::max('id') ?? 0) + 1,
                5,
                '0',
                STR_PAD_LEFT
            );

        });
    }

    protected $casts = [
        'cart' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
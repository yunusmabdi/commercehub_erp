<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'sale_date',
        'status',

        'payement_method',

        'subtotal',
        'discount',
        'tax',
        'total_amount',

        'amount_paid',
        'change_amount',
        
        'notes',
    ];

    protected static function booted(): void
    {
        static::creating(function ($sale) {

            if (! $sale->invoice_number) {

                $lastSale = static::latest('id')->first();

                $nextNumber = $lastSale
                    ? $lastSale->id + 1
                    : 1;

                $sale->invoice_number = 'INV-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }


    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
    protected function casts(): array{
        return[
            'sale_date' => 'datetime',
        ];
    }
}
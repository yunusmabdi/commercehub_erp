<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    protected $fillable = [
        'purchase_number',
        'supplier_id',
        'purchase_date',
        'status',
        'total_amount',
        'note',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function calculateTotal(): void
    {
        $this->update([
            'total_amount'=> $this->items()->sum('line_total'),
        ]);
    }
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}

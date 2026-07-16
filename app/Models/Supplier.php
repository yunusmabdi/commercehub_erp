<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Supplier extends Model
{
    protected $fillable = [
        'supplier_code',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'alternative_phone',
        'address',
        'city',
        'country',
        'tax_number',
        'is_active'
    ];
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}

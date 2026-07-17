<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_code',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::creating(function ($customer) {

            if (! $customer->customer_code) {

                $lastCustomer = static::latest('id')->first();

                $nextNumber = $lastCustomer
                    ? $lastCustomer->id + 1
                    : 1;

                $customer->customer_code = 'CUS-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }

}

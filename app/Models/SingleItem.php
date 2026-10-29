<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SingleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'country',
        'price',
        'currency',
        'initial_duration_days',
        'extend_price',
        'extend_duration_days',
        'stripe_product_id',
        'stripe_price_id',
        'is_active',
    ];

    protected $casts = [
        'price'        => 'float',
        'extend_price' => 'float',
        'is_active'    => 'boolean',
    ];

    public function scopeForCountry($query, string $countryCode)
    {
        return $query->where(function ($q) use ($countryCode) {
            $q->where('country', 'all')
              ->orWhere('country', strtolower($countryCode));
        });
    }
}

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
        'initial_duration_days',
        'extend_price',
        'extend_duration_days',
        'stripe_product_id',
        'stripe_price_id',
        'is_active',
    ];
}

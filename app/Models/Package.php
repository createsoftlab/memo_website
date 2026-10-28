<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'billing_type',
        'country',
        'business_profile',
        'duration_value',
        'duration_unit',
        'trial_days',
        'max_photos',
        'max_videos',
        'max_comments',
        'candles_limit',
        'flowers_limit',
        'stripe_product_id',
        'stripe_price_id',
        'is_active',
    ];
}

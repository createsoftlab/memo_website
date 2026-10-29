<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'business_profile',
        'price',
        'currency',
        'billing_type',
        'duration_value',
        'duration_unit',
        'trial_days',
        'events_limit',
        'max_photos',
        'max_videos',
        'max_audios',
        'candles_limit',
        'flowers_limit',
        'max_comments',
        'has_ai_features',
        'has_donations',
        'guest_story_limit',
        'guest_photo_limit',
        'guest_video_limit',
        'stripe_product_id',
        'stripe_price_id',
        'is_active',
    ];

    protected $casts = [
        'price'             => 'float',
        'has_ai_features'   => 'boolean',
        'has_donations'     => 'boolean',
        'is_active'         => 'boolean',
        'events_limit'      => 'integer',
        'max_photos'        => 'integer',
        'max_videos'        => 'integer',
        'max_audios'        => 'integer',
        'guest_story_limit' => 'integer',
        'guest_photo_limit' => 'integer',
        'guest_video_limit' => 'integer',
    ];

    // Scope for Geo-filtering
    public function scopeForCountry($query, string $countryCode)
    {
        return $query->where(function ($q) use ($countryCode) {
            $q->where('country', 'all')
              ->orWhere('country', strtolower($countryCode));
        });
    }
}

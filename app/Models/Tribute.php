<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'memory_id',
        'user_id',
        'package_id',
        'tribute_type',
        'flower_count',
        'candle_count',
        'unit_price',
        'total_amount',
        'message',
        'expire_date',
        'status',
        'stripe_session_id',
    ];

    protected $casts = [
        'expire_date'  => 'datetime',
        'unit_price'   => 'float',
        'total_amount' => 'float',
    ];

    // මෙම relationship එක Model එකට එකතු කරන්න
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }

    public function singleItem()
    {
        return $this->belongsTo(SingleItem::class, 'package_id');
    }
}

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
        'single_item_id', // හෝ package_id
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

    // Single Item එක සමඟ සම්බන්ධය
    public function singleItem()
    {
        return $this->belongsTo(SingleItem::class, 'single_item_id');
    }

    // අදාළ Memory එක සමඟ සම්බන්ධය
    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }
}

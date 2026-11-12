<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'memory_id',
        'event_id', // අලුතින් එක් කළා
        'user_id',
        'donation_for',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'currency',
        'referred_by',
        'visibility',
        'status',
        'stripe_session_id',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }

    // අදාළ Event එක සමඟ සම්බන්ධය
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


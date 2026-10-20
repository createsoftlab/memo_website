<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'event_type',
        'status',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'map_link',
        'live_stream_url',
        'description',
        'banner_image',
        'allow_donation',
        'is_private',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'allow_donation' => 'boolean',
            'is_private' => 'boolean',
        ];
    }

    /**
     * Accessor for event_date (alias of start_date).
     */
    public function getEventDateAttribute()
    {
        return $this->start_date;
    }

    /**
     * Get the user that owns the event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

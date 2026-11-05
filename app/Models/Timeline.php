<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timeline extends Model
{
   protected $fillable = [
        'memory_id',
        'profile_id',
        'title',
        'event_date',
        'location',
        'description',
        'icon',
        'image_path',
        'visibility',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // Timeline belongs to a Memory
    public function memory(): BelongsTo
    {
        return $this->belongsTo(Memory::class);
    }

    // Timeline belongs to a Profile
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}

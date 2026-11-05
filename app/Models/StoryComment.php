<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryComment extends Model
{
    protected $fillable = [
        'profile_id',
        'title',
        'contributor_name',
        'content',
        'relationship',
        'status',
    ];

    // Each story comment belongs to a memorial profile
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}

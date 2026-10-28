<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
     protected $fillable = [
        'profile_id',
        'story_type',
        'title',
        'content',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}

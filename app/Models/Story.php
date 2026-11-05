<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
     protected $fillable = [
        'profile_id',
        'user_id',
        'story_type',
        'title',
        'content',
        'status',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

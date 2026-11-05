<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostStory extends Model
{
    protected $fillable = [
        'profile_id',
        'from_whom',
        'title',
        'content',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}

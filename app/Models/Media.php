<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'profile_id',
        'type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}

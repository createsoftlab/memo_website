<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memory extends Model
{
    protected $fillable = [
        'user_id',
        'memorial_type',
        'web_address',
        'story',
        'status',
        'about',
        'tribute_quote',
        'funeral_location_name',
        'funeral_location_address',
        'family_contact_no',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }
}

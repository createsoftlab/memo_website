<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memory extends Model
{
    protected $fillable = [
        'user_id',
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

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}

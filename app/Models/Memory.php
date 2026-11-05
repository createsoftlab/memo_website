<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Memory extends Model
{
    protected $fillable = [
        'user_id',
        'story',
        'status',
        'about',
        'tribute_quote',
        'broadcast_link',
        'funeral_location_name',
        'funeral_location_address',
        'family_contact_no',
        'approval'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function timelines(): HasMany
{
    return $this->hasMany(Timeline::class);
}
}

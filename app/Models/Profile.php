<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [

        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'memorial_status',
        'passed_away_reason',
        'date_of_birth',
        'birth_location',
        'date_of_death',
        'death_location',
        'death_reason',
        // Address details
        'country',
        'city',
        'living_address',
        'zip_code',

        // Memorial content
        'main_tribute_quote',
        'about',

        // Funeral details
        'funeral_location_name',
        'funeral_address',
        'family_contact_number',
        'live_broadcast_link',

        // Privacy
        'privacy',
        'access_username',
        'access_password',

        // Publication status
        'status',
        'slug',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }

    public function stories()
{
    return $this->hasMany(Story::class);
}

public function photos()
{
    return $this->hasMany(Media::class)
                ->where('type', 'image');
}

// Videos only
public function videos()
{
    return $this->hasMany(Media::class)
                ->where('type', 'video');
}

// Audio files only
public function audios()
{
    return $this->hasMany(Media::class)
                ->where('type', 'audio');
}

}

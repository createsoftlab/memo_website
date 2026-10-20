<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            return;
        }

        Event::firstOrCreate(
            ['title' => 'Annual Memorial Alms Giving'],
            [
                'user_id' => $user->id,
                'event_type' => 'upcoming',
                'status' => 'approved',
                'start_date' => '2026-10-25',
                'end_date' => '2026-10-26',
                'start_time' => '10:00',
                'end_time' => '13:00',
                'location' => 'Memorial Hall, Colombo',
                'description' => 'Annual remembrance gathering and alms giving ceremony with family and friends.',
                'allow_donation' => true,
                'is_private' => false,
            ]
        );
    }
}

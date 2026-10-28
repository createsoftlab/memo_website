<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MemoryUserController extends Controller
{
    public function profileSettings(): View
    {
        return view('memory_user.profile_settings');
    }
}

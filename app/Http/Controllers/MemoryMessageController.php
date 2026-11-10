<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemoryMessage;
use Illuminate\Support\Facades\Auth;

class MemoryMessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'memory_id' => 'required|exists:memories,id',
            'message'   => 'required|string|max:3000',
        ]);

        MemoryMessage::create([
            'memory_id' => $request->memory_id,
            'user_id'   => Auth::id(), // Login වී ඇත්නම් user_id එක වැටේ, නැත්නම් null වේ
            'message'   => $request->message,
            'status'    => 'active',
        ]);

        return redirect()->back()->with('success', 'Your memory has been published successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tribute;
use App\Models\MemoryMessage;
use Illuminate\Support\Facades\Auth;

class TributeModerationController extends Controller
{
    public function disable($category, $id)
    {
        if ($category === 'message') {
            $item = MemoryMessage::with('memory.profile')->findOrFail($id);
        } else {
            $item = Tribute::with('memory.profile')->findOrFail($id);
        }

        // Login වී සිටින User මෙම Memory එකේ හිමිකරුදැයි තහවුරු කිරීම
        $memory = $item->memory;
        $ownerId = $memory->user_id ?? $memory->profile?->user_id;

        if ($ownerId && (int) $ownerId !== (int) Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Database එකෙන් delete නොකර status එක 'disabled' කිරීම
        $item->status = 'disabled';
        $item->save();

        return redirect()->back()->with('success', 'Tribute has been deleted successfully.');
    }
}

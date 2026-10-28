<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display a listing of all events across the system.
     */
    public function allEvents(Request $request): View
    {
        $query = Event::with('user');

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by approval status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by privacy
        if ($request->filled('privacy')) {
            if ($request->input('privacy') === 'private') {
                $query->where('is_private', true);
            } elseif ($request->input('privacy') === 'public') {
                $query->where('is_private', false);
            }
        }

        $events = $query->latest()->paginate(12)->withQueryString();

        return view('admin.all_events', compact('events'));
    }

    /**
     * Display a listing of events created by the logged-in user.
     */
    public function myEvents(Request $request): View
    {
        $userId = Auth::id() ?? User::first()?->id;

        $query = Event::where('user_id', $userId);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('privacy')) {
            if ($request->input('privacy') === 'private') {
                $query->where('is_private', true);
            } elseif ($request->input('privacy') === 'public') {
                $query->where('is_private', false);
            }
        }

        $events = $query->latest()->paginate(12)->withQueryString();

        return view('admin.my_events', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(): View
    {
        if (Auth::check() && in_array(Auth::user()->role, ['super_admin', 'admin'])) {
            abort(403, 'Administrators cannot create events. You can only view, approve, or reject user submissions.');
        }

        return view('admin.create_my_event');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if (Auth::check() && in_array(Auth::user()->role, ['super_admin', 'admin'])) {
            abort(403, 'Administrators cannot create events. You can only view, approve, or reject user submissions.');
        }

        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'event_type'      => 'required|string|in:upcoming,live,past',
            'start_date'      => 'required|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'start_time'      => 'required|string',
            'end_time'        => 'nullable|string',
            'location'        => 'nullable|string|max:255',
            'map_link'        => 'nullable|string',
            'live_stream_url' => 'nullable|url|max:2048',
            'description'     => 'nullable|string',
            'banner_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $userId = Auth::id() ?? User::first()?->id ?? 1;

        $imagePath = null;
        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/events');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $imagePath = 'uploads/events/' . $filename;
        }

        // Set status: super_admin or admin can auto-approve
        $status = 'approved';
        if (Auth::check() && !in_array(Auth::user()->role, ['super_admin', 'admin'])) {
            $status = 'pending';
        }

        Event::create([
            'user_id'         => $userId,
            'title'           => $validated['title'],
            'event_type'      => $validated['event_type'],
            'status'          => $status,
            'start_date'      => $validated['start_date'],
            'end_date'        => $validated['end_date'] ?? null,
            'start_time'      => $validated['start_time'],
            'end_time'        => $validated['end_time'] ?? null,
            'location'        => $validated['location'] ?? null,
            'map_link'        => $validated['map_link'] ?? null,
            'live_stream_url' => $validated['live_stream_url'] ?? null,
            'description'     => $validated['description'] ?? null,
            'banner_image'    => $imagePath,
            'allow_donation'  => $request->boolean('allow_donation'),
            'is_private'      => $request->boolean('is_private'),
        ]);

        return redirect()->route('admin.events.my')->with('success', 'Event created successfully!');
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(int $id): View
    {
        $event = Event::findOrFail($id);
        $this->authorizeAccess($event);

        return view('admin.edit_my_event', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $event = Event::findOrFail($id);
        $this->authorizeAccess($event);

        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'event_type'      => 'required|string|in:upcoming,live,past',
            'start_date'      => 'required|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'start_time'      => 'required|string',
            'end_time'        => 'nullable|string',
            'location'        => 'nullable|string|max:255',
            'map_link'        => 'nullable|string',
            'live_stream_url' => 'nullable|url|max:2048',
            'description'     => 'nullable|string',
            'banner_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $imagePath = $event->banner_image;
        if ($request->hasFile('banner_image')) {
            // Remove previous image if exists
            if ($event->banner_image && file_exists(public_path($event->banner_image))) {
                @unlink(public_path($event->banner_image));
            }

            $file = $request->file('banner_image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/events');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $imagePath = 'uploads/events/' . $filename;
        }

        $event->update([
            'title'           => $validated['title'],
            'event_type'      => $validated['event_type'],
            'start_date'      => $validated['start_date'],
            'end_date'        => $validated['end_date'] ?? null,
            'start_time'      => $validated['start_time'],
            'end_time'        => $validated['end_time'] ?? null,
            'location'        => $validated['location'] ?? null,
            'map_link'        => $validated['map_link'] ?? null,
            'live_stream_url' => $validated['live_stream_url'] ?? null,
            'description'     => $validated['description'] ?? null,
            'banner_image'    => $imagePath,
            'allow_donation'  => $request->boolean('allow_donation'),
            'is_private'      => $request->boolean('is_private'),
        ]);

        return redirect()->route('admin.events.my')->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $event = Event::findOrFail($id);
        $this->authorizeAccess($event);

        if (Auth::check() && !Auth::user()->canDelete()) {
            abort(403, 'Unauthorized: Only Super Administrators have permission to delete events.');
        }

        if ($event->banner_image && file_exists(public_path($event->banner_image))) {
            @unlink(public_path($event->banner_image));
        }

        $event->delete();

        return redirect()->back()->with('success', 'Event deleted successfully!');
    }

    /**
     * Quick status update (Approve/Reject) for admin.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,pending,rejected',
        ]);

        $event->update([
            'status' => $request->input('status'),
        ]);

        return redirect()->back()->with('success', "Event status changed to {$request->input('status')} successfully!");
    }

    /**
     * Helper to verify if user can manage the event.
     */
    private function authorizeAccess(Event $event): void
    {
        if (!Auth::check()) {
            return; // Allow guest access in local development/fallback if auth not enforced yet
        }

        $user = Auth::user();
        if ($user->id !== $event->user_id && !in_array($user->role, ['super_admin', 'admin'])) {
            abort(403, 'Unauthorized access to this event.');
        }
    }
}

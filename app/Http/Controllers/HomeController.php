<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\View\View;
use App\Models\Memory;
use App\Models\Profile;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    /**
     * Display the home/index page.
     */
    public function index(): View
    {
        $memories = Memory::with([
            'profile',
            'profile.images' => function ($query) {
                $query->where('type', 'profile');
            },
        ])
            ->where('approval', 1)
            ->where('status', 'published')
            ->orderBy('id', 'desc')
            ->get();

        return view('home.index',compact('memories'));
    }

    /**
     * Display the about page.
     */
    public function about(): View
    {
        return view('home.about');
    }

    /**
     * Display all memories page.
     */
    public function allMemories(): View
    {
        $memories = Memory::with([
            'profile',
            'profile.images' => function ($query) {
                $query->where('type', 'profile');
            },
        ])
            ->where('approval', 1)
            ->where('status', 'published')

            ->get();
        $totalMemories = Memory::count();

        return view('home.all_memories', compact('memories', 'totalMemories'));
    }

    public function verifyMemorialPassword(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string',
            'password' => 'required|string',
        ]);

        $profile = Profile::where('slug', $validated['slug'])
            ->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Memorial not found.'
            ], 404);
        }

        // Public memorial
        if ($profile->privacy === 'public') {
            return response()->json([
                'success' => true,
                'redirect' => url('/memory_index/' . $profile->slug)
            ]);
        }

        // Check password directly from database
        // Temporary plaintext comparison
        if (
            $profile->privacy === 'private' &&
            !hash_equals(
                (string) $profile->access_password,
                (string) $validated['password']
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Please try again.'
            ], 422);
        }

        if ($profile->privacy !== 'private') {
            return response()->json([
                'success' => false,
                'message' => 'Memorial access is unavailable.'
            ], 403);
        }

        // Grant access to this memorial
        $request->session()->put(
            'private_memorial_access.' . $profile->id,
            true
        );

        return response()->json([
            'success' => true,
            'redirect' => url('/memory_index/' . $profile->slug)
        ]);
    }

    /**
     * Display memories index page.
     */
    public function memoryIndex($slug): View
    {
        $profile = Profile::where('slug', $slug)->firstOrFail();

        $memory = Memory::with('profile')->where('id', $profile->memory_id)->firstOrFail();

        return view('home.memories_index', compact('memory', 'profile'));
    }

    /**
     * Display the 3D card page.
     */
    public function card(): View
    {
        return view('home.card');
    }

    /**
     * Display the contact page.
     */
    public function contact(): View
    {
        return view('home.contact');
    }

    /**
     * Display the create memory page.
     */
    public function createMemory(): View
    {


        return view('home.create_memory');
    }

    /**
     * Display the donation form page.
     */
    public function donationForm(): View
    {
        return view('home.donation_form');
    }



    /**
     * Display memories donation page.
     */
    public function memoriesDonation($slug): View
    {
        $profile = Profile::where('slug', $slug)->firstOrFail();

        $memory = Memory::with('profile')->where('id', $profile->memory_id)->firstOrFail();
        return view('home.memories_donation', compact('profile', 'memory'));
    }

    /**
     * Display memories event page.
     */
    public function memoriesEvent($slug): View
    {
        $profile = Profile::where('slug', $slug)->firstOrFail();

        $memory = Memory::with('profile')->where('id', $profile->memory_id)->firstOrFail();
        return view('home.memories_event', compact('profile', 'memory'));
    }

    /**
     * Display memories gallery page.
     */
    public function memoriesGallery($slug): View
    {
        $profile = Profile::with(['photos', 'videos', 'audios'])->where('slug', $slug)->firstOrFail();

        $memory = Memory::with('profile')->where('id', $profile->memory_id)->firstOrFail();
        $photos = $profile->photos;
        $videos = $profile->videos;
        $audios = $profile->audios;

        return view('home.memories_gallery', compact('memory', 'photos', 'profile', 'videos', 'audios'));
    }



    /**
     * Display memories life story page.
     */
    public function memoriesLife($slug): View
    {
        $profile = Profile::with('stories')->where('slug', $slug)->firstOrFail();

        $memory = Memory::with('profile')->where('id', $profile->memory_id)->firstOrFail();
        $stories = $profile->stories;

        return view('home.memories_life', compact('memory', 'stories', 'profile'));
    }

    /**
     * Display memories stories page.
     */
    public function memoriesStories($slug): View
    {
        $profile = Profile::where('slug', $slug)->firstOrFail();

        $memory = Memory::with('profile')->where('id', $profile->memory_id)->firstOrFail();


        return view('home.memories_stories', compact('memory', 'profile'));
    }

    /**
     * Display memories timeline page.
     */
    public function memoriesTimeline($slug): View
    {
        $profile = Profile::with('timelines')->where('slug', $slug)->firstOrFail();

        $memory = Memory::with('profile')->where('id', $profile->memory_id)->firstOrFail();
        $timelines = $profile->timelines;

        return view('home.memories_timeline', compact('memory', 'timelines', 'profile'));
    }

    /**
     * Display the pricing page.
     */
    public function pricing(): View
    {
        return view('home.pricing');
    }

    //media store
    public function mediaStore(Request $request, Profile $profile)
    {


        // ==========================================
        // 2. VALIDATE MEDIA TYPE
        // ==========================================
        $request->validate([
            'type' => [
                'required',
                Rule::in(['image', 'video', 'audio']),
            ],
            'files' => 'required|array|min:1|max:20',
        ]);

        $type = $request->input('type');

        // ==========================================
        // 3. MEDIA CONFIGURATION
        // ==========================================
        $config = [
            'image' => [
                'folder' => 'images',
                'mimes' => 'jpg,jpeg,png,webp,gif',
                'max' => 10240, // 10 MB
            ],
            'video' => [
                'folder' => 'videos',
                'mimes' => 'mp4,mov,webm',
                'max' => 102400, // 100 MB
            ],
            'audio' => [
                'folder' => 'audios',
                'mimes' => 'mp3,wav,ogg,m4a',
                'max' => 20480, // 20 MB
            ],
        ];

        $selected = $config[$type];

        // ==========================================
        // 4. VALIDATE UPLOADED FILES
        // ==========================================
        $request->validate([
            'files.*' => [
                'required',
                'file',
                'mimes:' . $selected['mimes'],
                'max:' . $selected['max'],
            ],
        ]);

        // ==========================================
        // 5. CREATE UPLOAD DIRECTORY
        // ==========================================
        $folder = 'uploads/memorial/gallery/'
            . $selected['folder'];

        $destination = public_path($folder);

        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $savedMedia = [];

        // ==========================================
        // 6. UPLOAD AND SAVE MEDIA
        // ==========================================
        foreach ($request->file('files') as $file) {

            $filename = Str::uuid() . '.' . $file->extension();

            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            // Move file to public directory
            $file->move($destination, $filename);

            $path = $folder . '/' . $filename;

            try {

                // ==========================================
                // 7. CREATE MEDIA RECORD
                // ==========================================
                $media = Media::create([
                    'profile_id' => $profile->id,
                    'type' => $type,
                    'file_path' => $path,
                    'file_name' => $originalName,
                    'mime_type' => $mimeType,
                    'file_size' => $fileSize,
                    'status' => 0,
                ]);
            } catch (\Throwable $e) {

                // Remove file if database insert fails
                File::delete(public_path($path));

                throw $e;
            }

            // ==========================================
            // 8. PREPARE AJAX RESPONSE
            // ==========================================
            $savedMedia[] = [
                'id' => $media->id,
                'profile_id' => $media->profile_id,
                'type' => $media->type,
                'file_path' => $media->file_path,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'file_size' => $media->file_size,
                'url' => asset($media->file_path),
                'status' => $media->status,
            ];
        }

        // ==========================================
        // 9. RETURN AJAX RESPONSE
        // ==========================================
        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' files uploaded successfully.',
            'media' => $savedMedia,
        ]);
    }

    
}

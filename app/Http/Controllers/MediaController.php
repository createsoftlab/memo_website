<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Profile $profile)
    {
         $this->authorize('view', $profile);

    $photos = $profile->photos()->latest()->get();
    $videos = $profile->videos()->latest()->get();
    $audios = $profile->audios()->latest()->get();

    return view('', compact(
        'profile',
        'photos',
        'videos',
        'audios'
    ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Profile $profile)
    {
         $this->authorize('update', $profile);

        $validated = $request->validate([
            'type' => [
                'required',
                Rule::in(['image', 'video', 'audio']),
            ],
            'files' => [
                'required',
                'array',
                'min:1',
                'max:20',
            ],
            'files.*' => [
                'required',
                'file',
            ],
        ]);

        // Validation rules for each media type
        $rules = [
            'image' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],
            'video' => [
                'required',
                'file',
                'mimes:mp4,mov,webm',
                'max:102400',
            ],
            'audio' => [
                'required',
                'file',
                'mimes:mp3,wav,m4a',
                'max:20480',
            ],
        ];

        $request->validate([
            'files.*' => $rules[$validated['type']],
        ]);

        $uploadedPaths = [];

        try {
            DB::transaction(function () use (
                $request,
                $profile,
                $validated,
                &$uploadedPaths
            ) {
                foreach ($request->file('files') as $file) {

                    // Store file
                    $path = $file->store(
                        'memorials/' . $profile->id . '/' . $validated['type'],
                        'public'
                    );

                    if ($path === false) {
                        throw new \RuntimeException(
                            'Failed to upload file.'
                        );
                    }

                    $uploadedPaths[] = $path;

                    // Save file information to database
                    $profile->media()->create([
                        'type' => $validated['type'],
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            });

        } catch (\Throwable $e) {

            // Remove uploaded files if saving fails
            Storage::disk('public')->delete($uploadedPaths);

            throw $e;
        }

        return back()->with(
            'success',
            'Media uploaded successfully.'
        );
    }

    // Delete a media file
    public function destroy(Profile $profile, Media $media)
    {
        $this->authorize('update', $profile);

        // Verify media belongs to this profile
        abort_unless(
            $media->profile_id === $profile->id,
            404
        );

        $path = $media->file_path;

        // Delete database record
        $media->delete();

        // Delete uploaded file
        Storage::disk('public')->delete($path);

        return back()->with(
            'success',
            'Media deleted successfully.'
        );
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
  
}

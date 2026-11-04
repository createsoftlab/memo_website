<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Models\Package;
use App\Models\PackagePurchase;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use App\Models\Story;
use Illuminate\Support\Facades\File;
use App\Models\Media;
use App\Models\Image;
use App\Models\Music;
use Illuminate\Support\Facades\Http;
use Stripe\Stripe;
use Stripe\Charge;

class MemoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created memory and handle plan purchase / activation.
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->completeMemoryPurchase($request);
    }

    /**
     * Process Step 3 submission & complete memory purchase lifecycle.
     */
    public function completeMemoryPurchase(Request $request): \Illuminate\Http\RedirectResponse
    {
        // 1. Validation for Step 1, 2, and 3
        $rules = [
            'first_name'   => 'nullable|string|max:150',
            'middle_name'  => 'nullable|string|max:150',
            'last_name'    => 'nullable|string|max:150',
            'gender'       => 'nullable|string',
            'country'      => 'nullable|string',
            'city'         => 'nullable|string|max:150',
            'relationship' => 'nullable|string',
            'designation'  => 'nullable|string',
            'privacy'      => 'nullable|string',
            'status'       => 'nullable|string',
            'package_id'   => 'required|exists:packages,id',
        ];

        // If not already existing memory, validate required Step 1 & 2 fields
        if (!$request->filled('memory_id') && !session()->has('memory_id')) {
            $rules['first_name'] = 'required|string|max:150';
            $rules['last_name']  = 'required|string|max:150';
            $rules['gender']     = 'required';
            $rules['country']    = 'required';
            $rules['privacy']    = 'required';
            $rules['status']     = 'required';
        }

        $request->validate($rules);

        // 2. Validate selected package is active
        $package = \App\Models\Package::where('id', $request->package_id)
            ->where('is_active', true)
            ->firstOrFail();

        $isPaid = (float) $package->price > 0;

        // Default Values
        $amountPaid = 0.00;
        $paymentMethod = 'free';
        $transactionId = null;
        $expiresAt = null;

        // Calculate expires_at based on duration_value, duration_unit, and billing_type
        if ($package->billing_type === 'lifetime' || strtolower($package->duration_unit ?? '') === 'unlimited') {
            $expiresAt = null;
        } else {
            $durationValue = (int) ($package->duration_value ?: 1);
            $durationUnit = strtolower($package->duration_unit ?? '');

            if ($durationUnit === 'years') {
                $expiresAt = now()->addYears($durationValue);
            } elseif ($durationUnit === 'months') {
                $expiresAt = now()->addMonths($durationValue);
            } elseif ($durationUnit === 'days') {
                $expiresAt = now()->addDays($durationValue);
            } else {
                $expiresAt = match ($package->billing_type) {
                    'yearly' => now()->addYear(),
                    'monthly' => now()->addMonth(),
                    default => null,
                };
            }
        }

        // Calculate trial_ends_at
        $trialEndsAt = ($package->trial_days && (int) $package->trial_days > 0)
            ? now()->addDays((int) $package->trial_days)
            : null;

        // =========================================================
        // මෙතන ඉඳන් තමයි අලුත් Payment Conversion Code එක පටන් ගන්නේ
        // =========================================================
        if ($isPaid) {
            $request->validate([
                'stripeToken' => 'required|string',
            ], [
                'stripeToken.required' => 'Payment validation failed. Please check your card details and try again.',
            ]);

            $amountPaid = (float) $package->price;
            $packageCurrency = strtoupper($package->currency ?? 'USD');
            $paymentMethod = 'stripe';

            // 1. Currency Conversion
            $chargeCurrency = 'usd'; // අපි Stripe එකට යවන්නේ අනිවාර්යයෙන්ම USD වලින්
            $chargeAmountInCents = 0;

            if ($packageCurrency === 'LKR') {
                try {
                    // නොමිලේ දෙන Exchange Rate API එකකින් Live Rate එක ගැනීම
                    $response = Http::timeout(5)->get('https://open.er-api.com/v6/latest/LKR');

                    if ($response->successful()) {
                        $rates = $response->json('rates');
                        $usdRate = $rates['USD']; // උදා: 0.0033

                        // LKR ගාණ USD වලට හැරවීම
                        $convertedUsd = $amountPaid * $usdRate;

                        // Stripe එකට Cents වලින් දෙන්න ඕනේ නිසා 100න් වැඩි කරනවා
                        $chargeAmountInCents = intval(round($convertedUsd * 100));
                    } else {
                        return back()->withInput()->with('error', 'Currency conversion service is currently unavailable.');
                    }
                } catch (\Exception $e) {
                    return back()->withInput()->with('error', 'Failed to fetch live exchange rates: ' . $e->getMessage());
                }
            } else {
                // LKR නෙමෙයි නම් අගය වෙනස් නොකර Cents වලට හරවන්න
                $chargeAmountInCents = intval(round($amountPaid * 100));
                $chargeCurrency = strtolower($packageCurrency);
            }

            // 2. Stripe Payment Processing
            try {
                Stripe::setApiKey(env('STRIPE_SECRET'));

                $charge = Charge::create([
                    'amount' => $chargeAmountInCents,
                    'currency' => $chargeCurrency,
                    'description' => 'Memory Package: ' . $package->name . ' (Original: ' . $amountPaid . ' ' . $packageCurrency . ')',
                    'source' => $request->stripeToken,
                ]);

                $transactionId = $charge->id;
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Payment failed: ' . $e->getMessage());
            }
        }
        // =========================================================
        // Payment Conversion Code එක ඉවරයි
        // =========================================================


        // 3. Database Insertion & Association
        DB::beginTransaction();
        try {
            $user = Auth::user();

            // Find existing or create new Memory & Profile
            $memoryId = $request->memory_id ?? session('memory_id');
            if ($memoryId) {
                $memory = \App\Models\Memory::findOrFail($memoryId);
            } else {
                $memory = new \App\Models\Memory();
                $memory->user_id = Auth::id();
                $memory->status = $request->status ?? 'published';
                $memory->save();

                $profile = new \App\Models\Profile();
                $profile->memory_id = $memory->id;
                $profile->user_id = Auth::id();
                $profile->memorial_status = $request->memorial_status ?? 'passed_away';
                $profile->passed_away_reason = $request->passed_away_reason;
                $profile->first_name = $request->first_name;
                $profile->middle_name = $request->middle_name;
                $profile->last_name = $request->last_name;
                $profile->gender = $request->gender;
                $profile->country = $request->country;
                $profile->city = $request->city;
                $profile->relationship = $request->relationship;
                $profile->designation = $request->designation;

                // Birth Date
                $dob = $request->date_of_birth;
                if (!$dob && $request->filled('birth_year') && $request->filled('birth_month') && $request->filled('birth_day')) {
                    $dob = sprintf('%04d-%02d-%02d', $request->birth_year, $request->birth_month, $request->birth_day);
                }
                $profile->date_of_birth = $dob;
                $profile->birth_location = $request->birth_location;

                // Death Date
                $dod = $request->date_of_death;
                if (!$dod && $request->filled('passed_year') && $request->filled('passed_month') && $request->filled('passed_day')) {
                    $dod = sprintf('%04d-%02d-%02d', $request->passed_year, $request->passed_month, $request->passed_day);
                }
                $profile->date_of_death = $dod;
                $profile->death_location = $request->death_location;

                // Privacy
                $profile->privacy = $request->privacy ?? 'public';
                $profile->access_username = $request->private_username;
                $profile->access_password = $request->private_password;

                // Slug
                $rawSlug = $request->web_address
                    ? Str::slug($request->web_address)
                    : Str::slug(trim($request->first_name . ' ' . $request->last_name));
                $slug = $rawSlug ?: 'memorial-' . $memory->id;
                $originalSlug = $slug;
                $count = 1;

                while (\App\Models\Profile::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
                $profile->slug = $slug;
                $profile->save();
            }

            // Create record in package_purchases
            $purchase = \App\Models\PackagePurchase::create([
                'user_id'        => Auth::id(),
                'package_id'     => $package->id,
                'amount_paid'    => $amountPaid,
                'currency'       => strtoupper($package->currency ?? 'USD'),
                'payment_method' => $paymentMethod,
                'transaction_id' => $transactionId,
                'status'         => 'active',
                'starts_at'      => now(),
                'expires_at'     => $expiresAt,
                'trial_ends_at'  => $trialEndsAt,
            ]);

            // Update user active package and role
            if ($user) {
                $user->package_id = $package->id;
                $user->package_expires_at = $expiresAt;
                if (in_array($user->role, ['user', 'general_user'], true)) {
                    $user->role = 'memory_user';
                }
                $user->save();
            }

            DB::commit();

            // Clear temporary multi-step session data if used
            session()->forget(['memory_wizard_data', 'memory_id', 'memory_step']);

            return redirect()->route('dashboard')
                ->with('success', 'Memory created and plan activated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error processing details: ' . $e->getMessage());
        }
    }

    public function myMemories(Request $request): View
    {
        $memory = Memory::with('profile')->where('user_id', Auth::id())->first();
        $profileImage = Image::where('profile_id', $memory->profile?->id)->where('type', 'profile')->first();

        $coverImage = Image::where('profile_id', $memory->profile?->id)->where('type', 'cover')->first();
        $backgroundMusic = Music::where('profile_id', $memory->profile?->id)->first();

        return view('memory_user.edit_memory', compact('memory', 'profileImage', 'coverImage', 'backgroundMusic'));
    }

    public function updateMemory(Request $request)
    {
        $memory = Memory::with('profile')->where('user_id', Auth::id())->first();

        $memory->status = $request->status;
        $memory->tribute_quote = $request->tribute_quote;
        $memory->about = $request->about;
        $memory->funeral_location_name = $request->funeral_location_name;
        $memory->funeral_location_address = $request->funeral_location_address;
        $memory->family_contact_no = $request->family_contact_no;
        $memory->broadcast_link = $request->broadcast_link;
        $memory->save();

        $profile = $memory->profile;

        $profile->memorial_status = $request->memorial_status;
        $profile->passed_away_reason = $request->passed_away_reason;
        $profile->first_name = $request->first_name;
        $profile->middle_name = $request->middle_name;
        $profile->last_name = $request->last_name;
        $profile->country = $request->country;
        $profile->city = $request->city;
        $profile->date_of_birth = $request->date_of_birth;
        $profile->birth_location = $request->birth_location;
        $profile->date_of_death = $request->date_of_death;
        $profile->death_location = $request->death_location;
        $profile->privacy = $request->privacy;
        $profile->access_username = $request->private_username;
        $profile->access_password = $request->private_password;

        $profile->save();

        // Update or create profile image
        if ($request->hasFile('profile_image')) {

            $image = Image::where('profile_id', $profile->id)
                ->where('type', 'profile')
                ->first();

            $file = $request->file('profile_image');

            $filename = uniqid() . '.' . $file->extension();

            $file->move(public_path('uploads/memorial/profile'), $filename);

            $path = 'uploads/memorial/profile/' . $filename;

            Image::updateOrCreate(
                [
                    'profile_id' => $profile->id,
                    'type' => 'profile',
                ],
                [
                    'path' => $path,
                ]
            );

            // Delete previous image
            if ($image && $image->path !== $path) {
                $oldPath = public_path($image->path);

                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
        }


        // Update or create cover image
        if ($request->hasFile('cover_image')) {

            $image = Image::where('profile_id', $profile->id)
                ->where('type', 'cover')
                ->first();

            $file = $request->file('cover_image');

            $filename = uniqid() . '.' . $file->extension();

            $file->move(public_path('uploads/memorial/cover'), $filename);

            $path = 'uploads/memorial/cover/' . $filename;

            Image::updateOrCreate(
                [
                    'profile_id' => $profile->id,
                    'type' => 'cover',
                ],
                [
                    'path' => $path,
                ]
            );

            // Delete previous image
            if ($image && $image->path !== $path) {
                $oldPath = public_path($image->path);

                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
        }

        // Update or create memorial music
        if ($request->hasFile('music')) {

            $music = Music::where('profile_id', $profile->id)->first();

            $file = $request->file('music');

            $filename = uniqid() . '.' . $file->extension();

            $file->move(public_path('uploads/memorial/music'), $filename);

            $path = 'uploads/memorial/music/' . $filename;

            Music::updateOrCreate(
                [
                    'profile_id' => $profile->id,
                ],
                [
                    'path' => $path,
                ]
            );

            // Delete previous music file
            if ($music && $music->path !== $path) {

                $oldPath = public_path($music->path);

                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
        }


        return back()->with('success', 'Memory created successfully.');
    }

    /**
     * Alias for Step 3 submission.
     */
    public function storeStep3(Request $request): RedirectResponse
    {
        return $this->completeMemoryPurchase($request);
    }


    //store stories

    public function storeStories(Request $request, Profile $profile)
    {
        abort_unless((int) $profile->user_id === (int) auth()->id(), 403);

        $validated = $request->validate([
            'stories' => 'required|array|min:1',
            'stories.*.id' => [
                'nullable',
                'integer',
                'distinct',
                Rule::exists('stories', 'id')
                    ->where('profile_id', $profile->id),
            ],
            'stories.*.story_type' => 'required|string|max:255',
            'stories.*.title' => 'required|string|max:255',
            'stories.*.content' => 'required|string',
        ]);

        $savedStories = DB::transaction(function () use ($profile, $validated) {

            $saved = [];

            foreach ($validated['stories'] as $story) {

                $storyId = $story['id'] ?? null;

                unset($story['id']);

                if ($storyId) {
                    $record = $profile->stories()->findOrFail($storyId);
                    $record->update($story);
                } else {
                    $record = $profile->stories()->create($story);
                }

                $saved[] = [
                    'id' => $record->id,
                    'story_type' => $record->story_type,
                    'title' => $record->title,
                    'content' => $record->content,
                ];
            }

            return $saved;
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stories saved successfully!',
                'stories' => $savedStories,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Stories saved successfully!');
    }

    //delete stories
    public function deleteStory(Story $story)
    {
        abort_unless(
            (int) $story->profile->user_id === (int) auth()->id(),
            403,
            'Unauthorized to delete this story.'
        );

        $story->delete();

        return response()->json([
            'success' => true,
            'message' => 'Story deleted successfully!'
        ]);
    }


    //update stories
    public function updateStory(Request $request, Story $story)
    {
        abort_unless(
            (int) $story->profile->user_id === (int) auth()->id(),
            403
        );

        $validated = $request->validate([
            'story_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $story->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Story updated successfully!'
        ]);
    }

    // media store
    public function mediaStore(Request $request, Profile $profile)
    {
        // Only the profile owner can upload media
        abort_unless(
            (int) $profile->user_id === (int) Auth::id(),
            403
        );

        // Validate media type
        $request->validate([
            'type' => [
                'required',
                Rule::in(['image', 'video', 'audio'])
            ],
            'files' => 'required|array|min:1|max:20',
        ]);

        $type = $request->type;

        // Separate folder and validation for each type
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

        // Validate uploaded files
        $request->validate([
            'files.*' => [
                'required',
                'file',
                'mimes:' . $selected['mimes'],
                'max:' . $selected['max'],
            ],
        ]);

        // Public folder path
        $folder = 'uploads/memorial/gallery/'
            . $selected['folder'];

        $destination = public_path($folder);

        // Create folder if it does not exist
        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $savedMedia = [];

        foreach ($request->file('files') as $file) {

            // Generate unique filename
            $filename = Str::uuid() . '.'
                . $file->extension();

            $originalName = $file->getClientOriginalName();

            $mimeType = $file->getMimeType();

            $fileSize = $file->getSize();

            // Move file to public folder
            $file->move($destination, $filename);

            // Save relative path in database
            $path = $folder . '/' . $filename;

            try {
                $media = Media::create([
                    'profile_id' => $profile->id,
                    'type' => $type,
                    'file_path' => $path,
                    'file_name' => $originalName,
                    'mime_type' => $mimeType,
                    'file_size' => $fileSize,
                ]);
            } catch (\Throwable $e) {
                File::delete(public_path($path));
                throw $e;
            }

            $savedMedia[] = [
                'id' => $media->id,
                'type' => $media->type,
                'file_path' => $media->file_path,
                'file_name' => $media->file_name,
                'url' => asset($media->file_path),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' files saved successfully.',
            'media' => $savedMedia,
        ]);
    }

    public function mediaDestroy(Profile $profile, Media $media)
    {
        abort_unless(
            (int) $profile->user_id === (int) Auth::id(),
            403
        );

        abort_unless(
            (int) $media->profile_id === (int) $profile->id,
            404
        );

        $filePath = $media->file_path;

        $media->delete();

        if (File::exists(public_path($filePath))) {
            File::delete(public_path($filePath));
        }

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully.'
        ]);
    }
}

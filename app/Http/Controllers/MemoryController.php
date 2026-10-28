<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:150',
            'middle_name' => 'required|string|max:150',
            'last_name' => 'required|string|max:150',
            'gender' => 'required',
            'country' => 'required',
            'city' => 'required|string|max:150',
            'relationship' => 'required',
            'designation' => 'nullable',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'date_of_death' => 'nullable|date|after_or_equal:date_of_birth|before_or_equal:today',
            'privacy' => 'required|in:public,private',
            'status' => 'required|in:draft,published',

        ]);

        $memory = new Memory();
        $memory->memorial_type = $request->memorial_type;
        $memory->web_address = $request->web_address;
        $memory->status = $request->status;

        $memory->save();

        $profile = new Profile();

        $profile->memory_id = $memory->id;
        $profile->user_id = auth()->id();
        $memory->memorial_status = $request->memorial_status;
        $profile->first_name = $request->first_name;
        $profile->middle_name = $request->middle_name;
        $profile->last_name = $request->last_name;
        $profile->gender = $request->gender;
        $profile->country = $request->country;
        $profile->city = $request->city;
        $profile->relationship = $request->relationship;
        $profile->designation = $request->designation;
        $profile->date_of_birth = $request->date_of_birth;
        $profile->birth_location = $request->birth_location;
        $profile->date_of_birth = $request->date_of_birth;
        $profile->death_location = $request->death_location;
        $profile->privacy = $request->privacy;
        $profile->access_username = $request->user_name;
        $profile->access_password = $request->password;

        $name = trim($request->first_name.' '.$request->last_name);

        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Profile::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$count;
            $count++;
        }

        $profile->save();

        return redirect()->back()
            ->with('success', 'Memory created successfully.');
    }


    public function myMemories(): View
    {
        return view('memory_user.all_memories');
    }
    
    public function editMemory(): View
    {
        return view('memory_user.edit_memory');
    }

}

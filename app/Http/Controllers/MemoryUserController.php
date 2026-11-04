<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class MemoryUserController extends Controller
{
    public function profileSettings(): View
    {

        $UserProfileImage = Image::where('user_id', Auth::id())->where('type', 'userProfile')->first();
        return view('memory_user.profile_settings',compact('UserProfileImage'));
    }

    public function profileImageSettings(Request $request)
    {

        // Update or create user profile image
        if ($request->hasFile('profile_image')) {

            $image = Image::where('user_id', Auth::id())
                ->where('type', 'userProfile')
                ->first();

            $file = $request->file('profile_image');

            $filename = uniqid() . '.' . $file->extension();

            $file->move(public_path('uploads/memorial/user/profile'), $filename);

            $path = 'uploads/memorial/user/profile/' . $filename;

            Image::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'type' => 'userProfile',
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
        return back()->with('success', 'Profile Updated Successfully.');
    }

    public function passwordSettings(Request $request)
    {


    }
    public function dashboard(): View
    {
        return view('memory_user.dashbord');
    }
}

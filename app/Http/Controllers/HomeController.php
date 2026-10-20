<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home/index page.
     */
    public function index(): View
    {
        return view('home.index');
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
        return view('home.all_memories');
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
     * Display the forgot password page.
     */
    public function forgotPassword(): View
    {
        return view('home.forgot-password');
    }

    /**
     * Display the login page.
     */
    public function login(): View
    {
        return view('home.login');
    }

    /**
     * Display memories donation page.
     */
    public function memoriesDonation(): View
    {
        return view('home.memories_donation');
    }

    /**
     * Display memories event page.
     */
    public function memoriesEvent(): View
    {
        return view('home.memories_event');
    }

    /**
     * Display memories gallery page.
     */
    public function memoriesGallery(): View
    {
        return view('home.memories_gallery');
    }

    /**
     * Display memories index page.
     */
    public function memoriesIndex(): View
    {
        return view('home.memories_index');
    }

    /**
     * Display memories life story page.
     */
    public function memoriesLife(): View
    {
        return view('home.memories_life');
    }

    /**
     * Display memories stories page.
     */
    public function memoriesStories(): View
    {
        return view('home.memories_stories');
    }

    /**
     * Display memories timeline page.
     */
    public function memoriesTimeline(): View
    {
        return view('home.memories_timeline');
    }

    /**
     * Display the pricing page.
     */
    public function pricing(): View
    {
        return view('home.pricing');
    }

    /**
     * Display the reset password page.
     */
    public function resetPassword(): View
    {
        return view('home.reset_password');
    }

    /**
     * Display the signup page.
     */
    public function signup(): View
    {
        return view('home.signup');
    }
}

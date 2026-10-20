<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication & Breeze Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Dashboard & Profile Routes (Breeze / Default)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Landing / Dashboard Routes
    Route::get('/', [AdminController::class, 'dashboard'])->name('index');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashbord_index', [AdminController::class, 'dashbordIndex'])->name('dashbord_index');
    Route::get('/dashbord-index', [AdminController::class, 'dashbordIndex'])->name('dashbord-index');

    // Employee Management
    Route::get('/add_employe', [AdminController::class, 'addEmploye'])->name('add_employe');
    Route::get('/add-employe', [AdminController::class, 'addEmploye'])->name('add-employe');
    Route::get('/edit_employe', [AdminController::class, 'editEmploye'])->name('edit_employe');
    Route::get('/edit-employe', [AdminController::class, 'editEmploye'])->name('edit-employe');
    Route::get('/view_employe', [AdminController::class, 'viewEmploye'])->name('view_employe');
    Route::get('/view-employe', [AdminController::class, 'viewEmploye'])->name('view-employe');

    // Events Management
    Route::get('/all_events', [AdminController::class, 'allEvents'])->name('all_events');
    Route::get('/all-events', [AdminController::class, 'allEvents'])->name('all-events');
    Route::get('/my_events', [AdminController::class, 'myEvents'])->name('my_events');
    Route::get('/my-events', [AdminController::class, 'myEvents'])->name('my-events');
    Route::get('/create_my_event', [AdminController::class, 'createMyEvent'])->name('create_my_event');
    Route::get('/create-my-event', [AdminController::class, 'createMyEvent'])->name('create-my-event');
    Route::get('/edit_my_event', [AdminController::class, 'editMyEvent'])->name('edit_my_event');
    Route::get('/edit-my-event', [AdminController::class, 'editMyEvent'])->name('edit-my-event');
    Route::get('/view_my_event', [AdminController::class, 'viewMyEvent'])->name('view_my_event');
    Route::get('/view-my-event', [AdminController::class, 'viewMyEvent'])->name('view-my-event');

    // Memories Management
    Route::get('/all_memories', [AdminController::class, 'allMemories'])->name('all_memories');
    Route::get('/all-memories', [AdminController::class, 'allMemories'])->name('all-memories');
    Route::get('/create-memory', [AdminController::class, 'createMemory'])->name('create-memory');
    Route::get('/create_memory', [AdminController::class, 'createMemory'])->name('create_memory');
    Route::get('/edit-memory', [AdminController::class, 'editMemory'])->name('edit-memory');
    Route::get('/edit_memory', [AdminController::class, 'editMemory'])->name('edit_memory');

    // Users Management
    Route::get('/all_users', [AdminController::class, 'allUsers'])->name('all_users');
    Route::get('/all-users', [AdminController::class, 'allUsers'])->name('all-users');

    // Packages Management
    Route::get('/my_package', [AdminController::class, 'myPackage'])->name('my_package');
    Route::get('/my-package', [AdminController::class, 'myPackage'])->name('my-package');
    Route::get('/create_package', [AdminController::class, 'createPackage'])->name('create_package');
    Route::get('/create-package', [AdminController::class, 'createPackage'])->name('create-package');
    Route::get('/view_pakages', [AdminController::class, 'viewPakages'])->name('view_pakages');
    Route::get('/view-pakages', [AdminController::class, 'viewPakages'])->name('view-pakages');
    Route::get('/view-packages', [AdminController::class, 'viewPakages'])->name('view-packages');

    // Other Admin Modules
    Route::get('/bulkemail', [AdminController::class, 'bulkemail'])->name('bulkemail');
    Route::get('/bulk-email', [AdminController::class, 'bulkemail'])->name('bulk-email');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::get('/profile_settings', [AdminController::class, 'profileSettings'])->name('profile_settings');
    Route::get('/profile-settings', [AdminController::class, 'profileSettings'])->name('profile-settings');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});

/*
|--------------------------------------------------------------------------
| Web Routes - Frontend / Home
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/all_memories', [HomeController::class, 'allMemories'])->name('home.all_memories');
Route::get('/all-memories', [HomeController::class, 'allMemories'])->name('home.all-memories');
Route::get('/card', [HomeController::class, 'card'])->name('home.card');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::get('/create_memory', [HomeController::class, 'createMemory'])->name('home.create_memory');
Route::get('/create-memory', [HomeController::class, 'createMemory'])->name('home.create-memory');
Route::get('/donation_form', [HomeController::class, 'donationForm'])->name('home.donation_form');
Route::get('/donation-form', [HomeController::class, 'donationForm'])->name('home.donation-form');
Route::get('/forgot-password', [HomeController::class, 'forgotPassword'])->name('home.forgot_password');
Route::get('/forgot_password', [HomeController::class, 'forgotPassword']);
Route::get('/login', [HomeController::class, 'login'])->name('home.login');
Route::get('/memories_donation', [HomeController::class, 'memoriesDonation'])->name('home.memories_donation');
Route::get('/memories-donation', [HomeController::class, 'memoriesDonation'])->name('home.memories-donation');
Route::get('/memories_event', [HomeController::class, 'memoriesEvent'])->name('home.memories_event');
Route::get('/memories-event', [HomeController::class, 'memoriesEvent'])->name('home.memories-event');
Route::get('/memories_gallery', [HomeController::class, 'memoriesGallery'])->name('home.memories_gallery');
Route::get('/memories-gallery', [HomeController::class, 'memoriesGallery'])->name('home.memories-gallery');
Route::get('/memories_index', [HomeController::class, 'memoriesIndex'])->name('home.memories_index');
Route::get('/memories-index', [HomeController::class, 'memoriesIndex'])->name('home.memories-index');
Route::get('/memories_life', [HomeController::class, 'memoriesLife'])->name('home.memories_life');
Route::get('/memories-life', [HomeController::class, 'memoriesLife'])->name('home.memories-life');
Route::get('/memories_stories', [HomeController::class, 'memoriesStories'])->name('home.memories_stories');
Route::get('/memories-stories', [HomeController::class, 'memoriesStories'])->name('home.memories-stories');
Route::get('/memories_timeline', [HomeController::class, 'memoriesTimeline'])->name('home.memories_timeline');
Route::get('/memories-timeline', [HomeController::class, 'memoriesTimeline'])->name('home.memories-timeline');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('home.pricing');
Route::get('/reset_password', [HomeController::class, 'resetPassword'])->name('home.reset_password');
Route::get('/reset-password', [HomeController::class, 'resetPassword'])->name('home.reset-password');
Route::get('/signup', [HomeController::class, 'signup'])->name('home.signup');

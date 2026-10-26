<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\UserController;
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
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

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
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Landing / Dashboard Routes
    // Route::get('/', [AdminController::class, 'dashboard'])->name('index'); 
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // Route::get('/dashbord_index', [AdminController::class, 'dashbordIndex'])->name('dashbord_index');
    // Route::get('/dashbord-index', [AdminController::class, 'dashbordIndex'])->name('dashbord-index');

    // User Directory & Employee Provisioning
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/employees/create', [UserController::class, 'createEmployee'])->name('employees.create');
    Route::post('/employees', [UserController::class, 'storeEmployee'])->name('employees.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Legacy Route Aliases for User & Employee Management
    Route::get('/add_employee', [UserController::class, 'createEmployee'])->name('add_employee');
    Route::get('/edit_employee', function () {
        $firstUser = \App\Models\User::first();
        return $firstUser
            ? redirect()->route('admin.users.edit', $firstUser->id)
            : redirect()->route('admin.users.index');
    })->name('edit_employee');
    Route::get('/view_employee', [AdminController::class, 'viewEmployee'])->name('view_employee');
    Route::get('/all_users', [UserController::class, 'index'])->name('all_users');

    // Events Management
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/all', [EventController::class, 'allEvents'])->name('all');
        Route::get('/my', [EventController::class, 'myEvents'])->name('my');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/store', [EventController::class, 'store'])->name('store');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
        Route::patch('/{event}/status', [EventController::class, 'updateStatus'])->name('status');
    });

    // Legacy Route Aliases
    Route::get('/all_events', [EventController::class, 'allEvents'])->name('all_events');
    Route::get('/my_events', [EventController::class, 'myEvents'])->name('my_events');
    Route::get('/create_my_event', [EventController::class, 'create'])->name('create_my_event');
    Route::get('/edit_my_event', function () {
        $firstEvent = \App\Models\Event::first();
        return $firstEvent
            ? redirect()->route('admin.events.edit', $firstEvent->id)
            : redirect()->route('admin.events.create');
    })->name('edit_my_event');
    Route::get('/view_my_event', [AdminController::class, 'viewMyEvent'])->name('view_my_event');

    // Memories Management
    Route::get('/all_memories', [AdminController::class, 'allMemories'])->name('all_memories');
    Route::get('/create_memory', [AdminController::class, 'createMemory'])->name('create_memory');
    Route::get('/edit_memory', [AdminController::class, 'editMemory'])->name('edit_memory');

    // Packages Management
    Route::get('/my_package', [AdminController::class, 'myPackage'])->name('my_package');
    Route::get('/create_package', [AdminController::class, 'createPackage'])->name('create_package');
    Route::get('/view_pakages', [AdminController::class, 'viewPakages'])->name('view_pakages');

    // Other Admin Modules
    Route::get('/bulkemail', [AdminController::class, 'bulkemail'])->name('bulkemail');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::get('/profile_settings', [AdminController::class, 'profileSettings'])->name('profile_settings');
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
Route::get('/card', [HomeController::class, 'card'])->name('home.card');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::get('/create_memory', [HomeController::class, 'createMemory'])->name('home.create_memory');
Route::get('/donation_form', [HomeController::class, 'donationForm'])->name('home.donation_form');


Route::get('/memories_donation', [HomeController::class, 'memoriesDonation'])->name('home.memories_donation');
Route::get('/memories_event', [HomeController::class, 'memoriesEvent'])->name('home.memories_event');
Route::get('/memories_gallery', [HomeController::class, 'memoriesGallery'])->name('home.memories_gallery');
Route::get('/memories_index', [HomeController::class, 'memoriesIndex'])->name('home.memories_index');
Route::get('/memories_life', [HomeController::class, 'memoriesLife'])->name('home.memories_life');
Route::get('/memories_stories', [HomeController::class, 'memoriesStories'])->name('home.memories_stories');
Route::get('/memories_timeline', [HomeController::class, 'memoriesTimeline'])->name('home.memories_timeline');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('home.pricing');


//auth routes 
Route::get('/reset_password', [HomeController::class, 'resetPassword'])->name('home.reset_password');

Route::get('/forgot_password', [HomeController::class, 'forgotPassword'])->name('home.forgot_password');
<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\UserManagementController;
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
| Frontend / Public Routes
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

// Auth helper routes
Route::get('/reset_password', [HomeController::class, 'resetPassword'])->name('home.reset_password');
Route::get('/forgot_password', [HomeController::class, 'forgotPassword'])->name('home.forgot_password');

/*
|--------------------------------------------------------------------------
| Protected Admin & Dashboard Routes
| All routes require active authenticated status via EnsureAccountIsActive
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'account.active'])->prefix('admin')->name('admin.')->group(function () {

    // General Staff Dashboard (Super Admin, Admin, Employee)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Super Admin - Admin & Employee Provisioning
    | Protected by Super Admin role or manage-admins permission
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:manage-admins'])->group(function () {
        Route::get('/employees', [AdminUserController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [AdminUserController::class, 'create'])->name('employees.create');
        Route::post('/employees', [AdminUserController::class, 'store'])->name('employees.store');
        Route::get('/employees/{employee}/edit', [AdminUserController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{employee}', [AdminUserController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{employee}', [AdminUserController::class, 'destroy'])->name('employees.destroy');
        Route::post('/employees/{employee}/resend-credentials', [AdminUserController::class, 'resendCredentials'])->name('employees.resend_credentials');

        // Backward compatibility route aliases
        Route::get('/add_employee', [AdminUserController::class, 'create'])->name('add_employee');
        Route::get('/edit_employee', function () {
            $first = \App\Models\User::staff()->where('id', '!=', auth()->id())->first() ?? auth()->user();
            return redirect()->route('admin.employees.edit', $first->id);
        })->name('edit_employee');
        Route::get('/view_employee', [AdminUserController::class, 'index'])->name('view_employee');
    });

    /*
    |--------------------------------------------------------------------------
    | User Management & Moderation
    | Protected by view-users / moderate-users permissions
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:view-users'])->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/all_users', [UserManagementController::class, 'index'])->name('all_users');
    });

    Route::middleware(['permission:moderate-users'])->group(function () {
        Route::patch('/users/{user}/status', [UserManagementController::class, 'updateStatus'])->name('users.status');
        Route::post('/users/{user}/toggle-memory-creator', [UserManagementController::class, 'toggleMemoryCreator'])->name('users.toggle_memory_creator');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Authenticated User Profile & Security Self-Service
    |--------------------------------------------------------------------------
    */
    Route::get('/profile_settings', [ProfileController::class, 'settings'])->name('profile_settings');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    /*
    |--------------------------------------------------------------------------
    | Events Management
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Memories Management
    |--------------------------------------------------------------------------
    */
    Route::get('/all_memories', [AdminController::class, 'allMemories'])->name('all_memories');
    Route::get('/create_memory', [AdminController::class, 'createMemory'])->name('create_memory');
    Route::get('/edit_memory', [AdminController::class, 'editMemory'])->name('edit_memory');

    /*
    |--------------------------------------------------------------------------
    | Packages & Payments
    |--------------------------------------------------------------------------
    */
    Route::get('/my_package', [AdminController::class, 'myPackage'])->name('my_package');
    Route::get('/create_package', [AdminController::class, 'createPackage'])->name('create_package');
    Route::get('/view_pakages', [AdminController::class, 'viewPakages'])->name('view_pakages');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');

    /*
    |--------------------------------------------------------------------------
    | Communications & Reports
    |--------------------------------------------------------------------------
    */
    Route::get('/bulkemail', [AdminController::class, 'bulkemail'])->name('bulkemail');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
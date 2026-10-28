<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemoryController;
use App\Http\Controllers\MemoryUserController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication & Breeze Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Dashboard & Profile Routes (Breeze / Default)
|--------------------------------------------------------------------------
*/
// Route::get('/dashboard', function () {
//     return redirect()->route('admin.dashboard');
// })->middleware(['auth'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Landing / Dashboard Routes
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard')->middleware('module:dashboard');

    // User Directory & Employee Provisioning (Strictly Super Admin Only)
    Route::middleware(['super_admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/employees/create', [UserController::class, 'createEmployee'])->name('employees.create');
        Route::post('/employees', [UserController::class, 'storeEmployee'])->name('employees.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('can.delete');

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
        Route::get('/bulkemail', [AdminController::class, 'bulkemail'])->name('bulkemail');
    });

    // Events Management
    Route::middleware(['module.permission:events,view'])->group(function () {
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/all', [EventController::class, 'allEvents'])->name('all');
            Route::get('/my', [EventController::class, 'myEvents'])->name('my');
            Route::get('/create', [EventController::class, 'create'])->name('create')->middleware('prevent_admin_creation');
            Route::post('/store', [EventController::class, 'store'])->name('store')->middleware('prevent_admin_creation');
            Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit')->middleware('module.permission:events,edit');
            Route::put('/{event}', [EventController::class, 'update'])->name('update')->middleware('module.permission:events,edit');
            Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy')->middleware('can.delete');
            Route::patch('/{event}/status', [EventController::class, 'updateStatus'])->name('status')->middleware('module.permission:events,approve');
        });

        // Legacy Route Aliases for Events
        Route::get('/all_events', [EventController::class, 'allEvents'])->name('all_events');
        Route::get('/my_events', [EventController::class, 'myEvents'])->name('my_events');
        Route::get('/create_my_event', [EventController::class, 'create'])->name('create_my_event')->middleware('prevent_admin_creation');
        Route::get('/edit_my_event', function () {
            $firstEvent = \App\Models\Event::first();
            return $firstEvent
                ? redirect()->route('admin.events.edit', $firstEvent->id)
                : redirect()->route('admin.events.create');
        })->name('edit_my_event');
        Route::get('/view_my_event', [AdminController::class, 'viewMyEvent'])->name('view_my_event');
    });

    // Memories Management
    Route::middleware(['module.permission:memories,view'])->group(function () {
        Route::get('/all_memories', [AdminController::class, 'allMemories'])->name('all_memories');
        Route::get('/create_memory', [AdminController::class, 'createMemory'])->name('create_memory')->middleware('prevent_admin_creation');
        Route::get('/edit_memory', [AdminController::class, 'editMemory'])->name('edit_memory')->middleware('module.permission:memories,edit');
    });

    // Packages & Add-on Items Management
    Route::middleware(['module.permission:packages,view'])->group(function () {
        // View / List Packages & Single Items
        Route::get('/view_pakages', [PackageController::class, 'index'])->name('view_pakages');
        //Route::get('/my_package', [AdminController::class, 'myPackage'])->name('my_package');

        // Package Creation Forms (Support both URL formats)
        Route::get('/create_package', [PackageController::class, 'create'])->name('create_package')->middleware('module.permission:packages,add');
        Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create')->middleware('module.permission:packages,add');

        // Packages Actions (Store, Update, Delete)
        Route::post('/packages/store', [PackageController::class, 'storePackage'])->name('packages.store')->middleware('module.permission:packages,add');
        Route::put('/packages/{package}', [PackageController::class, 'updatePackage'])->name('packages.update')->middleware('module.permission:packages,edit');
        Route::delete('/packages/{package}', [PackageController::class, 'destroyPackage'])->name('packages.destroy')->middleware('can.delete');

        // Single Items (Candle/Flower) Actions (Store, Update, Delete)
        Route::post('/single-items/store', [PackageController::class, 'storeItem'])->name('single-items.store')->middleware('module.permission:packages,add');
        Route::put('/single-items/{item}', [PackageController::class, 'updateItem'])->name('single-items.update')->middleware('module.permission:packages,edit');
        Route::delete('/single-items/{item}', [PackageController::class, 'destroyItem'])->name('single-items.destroy')->middleware('can.delete');
    });

    // Payments Management
    Route::middleware(['module.permission:payments,view'])->group(function () {
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    });

    // Profile Settings Module
    Route::middleware(['module.permission:profile_pages,view'])->group(function () {
        Route::get('/profile_settings', [AdminController::class, 'profileSettings'])->name('profile_settings');
    });

    // Reports Module
    Route::middleware(['module.permission:reports,view'])->group(function () {
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    });

    // Settings Module
    Route::middleware(['module.permission:settings,view'])->group(function () {
        Route::get('/settings', [AdminController::class, 'profileSettings'])->name('settings');
    });
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

Route::middleware('auth')->group(function () {
    Route::get('/create_memory', [MemoryController::class, 'store'])->name('home.create_memory');
});

Route::get('/donation_form', [HomeController::class, 'donationForm'])->name('home.donation_form');
Route::get('/memories_donation', [HomeController::class, 'memoriesDonation'])->name('home.memories_donation');
Route::get('/memories_event', [HomeController::class, 'memoriesEvent'])->name('home.memories_event');
Route::get('/memories_gallery', [HomeController::class, 'memoriesGallery'])->name('home.memories_gallery');
Route::get('/memories_index', [HomeController::class, 'memoriesIndex'])->name('home.memories_index');
Route::get('/memories_life', [HomeController::class, 'memoriesLife'])->name('home.memories_life');
Route::get('/memories_stories', [HomeController::class, 'memoriesStories'])->name('home.memories_stories');
Route::get('/memories_timeline', [HomeController::class, 'memoriesTimeline'])->name('home.memories_timeline');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('home.pricing');

/*
|--------------------------------------------------------------------------
| Memory User Routes
|--------------------------------------------------------------------------
*/
Route::get('memory_user/dashboard', [AdminController::class, 'memoryUser_dashboard'])->name('memoryUser.dashboard');
Route::get('memory_user/my_memories', [MemoryController::class, 'myMemories'])->name('my_memories');
Route::get('memory_user/edit_memory', [MemoryController::class, 'editMemory'])->name('edit_memory');
Route::get('memory_user/profile_settings', [MemoryUserController::class, 'profileSettings'])->name('userProfile_settings');

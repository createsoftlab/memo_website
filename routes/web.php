<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventController as PublicEventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemoryController;
use App\Http\Controllers\MemoryMessageController;
use App\Http\Controllers\MemoryUserController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TributeCheckoutController;
use Illuminate\Support\Facades\Auth;
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
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user && in_array($user->role, ['super_admin', 'admin', 'employee'], true)) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('memoryUser.dashboard');
})->middleware(['auth'])->name('dashboard');

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
            Route::get('/create', [EventController::class, 'create'])->name('create')->middleware(['prevent_admin_creation', 'active_package:event']);
            Route::post('/store', [EventController::class, 'store'])->name('store')->middleware(['prevent_admin_creation', 'active_package:event']);
            Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit')->middleware('module.permission:events,edit');
            Route::put('/{event}', [EventController::class, 'update'])->name('update')->middleware('module.permission:events,edit');
            Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy')->middleware('can.delete');
            Route::patch('/{event}/status', [EventController::class, 'updateStatus'])->name('status')->middleware('module.permission:events,approve');
        });

        // Legacy Route Aliases for Events
        Route::get('/all_events', [EventController::class, 'allEvents'])->name('all_events');
        Route::get('/my_events', [EventController::class, 'myEvents'])->name('my_events');
        Route::get('/create_my_event', [EventController::class, 'create'])->name('create_my_event')->middleware(['prevent_admin_creation', 'active_package:event']);
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
        Route::post('/all_memories/approveOrRejectMemories/{id}', [AdminController::class, 'approveOrRejectMemories'])->name('approveOrRejectMemories');

        Route::get('/create_memory', [AdminController::class, 'createMemory'])->name('create_memory')->middleware(['prevent_admin_creation', 'active_package:memory']);
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
Route::get('/card', [HomeController::class, 'card'])->name('home.card');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');


//view memory from all memories
Route::get('/all_memories', [HomeController::class, 'allMemories'])->name('home.all_memories');

// Public Global Events Stream & Details
Route::get('/all-events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/event/{id}', [PublicEventController::class, 'show'])->name('events.show');

//homepage inside memory navigation pages
Route::get('/memory_index/{slug}', [HomeController::class, 'memoryIndex'])->name('home.memory_index');
//verify privacy
Route::post('/memorial/verify-password', [HomeController::class, 'verifyMemorialPassword'])->middleware('throttle:5,1')->name('memorial.verifyPassword');
Route::get('/memories_life/{slug}', [HomeController::class, 'memoriesLife'])->name('home.memories_life');
Route::get('/memories_stories/{slug}', [HomeController::class, 'memoriesStories'])->name('home.memories_stories');
Route::get('/memories_timeline/{slug}', [HomeController::class, 'memoriesTimeline'])->name('home.memories_timeline');
Route::get('/memories_gallery/{slug}', [HomeController::class, 'memoriesGallery'])->name('home.memories_gallery');
Route::get('/memories_donation/{slug}', [HomeController::class, 'memoriesDonation'])->name('home.memories_donation');
Route::get('/memories_event/{slug}', [MemoryController::class, 'showEvents'])->name('memory.events');

Route::get('/donation_form', [HomeController::class, 'donationForm'])->name('home.donation_form');

Route::middleware(['auth', \App\Http\Middleware\CheckPackageExpiry::class])->group(function () {
    Route::get('/create_memory', [HomeController::class, 'createMemory'])->name('home.create_memory');
    Route::post('/store_memory', [MemoryController::class, 'store'])->name('home.create_memory.store');
    Route::post('/create_memory/purchase', [MemoryController::class, 'completeMemoryPurchase'])->name('home.create_memory.purchase');
    Route::post('/create_memory/step-3', [MemoryController::class, 'storeStep3'])->name('home.create_memory.step3');
});




Route::get('/pricing', [HomeController::class, 'pricing'])->name('home.pricing');
Route::get('/packages', [HomeController::class, 'pricing'])->name('packages.index');

/*
|--------------------------------------------------------------------------
| Memory User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\CheckPackageExpiry::class])->group(function () {
    Route::get('memory_user/dashboard', [MemoryUserController::class, 'dashboard'])->name('memoryUser.dashboard');
    Route::get('memory_user/my_memories', [MemoryController::class, 'myMemories'])->name('my_memories');
    Route::post('memory_user/my_memories/update', [MemoryController::class, 'updateMemory'])->name('update_my_memory');

    Route::get('memory_user/profile_settings', [MemoryUserController::class, 'profileSettings'])->name('userProfile_settings');
    Route::post('memory_user/profile_settings', [MemoryUserController::class, 'profileImageSettings'])->name('userProfile_imageSettings');
    Route::post('memory_user/profile_Passwordsettings', [MemoryUserController::class, 'passwordSettings'])->name('userProfile_passwordSettings');

    // Events management for Memory User
    Route::get('memory_user/my_events', [EventController::class, 'myEvents'])->name('memoryUser.my_events');
    Route::get('memory_user/all_events', [EventController::class, 'allEvents'])->name('memoryUser.all_events');
    Route::get('memory_user/create_my_event', [EventController::class, 'create'])->name('memoryUser.create_event')->middleware(['prevent_admin_creation', 'active_package:event']);

    // Canonical Named Route Aliases for Events & Memories
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create')->middleware(['prevent_admin_creation', 'active_package:event']);
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store')->middleware(['prevent_admin_creation', 'active_package:event']);
    Route::get('/memories/create', [AdminController::class, 'createMemory'])->name('memories.create')->middleware(['prevent_admin_creation', 'active_package:memory']);
});

Route::middleware('auth')->group(function () {

    Route::get('/billing/upgrade', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::get('/billing/success', [BillingController::class, 'success'])->name('billing.success');

    Route::post('/tribute/checkout', [TributeCheckoutController::class, 'checkout'])->name('tribute.checkout');
    Route::get('/tribute/checkout/success', [TributeCheckoutController::class, 'success'])->name('tribute.checkout.success');
    Route::get('/tribute/checkout/cancel', [TributeCheckoutController::class, 'cancel'])->name('tribute.checkout.cancel');

    Route::post('/memory-message/store', [MemoryMessageController::class, 'store'])->name('memory.message.store');

    // Donation Form එක පෙන්වීම
Route::get('/memory/{slug}/donate', [DonationController::class, 'showForm'])->name('donation.form');

// Stripe Checkout Session එක සෑදීම
Route::post('/donation/checkout', [DonationController::class, 'checkout'])->name('donation.checkout');

// Stripe සාර්ථක හා අවලංගු callbacks
Route::get('/donation/checkout/success', [DonationController::class, 'success'])->name('donation.checkout.success');
Route::get('/donation/checkout/cancel', [DonationController::class, 'cancel'])->name('donation.checkout.cancel');
});


Route::middleware('auth')->group(function () {

    // Store multiple stories
    Route::post(
        '/profiles/{profile}/stories',
        [MemoryController::class, 'storeStories']
    )->name('stories.store');

    // Update an existing story
    Route::patch(
        '/stories/{story}',
        [MemoryController::class, 'updateStory']
    )->name('stories.update');

    // Delete an existing story
    Route::delete(
        '/stories/{story}',
        [MemoryController::class, 'deleteStory']
    )->name('stories.destroy');

    // Store photos, videos and audio
    Route::post(
        '/profiles/{profile}/media',
        [MemoryController::class, 'mediaStore']
    )->name('media.store');

    Route::delete(
        '/profiles/{profile}/media/{media}',
        [MemoryController::class, 'mediaDestroy']
    )->name('media.destroy');
});

/*
|--------------------------------------------------------------------------
| Offline CAPTCHA Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Generate CAPTCHA image
    Route::get('/captcha/image', [CaptchaController::class, 'image'])
        ->name('captcha.image');

    // Verify CAPTCHA answer
    Route::post('/captcha/verify', [CaptchaController::class, 'verify'])
        ->name('captcha.verify');
});

Route::get('/test-gd', function () {
    return response()->json([
        'gd_enabled' => extension_loaded('gd'),
        'image_function' => function_exists('imagecreatetruecolor'),
        'imagepng_function' => function_exists('imagepng'),
        'php_ini' => php_ini_loaded_file(),
        'php_binary' => PHP_BINARY,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::post(
        '/timelines/store',
        [MemoryController::class, 'storeTimeline']
    )->name('timelines.store');
});

// Display memorial stories page
Route::get(
    '/memories_stories/{slug}',
    [MemoryController::class, 'memoriesStories']
)->name('memories.stories');

// Save a new story comment
Route::post(
    '/profiles/{profile}/story-comments',
    [MemoryController::class, 'storeStoryComment']
)->middleware('throttle:5,1')
    ->name('story-comments.store');


Route::middleware('auth')->group(function () {

    Route::post(
        '/profiles/{profile}/gallery/upload',
        [HomeController::class, 'mediaStore']
    )->name('gallery.media.store');
});

<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\BloodRequestPublicController;
use App\Http\Controllers\ProfileController as BreezeProfileController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\BloodRequestController as UserBloodRequestController;
use App\Http\Controllers\User\DonorProfileController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BloodRequestController as AdminBloodRequestController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// ─── Health Check (for Render) ────────────────────────────────────────────────
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'ok', 'db' => 'connected'], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'db' => 'disconnected'], 500);
    }
})->name('health');

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'storeContact'])->name('contact.store')
    ->middleware('throttle:5,1');

// Donors public
Route::get('/donors', [DonorController::class, 'index'])->name('donors.index');
Route::get('/become-a-donor', [DonorController::class, 'register'])->name('donors.register');
Route::post('/become-a-donor', [DonorController::class, 'store'])->name('donors.store');

// Blood Requests public
Route::get('/blood-requests', [BloodRequestPublicController::class, 'index'])->name('requests.index');
Route::get('/blood-requests/{bloodRequest}', [BloodRequestPublicController::class, 'show'])->name('requests.show');

// ─── Auth Routes (Breeze) ─────────────────────────────────────────────────────
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [BreezeProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [BreezeProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [BreezeProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Dashboard Gateway Route ────────────────────────────────────────────────
Route::get('/dashboard-redirect', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// ─── User Dashboard Routes ────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Blood Requests (user CRUD)
    Route::resource('requests', UserBloodRequestController::class)->except(['index'])->names([
        'create' => 'requests.create',
        'store' => 'requests.store',
        'show' => 'requests.show',
        'edit' => 'requests.edit',
        'update' => 'requests.update',
        'destroy' => 'requests.destroy',
    ]);
    Route::get('/requests', [UserBloodRequestController::class, 'index'])->name('requests.index');
    Route::post('/requests/{bloodRequest}/cancel', [UserBloodRequestController::class, 'cancel'])->name('requests.cancel');

    // Donor Profile
    Route::get('/donor-profile', [DonorProfileController::class, 'show'])->name('donor-profile');
    Route::post('/donor-profile', [DonorProfileController::class, 'update'])->name('donor-profile.update');
    Route::post('/donor-profile/toggle-availability', [DonorProfileController::class, 'toggleAvailability'])
        ->name('donor-profile.toggle-availability');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Blood Requests
    Route::get('/requests', [AdminBloodRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{bloodRequest}', [AdminBloodRequestController::class, 'show'])->name('requests.show');
    Route::post('/requests/{bloodRequest}/approve', [AdminBloodRequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{bloodRequest}/reject', [AdminBloodRequestController::class, 'reject'])->name('requests.reject');
    Route::patch('/requests/{bloodRequest}/status', [AdminBloodRequestController::class, 'updateStatus'])->name('requests.status');
    Route::delete('/requests/{bloodRequest}', [AdminBloodRequestController::class, 'destroy'])->name('requests.destroy');

    // Contacts
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contactMessage}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::delete('/contacts/{contactMessage}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

    // Testimonials
    Route::get('/testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimonials/{testimonial}/approve', [AdminTestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::delete('/testimonials/{testimonial}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // FAQs
    Route::resource('faqs', AdminFaqController::class)->names([
        'index' => 'faqs.index',
        'create' => 'faqs.create',
        'store' => 'faqs.store',
        'edit' => 'faqs.edit',
        'update' => 'faqs.update',
        'destroy' => 'faqs.destroy',
    ]);
});

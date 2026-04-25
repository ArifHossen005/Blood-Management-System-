<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

// ─── Public / Auth ──────────────────────────────────────────
Route::redirect('/', '/login');

// ─── Locale + Theme switchers (available to guests & auth) ──
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');
Route::get('/theme/{theme}', [ThemeController::class, 'switch'])->name('theme.switch');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Admin + Sub Admin shared routes ────────────────────────
// Both roles access /admin/* — sub_admin gated by per-route permission middleware.
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,sub_admin'])->group(function () {
    // Dashboard + own profile (no permission required)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::put('/change-password', [AdminController::class, 'changePassword'])->name('password.change');

    // Donor list (read) — approve_donors OR edit_donors
    Route::get('/donors', [AdminController::class, 'donors'])
        ->middleware('permission:approve_donors,edit_donors')->name('donors');

    // approve_donors
    Route::patch('/donors/{user}/approve', [AdminController::class, 'approveDonor'])
        ->middleware('permission:approve_donors')->name('donors.approve');
    Route::patch('/donors/{user}/reject', [AdminController::class, 'rejectDonor'])
        ->middleware('permission:approve_donors')->name('donors.reject');

    // edit_donors
    Route::get('/donors/{user}/edit', [AdminController::class, 'editDonor'])
        ->middleware('permission:edit_donors')->name('donors.edit');
    Route::put('/donors/{user}', [AdminController::class, 'updateDonor'])
        ->middleware('permission:edit_donors')->name('donors.update');
    Route::patch('/donors/{user}/toggle-contact', [AdminController::class, 'toggleContactVisible'])
        ->middleware('permission:edit_donors')->name('donors.toggle-contact');
    Route::patch('/donors/{user}/toggle-address', [AdminController::class, 'toggleAddressVisible'])
        ->middleware('permission:edit_donors')->name('donors.toggle-address');

    // manage_blood_requests
    Route::get('/blood-requests', [AdminController::class, 'bloodRequests'])
        ->middleware('permission:manage_blood_requests')->name('blood-requests');
    Route::patch('/blood-requests/{bloodRequest}', [AdminController::class, 'updateBloodRequest'])
        ->middleware('permission:manage_blood_requests')->name('blood-requests.update');

    // view_donations / manage_donations
    Route::get('/donation-histories', [AdminController::class, 'donationHistories'])
        ->middleware('permission:view_donations,manage_donations')->name('donation-histories');
    Route::patch('/donation-histories/{history}/verify', [AdminController::class, 'verifyDonation'])
        ->middleware('permission:manage_donations')->name('donation-histories.verify');
});

// ─── Main Admin exclusive routes ────────────────────────────
// Destructive / privileged actions — sub_admin blocked via strict role:admin.
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Donor destructive
    Route::delete('/donors/{user}', [AdminController::class, 'deleteDonor'])->name('donors.delete');
    Route::patch('/donors/{user}/ban', [AdminController::class, 'banDonor'])->name('donors.ban');

    // Sub Admin management
    Route::get('/sub-admins', [AdminController::class, 'subAdmins'])->name('sub-admins');
    Route::post('/sub-admins/{user}/promote', [AdminController::class, 'promoteSubAdmin'])->name('sub-admins.promote');
    Route::get('/sub-admins/{user}/edit', [AdminController::class, 'editSubAdmin'])->name('sub-admins.edit');
    Route::put('/sub-admins/{user}', [AdminController::class, 'updateSubAdminPermissions'])->name('sub-admins.update');
    Route::delete('/sub-admins/{user}', [AdminController::class, 'revokeSubAdmin'])->name('sub-admins.revoke');
});

// ─── Donor Routes ───────────────────────────────────────────
Route::prefix('donor')->name('donor.')->middleware(['auth', 'role:donor'])->group(function () {
    Route::get('/dashboard', [DonorController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [DonorController::class, 'profile'])->name('profile');
    Route::put('/profile', [DonorController::class, 'updateProfile'])->name('profile.update');
    Route::put('/change-password', [DonorController::class, 'changePassword'])->name('password.change');

    // Blood page (donor list)
    Route::get('/blood', [DonorController::class, 'bloodPage'])->name('blood');
    Route::get('/blood/{user}/profile', [DonorController::class, 'donorProfile'])->name('blood.profile');

    // Donation history
    Route::get('/donations', [DonorController::class, 'donationHistory'])->name('donations');
    Route::post('/donations', [DonorController::class, 'addDonation'])->name('donations.store');

    // Blood requests
    Route::get('/blood-requests', [DonorController::class, 'bloodRequests'])->name('blood-requests');
    Route::post('/blood-requests', [DonorController::class, 'createBloodRequest'])->name('blood-requests.store');
});

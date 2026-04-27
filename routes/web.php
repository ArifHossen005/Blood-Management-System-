<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\NotificationController;
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

// ─── Notifications (shared: admin + donor) ───────────────────
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/',                         [NotificationController::class, 'index'])->name('index');
    Route::get('/unread-count',             [NotificationController::class, 'unreadCount'])->name('unread-count');
    Route::post('/read-all',                [NotificationController::class, 'markAllRead'])->name('read-all');
    Route::post('/{id}/read',               [NotificationController::class, 'markRead'])->name('read');
    Route::delete('/{id}',                  [NotificationController::class, 'destroy'])->name('destroy');
});

// ─── Public Certificate Routes ──────────────────────────────
Route::get('/certificate/share/{certificateNumber}', [CertificateController::class, 'share'])->name('certificate.share');
Route::get('/certificate/{certificateNumber}/og-image', [CertificateController::class, 'ogImage'])->name('certificate.og-image');
Route::get('/verify/{certificateNumber}', [CertificateController::class, 'verify'])->name('certificate.verify');

// ─── Admin + Sub Admin shared routes ────────────────────────
// Both roles access /admin/* — sub_admin gated by per-route permission middleware.
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,sub_admin'])->group(function () {
    // Dashboard + own profile (no permission required)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::put('/donor-profile', [AdminController::class, 'updateDonorProfile'])->name('donor-profile.update');
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

    // Donation claims & certificates
    Route::get('/claims', [AdminController::class, 'claims'])
        ->middleware('permission:manage_donations')->name('claims');
    Route::patch('/claims/{claim}/approve', [AdminController::class, 'approveClaim'])
        ->middleware('permission:manage_donations')->name('claims.approve');
    Route::patch('/claims/{claim}/reject', [AdminController::class, 'rejectClaim'])
        ->middleware('permission:manage_donations')->name('claims.reject');

    // Admin acting as donor (self-service — no extra permission needed)
    Route::get('/my-blood-requests', [AdminController::class, 'myBloodRequests'])->name('my-blood-requests');
    Route::post('/my-blood-requests', [AdminController::class, 'createMyBloodRequest'])->name('my-blood-requests.store');
    Route::get('/my-donations', [AdminController::class, 'myDonations'])->name('my-donations');
    Route::post('/my-donations', [AdminController::class, 'submitMyDonation'])->name('my-donations.store');
    Route::get('/my-claims', [AdminController::class, 'myClaims'])->name('my-claims');
    Route::post('/my-claims', [AdminController::class, 'submitMyClaim'])->name('my-claims.store');
    Route::get('/my-certificate/{claim}', [AdminController::class, 'myCertificate'])->name('my-certificate');
    Route::get('/my-certificate/{claim}/download', [AdminController::class, 'downloadMyCertificate'])->name('my-certificate.download');
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

    // Donation claims & certificates
    Route::get('/claims', [DonorController::class, 'claims'])->name('claims');
    Route::post('/claims', [DonorController::class, 'submitClaim'])->name('claims.store');
    Route::get('/certificate/{claim}', [CertificateController::class, 'view'])->name('certificate');
    Route::get('/certificate/{claim}/download', [CertificateController::class, 'download'])->name('certificate.download');
});

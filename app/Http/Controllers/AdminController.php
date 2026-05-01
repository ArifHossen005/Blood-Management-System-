<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BloodRequest;
use App\Models\DonationClaim;
use App\Models\DonationHistory;
use App\Notifications\BloodRequestStatusUpdated;
use App\Notifications\DonationClaimApproved;
use App\Notifications\DonationClaimRejected;
use App\Notifications\DonationStatusUpdated;
use App\Notifications\DonorApproved;
use App\Notifications\DonorBanned;
use App\Notifications\DonorRejected;
use App\Notifications\NewBloodRequestCreated;
use App\Notifications\NewDonationClaimSubmitted;
use App\Notifications\NewDonationSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminController extends Controller
{
    // ─── Profile ────────────────────────────────────────────
    public function profile()
    {
        return view('admin.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $dir = public_path('uploads/profiles');
            if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
                return back()->withErrors(['profile_image' => __('ui.messages.upload_dir_failed')]);
            }

            $oldPath = $dir . DIRECTORY_SEPARATOR . $user->profile_image;
            if ($user->profile_image && is_file($oldPath)) {
                @unlink($oldPath);
            }

            $file = $request->file('profile_image');
            $filename = 'admin_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);

            $validated['profile_image'] = $filename;
        } else {
            unset($validated['profile_image']);
        }

        $user->update($validated);

        Auth::setUser($user->fresh());

        return back()->with('success', __('ui.messages.profile_updated'));
    }

    public function updateDonorProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'blood_group'   => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'gender'        => 'nullable|in:male,female',
            'date_of_birth' => 'nullable|date|before:today',
            'district'      => 'nullable|string|max:100',
            'division'      => 'nullable|string|max:100',
            'weight'        => 'nullable|numeric|min:30|max:200',
            'health_notes'  => 'nullable|string|max:1000',
            'is_available'  => 'nullable|boolean',
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        $user->update($validated);

        Auth::setUser($user->fresh());

        return back()->with('success', __('ui.messages.profile_updated'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('ui.auth.wrong_current_password')]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', __('ui.messages.password_changed'));
    }

    // ─── Dashboard ──────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_donors'     => User::donors()->count(),
            'approved_donors'  => User::donors()->approved()->count(),
            'temporary_donors' => User::donors()->temporary()->count(),
            'available_donors' => User::donors()->approved()->available()->count(),
            'total_donations'  => DonationHistory::where('status', 'verified')->count(),
            'pending_requests' => BloodRequest::where('status', 'pending')->count(),
            'pending_approvals'=> User::donors()->temporary()->count(),
        ];

        $bloodGroupStats = User::donors()->approved()
            ->selectRaw('blood_group, COUNT(*) as total')
            ->groupBy('blood_group')
            ->pluck('total', 'blood_group')
            ->toArray();

        $recentDonors   = User::donors()->latest()->take(5)->get();
        $pendingDonors  = User::donors()->temporary()->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'bloodGroupStats', 'recentDonors', 'pendingDonors'));
    }

    // ─── Donor Management ───────────────────────────────────
    public function donors(Request $request)
    {
        $query = User::donors();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('district', 'LIKE', "%{$search}%");
            });
        }

        $donors = $query->latest()->paginate(15)->withQueryString();

        return view('admin.donors', compact('donors'));
    }

    public function approveDonor(User $user)
    {
        $user->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        $user->notify(new DonorApproved());

        return back()->with('success', __('ui.messages.donor_approved', ['name' => $user->name]));
    }

    public function rejectDonor(User $user)
    {
        $user->update(['status' => 'rejected']);
        $user->notify(new DonorRejected());
        return back()->with('success', __('ui.messages.donor_rejected', ['name' => $user->name]));
    }

    public function banDonor(User $user)
    {
        $user->update(['status' => 'banned']);
        $user->notify(new DonorBanned());
        return back()->with('success', __('ui.messages.donor_banned', ['name' => $user->name]));
    }

    public function editDonor(User $user)
    {
        return view('admin.edit-donor', compact('user'));
    }

    public function updateDonor(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'phone'            => 'nullable|string|max:20',
            'blood_group'      => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'gender'           => 'nullable|in:male,female',
            'address'          => 'nullable|string|max:500',
            'district'         => 'nullable|string|max:100',
            'division'         => 'nullable|string|max:100',
            'status'           => 'required|in:temporary,approved,rejected,banned',
            'contact_visible'  => 'nullable|boolean',
            'address_visible'  => 'nullable|boolean',
            'is_available'     => 'nullable|boolean',
        ]);

        $validated['contact_visible'] = $request->boolean('contact_visible');
        $validated['address_visible'] = $request->boolean('address_visible');
        $validated['is_available']    = $request->boolean('is_available');

        $user->update($validated);

        return redirect()->route('admin.donors')->with('success', __('ui.messages.donor_updated'));
    }

    public function deleteDonor(User $user)
    {
        $user->delete();
        return back()->with('success', __('ui.messages.donor_deleted'));
    }

    // ─── Toggle Visibility ──────────────────────────────────
    public function toggleContactVisible(User $user)
    {
        $user->update(['contact_visible' => !$user->contact_visible]);
        return back()->with('success', __('ui.messages.contact_visibility_updated'));
    }

    public function toggleAddressVisible(User $user)
    {
        $user->update(['address_visible' => !$user->address_visible]);
        return back()->with('success', __('ui.messages.address_visibility_updated'));
    }

    // ─── Blood Requests ─────────────────────────────────────
    public function bloodRequests(Request $request)
    {
        $query = BloodRequest::with('requester');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(15)->withQueryString();
        return view('admin.blood-requests', compact('requests'));
    }

    public function updateBloodRequest(Request $request, BloodRequest $bloodRequest)
    {
        $newStatus = $request->status;
        $bloodRequest->update(['status' => $newStatus]);

        if ($bloodRequest->requester && in_array($newStatus, ['approved', 'fulfilled', 'cancelled'])) {
            $bloodRequest->requester->notify(new BloodRequestStatusUpdated($bloodRequest, $newStatus));
        }

        return back()->with('success', __('ui.messages.request_updated'));
    }

    // ─── Donation History ───────────────────────────────────
    public function donationHistories(Request $request)
    {
        $query = DonationHistory::with('donor');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $histories = $query->latest()->paginate(15)->withQueryString();
        return view('admin.donation-histories', compact('histories'));
    }

    public function verifyDonation(DonationHistory $history)
    {
        $history->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
        ]);

        $donor = $history->donor;
        $donor->increment('total_donations');
        $donor->update(['last_donation_date' => $history->donation_date]);

        $donor->notify(new DonationStatusUpdated($history, 'verified'));

        return back()->with('success', __('ui.messages.donation_verified'));
    }

    // ─── Donation Claims ────────────────────────────────────────
    public function claims(Request $request)
    {
        $query = DonationClaim::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $claims = $query->latest()->paginate(15)->withQueryString();
        $pendingCount = DonationClaim::where('status', 'pending')->count();

        return view('admin.claims', compact('claims', 'pendingCount'));
    }

    public function approveClaim(DonationClaim $claim)
    {
        if ($claim->status !== 'pending') {
            return back()->withErrors(['claim' => __('ui.certificate.already_processed')]);
        }

        $certificateNumber = DonationClaim::generateCertificateNumber($claim->user_id);

        $claim->update([
            'status'             => 'approved',
            'certificate_number' => $certificateNumber,
            'approved_by'        => auth()->id(),
            'approved_at'        => now(),
        ]);

        // Auto-create verified DonationHistory record
        $donor = $claim->user;
        DonationHistory::create([
            'donor_id'      => $claim->user_id,
            'blood_group'   => $donor->blood_group,
            'donation_date' => $claim->donation_date,
            'units'         => 1,
            'hospital_name' => $claim->hospital_name,
            'location'      => $claim->location,
            'notes'         => $claim->notes,
            'verified_by'   => auth()->id(),
            'status'        => 'verified',
        ]);

        // Update donor stats
        $donor->increment('total_donations');
        if (!$donor->last_donation_date || $claim->donation_date->greaterThan($donor->last_donation_date)) {
            $donor->update(['last_donation_date' => $claim->donation_date]);
        }

        $claim->user->notify(new DonationClaimApproved($claim));

        return back()->with('success', __('ui.certificate.claim_approved', ['cert' => $certificateNumber]));
    }

    public function rejectClaim(Request $request, DonationClaim $claim)
    {
        if ($claim->status !== 'pending') {
            return back()->withErrors(['claim' => __('ui.certificate.already_processed')]);
        }

        $claim->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        $claim->user->notify(new DonationClaimRejected($claim));

        return back()->with('success', __('ui.certificate.claim_rejected'));
    }

    // ─── Admin as Donor: Blood Requests ────────────────────────
    public function myBloodRequests()
    {
        $requests = BloodRequest::where('requester_id', Auth::id())->latest()->paginate(10);
        return view('admin.my-blood-requests', compact('requests'));
    }

    public function createMyBloodRequest(Request $request)
    {
        $validated = $request->validate([
            'patient_name'     => 'required|string|max:255',
            'blood_group'      => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_needed'     => 'required|integer|min:1|max:10',
            'hospital_name'    => 'required|string|max:255',
            'hospital_address' => 'nullable|string|max:500',
            'contact_number'   => 'required|string|max:20',
            'needed_date'      => 'required|date|after_or_equal:today',
            'urgency'          => 'required|in:normal,urgent,emergency',
            'reason'           => 'nullable|string|max:500',
        ]);

        $validated['requester_id'] = Auth::id();
        $bloodRequest = BloodRequest::create($validated);
        $bloodRequest->load('requester');

        User::where('role', 'sub_admin')
            ->whereJsonContains('permissions', 'manage_blood_requests')
            ->get()
            ->each(fn($admin) => $admin->notify(new NewBloodRequestCreated($bloodRequest)));

        return back()->with('success', __('ui.messages.request_submitted'));
    }

    // ─── Admin as Donor: Donation History ──────────────────────
    public function myDonations()
    {
        $donations = DonationHistory::where('donor_id', Auth::id())->latest()->paginate(10);
        return view('admin.my-donations', compact('donations'));
    }

    public function submitMyDonation(Request $request)
    {
        $validated = $request->validate([
            'donation_date'  => 'required|date|before_or_equal:today',
            'hospital_name'  => 'nullable|string|max:255',
            'location'       => 'nullable|string|max:255',
            'recipient_name' => 'nullable|string|max:255',
            'units'          => 'nullable|integer|min:1|max:3',
            'notes'          => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        abort_if(!$user->blood_group, 422, 'Set your blood group in profile first.');

        $validated['donor_id']    = $user->id;
        $validated['blood_group'] = $user->blood_group;
        $validated['status']      = 'pending';

        $donation = DonationHistory::create($validated);

        User::where('role', 'sub_admin')
            ->whereJsonContains('permissions', 'manage_donations')
            ->get()
            ->each(fn($a) => $a->notify(new NewDonationSubmitted($donation)));

        return back()->with('success', __('ui.messages.donation_submitted'));
    }

    // ─── Admin as Donor: Certificate Claims ────────────────────
    public function myClaims()
    {
        $claims = DonationClaim::where('user_id', Auth::id())->latest()->paginate(10);
        return view('admin.my-claims', compact('claims'));
    }

    public function submitMyClaim(Request $request)
    {
        $validated = $request->validate([
            'donation_date' => 'required|date|before_or_equal:today',
            'hospital_name' => 'nullable|string|max:255',
            'location'      => 'nullable|string|max:255',
            'notes'         => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();
        $claim = DonationClaim::create($validated);
        $claim->load('user');

        User::where('role', 'sub_admin')
            ->whereJsonContains('permissions', 'manage_donations')
            ->get()
            ->each(fn($a) => $a->notify(new NewDonationClaimSubmitted($claim)));

        return back()->with('success', __('ui.certificate.claim_submitted'));
    }

    public function myCertificate(DonationClaim $claim)
    {
        abort_unless(Auth::id() === $claim->user_id && $claim->status === 'approved', 403);
        $claim->load('user', 'approver');

        $qrSvg = QrCode::format('svg')->size(120)->color(220, 20, 60)->generate(
            route('certificate.verify', $claim->certificate_number)
        );

        return view('admin.my-certificate', compact('claim', 'qrSvg'));
    }

    public function downloadMyCertificate(DonationClaim $claim)
    {
        abort_unless(Auth::id() === $claim->user_id && $claim->status === 'approved', 403);
        $claim->load('user', 'approver');

        $qrSvg = QrCode::format('svg')->size(80)->color(220, 20, 60)->generate(
            route('certificate.verify', $claim->certificate_number)
        );
        $svgClean = trim(preg_replace('/\<\?xml[^\?]*\?\>/', '', (string) $qrSvg));
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($svgClean);

        $avatarBase64 = null;
        $avatarPath   = public_path('uploads/profiles/' . $claim->user->profile_image);
        if ($claim->user->profile_image && file_exists($avatarPath)) {
            $ext  = strtolower(pathinfo($avatarPath, PATHINFO_EXTENSION));
            $mime = ($ext === 'jpg' || $ext === 'jpeg') ? 'image/jpeg' : 'image/png';
            $avatarBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($avatarPath));
        }

        $pdf = Pdf::loadView('certificate.pdf', compact('claim', 'qrBase64', 'avatarBase64'))
            ->setPaper('A4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('margin_top', 0)
            ->setOption('margin_right', 0)
            ->setOption('margin_bottom', 0)
            ->setOption('margin_left', 0)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('dpi', 96);

        return $pdf->download('certificate-' . $claim->certificate_number . '.pdf');
    }

    // ─── Sub Admin Management ───────────────────────────────
    public function subAdmins(Request $request)
    {
        $subAdmins = User::subAdmins()->latest()->paginate(15);

        $eligibleDonors = User::donors()
            ->approved()
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->q;
                $q->where(function ($qq) use ($term) {
                    $qq->where('name', 'LIKE', "%{$term}%")
                       ->orWhere('email', 'LIKE', "%{$term}%")
                       ->orWhere('phone', 'LIKE', "%{$term}%");
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        $availablePermissions = User::availablePermissions();

        return view('admin.sub-admins.index', compact('subAdmins', 'eligibleDonors', 'availablePermissions'));
    }

    public function promoteSubAdmin(Request $request, User $user)
    {
        if ($user->role !== 'donor' || $user->status !== 'approved') {
            return back()->withErrors(['donor' => __('ui.messages.only_approved_donor_promotable')]);
        }

        $validated = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string|in:' . implode(',', array_keys(User::availablePermissions())),
        ]);

        $user->update([
            'role'        => 'sub_admin',
            'permissions' => array_values($validated['permissions'] ?? []),
        ]);

        return redirect()->route('admin.sub-admins.edit', $user)
            ->with('success', __('ui.messages.sub_admin_promoted', ['name' => $user->name]));
    }

    public function editSubAdmin(User $user)
    {
        if (!$user->isSubAdmin()) {
            abort(404);
        }

        return view('admin.sub-admins.edit', [
            'user'                 => $user,
            'availablePermissions' => User::availablePermissions(),
        ]);
    }

    public function updateSubAdminPermissions(Request $request, User $user)
    {
        if (!$user->isSubAdmin()) {
            abort(404);
        }

        $validated = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string|in:' . implode(',', array_keys(User::availablePermissions())),
        ]);

        $user->update([
            'permissions' => array_values($validated['permissions'] ?? []),
        ]);

        return back()->with('success', __('ui.messages.sub_admin_updated', ['name' => $user->name]));
    }

    public function revokeSubAdmin(User $user)
    {
        if (!$user->isSubAdmin()) {
            abort(404);
        }

        $user->update([
            'role'        => 'donor',
            'permissions' => null,
        ]);

        return redirect()->route('admin.sub-admins')
            ->with('success', __('ui.messages.sub_admin_revoked', ['name' => $user->name]));
    }
}

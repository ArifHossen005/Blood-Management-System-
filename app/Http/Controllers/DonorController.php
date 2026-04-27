<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BloodRequest;
use App\Models\DonationClaim;
use App\Models\DonationHistory;
use App\Notifications\NewBloodRequestCreated;
use App\Notifications\NewDonationClaimSubmitted;
use App\Notifications\NewDonationSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DonorController extends Controller
{
    // ─── Dashboard ──────────────────────────────────────────
    public function dashboard()
    {
        $user  = Auth::user();
        $stats = [
            'total_donations' => $user->total_donations,
            'last_donation'   => $user->last_donation_date?->format('d M, Y') ?? __('ui.dashboard.not_donated_yet'),
            'can_donate'      => $user->can_donate,
            'status'          => $user->status,
        ];

        $myDonations = DonationHistory::where('donor_id', $user->id)->latest()->take(5)->get();
        $myRequests  = BloodRequest::where('requester_id', $user->id)->latest()->take(5)->get();

        return view('donor.dashboard', compact('stats', 'myDonations', 'myRequests'));
    }

    // ─── Profile ────────────────────────────────────────────
    public function profile()
    {
        return view('donor.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'phone'             => 'nullable|string|max:20',
            'date_of_birth'     => 'nullable|date|before:today',
            'gender'            => 'nullable|in:male,female',
            'address'           => 'nullable|string|max:500',
            'city'              => 'nullable|string|max:100',
            'district'          => 'nullable|string|max:100',
            'division'          => 'nullable|string|max:100',
            'emergency_contact' => 'nullable|string|max:20',
            'weight'            => 'nullable|numeric|min:30|max:200',
            'health_notes'      => 'nullable|string|max:1000',
            'is_available'      => 'nullable|boolean',
            'profile_image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
            $filename = 'donor_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);

            $validated['profile_image'] = $filename;
        } else {
            unset($validated['profile_image']);
        }

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

    // ─── Blood Page (Donor List) ────────────────────────────
    public function bloodPage(Request $request)
    {
        $query = User::where(function ($q) {
            $q->where('role', 'donor')->where('status', 'approved')->where('is_available', true);
        })->orWhere(function ($q) {
            $q->whereIn('role', ['admin', 'sub_admin'])
              ->whereNotNull('blood_group')
              ->where('blood_group', '!=', '')
              ->where('is_available', true);
        });

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }
        if ($request->filled('district')) {
            $query->where('district', 'LIKE', "%{$request->district}%");
        }
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $donors = $query->latest('total_donations')->paginate(12)->withQueryString();
        $currentUser = Auth::user();

        return view('donor.blood', compact('donors', 'currentUser'));
    }

    // ─── Donor Profile Popup (AJAX) ─────────────────────────
    public function donorProfile(User $user)
    {
        $viewer = Auth::user();

        // Temporary members can only see limited info
        $canSeeFullDetails = $viewer->is_full_member;

        $data = [
            'id'              => $user->id,
            'name'            => $user->name,
            'blood_group'     => $user->blood_group,
            'profile_image'   => $user->profile_image_url,
            'total_donations' => $user->total_donations,
            'is_available'    => $user->is_available,
            'gender'          => $user->gender,
            'status'          => $user->status,
            'can_donate'      => $user->can_donate,
        ];

        // Full members get additional details based on admin visibility settings
        if ($canSeeFullDetails) {
            $data['last_donation_date'] = $user->last_donation_date?->format('d M, Y');
            $data['district']           = $user->district;
            $data['division']           = $user->division;

            if ($user->contact_visible) {
                $data['phone']             = $user->phone;
                $data['emergency_contact'] = $user->emergency_contact;
            }
            if ($user->address_visible) {
                $data['address'] = $user->address;
                $data['city']    = $user->city;
            }
        }

        $data['can_see_full'] = $canSeeFullDetails;

        return response()->json($data);
    }

    // ─── Donation History ───────────────────────────────────
    public function donationHistory()
    {
        $donations = DonationHistory::where('donor_id', Auth::id())->latest()->paginate(10);
        return view('donor.donation-history', compact('donations'));
    }

    public function addDonation(Request $request)
    {
        $validated = $request->validate([
            'donation_date'  => 'required|date|before_or_equal:today',
            'hospital_name'  => 'nullable|string|max:255',
            'location'       => 'nullable|string|max:255',
            'recipient_name' => 'nullable|string|max:255',
            'units'          => 'nullable|integer|min:1|max:3',
            'notes'          => 'nullable|string|max:500',
        ]);

        $validated['donor_id']    = Auth::id();
        $validated['blood_group'] = Auth::user()->blood_group;
        $validated['status']      = 'pending'; // needs admin verification

        $donation = DonationHistory::create($validated);

        User::where('role', 'admin')
            ->orWhere(function ($q) {
                $q->where('role', 'sub_admin')
                  ->whereJsonContains('permissions', 'manage_donations');
            })
            ->get()
            ->each(fn($admin) => $admin->notify(new NewDonationSubmitted($donation)));

        return back()->with('success', __('ui.messages.donation_submitted'));
    }

    // ─── Donation Claims ────────────────────────────────────────
    public function claims()
    {
        $claims = DonationClaim::where('user_id', Auth::id())->latest()->paginate(10);
        return view('donor.claims', compact('claims'));
    }

    public function submitClaim(Request $request)
    {
        $validated = $request->validate([
            'donation_date'    => 'required|date|before_or_equal:today',
            'hospital_name'    => 'nullable|string|max:255',
            'location'         => 'nullable|string|max:255',
            'blood_request_id' => 'nullable|exists:blood_requests,id',
            'notes'            => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();

        $claim = DonationClaim::create($validated);
        $claim->load('user');

        User::where('role', 'admin')
            ->orWhere(function ($q) {
                $q->where('role', 'sub_admin')
                  ->whereJsonContains('permissions', 'manage_donations');
            })
            ->get()
            ->each(fn($admin) => $admin->notify(new NewDonationClaimSubmitted($claim)));

        return back()->with('success', __('ui.certificate.claim_submitted'));
    }

    // ─── Blood Requests ─────────────────────────────────────
    public function bloodRequests()
    {
        $requests = BloodRequest::where('requester_id', Auth::id())->latest()->paginate(10);
        return view('donor.blood-requests', compact('requests'));
    }

    public function createBloodRequest(Request $request)
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

        User::where('role', 'admin')
            ->orWhere(function ($q) {
                $q->where('role', 'sub_admin')
                  ->whereJsonContains('permissions', 'manage_blood_requests');
            })
            ->get()
            ->each(fn($admin) => $admin->notify(new NewBloodRequestCreated($bloodRequest)));

        return back()->with('success', __('ui.messages.request_submitted'));
    }
}

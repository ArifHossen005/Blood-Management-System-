<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BloodRequest;
use App\Models\DonationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        return back()->with('success', __('ui.messages.donor_approved', ['name' => $user->name]));
    }

    public function rejectDonor(User $user)
    {
        $user->update(['status' => 'rejected']);
        return back()->with('success', __('ui.messages.donor_rejected', ['name' => $user->name]));
    }

    public function banDonor(User $user)
    {
        $user->update(['status' => 'banned']);
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
        $bloodRequest->update(['status' => $request->status]);
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

        // Update donor stats
        $donor = $history->donor;
        $donor->increment('total_donations');
        $donor->update(['last_donation_date' => $history->donation_date]);

        return back()->with('success', __('ui.messages.donation_verified'));
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

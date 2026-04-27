<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewDonorRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->status === 'banned') {
                Auth::logout();
                return back()->withErrors(['email' => __('ui.auth.banned')]);
            }

            return match (Auth::user()->role) {
                'admin', 'sub_admin' => redirect()->route('admin.dashboard'),
                default              => redirect()->route('donor.dashboard'),
            };
        }

        return back()->withErrors(['email' => __('ui.auth.bad_credentials')]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => ['required', 'confirmed', Password::min(6)],
            'phone'         => 'required|string|max:20',
            'blood_group'   => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'date_of_birth' => 'nullable|date|before:today',
            'gender'        => 'required|in:male,female',
            'address'       => 'nullable|string|max:500',
            'district'      => 'nullable|string|max:100',
            'division'      => 'nullable|string|max:100',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role']     = 'donor';
        $validated['status']   = 'temporary'; // needs admin approval

        $user = User::create($validated);

        User::where('role', 'admin')
            ->orWhere(function ($q) {
                $q->where('role', 'sub_admin')
                  ->whereJsonContains('permissions', 'approve_donors');
            })
            ->get()
            ->each(fn($admin) => $admin->notify(new NewDonorRegistered($user)));

        Auth::login($user);

        return redirect()->route('donor.dashboard')
            ->with('success', __('ui.messages.registration_success'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

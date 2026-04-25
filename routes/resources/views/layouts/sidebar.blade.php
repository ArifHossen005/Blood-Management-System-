<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-droplet-fill"></i>
        </div>
        <div>
            <h5>রক্ত ব্যবস্থাপনা</h5>
            <small class="text-muted" style="font-size:0.7rem;">Blood Management</small>
        </div>
    </div>

    <ul class="sidebar-nav">
        @if(auth()->user()->role === 'admin')
            {{-- Admin Navigation --}}
            <li class="nav-label">প্রধান মেনু</li>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> ড্যাশবোর্ড
                </a>
            </li>
            <li class="nav-label">ব্যবস্থাপনা</li>
            <li>
                <a href="{{ route('admin.donors') }}" class="{{ request()->routeIs('admin.donors*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> ডোনার তালিকা
                    @php $pendingCount = \App\Models\User::donors()->temporary()->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.blood-requests') }}" class="{{ request()->routeIs('admin.blood-requests*') ? 'active' : '' }}">
                    <i class="bi bi-heart-pulse-fill"></i> রক্তের অনুরোধ
                </a>
            </li>
            <li>
                <a href="{{ route('admin.donation-histories') }}" class="{{ request()->routeIs('admin.donation-histories*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> দানের ইতিহাস
                </a>
            </li>
        @else
            {{-- Donor Navigation --}}
            <li class="nav-label">প্রধান মেনু</li>
            <li>
                <a href="{{ route('donor.dashboard') }}" class="{{ request()->routeIs('donor.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> ড্যাশবোর্ড
                </a>
            </li>
            <li class="nav-label">রক্তদান</li>
            <li>
                <a href="{{ route('donor.blood') }}" class="{{ request()->routeIs('donor.blood') ? 'active' : '' }}">
                    <i class="bi bi-droplet-half"></i> রক্তদাতা তালিকা
                </a>
            </li>
            <li>
                <a href="{{ route('donor.donations') }}" class="{{ request()->routeIs('donor.donations') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> দানের ইতিহাস
                </a>
            </li>
            <li>
                <a href="{{ route('donor.blood-requests') }}" class="{{ request()->routeIs('donor.blood-requests') ? 'active' : '' }}">
                    <i class="bi bi-heart-pulse-fill"></i> রক্তের অনুরোধ
                </a>
            </li>
            <li class="nav-label">অ্যাকাউন্ট</li>
            <li>
                <a href="{{ route('donor.profile') }}" class="{{ request()->routeIs('donor.profile') ? 'active' : '' }}">
                    <i class="bi bi-person-fill"></i> প্রোফাইল
                </a>
            </li>
        @endif
    </ul>

    {{-- Status Badge --}}
    @if(auth()->user()->role === 'donor')
        <div class="px-3 mt-3">
            <div class="p-3 rounded-3 text-center" style="background: rgba(255,255,255,0.08);">
                <small class="text-muted d-block mb-1">সদস্যপদ</small>
                @if(auth()->user()->is_full_member)
                    <span class="badge bg-success px-3 py-2"><i class="bi bi-patch-check-fill me-1"></i> পূর্ণ সদস্য</span>
                @else
                    <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> অস্থায়ী</span>
                @endif
            </div>
        </div>
    @endif
</aside>

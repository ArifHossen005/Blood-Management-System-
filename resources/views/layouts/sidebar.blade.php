<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-droplet-fill"></i>
        </div>
        <div>
            <h5>{{ __('ui.app.short') }}</h5>
            <small class="text-muted" style="font-size:0.7rem;">{{ __('ui.app.tagline') }}</small>
        </div>
    </div>

    <ul class="sidebar-nav">
        @if(auth()->user()->isMainAdmin() || auth()->user()->isSubAdmin())
            {{-- Admin / Sub Admin Navigation (shared) --}}
            @php $u = auth()->user(); @endphp

            <li class="nav-label">{{ __('ui.nav.main_menu') }}</li>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> {{ __('ui.nav.dashboard') }}
                </a>
            </li>

            @if($u->hasAnyPermission(['approve_donors', 'edit_donors']) || $u->hasPermission('manage_blood_requests') || $u->hasAnyPermission(['view_donations', 'manage_donations']))
                <li class="nav-label">{{ __('ui.nav.management') }}</li>
            @endif

            @if($u->hasAnyPermission(['approve_donors', 'edit_donors']))
                <li>
                    <a href="{{ route('admin.donors') }}" class="{{ request()->routeIs('admin.donors*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> {{ __('ui.nav.donor_list_admin') }}
                        @if($u->isMainAdmin())
                            @php $pendingCount = \App\Models\User::donors()->temporary()->count(); @endphp
                            @if($pendingCount > 0)
                                <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                            @endif
                        @endif
                    </a>
                </li>
            @endif

            @if($u->hasPermission('manage_blood_requests'))
                <li>
                    <a href="{{ route('admin.blood-requests') }}" class="{{ request()->routeIs('admin.blood-requests*') ? 'active' : '' }}">
                        <i class="bi bi-heart-pulse-fill"></i> {{ __('ui.nav.blood_requests') }}
                    </a>
                </li>
            @endif

            @if($u->hasAnyPermission(['view_donations', 'manage_donations']))
                <li>
                    <a href="{{ route('admin.donation-histories') }}" class="{{ request()->routeIs('admin.donation-histories*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> {{ __('ui.nav.donation_history') }}
                    </a>
                </li>
            @endif

            @if($u->hasPermission('manage_donations'))
                <li>
                    <a href="{{ route('admin.claims') }}" class="{{ request()->routeIs('admin.claims*') ? 'active' : '' }}">
                        <i class="bi bi-award-fill"></i> {{ __('ui.certificate.donation_claims') }}
                        @php $pendingClaimsCount = \App\Models\DonationClaim::where('status','pending')->count(); @endphp
                        @if($pendingClaimsCount > 0)
                            <span class="badge bg-warning text-dark ms-auto">{{ $pendingClaimsCount }}</span>
                        @endif
                    </a>
                </li>
            @endif

            @if($u->isMainAdmin())
                <li class="nav-label">{{ __('ui.nav.access_control') }}</li>
                <li>
                    <a href="{{ route('admin.sub-admins') }}" class="{{ request()->routeIs('admin.sub-admins*') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i> {{ __('ui.nav.sub_admin') }}
                        @php $subAdminCount = \App\Models\User::subAdmins()->count(); @endphp
                        @if($subAdminCount > 0)
                            <span class="badge bg-info text-dark ms-auto">{{ $subAdminCount }}</span>
                        @endif
                    </a>
                </li>
            @endif

            @if($u->isSubAdmin())
                <li class="nav-label">{{ __('ui.nav.account') }}</li>
                <li>
                    <a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <i class="bi bi-person-fill"></i> {{ __('ui.nav.profile') }}
                    </a>
                </li>
            @endif

            @if($u->blood_group)
                <li class="nav-label">{{ __('ui.nav.as_donor') }}</li>
                <li>
                    <a href="{{ route('admin.my-blood-requests') }}" class="{{ request()->routeIs('admin.my-blood-requests*') ? 'active' : '' }}">
                        <i class="bi bi-heart-pulse-fill"></i> {{ __('ui.nav.my_blood_requests') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.my-donations') }}" class="{{ request()->routeIs('admin.my-donations*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> {{ __('ui.nav.my_donations') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.my-claims') }}" class="{{ request()->routeIs('admin.my-claims*') || request()->routeIs('admin.my-certificate*') ? 'active' : '' }}">
                        <i class="bi bi-award-fill"></i> {{ __('ui.nav.my_claims') }}
                    </a>
                </li>
            @endif

            <li class="nav-label">{{ __('ui.notifications.title') }}</li>
            <li>
                <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-bell-fill"></i> {{ __('ui.notifications.title') }}
                    @if($unreadCount > 0)
                        <span class="badge bg-danger ms-auto">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>
        @else
            {{-- Donor Navigation --}}
            <li class="nav-label">{{ __('ui.nav.main_menu') }}</li>
            <li>
                <a href="{{ route('donor.dashboard') }}" class="{{ request()->routeIs('donor.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> {{ __('ui.nav.dashboard') }}
                </a>
            </li>
            <li class="nav-label">{{ __('ui.nav.blood_donation') }}</li>
            <li>
                <a href="{{ route('donor.blood') }}" class="{{ request()->routeIs('donor.blood') ? 'active' : '' }}">
                    <i class="bi bi-droplet-half"></i> {{ __('ui.nav.donor_list') }}
                </a>
            </li>
            <li>
                <a href="{{ route('donor.donations') }}" class="{{ request()->routeIs('donor.donations') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> {{ __('ui.nav.donation_history') }}
                </a>
            </li>
            <li>
                <a href="{{ route('donor.claims') }}" class="{{ request()->routeIs('donor.claims*') || request()->routeIs('donor.certificate*') ? 'active' : '' }}">
                    <i class="bi bi-award-fill"></i> {{ __('ui.certificate.my_claims') }}
                    @php $myApproved = \App\Models\DonationClaim::where('user_id', auth()->id())->where('status','approved')->count(); @endphp
                    @if($myApproved > 0)
                        <span class="badge bg-success ms-auto">{{ $myApproved }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('donor.blood-requests') }}" class="{{ request()->routeIs('donor.blood-requests') ? 'active' : '' }}">
                    <i class="bi bi-heart-pulse-fill"></i> {{ __('ui.nav.blood_requests') }}
                </a>
            </li>
            <li class="nav-label">{{ __('ui.nav.account') }}</li>
            <li>
                <a href="{{ route('donor.profile') }}" class="{{ request()->routeIs('donor.profile') ? 'active' : '' }}">
                    <i class="bi bi-person-fill"></i> {{ __('ui.nav.profile') }}
                </a>
            </li>
            <li>
                <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-bell-fill"></i> {{ __('ui.notifications.title') }}
                    @if($unreadCount > 0)
                        <span class="badge bg-danger ms-auto">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>
        @endif
    </ul>

    {{-- Status Badge --}}
    @if(auth()->user()->role === 'donor')
        <div class="px-3 mt-3">
            <div class="p-3 rounded-3 text-center" style="background: rgba(255,255,255,0.08);">
                <small class="text-muted d-block mb-1">{{ __('ui.member.membership') }}</small>
                @if(auth()->user()->is_full_member)
                    <span class="badge bg-success px-3 py-2"><i class="bi bi-patch-check-fill me-1"></i> {{ __('ui.member.full') }}</span>
                @else
                    <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> {{ __('ui.member.temporary') }}</span>
                @endif
            </div>
        </div>
    @endif

    {{-- User Info Bottom --}}
    <div class="px-3 mt-3 mb-3">
        <div class="p-3 rounded-3 d-flex align-items-center gap-2" style="background: rgba(255,255,255,0.08);">
            <img src="{{ auth()->user()->profile_image_url }}"
                 alt=""
                 width="38"
                 height="38"
                 class="rounded-circle"
                 style="object-fit:cover; border:2px solid rgba(255,255,255,0.2); flex-shrink:0;">
            <div style="overflow:hidden;">
                <div class="fw-semibold text-white" style="font-size:0.82rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ auth()->user()->name }}
                </div>
                <small style="color:rgba(255,255,255,0.5); font-size:0.72rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">
                    {{ auth()->user()->email }}
                </small>
            </div>
        </div>
    </div>

</aside>

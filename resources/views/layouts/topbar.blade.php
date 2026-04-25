<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-outline-secondary sidebar-toggler d-md-none">
            <i class="bi bi-list"></i>
        </button>
        <span class="page-title">@yield('page-title', __('ui.nav.dashboard'))</span>
    </div>

    <div class="user-info">
        {{-- Language switcher --}}
        <div class="dropdown">
            <button class="topbar-btn" data-bs-toggle="dropdown" title="{{ __('ui.tooltips.change_language') }}" aria-label="{{ __('ui.tooltips.change_language') }}">
                <i class="bi bi-translate"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach($supportedLocales as $code => $meta)
                    <li>
                        <a class="dropdown-item {{ $appLocale === $code ? 'active' : '' }}"
                           href="{{ route('locale.switch', ['locale' => $code]) }}">
                            @if($appLocale === $code)
                                <i class="bi bi-check-lg me-2"></i>
                            @else
                                <span class="me-2" style="display:inline-block;width:1rem;"></span>
                            @endif
                            {{ $meta['native'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Theme toggle --}}
        @php $nextTheme = $appTheme === 'dark' ? 'light' : 'dark'; @endphp
        <a href="{{ route('theme.switch', ['theme' => $nextTheme]) }}"
           class="topbar-btn"
           title="{{ __('ui.tooltips.'.$nextTheme.'_mode') }}"
           aria-label="{{ __('ui.tooltips.'.$nextTheme.'_mode') }}">
            <i class="bi bi-{{ $appTheme === 'dark' ? 'sun' : 'moon-stars' }}"></i>
        </a>

        <div class="text-end d-none d-sm-block">
            <div class="fw-semibold" style="font-size:0.85rem;">{{ auth()->user()->name }}</div>
            <small class="text-muted">{{ auth()->user()->role === 'admin' ? __('ui.role.admin') : __('ui.role.donor') }}</small>
        </div>
        <img src="{{ auth()->user()->profile_image_url }}" alt="Avatar" class="user-avatar">
        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-chevron-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @if(auth()->user()->role === 'donor')
                    <li><a class="dropdown-item" href="{{ route('donor.profile') }}"><i class="bi bi-person me-2"></i>{{ __('ui.nav.profile') }}</a></li>
                    <li><hr class="dropdown-divider"></li>
                @elseif(auth()->user()->role === 'admin')
                    <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="bi bi-person me-2"></i>{{ __('ui.nav.profile') }}</a></li>
                    <li><hr class="dropdown-divider"></li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>{{ __('ui.auth.logout') }}</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

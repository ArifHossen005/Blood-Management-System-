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

        {{-- Notification Bell --}}
        @auth
        <div class="dropdown">
            <button class="topbar-btn position-relative" data-bs-toggle="dropdown" aria-label="{{ __('ui.notifications.title') }}" id="notifBellBtn">
                <i class="bi bi-bell"></i>
                @if($unreadCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          style="font-size:0.6rem;min-width:1.1rem;padding:0.2rem 0.35rem;" id="notifBadge">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @else
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none"
                          style="font-size:0.6rem;min-width:1.1rem;padding:0.2rem 0.35rem;" id="notifBadge">0</span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-sm p-0" style="width:340px;max-height:420px;overflow-y:auto;border-radius:0.75rem;">
                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                    <span class="fw-semibold" style="font-size:0.9rem;">{{ __('ui.notifications.title') }}</span>
                    @if($unreadCount > 0)
                        <form method="POST" action="{{ route('notifications.read-all') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link text-muted p-0 text-decoration-none" style="font-size:0.78rem;">
                                {{ __('ui.notifications.mark_all_read') }}
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Notification Items --}}
                @forelse($recentNotifications as $notif)
                    @php
                        $data = $notif->data;
                        $type = $data['type'] ?? '';
                        $isUnread = is_null($notif->read_at);
                        $color = $data['color'] ?? 'secondary';
                        $icon  = $data['icon']  ?? 'bi-bell';
                    @endphp
                    <div class="d-flex align-items-start gap-2 px-3 py-2 border-bottom notif-item {{ $isUnread ? 'bg-light' : '' }}"
                         style="{{ $isUnread ? 'background:var(--bs-light) !important;' : '' }}">
                        <div class="flex-shrink-0 mt-1">
                            <span class="text-{{ $color }}" style="font-size:1.1rem;"><i class="bi {{ $icon }}"></i></span>
                        </div>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="fw-semibold text-truncate" style="font-size:0.82rem;">
                                {{ __('ui.notifications.'.$type.'.title', [], app()->getLocale()) ?? __('ui.notifications.title') }}
                            </div>
                            <div class="text-muted" style="font-size:0.78rem;line-height:1.3;white-space:normal;">
                                @include('notifications._message', ['data' => $data, 'type' => $type])
                            </div>
                            <div class="text-muted mt-1" style="font-size:0.72rem;">{{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="flex-shrink-0 d-flex flex-column gap-1">
                            @if($isUnread)
                                <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-0 text-muted" title="Mark read" style="font-size:0.75rem;line-height:1;">
                                        <i class="bi bi-check2"></i>
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm p-0 text-muted" title="Delete" style="font-size:0.75rem;line-height:1;">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4 px-3">
                        <i class="bi bi-bell-slash" style="font-size:2rem;opacity:.4;"></i>
                        <p class="mt-2 mb-0" style="font-size:0.85rem;">{{ __('ui.notifications.none') }}</p>
                    </div>
                @endforelse

                {{-- Footer --}}
                <div class="text-center py-2 border-top">
                    <a href="{{ route('notifications.index') }}" class="text-danger text-decoration-none" style="font-size:0.82rem;">
                        {{ __('ui.notifications.view_all') }}
                    </a>
                </div>
            </div>
        </div>
        @endauth

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

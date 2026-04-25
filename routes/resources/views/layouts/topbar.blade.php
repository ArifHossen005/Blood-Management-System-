<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-outline-secondary sidebar-toggler d-md-none">
            <i class="bi bi-list"></i>
        </button>
        <span class="page-title">@yield('page-title', 'ড্যাশবোর্ড')</span>
    </div>

    <div class="user-info">
        <div class="text-end d-none d-sm-block">
            <div class="fw-semibold" style="font-size:0.85rem;">{{ auth()->user()->name }}</div>
            <small class="text-muted">{{ auth()->user()->role === 'admin' ? 'অ্যাডমিন' : 'ডোনার' }}</small>
        </div>
        <img src="{{ auth()->user()->profile_image_url }}" alt="Avatar" class="user-avatar">
        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-chevron-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @if(auth()->user()->role === 'donor')
                    <li><a class="dropdown-item" href="{{ route('donor.profile') }}"><i class="bi bi-person me-2"></i>প্রোফাইল</a></li>
                    <li><hr class="dropdown-divider"></li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>লগআউট</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

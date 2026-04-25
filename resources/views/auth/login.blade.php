<!DOCTYPE html>
<html lang="{{ $supportedLocales[$appLocale]['html_lang'] ?? $appLocale }}" data-theme="{{ $appTheme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.auth.login') }} - {{ __('ui.app.full_name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Noto Sans Bengali', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex; align-items: center; justify-content: center;
        }
        [data-theme="dark"] body {
            background: linear-gradient(135deg, #07080f 0%, #0b0d18 50%, #0a1c30 100%);
        }
        .auth-card {
            background: #fff; color: #212529;
            border-radius: 20px; overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 440px; width: 100%;
            position: relative;
        }
        [data-theme="dark"] .auth-card { background: #1a1d26; color: #e4e6eb; }
        .auth-header {
            background: linear-gradient(135deg, #DC143C, #8B0000);
            padding: 2.5rem 2rem; text-align: center; color: #fff;
            position: relative;
        }
        .auth-header .icon-circle {
            width: 70px; height: 70px; border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem; font-size: 2rem;
        }
        .auth-body { padding: 2rem; }
        .form-floating > .form-control { border-radius: 10px; border: 2px solid #e8e8e8; }
        .form-floating > .form-control:focus {
            border-color: #DC143C; box-shadow: 0 0 0 3px rgba(220,20,60,0.1);
        }
        [data-theme="dark"] .form-floating > .form-control,
        [data-theme="dark"] .form-floating > .form-control:focus {
            background-color: #252834; color: #e4e6eb; border-color: #3a3d48;
        }
        [data-theme="dark"] .form-floating > label { color: #8b90a0; }
        [data-theme="dark"] .text-muted { color: #8b90a0 !important; }
        [data-theme="dark"] .bg-light { background: #252834 !important; color: #e4e6eb; }
        .btn-blood {
            background: #DC143C; border: none; padding: 0.75rem;
            font-weight: 600; border-radius: 10px; font-size: 1rem;
        }
        .btn-blood:hover { background: #8B0000; }
        .auth-topbar {
            position: absolute; top: 12px; right: 12px;
            display: flex; gap: 8px; z-index: 2;
        }
        .auth-topbar a, .auth-topbar button {
            width: 32px; height: 32px; border-radius: 8px;
            background: rgba(255,255,255,0.15); color: #fff;
            border: none; display: inline-flex; align-items: center; justify-content: center;
            text-decoration: none; font-size: 0.85rem;
            transition: background 0.15s;
        }
        .auth-topbar a:hover, .auth-topbar button:hover { background: rgba(255,255,255,0.3); }
        .auth-topbar .dropdown-menu { min-width: 140px; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-topbar">
            {{-- Language switcher --}}
            <div class="dropdown">
                <button data-bs-toggle="dropdown" title="{{ __('ui.tooltips.change_language') }}" aria-label="{{ __('ui.tooltips.change_language') }}">
                    <i class="bi bi-translate"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @foreach($supportedLocales as $code => $meta)
                        <li>
                            <a class="dropdown-item {{ $appLocale === $code ? 'active' : '' }}"
                               href="{{ route('locale.switch', ['locale' => $code]) }}">
                                @if($appLocale === $code)<i class="bi bi-check-lg me-2"></i>@endif
                                {{ $meta['native'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            {{-- Theme toggle --}}
            @php $nextTheme = $appTheme === 'dark' ? 'light' : 'dark'; @endphp
            <a href="{{ route('theme.switch', ['theme' => $nextTheme]) }}"
               title="{{ __('ui.tooltips.'.$nextTheme.'_mode') }}"
               aria-label="{{ __('ui.tooltips.'.$nextTheme.'_mode') }}">
                <i class="bi bi-{{ $appTheme === 'dark' ? 'sun' : 'moon-stars' }}"></i>
            </a>
        </div>

        <div class="auth-header">
            <div class="icon-circle">
                <i class="bi bi-droplet-fill"></i>
            </div>
            <h3 class="fw-bold mb-1">{{ __('ui.app.name') }}</h3>
            <p class="mb-0 opacity-75">{{ __('ui.auth.login_subtitle') }}</p>
        </div>
        <div class="auth-body">
            @if($errors->any())
                <div class="alert alert-danger py-2 rounded-3">
                    @foreach($errors->all() as $error) <small>{{ $error }}</small><br> @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('ui.fields.email') }}" required>
                    <label for="email"><i class="bi bi-envelope me-1"></i> {{ __('ui.fields.email') }}</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('ui.fields.password') }}" required>
                    <label for="password"><i class="bi bi-lock me-1"></i> {{ __('ui.fields.password') }}</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">{{ __('ui.fields.remember_me') }}</label>
                </div>
                <button type="submit" class="btn btn-blood text-white w-100 mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('ui.auth.login_cta') }}
                </button>
            </form>
            <div class="text-center">
                <span class="text-muted">{{ __('ui.auth.no_account') }}</span>
                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:#DC143C;">{{ __('ui.auth.register_cta') }}</a>
            </div>

            <div class="mt-3 p-2 rounded-3 bg-light text-center" style="font-size:0.78rem;">
                <strong>Demo:</strong> admin@bloodbank.com / password
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

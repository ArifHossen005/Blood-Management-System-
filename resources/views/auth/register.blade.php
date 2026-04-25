<!DOCTYPE html>
<html lang="{{ $supportedLocales[$appLocale]['html_lang'] ?? $appLocale }}" data-theme="{{ $appTheme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.auth.register') }} - {{ __('ui.app.full_name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Noto Sans Bengali', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 0;
        }
        [data-theme="dark"] body {
            background: linear-gradient(135deg, #07080f 0%, #0b0d18 50%, #0a1c30 100%);
        }
        .auth-card {
            background: #fff; color: #212529;
            border-radius: 20px; overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 550px; width: 100%;
            position: relative;
        }
        [data-theme="dark"] .auth-card { background: #1a1d26; color: #e4e6eb; }
        .auth-header {
            background: linear-gradient(135deg, #DC143C, #8B0000);
            padding: 2rem; text-align: center; color: #fff;
            position: relative;
        }
        .auth-header .icon-circle {
            width: 60px; height: 60px; border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 0.75rem; font-size: 1.8rem;
        }
        .auth-body { padding: 2rem; }
        .form-control, .form-select { border-radius: 10px; border: 2px solid #e8e8e8; }
        .form-control:focus, .form-select:focus {
            border-color: #DC143C; box-shadow: 0 0 0 3px rgba(220,20,60,0.1);
        }
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select,
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background-color: #252834; color: #e4e6eb; border-color: #3a3d48;
        }
        [data-theme="dark"] .text-muted { color: #8b90a0 !important; }
        .btn-blood {
            background: #DC143C; border: none;
            padding: 0.75rem; font-weight: 600;
            border-radius: 10px; font-size: 1rem;
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
            @php $nextTheme = $appTheme === 'dark' ? 'light' : 'dark'; @endphp
            <a href="{{ route('theme.switch', ['theme' => $nextTheme]) }}"
               title="{{ __('ui.tooltips.'.$nextTheme.'_mode') }}"
               aria-label="{{ __('ui.tooltips.'.$nextTheme.'_mode') }}">
                <i class="bi bi-{{ $appTheme === 'dark' ? 'sun' : 'moon-stars' }}"></i>
            </a>
        </div>

        <div class="auth-header">
            <div class="icon-circle"><i class="bi bi-person-plus-fill"></i></div>
            <h3 class="fw-bold mb-1">{{ __('ui.auth.register_title') }}</h3>
            <p class="mb-0 opacity-75">{{ __('ui.auth.register_subtitle') }}</p>
        </div>
        <div class="auth-body">
            @if($errors->any())
                <div class="alert alert-danger py-2 rounded-3">
                    @foreach($errors->all() as $error) <small>{{ $error }}</small><br> @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('ui.fields.name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.email') }} <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.phone') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required placeholder="01XXXXXXXXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.blood_group') }} <span class="text-danger">*</span></label>
                        <select class="form-select" name="blood_group" required>
                            <option value="">{{ __('ui.common.select') }}</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.gender') }} <span class="text-danger">*</span></label>
                        <select class="form-select" name="gender" required>
                            <option value="">{{ __('ui.common.select') }}</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('ui.gender.male') }}</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('ui.gender.female') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.date_of_birth') }}</label>
                        <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.district') }}</label>
                        <input type="text" class="form-control" name="district" value="{{ old('district') }}" placeholder="{{ __('ui.fields.district_example') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('ui.fields.address') }}</label>
                        <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.password') }} <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" required minlength="6">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.confirm_password') }} <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-blood py-2 mb-0" style="background:#FFE4E8;border:1px solid rgba(220,20,60,0.2);color:#8B0000;border-radius:10px;">
                            <i class="bi bi-info-circle me-1"></i>
                            <small>{!! __('ui.auth.temp_notice_html') !!}</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-blood text-white w-100">
                            <i class="bi bi-person-check me-2"></i>{{ __('ui.auth.register_cta') }}
                        </button>
                    </div>
                </div>
            </form>
            <div class="text-center mt-3">
                <span class="text-muted">{{ __('ui.auth.have_account') }}</span>
                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color:#DC143C;">{{ __('ui.auth.login_cta') }}</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

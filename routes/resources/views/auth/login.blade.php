<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>লগইন - Blood Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Noto Sans Bengali', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 440px;
            width: 100%;
        }
        .auth-header {
            background: linear-gradient(135deg, #DC143C, #8B0000);
            padding: 2.5rem 2rem;
            text-align: center;
            color: #fff;
        }
        .auth-header .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
        }
        .auth-body { padding: 2rem; }
        .form-floating > .form-control {
            border-radius: 10px;
            border: 2px solid #e8e8e8;
        }
        .form-floating > .form-control:focus {
            border-color: #DC143C;
            box-shadow: 0 0 0 3px rgba(220,20,60,0.1);
        }
        .btn-blood {
            background: #DC143C;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 10px;
            font-size: 1rem;
        }
        .btn-blood:hover { background: #8B0000; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <div class="icon-circle">
                <i class="bi bi-droplet-fill"></i>
            </div>
            <h3 class="fw-bold mb-1">রক্ত ব্যবস্থাপনা সিস্টেম</h3>
            <p class="mb-0 opacity-75">আপনার অ্যাকাউন্টে লগইন করুন</p>
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
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="ইমেইল" required>
                    <label for="email"><i class="bi bi-envelope me-1"></i> ইমেইল</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="পাসওয়ার্ড" required>
                    <label for="password"><i class="bi bi-lock me-1"></i> পাসওয়ার্ড</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">আমাকে মনে রাখুন</label>
                </div>
                <button type="submit" class="btn btn-blood text-white w-100 mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>লগইন করুন
                </button>
            </form>
            <div class="text-center">
                <span class="text-muted">অ্যাকাউন্ট নেই?</span>
                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:#DC143C;">রেজিস্ট্রেশন করুন</a>
            </div>

            <div class="mt-3 p-2 rounded-3 bg-light text-center" style="font-size:0.78rem;">
                <strong>Demo:</strong> admin@bloodbank.com / password
            </div>
        </div>
    </div>
</body>
</html>

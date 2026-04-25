<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>রেজিস্ট্রেশন - Blood Management System</title>
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
            padding: 2rem 0;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 550px;
            width: 100%;
        }
        .auth-header {
            background: linear-gradient(135deg, #DC143C, #8B0000);
            padding: 2rem;
            text-align: center;
            color: #fff;
        }
        .auth-header .icon-circle {
            width: 60px; height: 60px; border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 0.75rem; font-size: 1.8rem;
        }
        .auth-body { padding: 2rem; }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e8e8e8;
        }
        .form-control:focus, .form-select:focus {
            border-color: #DC143C;
            box-shadow: 0 0 0 3px rgba(220,20,60,0.1);
        }
        .btn-blood {
            background: #DC143C; border: none;
            padding: 0.75rem; font-weight: 600;
            border-radius: 10px; font-size: 1rem;
        }
        .btn-blood:hover { background: #8B0000; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <div class="icon-circle"><i class="bi bi-person-plus-fill"></i></div>
            <h3 class="fw-bold mb-1">রক্তদাতা রেজিস্ট্রেশন</h3>
            <p class="mb-0 opacity-75">রক্তদানের সদস্য হতে তথ্য পূরণ করুন</p>
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
                        <label class="form-label fw-semibold">নাম <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ইমেইল <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ফোন <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required placeholder="01XXXXXXXXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">রক্তের গ্রুপ <span class="text-danger">*</span></label>
                        <select class="form-select" name="blood_group" required>
                            <option value="">নির্বাচন করুন</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">লিঙ্গ <span class="text-danger">*</span></label>
                        <select class="form-select" name="gender" required>
                            <option value="">নির্বাচন করুন</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>পুরুষ</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>মহিলা</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">জন্ম তারিখ</label>
                        <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">জেলা</label>
                        <input type="text" class="form-control" name="district" value="{{ old('district') }}" placeholder="যেমন: ঢাকা">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">ঠিকানা</label>
                        <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">পাসওয়ার্ড <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" required minlength="6">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">পাসওয়ার্ড নিশ্চিত <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-blood py-2 mb-0" style="background:#FFE4E8;border:1px solid rgba(220,20,60,0.2);color:#8B0000;border-radius:10px;">
                            <i class="bi bi-info-circle me-1"></i>
                            <small>রেজিস্ট্রেশনের পর আপনি <strong>অস্থায়ী সদস্য</strong> হবেন। অ্যাডমিন অনুমোদনের পর পূর্ণ সদস্যপদ পাবেন।</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-blood text-white w-100">
                            <i class="bi bi-person-check me-2"></i>রেজিস্ট্রেশন করুন
                        </button>
                    </div>
                </div>
            </form>
            <div class="text-center mt-3">
                <span class="text-muted">ইতিমধ্যে অ্যাকাউন্ট আছে?</span>
                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color:#DC143C;">লগইন করুন</a>
            </div>
        </div>
    </div>
</body>
</html>

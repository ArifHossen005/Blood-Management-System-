<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $claim->user->name }} donated blood! — Blood Management System</title>

{{-- Open Graph --}}
<meta property="og:type"        content="website">
<meta property="og:url"         content="{{ url()->current() }}">
<meta property="og:title"       content="{{ $claim->user->name }} donated {{ $claim->user->blood_group }} blood!">
<meta property="og:description" content="On {{ $claim->donation_date->format('d M Y') }}@if($claim->hospital_name) at {{ $claim->hospital_name }}@endif. Join the mission — donate blood, save lives!">
<meta property="og:image"       content="{{ route('certificate.og-image', $claim->certificate_number) }}">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name"   content="Blood Management System">

{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $claim->user->name }} donated {{ $claim->user->blood_group }} blood!">
<meta name="twitter:description" content="On {{ $claim->donation_date->format('d M Y') }}. Donate blood, save lives!">
<meta name="twitter:image"       content="{{ route('certificate.og-image', $claim->certificate_number) }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

<style>
* { font-family: 'Noto Sans Bengali', system-ui, sans-serif; }
body { background: linear-gradient(135deg, #fff5f7 0%, #fff 100%); min-height: 100vh; }
.hero { background: linear-gradient(135deg, #DC143C, #8B0000); color:#fff; padding:40px 0 30px; }
.cert-preview {
    background:#fff; border:8px solid #DC143C; border-radius:8px;
    padding:30px 40px; max-width:640px; margin:0 auto;
    box-shadow: 0 12px 40px rgba(220,20,60,.2);
    position:relative;
}
.cert-preview::after {
    content:''; position:absolute; top:6px; left:6px; right:6px; bottom:6px;
    border:1px solid #D4AF37; border-radius:2px; pointer-events:none;
}
.avatar-circle {
    width:90px; height:90px; border-radius:50%;
    border:3px solid #DC143C;
    box-shadow: 0 0 0 2px #D4AF37;
    object-fit:cover;
}
.blood-pill {
    background:#DC143C; color:#fff; padding:3px 16px;
    border-radius:20px; font-weight:800; font-size:1rem;
    display:inline-block;
}
.share-btn { border-radius:8px; font-weight:600; font-size:0.9rem; padding:10px 20px; }
.share-fb { background:#1877F2; color:#fff; border:none; }
.share-fb:hover { background:#1665d8; color:#fff; }
.share-wa { background:#25D366; color:#fff; border:none; }
.share-wa:hover { background:#1da851; color:#fff; }
.share-tw { background:#000; color:#fff; border:none; }
.share-tw:hover { background:#333; color:#fff; }
.share-li { background:#0A66C2; color:#fff; border:none; }
.share-li:hover { background:#084e96; color:#fff; }
.copy-btn { background:#6c757d; color:#fff; border:none; }
.copy-btn:hover { background:#5a6268; color:#fff; }
.bangla-msg { font-size:1.15rem; font-weight:700; color:#8B0000; }
</style>
</head>
<body>

{{-- Hero --}}
<div class="hero text-center">
    <div class="container">
        <i class="bi bi-droplet-fill" style="font-size:2.5rem; margin-bottom:10px; display:block;"></i>
        <h1 style="font-size:1.6rem; font-weight:800; margin-bottom:6px;">রক্তদান সনদপত্র</h1>
        <p style="opacity:.8; margin:0;">Blood Donation Certificate — Blood Management System</p>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- Certificate Preview --}}
            <div class="cert-preview mb-4">
                <div class="text-center mb-3">
                    <img src="{{ $claim->user->profile_image_url }}" alt="{{ $claim->user->name }}" class="avatar-circle mb-2">
                    <h4 class="fw-bold mb-1">{{ $claim->user->name }}</h4>
                    <span class="blood-pill">{{ $claim->user->blood_group }}</span>
                </div>

                <div class="text-center mb-3 bangla-msg">
                    এই মহৎ কাজের জন্য আন্তরিক ধন্যবাদ
                </div>

                <hr style="border-color:#D4AF37;">

                <div class="row text-center g-2">
                    <div class="col-4">
                        <div class="text-muted" style="font-size:.75rem;">Donation Date</div>
                        <div class="fw-semibold" style="font-size:.9rem;">{{ $claim->donation_date->format('d M Y') }}</div>
                    </div>
                    @if($claim->hospital_name)
                    <div class="col-4">
                        <div class="text-muted" style="font-size:.75rem;">Hospital</div>
                        <div class="fw-semibold" style="font-size:.9rem;">{{ $claim->hospital_name }}</div>
                    </div>
                    @endif
                    @if($claim->location)
                    <div class="col-4">
                        <div class="text-muted" style="font-size:.75rem;">Location</div>
                        <div class="fw-semibold" style="font-size:.9rem;">{{ $claim->location }}</div>
                    </div>
                    @endif
                </div>

                <hr style="border-color:#D4AF37;">
                <div class="text-center text-muted" style="font-size:.72rem;">
                    Certificate No: <strong style="color:#DC143C;">{{ $claim->certificate_number }}</strong>
                    &nbsp;·&nbsp; Issued {{ $claim->approved_at->format('d M Y') }}
                    &nbsp;·&nbsp;
                    <a href="{{ route('certificate.verify', $claim->certificate_number) }}" class="text-danger">Verify</a>
                </div>
            </div>

            {{-- Share Buttons --}}
            <h6 class="text-center fw-bold mb-3">Share this achievement</h6>
            @php
                $shareUrl   = urlencode(url()->current());
                $shareText  = urlencode($claim->user->name . ' donated ' . $claim->user->blood_group . ' blood! Join the mission — donate blood, save lives!');
            @endphp
            <div class="d-flex flex-wrap gap-2 justify-content-center mb-4">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                   target="_blank" class="btn share-btn share-fb">
                    <i class="bi bi-facebook me-1"></i> Facebook
                </a>
                <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}"
                   target="_blank" class="btn share-btn share-wa">
                    <i class="bi bi-whatsapp me-1"></i> WhatsApp
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}"
                   target="_blank" class="btn share-btn share-tw">
                    <i class="bi bi-twitter-x me-1"></i> Twitter/X
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"
                   target="_blank" class="btn share-btn share-li">
                    <i class="bi bi-linkedin me-1"></i> LinkedIn
                </a>
                <button onclick="copyLink()" class="btn share-btn copy-btn" id="copyBtn">
                    <i class="bi bi-link-45deg me-1"></i> Copy Link
                </button>
            </div>

            {{-- CTA --}}
            <div class="text-center p-4 rounded-3" style="background:linear-gradient(135deg,#fff5f7,#fff);border:2px solid #DC143C;">
                <i class="bi bi-heart-fill text-danger" style="font-size:2rem;"></i>
                <h5 class="fw-bold mt-2 mb-1">আমিও রক্ত দিতে চাই!</h5>
                <p class="text-muted mb-3" style="font-size:.9rem;">Join thousands of donors saving lives every day.</p>
                <a href="{{ route('register') }}" class="btn btn-danger px-4 fw-semibold">
                    <i class="bi bi-person-plus me-1"></i> Register as Donor
                </a>
            </div>

        </div>
    </div>
</div>

<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.innerHTML = '<i class="bi bi-check2 me-1"></i> Copied!';
        btn.style.background = '#28a745';
        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-link-45deg me-1"></i> Copy Link';
            btn.style.background = '';
        }, 2000);
    });
}
</script>
</body>
</html>

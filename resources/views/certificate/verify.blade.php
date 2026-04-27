<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Certificate Verification — Blood Management System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<style>
* { font-family: 'Noto Sans Bengali', system-ui, sans-serif; }
body { background: #f4f6f9; min-height:100vh; display:flex; align-items:center; }
.verify-card { max-width:520px; margin:40px auto; }
.valid-header { background:linear-gradient(135deg,#28a745,#155724); color:#fff; border-radius:12px 12px 0 0; padding:30px; text-align:center; }
.invalid-header { background:linear-gradient(135deg,#dc3545,#721c24); color:#fff; border-radius:12px 12px 0 0; padding:30px; text-align:center; }
.cert-body-card { background:#fff; border:none; border-radius:0 0 12px 12px; box-shadow:0 10px 30px rgba(0,0,0,.1); }
.info-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f0f0f0; font-size:.9rem; }
.info-row:last-child { border-bottom:none; }
.avatar-ring { width:80px;height:80px;border-radius:50%;border:3px solid #fff;box-shadow:0 0 0 2px #D4AF37;object-fit:cover; }
.cert-number-badge { background:rgba(255,255,255,.2); padding:5px 14px; border-radius:20px; font-family:monospace; font-size:.9rem; margin-top:8px; display:inline-block; }
</style>
</head>
<body>
<div class="container verify-card">
    @if($claim)
    {{-- Valid Certificate --}}
    <div>
        <div class="valid-header">
            <i class="bi bi-patch-check-fill" style="font-size:3rem; margin-bottom:10px; display:block;"></i>
            <h4 class="fw-bold mb-1">Certificate Verified ✓</h4>
            <p class="mb-2" style="opacity:.85;">This certificate is authentic and valid.</p>
            <div class="cert-number-badge">{{ $certificateNumber }}</div>
        </div>
        <div class="cert-body-card p-4">
            <div class="text-center mb-4">
                <img src="{{ $claim->user->profile_image_url }}" alt="{{ $claim->user->name }}" class="avatar-ring mb-2">
                <h5 class="fw-bold mb-1">{{ $claim->user->name }}</h5>
                <span class="badge bg-danger px-3 py-2" style="font-size:1rem; font-weight:800;">{{ $claim->user->blood_group }}</span>
            </div>

            <div class="px-2">
                <div class="info-row">
                    <span class="text-muted">Donation Date</span>
                    <span class="fw-semibold">{{ $claim->donation_date->format('d F, Y') }}</span>
                </div>
                @if($claim->hospital_name)
                <div class="info-row">
                    <span class="text-muted">Hospital</span>
                    <span class="fw-semibold">{{ $claim->hospital_name }}</span>
                </div>
                @endif
                @if($claim->location)
                <div class="info-row">
                    <span class="text-muted">Location</span>
                    <span class="fw-semibold">{{ $claim->location }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="text-muted">Certificate No</span>
                    <code>{{ $claim->certificate_number }}</code>
                </div>
                <div class="info-row">
                    <span class="text-muted">Issued On</span>
                    <span class="fw-semibold">{{ $claim->approved_at->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Verified By</span>
                    <span class="fw-semibold">{{ $claim->approver?->name ?? 'Administrator' }}</span>
                </div>
            </div>

            <div class="text-center mt-4 pt-3 border-top">
                <a href="{{ route('certificate.share', $claim->certificate_number) }}" class="btn btn-danger btn-sm me-2">
                    <i class="bi bi-share me-1"></i> Share
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-person-plus me-1"></i> Donate Blood
                </a>
            </div>
        </div>
    </div>
    @else
    {{-- Invalid Certificate --}}
    <div>
        <div class="invalid-header">
            <i class="bi bi-x-circle-fill" style="font-size:3rem; margin-bottom:10px; display:block;"></i>
            <h4 class="fw-bold mb-1">Certificate Not Found</h4>
            <p class="mb-2" style="opacity:.85;">This certificate number is invalid or does not exist.</p>
            <div class="cert-number-badge">{{ $certificateNumber }}</div>
        </div>
        <div class="cert-body-card p-4 text-center">
            <p class="text-muted mb-3">The certificate you are trying to verify could not be found in our system. Please check the certificate number and try again.</p>
            <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-house me-1"></i> Go to Homepage
            </a>
        </div>
    </div>
    @endif
</div>
</body>
</html>

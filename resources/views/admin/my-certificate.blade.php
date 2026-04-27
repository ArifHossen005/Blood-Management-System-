@extends('layouts.app')
@section('title', 'Certificate — ' . $claim->certificate_number)
@section('page-title', __('ui.certificate.donation_certificate'))

@push('styles')
<style>
.certificate-wrap { max-width: 860px; margin: 0 auto; }
.cert-frame {
    background: #fff;
    border: 14px solid #DC143C;
    border-radius: 6px;
    position: relative;
    box-shadow: 0 20px 60px rgba(220,20,60,0.18), 0 4px 20px rgba(0,0,0,0.12);
    overflow: hidden;
    color: #1a1a1a;
}
.cert-frame::after {
    content:'';
    position:absolute; top:8px; left:8px; right:8px; bottom:8px;
    border:1.5px solid #D4AF37;
    border-radius:2px;
    pointer-events:none;
    z-index:1;
}
.cert-corner { position:absolute; width:36px; height:36px; z-index:2; }
.cert-corner.tl { top:16px; left:16px; border-top:3px solid #D4AF37; border-left:3px solid #D4AF37; }
.cert-corner.tr { top:16px; right:16px; border-top:3px solid #D4AF37; border-right:3px solid #D4AF37; }
.cert-corner.bl { bottom:16px; left:16px; border-bottom:3px solid #D4AF37; border-left:3px solid #D4AF37; }
.cert-corner.br { bottom:16px; right:16px; border-bottom:3px solid #D4AF37; border-right:3px solid #D4AF37; }
.cert-header {
    background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
    padding: 28px 40px 22px;
    text-align: center;
    color: #fff;
    position: relative;
}
.cert-header .cert-app-name { font-size: 1.05rem; letter-spacing: 3px; text-transform: uppercase; opacity: .85; margin: 0 0 6px; font-weight: 600; }
.cert-header .cert-title { font-size: 1.8rem; font-weight: 800; letter-spacing: 4px; text-transform: uppercase; margin: 0 0 4px; }
.cert-header .cert-subtitle { font-size: 0.78rem; letter-spacing: 2px; opacity: .7; margin: 0; }
.cert-ribbon { height: 5px; background: linear-gradient(90deg, #8B0000, #D4AF37 30%, #fff 50%, #D4AF37 70%, #8B0000); }
.cert-body { padding: 32px 56px 24px; text-align: center; }
.cert-intro { font-style: italic; color: #666; font-size: 0.95rem; margin-bottom: 20px; }
.cert-avatar-ring { width: 110px; height: 110px; border-radius: 50%; border: 4px solid #DC143C; box-shadow: 0 0 0 3px #D4AF37, 0 6px 20px rgba(220,20,60,.3); overflow: hidden; margin: 0 auto 16px; background: #f0f0f0; }
.cert-avatar-ring img { width:100%; height:100%; object-fit:cover; }
.cert-donor-name { font-size: 2rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #1a1a1a; margin: 0 0 10px; }
.cert-blood-badge { display:inline-block; background:#DC143C; color:#fff; padding: 5px 20px; border-radius: 30px; font-weight:800; font-size:1.1rem; letter-spacing:1px; box-shadow: 0 3px 10px rgba(220,20,60,.35); margin-bottom: 20px; }
.cert-gold-line { height: 2px; background: linear-gradient(90deg, transparent, #D4AF37, transparent); margin: 20px auto; width: 60%; }
.cert-description { font-size: 1rem; color: #444; line-height: 2; }
.cert-description strong { color: #1a1a1a; }
.cert-bangla { font-size: 1.4rem; font-weight: 700; color: #8B0000; font-family: 'Noto Sans Bengali', sans-serif; margin: 18px 0 4px; }
.cert-thanks { font-size: 0.88rem; color: #777; font-style: italic; }
.cert-footer { background: #fafafa; border-top: 1px solid #f0e8e8; padding: 18px 40px; display: flex; justify-content: space-between; align-items: flex-end; gap: 20px; }
.cert-meta { font-size: 0.78rem; color: #888; line-height: 1.8; }
.cert-meta strong { color: #DC143C; font-size: 0.82rem; }
.cert-sig { text-align: center; }
.cert-sig-line { width: 160px; height: 1px; background: #333; margin: 0 auto 5px; }
.cert-sig p { font-size: 0.72rem; color: #666; margin: 0; }
.cert-qr { text-align: center; }
.cert-qr svg { display:block; margin:0 auto; }
.cert-qr p { font-size: 0.68rem; color: #aaa; margin: 4px 0 0; }
@media print {
    .cert-actions { display:none !important; }
    .sidebar,.topbar { display:none !important; }
    .main-content { margin-left:0 !important; }
    .content-area { padding:0 !important; }
    body { background:#fff !important; }
    .cert-frame { box-shadow:none !important; border-width:8px !important; }
}
[data-theme="dark"] .cert-frame { background: #fff !important; }
[data-theme="dark"] .cert-footer { background: #fafafa !important; }
</style>
@endpush

@section('content')
<div class="certificate-wrap">

    {{-- Action bar --}}
    <div class="d-flex flex-wrap gap-2 mb-3 cert-actions">
        <a href="{{ route('admin.my-certificate.download', $claim) }}" class="btn btn-blood btn-sm">
            <i class="bi bi-file-pdf me-1"></i>{{ __('ui.certificate.download_pdf') }}
        </a>
        <a href="{{ route('certificate.share', $claim->certificate_number) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-share me-1"></i>{{ __('ui.certificate.share') }}
        </a>
        <a href="{{ route('certificate.verify', $claim->certificate_number) }}" target="_blank" class="btn btn-sm btn-outline-success">
            <i class="bi bi-patch-check me-1"></i>{{ __('ui.certificate.verify') }}
        </a>
        <button onclick="window.print()" class="btn btn-sm btn-outline-dark">
            <i class="bi bi-printer me-1"></i>{{ __('ui.certificate.print') }}
        </button>
        <a href="{{ route('admin.my-claims') }}" class="btn btn-sm btn-link text-muted">
            <i class="bi bi-arrow-left me-1"></i>{{ __('ui.common.back') }}
        </a>
    </div>

    {{-- Certificate --}}
    <div class="cert-frame">
        <div class="cert-corner tl"></div>
        <div class="cert-corner tr"></div>
        <div class="cert-corner bl"></div>
        <div class="cert-corner br"></div>

        <div class="cert-header">
            <p class="cert-app-name"><i class="bi bi-droplet-fill me-2"></i>{{ __('ui.app.full_name') }}</p>
            <h1 class="cert-title">Certificate of Blood Donation</h1>
            <p class="cert-subtitle">রক্তদান সনদপত্র &nbsp;·&nbsp; OFFICIAL RECOGNITION</p>
        </div>
        <div class="cert-ribbon"></div>

        <div class="cert-body">
            <p class="cert-intro">This is to proudly certify that</p>

            <div class="cert-avatar-ring">
                <img src="{{ $claim->user->profile_image_url }}" alt="{{ $claim->user->name }}">
            </div>

            <h2 class="cert-donor-name">{{ $claim->user->name }}</h2>
            <div class="cert-blood-badge">{{ $claim->user->blood_group }}</div>

            <div class="cert-gold-line"></div>

            <p class="cert-description">
                has selflessly and voluntarily donated blood on<br>
                <strong>{{ $claim->donation_date->format('d F, Y') }}</strong>
                @if($claim->hospital_name)
                    &nbsp;at&nbsp; <strong>{{ $claim->hospital_name }}</strong>
                @endif
                @if($claim->location)
                    ,&nbsp; <strong>{{ $claim->location }}</strong>
                @endif
            </p>

            <div class="cert-gold-line"></div>

            <p class="cert-bangla">এই মহৎ কাজের জন্য আন্তরিক ধন্যবাদ ও অভিনন্দন জানাই</p>
            <p class="cert-thanks">For this noble act of humanity, we express our deepest gratitude and heartfelt appreciation.</p>
        </div>

        <div class="cert-footer">
            <div class="cert-meta">
                <div>{{ __('ui.certificate.certificate_no') }}: <strong>{{ $claim->certificate_number }}</strong></div>
                <div>{{ __('ui.certificate.issued_on') }}: {{ $claim->approved_at->format('d M Y') }}</div>
                <div>{{ __('ui.certificate.blood_group') }}: <strong>{{ $claim->user->blood_group }}</strong></div>
                <div class="mt-1" style="font-size:0.7rem;color:#bbb;">
                    Verify: {{ config('app.url') }}/verify/{{ $claim->certificate_number }}
                </div>
            </div>

            <div class="cert-sig">
                <div class="cert-sig-line"></div>
                <p class="fw-semibold">{{ $claim->approver?->name ?? 'Administrator' }}</p>
                <p>{{ __('ui.certificate.authorized_by') }}</p>
                <p>{{ __('ui.app.short') }}</p>
            </div>

            <div class="cert-qr">
                {!! $qrSvg !!}
                <p>{{ __('ui.certificate.scan_to_verify') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

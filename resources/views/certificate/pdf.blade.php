<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family: DejaVu Sans, sans-serif; }
body { background:#fff; width:297mm; }

.page {
    width: 100%; height: 210mm;
    border: 10px solid #DC143C;
    position: relative;
    background: #fff;
}

.inner-border {
    position: absolute; top: 7px; left: 7px; right: 7px; bottom: 7px;
    border: 1.5px solid #D4AF37;
}

/* Corners */
.corner { position:absolute; width:28px; height:28px; }
.tl { top:14px; left:14px; border-top:2px solid #D4AF37; border-left:2px solid #D4AF37; }
.tr { top:14px; right:14px; border-top:2px solid #D4AF37; border-right:2px solid #D4AF37; }
.bl { bottom:14px; left:14px; border-bottom:2px solid #D4AF37; border-left:2px solid #D4AF37; }
.br { bottom:14px; right:14px; border-bottom:2px solid #D4AF37; border-right:2px solid #D4AF37; }

/* Header */
.header {
    background: #DC143C;
    padding: 16px 30px 12px;
    text-align: center;
    color: #fff;
}
.header .app-name { font-size:9pt; letter-spacing:3px; text-transform:uppercase; opacity:.85; margin-bottom:4px; }
.header .cert-title { font-size:18pt; font-weight:bold; letter-spacing:3px; text-transform:uppercase; }
.header .cert-sub { font-size:7pt; letter-spacing:2px; opacity:.7; margin-top:3px; }

/* Gold ribbon */
.ribbon { height:4px; background: linear-gradient(90deg, #8B0000, #D4AF37 35%, #fff 50%, #D4AF37 65%, #8B0000); }

/* Body — two column table */
.body { padding: 18px 36px 12px; }

.main-table { width:100%; border-collapse:collapse; }
.main-table td { vertical-align:middle; }

.avatar-cell { width:110px; padding-right:20px; }
.avatar-cell img {
    width:100px; height:100px; border-radius:50%;
    border:3px solid #DC143C; object-fit:cover;
}
.avatar-placeholder {
    width:100px; height:100px; border-radius:50%;
    border:3px solid #DC143C; background:#f5f5f5;
    display:flex; align-items:center; justify-content:center;
    font-size:28pt; color:#DC143C;
}

.info-cell { text-align:left; }
.certify-text { font-size:9pt; font-style:italic; color:#666; margin-bottom:6px; }
.donor-name { font-size:20pt; font-weight:bold; text-transform:uppercase; color:#1a1a1a; letter-spacing:1px; }

.blood-badge {
    display:inline-block; background:#DC143C; color:#fff;
    padding:2px 14px; border-radius:20px; font-weight:bold;
    font-size:11pt; margin:6px 0 10px;
}

.detail-row { font-size:9pt; color:#555; margin-bottom:4px; }
.detail-row strong { color:#1a1a1a; }

.qr-cell { width:100px; text-align:center; padding-left:16px; }
.qr-cell img { width:90px; height:90px; }
.qr-label { font-size:6pt; color:#aaa; margin-top:3px; }

/* Gold divider */
.gold-line { height:1px; background: linear-gradient(90deg, transparent, #D4AF37, transparent); margin:12px 0; }

/* Thanks text */
.thanks-section { text-align:center; padding:0 36px; }
.thanks-en { font-size:8.5pt; font-style:italic; color:#666; }

/* Footer */
.footer {
    padding: 10px 36px;
    border-top: 1px solid #f5eded;
    background: #fafafa;
}
.footer-table { width:100%; border-collapse:collapse; }
.footer-table td { vertical-align:bottom; }
.cert-meta { font-size:7pt; color:#888; line-height:1.7; }
.cert-meta strong { color:#DC143C; font-size:8pt; }
.sig-cell { text-align:center; width:200px; }
.sig-line { width:160px; height:1px; background:#333; margin:0 auto 4px; }
.sig-name { font-size:7.5pt; font-weight:bold; color:#333; }
.sig-title { font-size:6.5pt; color:#888; }
</style>
</head>
<body>
<div class="page">
    <div class="inner-border"></div>
    <div class="corner tl"></div>
    <div class="corner tr"></div>
    <div class="corner bl"></div>
    <div class="corner br"></div>

    <!-- Header -->
    <div class="header">
        <div class="app-name">&#9632; Blood Management System &#9632;</div>
        <div class="cert-title">Certificate of Blood Donation</div>
        <div class="cert-sub">OFFICIAL RECOGNITION &nbsp;·&nbsp; BLOOD DONATION CERTIFICATE</div>
    </div>
    <div class="ribbon"></div>

    <!-- Body -->
    <div class="body">
        <table class="main-table">
            <tr>
                <td class="avatar-cell">
                    @if($avatarBase64)
                        <img src="{{ $avatarBase64 }}" alt="Donor">
                    @else
                        <div style="width:100px;height:100px;border-radius:50%;border:3px solid #DC143C;background:#f5f5f5;text-align:center;line-height:100px;font-size:28pt;color:#DC143C;">&#9829;</div>
                    @endif
                </td>
                <td class="info-cell">
                    <div class="certify-text">This is to proudly certify that</div>
                    <div class="donor-name">{{ $claim->user->name }}</div>
                    <div class="blood-badge">{{ $claim->user->blood_group }}</div>

                    <div class="detail-row">
                        <strong>Donation Date:</strong> {{ $claim->donation_date->format('d F, Y') }}
                    </div>
                    @if($claim->hospital_name)
                    <div class="detail-row">
                        <strong>Hospital:</strong> {{ $claim->hospital_name }}
                    </div>
                    @endif
                    @if($claim->location)
                    <div class="detail-row">
                        <strong>Location:</strong> {{ $claim->location }}
                    </div>
                    @endif
                </td>
                <td class="qr-cell">
                    <div style="width:90px;height:90px;">{!! $qrSvg !!}</div>
                    <div class="qr-label">Scan to Verify</div>
                </td>
            </tr>
        </table>

        <div class="gold-line"></div>

        <div class="thanks-section">
            <div class="thanks-en">
                has selflessly donated blood, contributing to the gift of life.<br>
                For this noble act of humanity, we express our deepest gratitude and heartfelt appreciation.
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>
                    <div class="cert-meta">
                        <div>Certificate No: <strong>{{ $claim->certificate_number }}</strong></div>
                        <div>Issued On: {{ $claim->approved_at->format('d M Y') }}</div>
                        <div>Verify: {{ config('app.url') }}/verify/{{ $claim->certificate_number }}</div>
                    </div>
                </td>
                <td class="sig-cell">
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $claim->approver?->name ?? 'Administrator' }}</div>
                    <div class="sig-title">Authorized Administrator</div>
                    <div class="sig-title">Blood Management System</div>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>

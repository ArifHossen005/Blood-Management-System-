<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
@page { margin: 0; size: 297mm 210mm; }
* { margin: 0; padding: 0; box-sizing: border-box; }
html, body {
    margin: 0; padding: 0;
    font-family: DejaVu Sans, sans-serif;
    background: #fff;
    width: 297mm;
}
table { border-collapse: collapse; }
</style>
</head>
<body>

{{-- Outer red frame + inner gold border via nested table --}}
<table width="100%" cellpadding="0" cellspacing="0"
       style="border: 10px solid #DC143C; background: #fff;">
<tr><td style="border: 2px solid #D4AF37; padding: 0; vertical-align: top;">

    {{-- ── Header ── --}}
    <table width="100%" cellpadding="0" cellspacing="0">
    <tr><td style="background:#9B0020; padding:10px 40px 8px; text-align:center; border-bottom:3px solid #8B0000;">
        <div style="font-size:7pt; letter-spacing:2px; text-transform:uppercase; color:#f5c0c8; font-weight:600; margin-bottom:3px;">&#9679; Blood Management System &#9679;</div>
        <div style="font-size:17pt; font-weight:bold; letter-spacing:3px; text-transform:uppercase; color:#fff; margin-bottom:3px;">Certificate of Blood Donation</div>
        <div style="font-size:6pt; letter-spacing:1.5px; color:#f8c8c8;">OFFICIAL RECOGNITION &nbsp;&middot;&nbsp; BLOOD DONATION CERTIFICATE</div>
    </td></tr>
    {{-- Gold ribbon --}}
    <tr><td style="height:4px; background:#D4AF37; font-size:0; line-height:0;">&nbsp;</td></tr>
    </table>

    {{-- ── Body ── --}}
    <div style="padding:10px 80px 6px; text-align:center;">

        <div style="font-style:italic; color:#666; font-size:8pt; margin-bottom:8px;">This is to proudly certify that</div>

        {{-- Avatar --}}
        @if($avatarBase64)
        <div style="width:78px; height:78px; border:3px solid #D4AF37; border-radius:39px; padding:2px; margin:0 auto 8px; background:#fff;">
            <img src="{{ $avatarBase64 }}" width="70" height="70"
                 style="border-radius:35px; border:2px solid #DC143C; display:block; object-fit:cover;">
        </div>
        @else
        <div style="width:78px; height:78px; border:3px solid #D4AF37; border-radius:39px; background:#f5f5f5; text-align:center; line-height:72px; font-size:22pt; color:#DC143C; margin:0 auto 8px;">&#9829;</div>
        @endif

        <div style="font-size:16pt; font-weight:bold; letter-spacing:2px; text-transform:uppercase; color:#1a1a1a; margin-bottom:5px;">{{ $claim->user->name }}</div>

        <div style="text-align:center; margin-bottom:6px;">
            <span style="background:#DC143C; color:#fff; padding:3px 20px; border-radius:20px; font-weight:bold; font-size:10.5pt; letter-spacing:1px;">{{ $claim->user->blood_group }}</span>
        </div>

        <div style="height:1px; background:#D4AF37; width:55%; margin:6px auto;"></div>

        <div style="font-size:8.5pt; color:#444; line-height:1.8; margin-bottom:3px;">
@php
    $parts = ['has selflessly and voluntarily donated blood on'];
    $parts[] = '<b style="color:#111;">' . $claim->donation_date->format('d F, Y') . '</b>';
    if ($claim->hospital_name) $parts[] = 'at <b style="color:#111;">' . e($claim->hospital_name) . '</b>';
    if ($claim->location)      $parts[] = '<b style="color:#111;">' . e($claim->location) . '</b>';
@endphp
{!! implode(' &nbsp; ', $parts) !!}
        </div>

        <div style="height:1px; background:#D4AF37; width:55%; margin:6px auto;"></div>

        <div style="font-size:7.5pt; color:#777; font-style:italic;">For this noble act of humanity, we express our deepest gratitude and heartfelt appreciation.</div>
    </div>

    {{-- ── Footer ── --}}
    <table width="100%" cellpadding="8" cellspacing="0"
           style="border-top:1px solid #ede4e4; background:#fafafa;">
    <tr>
        <td style="vertical-align:bottom; font-size:5.5pt; color:#888; line-height:1.9;">
            Certificate No: <b style="color:#DC143C; font-size:6pt;">{{ $claim->certificate_number }}</b><br>
            Issued On: {{ $claim->approved_at->format('d M Y') }}<br>
            Blood Group: <b style="color:#DC143C;">{{ $claim->user->blood_group }}</b><br>
            <span style="font-size:4.5pt; color:#bbb;">Verify: {{ config('app.url') }}/verify/{{ $claim->certificate_number }}</span>
        </td>
        <td style="vertical-align:bottom; text-align:center; width:170px;">
            <div style="width:130px; height:1px; background:#444; margin:0 auto 4px;"></div>
            <div style="font-size:7pt; font-weight:bold; color:#333;">{{ $claim->approver?->name ?? 'Administrator' }}</div>
            <div style="font-size:5.5pt; color:#888;">Authorized Administrator</div>
            <div style="font-size:5.5pt; color:#888;">Blood Management System</div>
        </td>
        <td style="vertical-align:bottom; text-align:center; width:95px;">
            <img src="{{ $qrBase64 }}" width="80" height="80" style="display:block; margin:0 auto;">
            <div style="font-size:5pt; color:#aaa; margin-top:3px;">Scan to Verify</div>
        </td>
    </tr>
    </table>

</td></tr>
</table>

</body>
</html>

<?php

namespace App\Http\Controllers;

use App\Models\DonationClaim;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateController extends Controller
{
    // ─── Donor: view certificate (within app) ──────────────────
    public function view(DonationClaim $claim)
    {
        abort_unless(Auth::id() === $claim->user_id && $claim->status === 'approved', 403);
        $claim->load('user', 'approver');

        $qrSvg = QrCode::format('svg')->size(120)->color(220, 20, 60)->generate(
            route('certificate.verify', $claim->certificate_number)
        );

        return view('donor.certificate', compact('claim', 'qrSvg'));
    }

    // ─── Donor: download PDF ────────────────────────────────────
    public function download(DonationClaim $claim)
    {
        abort_unless(Auth::id() === $claim->user_id && $claim->status === 'approved', 403);
        $claim->load('user', 'approver');

        // SVG as base64 data URI — works without Imagick; dompdf renders it via <img>
        $qrSvg = QrCode::format('svg')->size(80)->color(220, 20, 60)->generate(
            route('certificate.verify', $claim->certificate_number)
        );
        $svgClean = trim(preg_replace('/\<\?xml[^\?]*\?\>/', '', (string) $qrSvg));
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($svgClean);

        // Donor avatar as base64 for dompdf
        $avatarPath = public_path('uploads/profiles/' . $claim->user->profile_image);
        $avatarBase64 = null;
        if ($claim->user->profile_image && file_exists($avatarPath)) {
            $ext = pathinfo($avatarPath, PATHINFO_EXTENSION);
            $mime = match (strtolower($ext)) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png'         => 'image/png',
                default       => 'image/png',
            };
            $avatarBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($avatarPath));
        }

        $pdf = Pdf::loadView('certificate.pdf', compact('claim', 'qrBase64', 'avatarBase64'))
            ->setPaper('A4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('margin_top', 0)
            ->setOption('margin_right', 0)
            ->setOption('margin_bottom', 0)
            ->setOption('margin_left', 0)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('dpi', 96);

        return $pdf->download('certificate-' . $claim->certificate_number . '.pdf');
    }

    // ─── Public: share page (OG meta tags) ─────────────────────
    public function share(string $certificateNumber)
    {
        $claim = DonationClaim::where('certificate_number', $certificateNumber)
            ->where('status', 'approved')
            ->with('user')
            ->firstOrFail();

        return view('certificate.share', compact('claim'));
    }

    // ─── Public: verify certificate ────────────────────────────
    public function verify(string $certificateNumber)
    {
        $claim = DonationClaim::where('certificate_number', $certificateNumber)
            ->where('status', 'approved')
            ->with('user', 'approver')
            ->first();

        return view('certificate.verify', compact('claim', 'certificateNumber'));
    }

    // ─── Public: OG image for social sharing ───────────────────
    public function ogImage(string $certificateNumber)
    {
        $claim = DonationClaim::where('certificate_number', $certificateNumber)
            ->where('status', 'approved')
            ->with('user')
            ->firstOrFail();

        $w = 1200; $h = 630;
        $img = imagecreatetruecolor($w, $h);

        $red      = imagecolorallocate($img, 220, 20, 60);
        $darkRed  = imagecolorallocate($img, 139, 0, 0);
        $white    = imagecolorallocate($img, 255, 255, 255);
        $gold     = imagecolorallocate($img, 212, 175, 55);
        $lightPink= imagecolorallocate($img, 255, 240, 245);
        $gray     = imagecolorallocate($img, 100, 100, 100);

        // Background
        imagefill($img, 0, 0, $lightPink);

        // Header bar
        imagefilledrectangle($img, 0, 0, $w, 200, $red);

        // Gold accent bar
        imagefilledrectangle($img, 0, 198, $w, 206, $gold);

        // Border
        imagerectangle($img, 10, 10, $w - 10, $h - 10, $gold);
        imagerectangle($img, 14, 14, $w - 14, $h - 14, $gold);

        // Blood drop circle on left
        imagefilledellipse($img, 120, 100, 100, 100, $darkRed);
        imagestring($img, 5, 104, 88, 'B', $white);

        // App name
        imagestring($img, 5, 200, 60, 'BLOOD MANAGEMENT SYSTEM', $white);
        imagestring($img, 5, 200, 100, 'CERTIFICATE OF BLOOD DONATION', $gold);

        // Divider
        imageline($img, 80, 230, $w - 80, 230, $gold);

        // Donor name
        $name = mb_strtoupper($claim->user->name, 'UTF-8');
        imagestring($img, 5, 100, 265, 'DONOR:', $gray);
        imagestring($img, 5, 100, 295, $name, $darkRed);

        // Blood group badge
        imagefilledrectangle($img, 100, 340, 200, 375, $red);
        imagestring($img, 5, 120, 352, $claim->user->blood_group, $white);

        // Donation info
        imagestring($img, 4, 260, 265, 'Date: ' . $claim->donation_date->format('d M Y'), $gray);
        if ($claim->hospital_name) {
            imagestring($img, 4, 260, 295, 'Hospital: ' . mb_substr($claim->hospital_name, 0, 40, 'UTF-8'), $gray);
        }
        if ($claim->location) {
            imagestring($img, 4, 260, 325, 'Location: ' . mb_substr($claim->location, 0, 40, 'UTF-8'), $gray);
        }

        // Certificate number at bottom
        imagestring($img, 3, 100, 480, 'Certificate No: ' . $claim->certificate_number, $gray);
        imagestring($img, 3, 100, 510, 'Verify at: ' . config('app.url') . '/verify/' . $claim->certificate_number, $gray);

        // Divider bottom
        imageline($img, 80, 465, $w - 80, 465, $gold);

        ob_start();
        imagepng($img);
        $data = ob_get_clean();
        imagedestroy($img);

        return response($data, 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=86400');
    }
}

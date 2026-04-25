@extends('layouts.app')
@section('title', 'ডোনার ড্যাশবোর্ড')
@section('page-title', 'ড্যাশবোর্ড')

@section('content')
{{-- Membership Alert --}}
@if(auth()->user()->is_temporary)
    <div class="alert alert-blood d-flex align-items-center gap-3 mb-4">
        <div class="stat-icon bg-warning bg-opacity-25 text-warning" style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div>
            <strong>আপনি অস্থায়ী সদস্য</strong><br>
            <small>অ্যাডমিন অনুমোদনের পর আপনি পূর্ণ সদস্যপদ পাবেন এবং অন্য ডোনারদের সম্পূর্ণ তথ্য দেখতে পারবেন।</small>
        </div>
    </div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-droplet-fill"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['total_donations'] }}</div>
                    <div class="stat-label">মোট রক্তদান</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <div class="stat-value" style="font-size:1rem;">{{ $stats['last_donation'] }}</div>
                    <div class="stat-label">সর্বশেষ দান</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon {{ $stats['can_donate'] ? 'bg-success' : 'bg-warning' }} bg-opacity-10 {{ $stats['can_donate'] ? 'text-success' : 'text-warning' }}">
                    <i class="bi bi-{{ $stats['can_donate'] ? 'check-circle' : 'exclamation-circle' }}"></i>
                </div>
                <div>
                    <div class="stat-value" style="font-size:1rem;">{{ $stats['can_donate'] ? 'হ্যাঁ' : 'না' }}</div>
                    <div class="stat-label">দানের উপযুক্ত</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon {{ auth()->user()->is_full_member ? 'bg-success' : 'bg-warning' }} bg-opacity-10 {{ auth()->user()->is_full_member ? 'text-success' : 'text-warning' }}">
                    <i class="bi bi-{{ auth()->user()->is_full_member ? 'patch-check-fill' : 'hourglass-split' }}"></i>
                </div>
                <div>
                    <div class="stat-value" style="font-size:1rem;">{{ auth()->user()->is_full_member ? 'পূর্ণ সদস্য' : 'অস্থায়ী' }}</div>
                    <div class="stat-label">সদস্যপদ</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Quick Links --}}
    <div class="col-lg-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning-fill text-warning me-2"></i>দ্রুত লিঙ্ক</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('donor.blood') }}" class="btn btn-blood-outline btn-sm text-start">
                    <i class="bi bi-droplet-half me-2"></i>রক্তদাতা তালিকা দেখুন
                </a>
                <a href="{{ route('donor.donations') }}" class="btn btn-outline-success btn-sm text-start">
                    <i class="bi bi-plus-circle me-2"></i>নতুন দান রেকর্ড করুন
                </a>
                <a href="{{ route('donor.blood-requests') }}" class="btn btn-outline-info btn-sm text-start">
                    <i class="bi bi-heart-pulse me-2"></i>রক্তের অনুরোধ করুন
                </a>
                <a href="{{ route('donor.profile') }}" class="btn btn-outline-secondary btn-sm text-start">
                    <i class="bi bi-person me-2"></i>প্রোফাইল সম্পাদনা
                </a>
            </div>
        </div>

        {{-- My Profile Card --}}
        <div class="table-card p-4 mt-4 text-center">
            <img src="{{ auth()->user()->profile_image_url }}" alt="" width="80" height="80" class="rounded-circle mb-3" style="object-fit:cover;border:3px solid #FFE4E8;">
            <h6 class="fw-bold mb-1">{{ auth()->user()->name }}</h6>
            <span class="badge-blood mb-2">{{ auth()->user()->blood_group }}</span>
            <div class="text-muted" style="font-size:0.85rem;">
                <div><i class="bi bi-geo-alt me-1"></i>{{ auth()->user()->district ?? 'জেলা সেট করা হয়নি' }}</div>
                <div class="mt-1"><i class="bi bi-telephone me-1"></i>{{ auth()->user()->phone ?? 'ফোন সেট করা হয়নি' }}</div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="col-lg-8">
        {{-- My Donations --}}
        <div class="table-card mb-4">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="bi bi-droplet text-danger me-2"></i>সাম্প্রতিক দান</h6>
                <a href="{{ route('donor.donations') }}" class="btn btn-sm btn-blood-outline">সব দেখুন</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr><th>তারিখ</th><th>হাসপাতাল</th><th>ইউনিট</th><th>স্ট্যাটাস</th></tr>
                    </thead>
                    <tbody>
                        @forelse($myDonations as $d)
                            <tr>
                                <td>{{ $d->donation_date->format('d M, Y') }}</td>
                                <td>{{ $d->hospital_name ?? '—' }}</td>
                                <td>{{ $d->units }}</td>
                                <td>
                                    @if($d->status === 'verified') <span class="badge bg-success">যাচাইকৃত</span>
                                    @elseif($d->status === 'pending') <span class="badge bg-warning text-dark">অপেক্ষমান</span>
                                    @else <span class="badge bg-danger">বাতিল</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">এখনো কোনো দানের রেকর্ড নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- My Blood Requests --}}
        <div class="table-card">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="bi bi-heart-pulse text-info me-2"></i>আমার রক্তের অনুরোধ</h6>
                <a href="{{ route('donor.blood-requests') }}" class="btn btn-sm btn-blood-outline">সব দেখুন</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr><th>রোগী</th><th>গ্রুপ</th><th>হাসপাতাল</th><th>স্ট্যাটাস</th></tr>
                    </thead>
                    <tbody>
                        @forelse($myRequests as $r)
                            <tr>
                                <td>{{ $r->patient_name }}</td>
                                <td><span class="badge-blood">{{ $r->blood_group }}</span></td>
                                <td>{{ $r->hospital_name }}</td>
                                <td>
                                    @if($r->status === 'approved') <span class="badge bg-success">অনুমোদিত</span>
                                    @elseif($r->status === 'pending') <span class="badge bg-warning text-dark">অপেক্ষমান</span>
                                    @elseif($r->status === 'fulfilled') <span class="badge bg-primary">পূরণ</span>
                                    @else <span class="badge bg-secondary">বাতিল</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">কোনো অনুরোধ নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

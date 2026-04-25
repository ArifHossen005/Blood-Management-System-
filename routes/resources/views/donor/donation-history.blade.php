@extends('layouts.app')
@section('title', 'দানের ইতিহাস')
@section('page-title', 'আমার দানের ইতিহাস')

@section('content')
<div class="row g-4">
    {{-- Add Donation Form --}}
    <div class="col-lg-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle text-success me-2"></i>নতুন দান রেকর্ড</h6>
            <form method="POST" action="{{ route('donor.donations.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">দানের তারিখ <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="donation_date" value="{{ old('donation_date', date('Y-m-d')) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">হাসপাতালের নাম</label>
                    <input type="text" class="form-control" name="hospital_name" value="{{ old('hospital_name') }}" placeholder="যেমন: ঢাকা মেডিকেল">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">স্থান</label>
                    <input type="text" class="form-control" name="location" value="{{ old('location') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">প্রাপকের নাম</label>
                    <input type="text" class="form-control" name="recipient_name" value="{{ old('recipient_name') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">ইউনিট</label>
                    <input type="number" class="form-control" name="units" value="{{ old('units', 1) }}" min="1" max="3">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">নোট</label>
                    <textarea class="form-control" name="notes" rows="2">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="btn btn-blood w-100"><i class="bi bi-check-lg me-2"></i>জমা দিন</button>
                <small class="text-muted d-block mt-2 text-center">অ্যাডমিন যাচাইয়ের পর এটি আপনার রেকর্ডে যুক্ত হবে</small>
            </form>
        </div>
    </div>

    {{-- History Table --}}
    <div class="col-lg-8">
        <div class="table-card">
            <div class="p-3 border-bottom">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history text-info me-2"></i>দানের ইতিহাস</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>তারিখ</th>
                            <th>হাসপাতাল</th>
                            <th>প্রাপক</th>
                            <th>ইউনিট</th>
                            <th>স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $d)
                            <tr>
                                <td>{{ $loop->iteration + ($donations->currentPage() - 1) * $donations->perPage() }}</td>
                                <td>{{ $d->donation_date->format('d M, Y') }}</td>
                                <td>{{ $d->hospital_name ?? '—' }}</td>
                                <td>{{ $d->recipient_name ?? '—' }}</td>
                                <td>{{ $d->units }}</td>
                                <td>
                                    @if($d->status === 'verified') <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>যাচাইকৃত</span>
                                    @elseif($d->status === 'pending') <span class="badge bg-warning text-dark"><i class="bi bi-hourglass me-1"></i>অপেক্ষমান</span>
                                    @else <span class="badge bg-danger">বাতিল</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>এখনো কোনো দানের রেকর্ড নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($donations->hasPages())
                <div class="p-3 border-top">{{ $donations->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

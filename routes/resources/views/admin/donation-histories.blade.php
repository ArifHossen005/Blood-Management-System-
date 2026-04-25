@extends('layouts.app')
@section('title', 'দানের ইতিহাস')
@section('page-title', 'দানের ইতিহাস ব্যবস্থাপনা')

@section('content')
<div class="table-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <select class="form-select form-select-sm" name="status">
                <option value="">সব স্ট্যাটাস</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>অপেক্ষমান</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>যাচাইকৃত</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>বাতিল</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-blood btn-sm w-100"><i class="bi bi-funnel me-1"></i>ফিল্টার</button>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ডোনার</th>
                    <th>রক্তের গ্রুপ</th>
                    <th>তারিখ</th>
                    <th>হাসপাতাল</th>
                    <th>প্রাপক</th>
                    <th>ইউনিট</th>
                    <th>স্ট্যাটাস</th>
                    <th>কার্যক্রম</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $h)
                    <tr>
                        <td>{{ $loop->iteration + ($histories->currentPage() - 1) * $histories->perPage() }}</td>
                        <td class="fw-semibold">{{ $h->donor->name ?? '—' }}</td>
                        <td><span class="badge-blood">{{ $h->blood_group }}</span></td>
                        <td>{{ $h->donation_date->format('d M, Y') }}</td>
                        <td>{{ $h->hospital_name ?? '—' }}</td>
                        <td>{{ $h->recipient_name ?? '—' }}</td>
                        <td>{{ $h->units }}</td>
                        <td>
                            @switch($h->status)
                                @case('pending') <span class="badge bg-warning text-dark">অপেক্ষমান</span> @break
                                @case('verified') <span class="badge bg-success">যাচাইকৃত</span> @break
                                @case('rejected') <span class="badge bg-danger">বাতিল</span> @break
                            @endswitch
                        </td>
                        <td>
                            @if($h->status === 'pending')
                                <form method="POST" action="{{ route('admin.donation-histories.verify', $h) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-success" title="যাচাই করুন"><i class="bi bi-check-lg"></i> যাচাই</button>
                                </form>
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">কোনো দানের রেকর্ড পাওয়া যায়নি</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($histories->hasPages())
        <div class="p-3 border-top">{{ $histories->links() }}</div>
    @endif
</div>
@endsection

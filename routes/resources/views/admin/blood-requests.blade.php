@extends('layouts.app')
@section('title', 'রক্তের অনুরোধ')
@section('page-title', 'রক্তের অনুরোধ ব্যবস্থাপনা')

@section('content')
{{-- Filters --}}
<div class="table-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">স্ট্যাটাস</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">সব</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>অপেক্ষমান</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>অনুমোদিত</option>
                <option value="fulfilled" {{ request('status') == 'fulfilled' ? 'selected' : '' }}>পূরণ হয়েছে</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>বাতিল</option>
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
                    <th>অনুরোধকারী</th>
                    <th>রোগীর নাম</th>
                    <th>রক্তের গ্রুপ</th>
                    <th>ইউনিট</th>
                    <th>হাসপাতাল</th>
                    <th>জরুরিতা</th>
                    <th>তারিখ</th>
                    <th>স্ট্যাটাস</th>
                    <th>কার্যক্রম</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td>{{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                        <td class="fw-semibold">{{ $req->requester->name ?? '—' }}</td>
                        <td>{{ $req->patient_name }}</td>
                        <td><span class="badge-blood">{{ $req->blood_group }}</span></td>
                        <td>{{ $req->units_needed }}</td>
                        <td>{{ $req->hospital_name }}</td>
                        <td>
                            @switch($req->urgency)
                                @case('emergency') <span class="badge bg-danger">জরুরি</span> @break
                                @case('urgent') <span class="badge bg-warning text-dark">দ্রুত</span> @break
                                @default <span class="badge bg-info">সাধারণ</span>
                            @endswitch
                        </td>
                        <td>{{ $req->needed_date->format('d M, Y') }}</td>
                        <td>
                            @switch($req->status)
                                @case('pending') <span class="badge bg-warning text-dark">অপেক্ষমান</span> @break
                                @case('approved') <span class="badge bg-success">অনুমোদিত</span> @break
                                @case('fulfilled') <span class="badge bg-primary">পূরণ</span> @break
                                @case('cancelled') <span class="badge bg-secondary">বাতিল</span> @break
                            @endswitch
                        </td>
                        <td>
                            @if($req->status === 'pending')
                                <form method="POST" action="{{ route('admin.blood-requests.update', $req) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm btn-success" title="অনুমোদন"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <form method="POST" action="{{ route('admin.blood-requests.update', $req) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button class="btn btn-sm btn-danger" title="বাতিল"><i class="bi bi-x-lg"></i></button>
                                </form>
                            @endif
                            @if($req->status === 'approved')
                                <form method="POST" action="{{ route('admin.blood-requests.update', $req) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="fulfilled">
                                    <button class="btn btn-sm btn-primary" title="পূরণ হয়েছে"><i class="bi bi-check-all"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-center text-muted py-4">কোনো অনুরোধ পাওয়া যায়নি</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
        <div class="p-3 border-top">{{ $requests->links() }}</div>
    @endif
</div>
@endsection

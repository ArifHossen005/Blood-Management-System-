@extends('layouts.app')
@section('title', 'ডোনার তালিকা')
@section('page-title', 'ডোনার ব্যবস্থাপনা')

@section('content')
{{-- Filters --}}
<div class="table-card p-3 mb-4">
    <form method="GET" action="{{ route('admin.donors') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">অনুসন্ধান</label>
            <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="নাম, ইমেইল, ফোন...">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">স্ট্যাটাস</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">সব</option>
                <option value="temporary" {{ request('status') == 'temporary' ? 'selected' : '' }}>অস্থায়ী</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>অনুমোদিত</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>বাতিল</option>
                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>নিষিদ্ধ</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">রক্তের গ্রুপ</label>
            <select class="form-select form-select-sm" name="blood_group">
                <option value="">সব</option>
                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-blood btn-sm w-100"><i class="bi bi-search me-1"></i>ফিল্টার</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.donors') }}" class="btn btn-outline-secondary btn-sm w-100">রিসেট</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="fw-bold mb-0">মোট: {{ $donors->total() }} জন ডোনার</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>নাম</th>
                    <th>রক্তের গ্রুপ</th>
                    <th>ফোন</th>
                    <th>জেলা</th>
                    <th>মোট দান</th>
                    <th>স্ট্যাটাস</th>
                    <th>দৃশ্যমানতা</th>
                    <th>কার্যক্রম</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donors as $donor)
                    <tr>
                        <td>{{ $loop->iteration + ($donors->currentPage() - 1) * $donors->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $donor->profile_image_url }}" alt="" width="32" height="32" class="rounded-circle" style="object-fit:cover;">
                                <div>
                                    <div class="fw-semibold">{{ $donor->name }}</div>
                                    <small class="text-muted">{{ $donor->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge-blood">{{ $donor->blood_group }}</span></td>
                        <td>{{ $donor->phone ?? '—' }}</td>
                        <td>{{ $donor->district ?? '—' }}</td>
                        <td><span class="fw-bold">{{ $donor->total_donations }}</span></td>
                        <td>
                            @switch($donor->status)
                                @case('approved')
                                    <span class="badge bg-success">পূর্ণ সদস্য</span>
                                    @break
                                @case('temporary')
                                    <span class="badge bg-warning text-dark">অস্থায়ী</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger">বাতিল</span>
                                    @break
                                @case('banned')
                                    <span class="badge bg-dark">নিষিদ্ধ</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            {{-- Contact Visible Toggle --}}
                            <form method="POST" action="{{ route('admin.donors.toggle-contact', $donor) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $donor->contact_visible ? 'btn-outline-success' : 'btn-outline-secondary' }}" title="যোগাযোগ {{ $donor->contact_visible ? 'দৃশ্যমান' : 'গোপন' }}">
                                    <i class="bi bi-telephone{{ $donor->contact_visible ? '-fill' : '' }}"></i>
                                </button>
                            </form>
                            {{-- Address Visible Toggle --}}
                            <form method="POST" action="{{ route('admin.donors.toggle-address', $donor) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $donor->address_visible ? 'btn-outline-success' : 'btn-outline-secondary' }}" title="ঠিকানা {{ $donor->address_visible ? 'দৃশ্যমান' : 'গোপন' }}">
                                    <i class="bi bi-geo-alt{{ $donor->address_visible ? '-fill' : '' }}"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($donor->status === 'temporary')
                                    <form method="POST" action="{{ route('admin.donors.approve', $donor) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-success" title="অনুমোদন"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.donors.edit', $donor) }}" class="btn btn-sm btn-outline-primary" title="সম্পাদনা"><i class="bi bi-pencil"></i></a>
                                @if($donor->status !== 'banned')
                                    <form method="POST" action="{{ route('admin.donors.ban', $donor) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-dark" title="নিষিদ্ধ" onclick="return confirm('আপনি কি নিশ্চিত?')"><i class="bi bi-slash-circle"></i></button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.donors.delete', $donor) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="মুছুন" onclick="return confirm('এই ডোনার মুছে ফেলতে চান?')"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">কোনো ডোনার পাওয়া যায়নি</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($donors->hasPages())
        <div class="p-3 border-top">
            {{ $donors->links() }}
        </div>
    @endif
</div>
@endsection

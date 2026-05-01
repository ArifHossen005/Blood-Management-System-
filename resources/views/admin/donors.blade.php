@extends('layouts.app')
@section('title', __('ui.donors.page_title'))
@section('page-title', __('ui.donors.page_subtitle'))

@section('content')
{{-- Filters --}}
<div class="table-card p-3 mb-4">
    <form method="GET" action="{{ route('admin.donors') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">{{ __('ui.common.search') }}</label>
            <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="{{ __('ui.donors.search_placeholder') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">{{ __('ui.fields.status') }}</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">{{ __('ui.common.all') }}</option>
                <option value="temporary" {{ request('status') == 'temporary' ? 'selected' : '' }}>{{ __('ui.donor_status.temporary') }}</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('ui.donor_status.approved') }}</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('ui.donor_status.rejected') }}</option>
                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>{{ __('ui.donor_status.banned') }}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">{{ __('ui.fields.blood_group') }}</label>
            <select class="form-select form-select-sm" name="blood_group">
                <option value="">{{ __('ui.common.all') }}</option>
                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-blood btn-sm w-100"><i class="bi bi-search me-1"></i>{{ __('ui.common.filter') }}</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.donors') }}" class="btn btn-outline-secondary btn-sm w-100">{{ __('ui.common.reset') }}</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="fw-bold mb-0">{{ __('ui.donors.total_count', ['count' => $donors->total()]) }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('ui.fields.name') }}</th>
                    <th>{{ __('ui.fields.blood_group') }}</th>
                    <th>{{ __('ui.fields.phone') }}</th>
                    <th>{{ __('ui.fields.district') }}</th>
                    <th>{{ __('ui.donors.total_donations_col') }}</th>
                    <th>{{ __('ui.fields.status') }}</th>
                    <th>{{ __('ui.donors.visibility') }}</th>
                    <th>{{ __('ui.common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donors as $donor)
                    <tr>
                        <td>{{ $loop->iteration + ($donors->currentPage() - 1) * $donors->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.donors.edit', $donor) }}" class="text-decoration-none">
                                    <img src="{{ $donor->profile_image_url }}" alt="" width="32" height="32" class="rounded-circle" style="object-fit:cover;">
                                </a>
                                <div>
                                    <a href="{{ route('admin.donors.edit', $donor) }}" class="fw-semibold text-decoration-none" style="color:#DC143C;">{{ $donor->name }}</a>
                                    <br><small class="text-muted">{{ $donor->email }}</small>
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
                                    <span class="badge bg-success">{{ __('ui.donor_status.full_member') }}</span>
                                    @break
                                @case('temporary')
                                    <span class="badge bg-warning text-dark">{{ __('ui.donor_status.temporary') }}</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger">{{ __('ui.donor_status.rejected') }}</span>
                                    @break
                                @case('banned')
                                    <span class="badge bg-dark">{{ __('ui.donor_status.banned') }}</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            {{-- Contact Visible Toggle --}}
                            <form method="POST" action="{{ route('admin.donors.toggle-contact', $donor) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $donor->contact_visible ? 'btn-outline-success' : 'btn-outline-secondary' }}" title="{{ $donor->contact_visible ? __('ui.donors.contact_visible') : __('ui.donors.contact_hidden') }}">
                                    <i class="bi bi-telephone{{ $donor->contact_visible ? '-fill' : '' }}"></i>
                                </button>
                            </form>
                            {{-- Address Visible Toggle --}}
                            <form method="POST" action="{{ route('admin.donors.toggle-address', $donor) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $donor->address_visible ? 'btn-outline-success' : 'btn-outline-secondary' }}" title="{{ $donor->address_visible ? __('ui.donors.address_visible_on') : __('ui.donors.address_visible_off') }}">
                                    <i class="bi bi-geo-alt{{ $donor->address_visible ? '-fill' : '' }}"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($donor->status === 'temporary')
                                    <form method="POST" action="{{ route('admin.donors.approve', $donor) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-success" title="{{ __('ui.common.approve') }}"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.donors.edit', $donor) }}" class="btn btn-sm btn-outline-primary" title="{{ __('ui.common.edit') }}"><i class="bi bi-pencil"></i></a>
                                @if($donor->status !== 'banned')
                                    <form method="POST" action="{{ route('admin.donors.ban', $donor) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-dark" title="{{ __('ui.common.ban') }}" onclick="return confirm('{{ __('ui.donors.confirm_ban') }}')"><i class="bi bi-slash-circle"></i></button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.donors.delete', $donor) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="{{ __('ui.common.delete') }}" onclick="return confirm('{{ __('ui.donors.confirm_delete') }}')"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">{{ __('ui.donors.none_found') }}</td></tr>
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

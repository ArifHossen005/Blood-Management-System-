@extends('layouts.app')
@section('title', __('ui.blood_requests.page_title'))
@section('page-title', __('ui.blood_requests.page_subtitle_admin'))

@section('content')
{{-- Filters --}}
<div class="table-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">{{ __('ui.fields.status') }}</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">{{ __('ui.common.all') }}</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('ui.request_status.pending') }}</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('ui.request_status.approved') }}</option>
                <option value="fulfilled" {{ request('status') == 'fulfilled' ? 'selected' : '' }}>{{ __('ui.request_status.fulfilled') }}</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('ui.request_status.cancelled') }}</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-blood btn-sm w-100"><i class="bi bi-funnel me-1"></i>{{ __('ui.common.filter') }}</button>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('ui.blood_requests.requester') }}</th>
                    <th>{{ __('ui.fields.patient_name') }}</th>
                    <th>{{ __('ui.fields.blood_group') }}</th>
                    <th>{{ __('ui.common.unit') }}</th>
                    <th>{{ __('ui.columns.hospital') }}</th>
                    <th>{{ __('ui.fields.urgency') }}</th>
                    <th>{{ __('ui.common.date') }}</th>
                    <th>{{ __('ui.fields.status') }}</th>
                    <th>{{ __('ui.common.actions') }}</th>
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
                                @case('emergency') <span class="badge bg-danger">{{ __('ui.urgency.emergency') }}</span> @break
                                @case('urgent') <span class="badge bg-warning text-dark">{{ __('ui.urgency.urgent') }}</span> @break
                                @default <span class="badge bg-info">{{ __('ui.urgency.normal') }}</span>
                            @endswitch
                        </td>
                        <td>{{ $req->needed_date->format('d M, Y') }}</td>
                        <td>
                            @switch($req->status)
                                @case('pending') <span class="badge bg-warning text-dark">{{ __('ui.request_status.pending') }}</span> @break
                                @case('approved') <span class="badge bg-success">{{ __('ui.request_status.approved') }}</span> @break
                                @case('fulfilled') <span class="badge bg-primary">{{ __('ui.request_status.fulfilled_short') }}</span> @break
                                @case('cancelled') <span class="badge bg-secondary">{{ __('ui.request_status.cancelled') }}</span> @break
                            @endswitch
                        </td>
                        <td>
                            @if($req->status === 'pending')
                                <form method="POST" action="{{ route('admin.blood-requests.update', $req) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm btn-success" title="{{ __('ui.common.approve') }}"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <form method="POST" action="{{ route('admin.blood-requests.update', $req) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button class="btn btn-sm btn-danger" title="{{ __('ui.common.reject') }}"><i class="bi bi-x-lg"></i></button>
                                </form>
                            @endif
                            @if($req->status === 'approved')
                                <form method="POST" action="{{ route('admin.blood-requests.update', $req) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="fulfilled">
                                    <button class="btn btn-sm btn-primary" title="{{ __('ui.blood_requests.mark_fulfilled') }}"><i class="bi bi-check-all"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-center text-muted py-4">{{ __('ui.blood_requests.none_found_admin') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
        <div class="p-3 border-top">{{ $requests->links() }}</div>
    @endif
</div>
@endsection

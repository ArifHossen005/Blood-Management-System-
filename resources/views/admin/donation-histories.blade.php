@extends('layouts.app')
@section('title', __('ui.donation_history.page_title'))
@section('page-title', __('ui.donation_history.page_subtitle_admin'))

@section('content')
<div class="table-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <select class="form-select form-select-sm" name="status">
                <option value="">{{ __('ui.donation_history.all_statuses') }}</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('ui.donation_status.pending') }}</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>{{ __('ui.donation_status.verified') }}</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('ui.donation_status.rejected') }}</option>
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
                    <th>{{ __('ui.donation_history.donor_col') }}</th>
                    <th>{{ __('ui.fields.blood_group') }}</th>
                    <th>{{ __('ui.common.date') }}</th>
                    <th>{{ __('ui.columns.hospital') }}</th>
                    <th>{{ __('ui.fields.recipient') }}</th>
                    <th>{{ __('ui.common.unit') }}</th>
                    <th>{{ __('ui.fields.status') }}</th>
                    <th>{{ __('ui.common.actions') }}</th>
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
                                @case('pending') <span class="badge bg-warning text-dark">{{ __('ui.donation_status.pending') }}</span> @break
                                @case('verified') <span class="badge bg-success">{{ __('ui.donation_status.verified') }}</span> @break
                                @case('rejected') <span class="badge bg-danger">{{ __('ui.donation_status.rejected') }}</span> @break
                            @endswitch
                        </td>
                        <td>
                            @if($h->status === 'pending')
                                <form method="POST" action="{{ route('admin.donation-histories.verify', $h) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-success" title="{{ __('ui.donation_history.verify_cta') }}"><i class="bi bi-check-lg"></i> {{ __('ui.common.verify') }}</button>
                                </form>
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">{{ __('ui.donation_history.none_found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($histories->hasPages())
        <div class="p-3 border-top">{{ $histories->links() }}</div>
    @endif
</div>
@endsection

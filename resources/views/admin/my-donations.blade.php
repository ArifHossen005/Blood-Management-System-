@extends('layouts.app')
@section('title', __('ui.nav.my_donations'))
@section('page-title', __('ui.nav.my_donations'))

@section('content')
<div class="row g-4">
    {{-- Add Donation Form --}}
    <div class="col-lg-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle text-success me-2"></i>{{ __('ui.donation_history.add_new') }}</h6>
            <form method="POST" action="{{ route('admin.my-donations.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('ui.fields.donation_date') }} <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="donation_date" value="{{ old('donation_date', date('Y-m-d')) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('ui.fields.hospital_name') }}</label>
                    <input type="text" class="form-control" name="hospital_name" value="{{ old('hospital_name') }}" placeholder="{{ __('ui.fields.hospital_example') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('ui.fields.location') }}</label>
                    <input type="text" class="form-control" name="location" value="{{ old('location') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('ui.fields.recipient_name') }}</label>
                    <input type="text" class="form-control" name="recipient_name" value="{{ old('recipient_name') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('ui.common.unit') }}</label>
                    <input type="number" class="form-control" name="units" value="{{ old('units', 1) }}" min="1" max="3">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('ui.fields.notes') }}</label>
                    <textarea class="form-control" name="notes" rows="2">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="btn btn-blood w-100"><i class="bi bi-check-lg me-2"></i>{{ __('ui.common.submit') }}</button>
                <small class="text-muted d-block mt-2 text-center">{{ __('ui.donation_history.pending_admin_verify') }}</small>
            </form>
        </div>
    </div>

    {{-- History Table --}}
    <div class="col-lg-8">
        <div class="table-card">
            <div class="p-3 border-bottom">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history text-info me-2"></i>{{ __('ui.donation_history.list_title') }}</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('ui.common.date') }}</th>
                            <th>{{ __('ui.columns.hospital') }}</th>
                            <th>{{ __('ui.fields.recipient') }}</th>
                            <th>{{ __('ui.common.unit') }}</th>
                            <th>{{ __('ui.fields.status') }}</th>
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
                                    @if($d->status === 'verified') <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>{{ __('ui.donation_status.verified') }}</span>
                                    @elseif($d->status === 'pending') <span class="badge bg-warning text-dark"><i class="bi bi-hourglass me-1"></i>{{ __('ui.donation_status.pending') }}</span>
                                    @else <span class="badge bg-danger">{{ __('ui.donation_status.rejected') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>{{ __('ui.donation_history.none_yet') }}</td></tr>
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

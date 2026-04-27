@extends('layouts.app')
@section('title', __('ui.nav.my_claims'))
@section('page-title', __('ui.nav.my_claims'))

@section('content')

{{-- Submit new claim --}}
<div class="table-card p-4 mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle-fill text-danger me-2"></i>{{ __('ui.certificate.submit_new_claim') }}</h6>
    <form method="POST" action="{{ route('admin.my-claims.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold" style="font-size:0.82rem;">{{ __('ui.certificate.donation_date') }} <span class="text-danger">*</span></label>
                <input type="date" name="donation_date" class="form-control form-control-sm"
                       max="{{ today()->toDateString() }}" value="{{ old('donation_date') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold" style="font-size:0.82rem;">{{ __('ui.certificate.hospital') }}</label>
                <input type="text" name="hospital_name" class="form-control form-control-sm"
                       value="{{ old('hospital_name') }}" placeholder="{{ __('ui.certificate.hospital_placeholder') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold" style="font-size:0.82rem;">{{ __('ui.certificate.location') }}</label>
                <input type="text" name="location" class="form-control form-control-sm"
                       value="{{ old('location') }}" placeholder="{{ __('ui.certificate.location_placeholder') }}">
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold" style="font-size:0.82rem;">{{ __('ui.certificate.notes') }}</label>
                <input type="text" name="notes" class="form-control form-control-sm"
                       value="{{ old('notes') }}" placeholder="{{ __('ui.certificate.notes_placeholder') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-blood btn-sm w-100">
                    <i class="bi bi-send me-1"></i>{{ __('ui.certificate.submit_claim') }}
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Claims list --}}
<div class="table-card">
    <div class="p-3 border-bottom fw-semibold">{{ __('ui.certificate.my_claims') }}</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ __('ui.certificate.donation_date') }}</th>
                    <th>{{ __('ui.certificate.hospital') }}</th>
                    <th>{{ __('ui.certificate.location') }}</th>
                    <th>{{ __('ui.common.status') }}</th>
                    <th>{{ __('ui.certificate.certificate_no') }}</th>
                    <th>{{ __('ui.common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($claims as $claim)
                    <tr>
                        <td>{{ $claim->donation_date->format('d M Y') }}</td>
                        <td>{{ $claim->hospital_name ?? '—' }}</td>
                        <td>{{ $claim->location ?? '—' }}</td>
                        <td>
                            @if($claim->status === 'approved')
                                <span class="badge bg-success">{{ __('ui.certificate.approved') }}</span>
                            @elseif($claim->status === 'rejected')
                                <span class="badge bg-danger" title="{{ $claim->rejection_reason }}">{{ __('ui.certificate.rejected') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ __('ui.certificate.pending') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($claim->certificate_number)
                                <code style="font-size:0.78rem;">{{ $claim->certificate_number }}</code>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($claim->status === 'approved')
                                <a href="{{ route('admin.my-certificate', $claim) }}" class="btn btn-sm btn-blood">
                                    <i class="bi bi-award me-1"></i>{{ __('ui.certificate.view_certificate') }}
                                </a>
                                <a href="{{ route('admin.my-certificate.download', $claim) }}" class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="bi bi-download"></i>
                                </a>
                            @elseif($claim->status === 'rejected' && $claim->rejection_reason)
                                <small class="text-danger">{{ $claim->rejection_reason }}</small>
                            @else
                                <span class="text-muted" style="font-size:0.82rem;">{{ __('ui.certificate.awaiting_review') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-award" style="font-size:2rem;opacity:.3;"></i>
                            <p class="mt-2 mb-0">{{ __('ui.certificate.no_claims') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($claims->hasPages())
        <div class="p-3 border-top">{{ $claims->links() }}</div>
    @endif
</div>
@endsection

@extends('layouts.app')
@section('title', __('ui.certificate.donation_claims'))
@section('page-title', __('ui.certificate.donation_claims'))

@section('content')

{{-- Filter --}}
<div class="table-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:.82rem;">{{ __('ui.common.status') }}</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">{{ __('ui.common.all') }}</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>{{ __('ui.certificate.pending') }}</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('ui.certificate.approved') }}</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('ui.certificate.rejected') }}</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-blood btn-sm w-100"><i class="bi bi-search me-1"></i>{{ __('ui.common.search_cta') }}</button>
        </div>
        <div class="col-md-1">
            <a href="{{ route('admin.claims') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
        </div>
        @if($pendingCount > 0)
        <div class="col-md-auto ms-auto">
            <span class="badge bg-warning text-dark px-3 py-2" style="font-size:.85rem;">
                <i class="bi bi-clock me-1"></i>{{ $pendingCount }} {{ __('ui.certificate.pending') }}
            </span>
        </div>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ __('ui.fields.donor') }}</th>
                    <th>{{ __('ui.fields.blood_group') }}</th>
                    <th>{{ __('ui.certificate.donation_date') }}</th>
                    <th>{{ __('ui.certificate.hospital') }}</th>
                    <th>{{ __('ui.certificate.location') }}</th>
                    <th>{{ __('ui.common.status') }}</th>
                    <th>{{ __('ui.certificate.certificate_no') }}</th>
                    <th>{{ __('ui.common.submitted') }}</th>
                    <th>{{ __('ui.common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($claims as $claim)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $claim->user->profile_image_url }}" width="32" height="32"
                                 class="rounded-circle" style="object-fit:cover;border:1.5px solid #DC143C;">
                            <span class="fw-semibold" style="font-size:.88rem;">{{ $claim->user->name }}</span>
                        </div>
                    </td>
                    <td><span class="badge-blood badge">{{ $claim->user->blood_group }}</span></td>
                    <td>{{ $claim->donation_date->format('d M Y') }}</td>
                    <td>{{ $claim->hospital_name ?? '—' }}</td>
                    <td>{{ $claim->location ?? '—' }}</td>
                    <td>
                        @if($claim->status === 'approved')
                            <span class="badge bg-success">{{ __('ui.certificate.approved') }}</span>
                        @elseif($claim->status === 'rejected')
                            <span class="badge bg-danger">{{ __('ui.certificate.rejected') }}</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ __('ui.certificate.pending') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($claim->certificate_number)
                            <code style="font-size:.75rem;">{{ $claim->certificate_number }}</code>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td style="font-size:.82rem; color:var(--text-muted);">{{ $claim->created_at->diffForHumans() }}</td>
                    <td>
                        @if($claim->status === 'pending')
                            {{-- Approve --}}
                            <form method="POST" action="{{ route('admin.claims.approve', $claim) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success"
                                        onclick="return confirm('{{ __('ui.certificate.confirm_approve') }}')">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            {{-- Reject --}}
                            <button class="btn btn-sm btn-danger ms-1" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal{{ $claim->id }}">
                                <i class="bi bi-x-lg"></i>
                            </button>

                            {{-- Reject Modal --}}
                            <div class="modal fade" id="rejectModal{{ $claim->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.claims.reject', $claim) }}">
                                            @csrf @method('PATCH')
                                            <div class="modal-header">
                                                <h6 class="modal-title">{{ __('ui.certificate.reject_claim') }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label fw-semibold" style="font-size:.85rem;">{{ __('ui.certificate.rejection_reason') }}</label>
                                                <input type="text" name="rejection_reason" class="form-control"
                                                       placeholder="{{ __('ui.certificate.rejection_reason_placeholder') }}">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __('ui.common.cancel') }}</button>
                                                <button type="submit" class="btn btn-sm btn-danger">{{ __('ui.certificate.reject_claim') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @elseif($claim->status === 'approved')
                            <a href="{{ route('certificate.verify', $claim->certificate_number) }}" target="_blank"
                               class="btn btn-sm btn-outline-success">
                                <i class="bi bi-award me-1"></i>{{ __('ui.certificate.view_certificate') }}
                            </a>
                        @elseif($claim->status === 'rejected' && $claim->rejection_reason)
                            <small class="text-muted">{{ $claim->rejection_reason }}</small>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
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

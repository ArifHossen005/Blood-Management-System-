@extends('layouts.app')
@section('title', __('ui.blood_requests.page_title'))
@section('page-title', __('ui.blood_requests.page_title'))

@section('content')
<div class="row g-4">
    {{-- Create Request Form --}}
    <div class="col-lg-5">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-heart-pulse text-danger me-2"></i>{{ __('ui.blood_requests.new') }}</h6>
            <form method="POST" action="{{ route('donor.blood-requests.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('ui.fields.patient_name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="patient_name" value="{{ old('patient_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.blood_group') }} <span class="text-danger">*</span></label>
                        <select class="form-select" name="blood_group" required>
                            <option value="">{{ __('ui.common.select') }}</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.units_needed') }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="units_needed" value="{{ old('units_needed', 1) }}" min="1" max="10" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('ui.fields.hospital_name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="hospital_name" value="{{ old('hospital_name') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('ui.fields.hospital_address') }}</label>
                        <input type="text" class="form-control" name="hospital_address" value="{{ old('hospital_address') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.contact_number') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="contact_number" value="{{ old('contact_number') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.needed_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="needed_date" value="{{ old('needed_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('ui.fields.urgency') }} <span class="text-danger">*</span></label>
                        <select class="form-select" name="urgency" required>
                            <option value="normal" {{ old('urgency') == 'normal' ? 'selected' : '' }}>{{ __('ui.urgency.normal') }}</option>
                            <option value="urgent" {{ old('urgency') == 'urgent' ? 'selected' : '' }}>{{ __('ui.urgency.urgent') }}</option>
                            <option value="emergency" {{ old('urgency') == 'emergency' ? 'selected' : '' }}>{{ __('ui.urgency.emergency') }}</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('ui.fields.reason') }}</label>
                        <textarea class="form-control" name="reason" rows="2">{{ old('reason') }}</textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-blood w-100"><i class="bi bi-send me-2"></i>{{ __('ui.blood_requests.submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- My Requests --}}
    <div class="col-lg-7">
        <div class="table-card">
            <div class="p-3 border-bottom">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-check text-info me-2"></i>{{ __('ui.blood_requests.my_list') }}</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('ui.columns.patient') }}</th>
                            <th>{{ __('ui.columns.group') }}</th>
                            <th>{{ __('ui.columns.hospital') }}</th>
                            <th>{{ __('ui.fields.urgency') }}</th>
                            <th>{{ __('ui.common.date') }}</th>
                            <th>{{ __('ui.fields.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $r)
                            <tr>
                                <td>{{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                                <td class="fw-semibold">{{ $r->patient_name }}</td>
                                <td><span class="badge-blood">{{ $r->blood_group }}</span></td>
                                <td>{{ $r->hospital_name }}</td>
                                <td>
                                    @switch($r->urgency)
                                        @case('emergency') <span class="badge bg-danger">{{ __('ui.urgency.emergency') }}</span> @break
                                        @case('urgent') <span class="badge bg-warning text-dark">{{ __('ui.urgency.urgent') }}</span> @break
                                        @default <span class="badge bg-info">{{ __('ui.urgency.normal') }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $r->needed_date->format('d M, Y') }}</td>
                                <td>
                                    @switch($r->status)
                                        @case('pending') <span class="badge bg-warning text-dark">{{ __('ui.request_status.pending') }}</span> @break
                                        @case('approved') <span class="badge bg-success">{{ __('ui.request_status.approved') }}</span> @break
                                        @case('fulfilled') <span class="badge bg-primary">{{ __('ui.request_status.fulfilled_short') }}</span> @break
                                        @case('cancelled') <span class="badge bg-secondary">{{ __('ui.request_status.cancelled') }}</span> @break
                                    @endswitch
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>{{ __('ui.blood_requests.none_donor') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
                <div class="p-3 border-top">{{ $requests->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

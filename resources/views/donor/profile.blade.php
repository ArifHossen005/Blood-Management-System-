@extends('layouts.app')
@section('title', __('ui.profile.page_title'))
@section('page-title', __('ui.profile.subtitle'))

@section('content')
<div class="row g-4">
    {{-- Profile Edit --}}
    <div class="col-lg-8">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-lines-fill text-danger me-2"></i>{{ __('ui.profile.edit_info') }}</h6>
            <form method="POST" action="{{ route('donor.profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12 text-center mb-3">
                        <img src="{{ $user->profile_image_url }}" alt="" width="100" height="100" class="rounded-circle mb-2" style="object-fit:cover;border:4px solid #FFE4E8;" id="previewImg">
                        <div>
                            <label class="btn btn-sm btn-blood-outline mt-1">
                                <i class="bi bi-camera me-1"></i>{{ __('ui.common.change_photo') }}
                                <input type="file" name="profile_image" class="d-none" accept="image/*" onchange="document.getElementById('previewImg').src=window.URL.createObjectURL(this.files[0])">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.name') }}</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.email') }} <small class="text-muted">({{ __('ui.common.not_editable') }})</small></label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.phone') }}</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.blood_group') }} <small class="text-muted">({{ __('ui.common.not_editable') }})</small></label>
                        <input type="text" class="form-control bg-light" value="{{ $user->blood_group }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('ui.fields.date_of_birth') }}</label>
                        <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('ui.fields.gender') }}</label>
                        <select class="form-select" name="gender">
                            <option value="">{{ __('ui.common.select') }}</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('ui.gender.male') }}</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('ui.gender.female') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('ui.fields.weight') }}</label>
                        <input type="number" step="0.1" class="form-control" name="weight" value="{{ old('weight', $user->weight) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('ui.fields.address') }} <small class="text-muted">(Village / Road / House)</small></label>
                        <input type="text" class="form-control" name="address" value="{{ old('address', $user->address) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('ui.fields.district') }}</label>
                        <select class="form-select" id="profile_district" name="district">
                            <option value="">-- Select District --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Upazila / Area</label>
                        <select class="form-select" id="profile_upazila" name="city">
                            <option value="">-- Select Upazila --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('ui.fields.division') }}</label>
                        <input type="text" class="form-control bg-light" id="profile_division" name="division" value="{{ old('division', $user->division) }}" readonly placeholder="Auto-filled">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.emergency_contact') }}</label>
                        <input type="text" class="form-control" name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-block">{{ __('ui.edit_donor.available_label') }}</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_available" value="1" {{ old('is_available', $user->is_available) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ __('ui.profile.wants_to_donate') }}</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('ui.fields.health_notes') }}</label>
                        <textarea class="form-control" name="health_notes" rows="2">{{ old('health_notes', $user->health_notes) }}</textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-blood"><i class="bi bi-check-lg me-2"></i>{{ __('ui.profile.update_cta') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Sidebar Info & Password --}}
    <div class="col-lg-4">
        {{-- Account Info --}}
        <div class="table-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-info me-2"></i>{{ __('ui.profile.account_info') }}</h6>
            <div class="mb-2">
                <small class="text-muted">{{ __('ui.member.membership') }}</small>
                <div>
                    @if($user->is_full_member)
                        <span class="badge bg-success"><i class="bi bi-patch-check-fill me-1"></i>{{ __('ui.member.full') }}</span>
                    @else
                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>{{ __('ui.member.temporary') }}</span>
                    @endif
                </div>
            </div>
            <div class="mb-2">
                <small class="text-muted">{{ __('ui.profile.total_donations') }}</small>
                <div class="fw-bold">{{ $user->total_donations }} {{ __('ui.common.times') }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">{{ __('ui.profile.last_donation') }}</small>
                <div class="fw-semibold">{{ $user->last_donation_date?->format('d M, Y') ?? __('ui.dashboard.not_donated_yet') }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">{{ __('ui.profile.eligibility') }}</small>
                <div>
                    @if(!$user->gender)
                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ __('ui.profile.add_gender_hint') }}</span>
                    @elseif($user->isEligibleToDonate())
                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>{{ __('ui.profile.can_donate_now') }}</span>
                    @else
                        <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i>{{ __('ui.profile.eligible_on', ['date' => $user->eligibleDate()->format('d M, Y')]) }}</span>
                    @endif
                </div>
            </div>
            <div class="mb-2">
                <small class="text-muted">{{ __('ui.common.joined') }}</small>
                <div>{{ $user->created_at->format('d M, Y') }}</div>
            </div>
            @if($user->approved_at)
                <div>
                    <small class="text-muted">{{ __('ui.profile.approved_on') }}</small>
                    <div>{{ $user->approved_at->format('d M, Y') }}</div>
                </div>
            @endif
        </div>

        {{-- Change Password --}}
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-lock text-danger me-2"></i>{{ __('ui.profile.change_password_title') }}</h6>
            <form method="POST" action="{{ route('donor.password.change') }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('ui.fields.current_password') }}</label>
                    <input type="password" class="form-control" name="current_password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('ui.fields.new_password') }}</label>
                    <input type="password" class="form-control" name="password" required minlength="6">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('ui.fields.confirm_new_password') }}</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-blood btn-sm w-100"><i class="bi bi-shield-lock me-2"></i>{{ __('ui.profile.change_password_cta') }}</button>
            </form>
        </div>
    </div>
</div>
@push('scripts')
@include('partials.bd-districts-data')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initDistrictDropdown({
        districtId: 'profile_district',
        upazilaId:  'profile_upazila',
        divisionId: 'profile_division',
        currentDistrict: '{{ old('district', $user->district) }}',
        currentUpazila:  '{{ old('city', $user->city) }}'
    });
});
</script>
@endpush
@endsection

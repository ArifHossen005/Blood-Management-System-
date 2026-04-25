@extends('layouts.app')
@section('title', __('ui.edit_donor.page_title'))
@section('page-title', __('ui.edit_donor.page_subtitle', ['name' => $user->name]))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="table-card p-4">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <img src="{{ $user->profile_image_url }}" alt="" width="60" height="60" class="rounded-circle" style="object-fit:cover;">
                <div>
                    <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                    <span class="badge-blood">{{ $user->blood_group }}</span>
                    <small class="text-muted ms-2">{{ __('ui.common.joined') }}: {{ $user->created_at->format('d M, Y') }}</small>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.donors.update', $user) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.name') }}</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.email') }}</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('ui.fields.phone') }}</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('ui.fields.blood_group') }}</label>
                        <select class="form-select" name="blood_group" required>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $user->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('ui.fields.gender') }}</label>
                        <select class="form-select" name="gender">
                            <option value="">{{ __('ui.common.select') }}</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('ui.gender.male') }}</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('ui.gender.female') }}</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('ui.fields.address') }}</label>
                        <input type="text" class="form-control" name="address" value="{{ old('address', $user->address) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.district') }}</label>
                        <input type="text" class="form-control" name="district" value="{{ old('district', $user->district) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.division') }}</label>
                        <input type="text" class="form-control" name="division" value="{{ old('division', $user->division) }}">
                    </div>

                    {{-- Admin Controls --}}
                    <div class="col-12"><hr><h6 class="fw-bold text-danger"><i class="bi bi-shield-lock me-2"></i>{{ __('ui.edit_donor.admin_controls') }}</h6></div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('ui.fields.status') }}</label>
                        <select class="form-select" name="status" required>
                            <option value="temporary" {{ old('status', $user->status) == 'temporary' ? 'selected' : '' }}>{{ __('ui.donor_status.temporary') }}</option>
                            <option value="approved" {{ old('status', $user->status) == 'approved' ? 'selected' : '' }}>{{ __('ui.edit_donor.approved_full_member') }}</option>
                            <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>{{ __('ui.donor_status.rejected') }}</option>
                            <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>{{ __('ui.donor_status.banned') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold d-block">{{ __('ui.edit_donor.contact_visible_label') }}</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="contact_visible" value="1"
                                {{ old('contact_visible', $user->contact_visible) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ __('ui.edit_donor.contact_visible_hint') }}</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold d-block">{{ __('ui.edit_donor.address_visible_label') }}</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="address_visible" value="1"
                                {{ old('address_visible', $user->address_visible) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ __('ui.edit_donor.address_visible_hint') }}</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold d-block">{{ __('ui.edit_donor.available_label') }}</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_available" value="1"
                                {{ old('is_available', $user->is_available) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ __('ui.edit_donor.available_hint') }}</label>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-blood"><i class="bi bi-check-lg me-2"></i>{{ __('ui.common.update') }}</button>
                        <a href="{{ route('admin.donors') }}" class="btn btn-outline-secondary">{{ __('ui.common.cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

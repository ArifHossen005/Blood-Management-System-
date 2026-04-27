@extends('layouts.app')
@section('title', __('ui.profile.page_title'))
@section('page-title', __('ui.profile.subtitle'))

@section('content')
@php $roleLabel = $user->isMainAdmin() ? __('ui.role.admin') : ($user->isSubAdmin() ? __('ui.role.sub_admin') : __('ui.role.donor')); @endphp
<div class="row g-4">
    {{-- Profile Edit --}}
    <div class="col-lg-8">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-lines-fill text-danger me-2"></i>{{ __('ui.profile.edit_info') }}</h6>
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
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
                        <label class="form-label fw-semibold">{{ __('ui.fields.phone_full') }}</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('ui.fields.role') }} <small class="text-muted">({{ __('ui.common.not_editable') }})</small></label>
                        <input type="text" class="form-control bg-light" value="{{ $roleLabel }}" disabled>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-blood"><i class="bi bi-check-lg me-2"></i>{{ __('ui.profile.update_cta') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Donor Profile Section --}}
    <div class="col-12">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-1"><i class="bi bi-droplet-fill text-danger me-2"></i>{{ __('ui.profile.donor_profile') }}</h6>
            <p class="text-muted mb-3" style="font-size:.82rem;">{{ __('ui.profile.donor_profile_hint') }}</p>
            <form method="POST" action="{{ route('admin.donor-profile.update') }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">{{ __('ui.fields.blood_group') }}</label>
                        <select class="form-select" name="blood_group">
                            <option value="">{{ __('ui.common.select') }}</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ $user->blood_group === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">{{ __('ui.fields.gender') }}</label>
                        <select class="form-select" name="gender">
                            <option value="">{{ __('ui.common.select') }}</option>
                            <option value="male"   {{ $user->gender === 'male'   ? 'selected' : '' }}>{{ __('ui.gender.male') }}</option>
                            <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>{{ __('ui.gender.female') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">{{ __('ui.fields.date_of_birth') }}</label>
                        <input type="date" class="form-control" name="date_of_birth"
                               value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">{{ __('ui.fields.weight') }}</label>
                        <input type="number" class="form-control" name="weight" min="30" max="200"
                               value="{{ old('weight', $user->weight) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('ui.fields.district') }}</label>
                        <input type="text" class="form-control" name="district"
                               value="{{ old('district', $user->district) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('ui.fields.division') }}</label>
                        <input type="text" class="form-control" name="division"
                               value="{{ old('division', $user->division) }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch ps-0 w-100">
                            <label class="form-label fw-semibold d-block">{{ __('ui.profile.availability') }}</label>
                            <input class="form-check-input ms-0" type="checkbox" name="is_available"
                                   value="1" {{ $user->is_available ? 'checked' : '' }} style="width:2.5rem;height:1.3rem;">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('ui.fields.health_notes') }}</label>
                        <textarea class="form-control" name="health_notes" rows="2">{{ old('health_notes', $user->health_notes) }}</textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-blood btn-sm"><i class="bi bi-droplet me-2"></i>{{ __('ui.profile.update_cta') }}</button>
                        @if($user->blood_group)
                            <span class="ms-3 badge badge-blood px-3 py-2">{{ $user->blood_group }}</span>
                            @if($user->is_available)
                                <span class="badge bg-success ms-1"><i class="bi bi-check-circle me-1"></i>{{ __('ui.profile.availability') }}</span>
                            @endif
                        @endif
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
                <small class="text-muted">{{ __('ui.fields.role') }}</small>
                <div>
                    <span class="badge bg-danger"><i class="bi bi-shield-fill-check me-1"></i>{{ $roleLabel }}</span>
                </div>
            </div>
            <div class="mb-2">
                <small class="text-muted">{{ __('ui.fields.email') }}</small>
                <div class="fw-semibold">{{ $user->email }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">{{ __('ui.common.joined') }}</small>
                <div>{{ $user->created_at->format('d M, Y') }}</div>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-lock text-danger me-2"></i>{{ __('ui.profile.change_password_title') }}</h6>
            <form method="POST" action="{{ route('admin.password.change') }}">
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
@endsection

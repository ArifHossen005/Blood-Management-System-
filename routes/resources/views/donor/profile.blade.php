@extends('layouts.app')
@section('title', 'প্রোফাইল')
@section('page-title', 'আমার প্রোফাইল')

@section('content')
<div class="row g-4">
    {{-- Profile Edit --}}
    <div class="col-lg-8">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-lines-fill text-danger me-2"></i>প্রোফাইল তথ্য সম্পাদনা</h6>
            <form method="POST" action="{{ route('donor.profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12 text-center mb-3">
                        <img src="{{ $user->profile_image_url }}" alt="" width="100" height="100" class="rounded-circle mb-2" style="object-fit:cover;border:4px solid #FFE4E8;" id="previewImg">
                        <div>
                            <label class="btn btn-sm btn-blood-outline mt-1">
                                <i class="bi bi-camera me-1"></i>ছবি পরিবর্তন
                                <input type="file" name="profile_image" class="d-none" accept="image/*" onchange="document.getElementById('previewImg').src=window.URL.createObjectURL(this.files[0])">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">নাম</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ইমেইল <small class="text-muted">(পরিবর্তনযোগ্য নয়)</small></label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ফোন</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">রক্তের গ্রুপ <small class="text-muted">(পরিবর্তনযোগ্য নয়)</small></label>
                        <input type="text" class="form-control bg-light" value="{{ $user->blood_group }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">জন্ম তারিখ</label>
                        <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">লিঙ্গ</label>
                        <select class="form-select" name="gender">
                            <option value="">নির্বাচন করুন</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>পুরুষ</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>মহিলা</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>অন্যান্য</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ওজন (কেজি)</label>
                        <input type="number" step="0.1" class="form-control" name="weight" value="{{ old('weight', $user->weight) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">ঠিকানা</label>
                        <input type="text" class="form-control" name="address" value="{{ old('address', $user->address) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">শহর</label>
                        <input type="text" class="form-control" name="city" value="{{ old('city', $user->city) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">জেলা</label>
                        <input type="text" class="form-control" name="district" value="{{ old('district', $user->district) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">বিভাগ</label>
                        <input type="text" class="form-control" name="division" value="{{ old('division', $user->division) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">জরুরি যোগাযোগ</label>
                        <input type="text" class="form-control" name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-block">দানে উপলব্ধ</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_available" value="1" {{ old('is_available', $user->is_available) ? 'checked' : '' }}>
                            <label class="form-check-label">আমি এখন রক্তদানে ইচ্ছুক</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">স্বাস্থ্য সংক্রান্ত নোট</label>
                        <textarea class="form-control" name="health_notes" rows="2">{{ old('health_notes', $user->health_notes) }}</textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-blood"><i class="bi bi-check-lg me-2"></i>প্রোফাইল আপডেট</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Sidebar Info & Password --}}
    <div class="col-lg-4">
        {{-- Account Info --}}
        <div class="table-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-info me-2"></i>অ্যাকাউন্ট তথ্য</h6>
            <div class="mb-2">
                <small class="text-muted">সদস্যপদ</small>
                <div>
                    @if($user->is_full_member)
                        <span class="badge bg-success"><i class="bi bi-patch-check-fill me-1"></i>পূর্ণ সদস্য</span>
                    @else
                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>অস্থায়ী</span>
                    @endif
                </div>
            </div>
            <div class="mb-2">
                <small class="text-muted">মোট রক্তদান</small>
                <div class="fw-bold">{{ $user->total_donations }} বার</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">সর্বশেষ দান</small>
                <div class="fw-semibold">{{ $user->last_donation_date?->format('d M, Y') ?? 'এখনো দান করেননি' }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">যোগদান</small>
                <div>{{ $user->created_at->format('d M, Y') }}</div>
            </div>
            @if($user->approved_at)
                <div>
                    <small class="text-muted">অনুমোদন</small>
                    <div>{{ $user->approved_at->format('d M, Y') }}</div>
                </div>
            @endif
        </div>

        {{-- Change Password --}}
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-lock text-danger me-2"></i>পাসওয়ার্ড পরিবর্তন</h6>
            <form method="POST" action="{{ route('donor.password.change') }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">বর্তমান পাসওয়ার্ড</label>
                    <input type="password" class="form-control" name="current_password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">নতুন পাসওয়ার্ড</label>
                    <input type="password" class="form-control" name="password" required minlength="6">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">নতুন পাসওয়ার্ড নিশ্চিত</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-blood btn-sm w-100"><i class="bi bi-shield-lock me-2"></i>পাসওয়ার্ড পরিবর্তন</button>
            </form>
        </div>
    </div>
</div>
@endsection

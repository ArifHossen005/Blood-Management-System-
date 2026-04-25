@extends('layouts.app')
@section('title', 'ডোনার সম্পাদনা')
@section('page-title', 'ডোনার সম্পাদনা: ' . $user->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="table-card p-4">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <img src="{{ $user->profile_image_url }}" alt="" width="60" height="60" class="rounded-circle" style="object-fit:cover;">
                <div>
                    <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                    <span class="badge-blood">{{ $user->blood_group }}</span>
                    <small class="text-muted ms-2">যোগদান: {{ $user->created_at->format('d M, Y') }}</small>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.donors.update', $user) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">নাম</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ইমেইল</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ফোন</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">রক্তের গ্রুপ</label>
                        <select class="form-select" name="blood_group" required>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $user->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
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
                    <div class="col-12">
                        <label class="form-label fw-semibold">ঠিকানা</label>
                        <input type="text" class="form-control" name="address" value="{{ old('address', $user->address) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">জেলা</label>
                        <input type="text" class="form-control" name="district" value="{{ old('district', $user->district) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">বিভাগ</label>
                        <input type="text" class="form-control" name="division" value="{{ old('division', $user->division) }}">
                    </div>

                    {{-- Admin Controls --}}
                    <div class="col-12"><hr><h6 class="fw-bold text-danger"><i class="bi bi-shield-lock me-2"></i>অ্যাডমিন নিয়ন্ত্রণ</h6></div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">স্ট্যাটাস</label>
                        <select class="form-select" name="status" required>
                            <option value="temporary" {{ old('status', $user->status) == 'temporary' ? 'selected' : '' }}>অস্থায়ী</option>
                            <option value="approved" {{ old('status', $user->status) == 'approved' ? 'selected' : '' }}>অনুমোদিত (পূর্ণ সদস্য)</option>
                            <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>বাতিল</option>
                            <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>নিষিদ্ধ</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold d-block">যোগাযোগ দৃশ্যমান</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="contact_visible" value="1"
                                {{ old('contact_visible', $user->contact_visible) ? 'checked' : '' }}>
                            <label class="form-check-label">অন্য সদস্যরা ফোন দেখতে পারবে</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold d-block">ঠিকানা দৃশ্যমান</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="address_visible" value="1"
                                {{ old('address_visible', $user->address_visible) ? 'checked' : '' }}>
                            <label class="form-check-label">অন্য সদস্যরা ঠিকানা দেখতে পারবে</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold d-block">দানে উপলব্ধ</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_available" value="1"
                                {{ old('is_available', $user->is_available) ? 'checked' : '' }}>
                            <label class="form-check-label">রক্তদানে ইচ্ছুক</label>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-blood"><i class="bi bi-check-lg me-2"></i>আপডেট করুন</button>
                        <a href="{{ route('admin.donors') }}" class="btn btn-outline-secondary">বাতিল</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

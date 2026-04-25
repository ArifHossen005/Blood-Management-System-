@extends('layouts.app')
@section('title', 'রক্তের অনুরোধ')
@section('page-title', 'রক্তের অনুরোধ')

@section('content')
<div class="row g-4">
    {{-- Create Request Form --}}
    <div class="col-lg-5">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-heart-pulse text-danger me-2"></i>নতুন রক্তের অনুরোধ</h6>
            <form method="POST" action="{{ route('donor.blood-requests.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">রোগীর নাম <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="patient_name" value="{{ old('patient_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">রক্তের গ্রুপ <span class="text-danger">*</span></label>
                        <select class="form-select" name="blood_group" required>
                            <option value="">নির্বাচন করুন</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ইউনিট প্রয়োজন <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="units_needed" value="{{ old('units_needed', 1) }}" min="1" max="10" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">হাসপাতালের নাম <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="hospital_name" value="{{ old('hospital_name') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">হাসপাতালের ঠিকানা</label>
                        <input type="text" class="form-control" name="hospital_address" value="{{ old('hospital_address') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">যোগাযোগ নম্বর <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="contact_number" value="{{ old('contact_number') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">প্রয়োজনের তারিখ <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="needed_date" value="{{ old('needed_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">জরুরিতা <span class="text-danger">*</span></label>
                        <select class="form-select" name="urgency" required>
                            <option value="normal" {{ old('urgency') == 'normal' ? 'selected' : '' }}>সাধারণ</option>
                            <option value="urgent" {{ old('urgency') == 'urgent' ? 'selected' : '' }}>দ্রুত</option>
                            <option value="emergency" {{ old('urgency') == 'emergency' ? 'selected' : '' }}>জরুরি</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">কারণ / বিবরণ</label>
                        <textarea class="form-control" name="reason" rows="2">{{ old('reason') }}</textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-blood w-100"><i class="bi bi-send me-2"></i>অনুরোধ জমা দিন</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- My Requests --}}
    <div class="col-lg-7">
        <div class="table-card">
            <div class="p-3 border-bottom">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-check text-info me-2"></i>আমার অনুরোধ সমূহ</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>রোগী</th>
                            <th>গ্রুপ</th>
                            <th>হাসপাতাল</th>
                            <th>জরুরিতা</th>
                            <th>তারিখ</th>
                            <th>স্ট্যাটাস</th>
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
                                        @case('emergency') <span class="badge bg-danger">জরুরি</span> @break
                                        @case('urgent') <span class="badge bg-warning text-dark">দ্রুত</span> @break
                                        @default <span class="badge bg-info">সাধারণ</span>
                                    @endswitch
                                </td>
                                <td>{{ $r->needed_date->format('d M, Y') }}</td>
                                <td>
                                    @switch($r->status)
                                        @case('pending') <span class="badge bg-warning text-dark">অপেক্ষমান</span> @break
                                        @case('approved') <span class="badge bg-success">অনুমোদিত</span> @break
                                        @case('fulfilled') <span class="badge bg-primary">পূরণ</span> @break
                                        @case('cancelled') <span class="badge bg-secondary">বাতিল</span> @break
                                    @endswitch
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>এখনো কোনো অনুরোধ নেই</td></tr>
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

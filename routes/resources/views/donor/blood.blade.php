@extends('layouts.app')
@section('title', 'রক্তদাতা তালিকা')
@section('page-title', 'রক্তদাতা তালিকা (Blood Page)')

@section('content')
{{-- Temporary member notice --}}
@if($currentUser->is_temporary)
    <div class="alert alert-blood mb-4">
        <i class="bi bi-info-circle me-2"></i>
        <strong>আপনি অস্থায়ী সদস্য।</strong> আপনি ডোনারদের সীমিত তথ্য দেখতে পারবেন। পূর্ণ সদস্য হলে যোগাযোগ ও বিস্তারিত তথ্য দেখতে পারবেন।
    </div>
@endif

{{-- Filters --}}
<div class="table-card p-3 mb-4">
    <form method="GET" action="{{ route('donor.blood') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">নাম অনুসন্ধান</label>
            <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="ডোনারের নাম...">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">রক্তের গ্রুপ</label>
            <select class="form-select form-select-sm" name="blood_group">
                <option value="">সব গ্রুপ</option>
                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">জেলা</label>
            <input type="text" class="form-control form-control-sm" name="district" value="{{ request('district') }}" placeholder="জেলা...">
        </div>
        <div class="col-md-2">
            <button class="btn btn-blood btn-sm w-100"><i class="bi bi-search me-1"></i>খুঁজুন</button>
        </div>
        <div class="col-md-1">
            <a href="{{ route('donor.blood') }}" class="btn btn-outline-secondary btn-sm w-100" title="রিসেট"><i class="bi bi-arrow-counterclockwise"></i></a>
        </div>
    </form>
</div>

{{-- Donor Cards Grid --}}
<div class="row g-3">
    @forelse($donors as $donor)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="donor-card position-relative" onclick="showDonorProfile({{ $donor->id }})">
                <div class="card-header-bg"></div>
                <span class="blood-badge">{{ $donor->blood_group }}</span>
                <div class="text-center px-3 pb-3">
                    <img src="{{ $donor->profile_image_url }}" alt="{{ $donor->name }}" class="donor-avatar">
                    <h6 class="fw-bold mt-2 mb-1">{{ $donor->name }}</h6>
                    <div class="mb-2">
                        @if($donor->is_available)
                            <span class="status-dot available"></span><small class="text-success">দানে উপলব্ধ</small>
                        @else
                            <span class="status-dot unavailable"></span><small class="text-muted">এখন উপলব্ধ নয়</small>
                        @endif
                    </div>
                    <div class="d-flex justify-content-center gap-3" style="font-size:0.8rem;">
                        <span class="text-muted"><i class="bi bi-droplet me-1"></i>{{ $donor->total_donations }} বার দান</span>
                        @if($currentUser->is_full_member && $donor->contact_visible)
                            <span class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $donor->phone }}</span>
                        @endif
                    </div>
                    @if(!$currentUser->is_full_member)
                        <small class="text-warning mt-2 d-block"><i class="bi bi-lock me-1"></i>বিস্তারিত দেখতে পূর্ণ সদস্য হন</small>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="table-card p-5 text-center">
                <i class="bi bi-search" style="font-size:3rem;color:#ddd;"></i>
                <h6 class="mt-3 text-muted">কোনো ডোনার পাওয়া যায়নি</h6>
                <p class="text-muted" style="font-size:0.85rem;">অনুসন্ধানের শর্ত পরিবর্তন করে আবার চেষ্টা করুন</p>
            </div>
        </div>
    @endforelse
</div>

@if($donors->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $donors->links() }}
    </div>
@endif

{{-- Donor Profile Popup Modal --}}
<div class="modal fade profile-modal" id="donorProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-bg" id="modalHeader">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                <img src="" alt="" class="modal-avatar" id="modalAvatar">
                <h5 class="fw-bold mt-2 mb-0" id="modalName"></h5>
                <div class="mt-1" id="modalBloodBadge"></div>
                <div class="mt-1" id="modalMemberStatus"></div>
            </div>
            <div class="modal-body p-4" id="modalBody">
                {{-- Filled by JS --}}
                <div class="text-center py-3" id="modalLoading">
                    <div class="spinner-border text-danger" role="status"></div>
                    <p class="text-muted mt-2">তথ্য লোড হচ্ছে...</p>
                </div>
                <div id="modalContent" style="display:none;"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const modal = new bootstrap.Modal(document.getElementById('donorProfileModal'));

    function showDonorProfile(donorId) {
        document.getElementById('modalLoading').style.display = 'block';
        document.getElementById('modalContent').style.display = 'none';
        modal.show();

        fetch(`/donor/blood/${donorId}/profile`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('modalLoading').style.display = 'none';
            document.getElementById('modalContent').style.display = 'block';

            document.getElementById('modalAvatar').src = data.profile_image;
            document.getElementById('modalName').textContent = data.name;
            document.getElementById('modalBloodBadge').innerHTML = `<span class="badge bg-white text-danger fw-bold px-3 py-1" style="font-size:1.1rem;">${data.blood_group}</span>`;

            // Member status
            if (data.status === 'approved') {
                document.getElementById('modalMemberStatus').innerHTML = '<span class="badge bg-success mt-1"><i class="bi bi-patch-check-fill me-1"></i>পূর্ণ সদস্য</span>';
            } else {
                document.getElementById('modalMemberStatus').innerHTML = '<span class="badge bg-warning text-dark mt-1"><i class="bi bi-hourglass-split me-1"></i>অস্থায়ী সদস্য</span>';
            }

            let html = '';

            // Basic info - always visible
            html += buildInfoRow('bi-droplet-fill', 'bg-danger bg-opacity-10 text-danger', 'মোট রক্তদান', `${data.total_donations} বার`);
            html += buildInfoRow('bi-check-circle', data.is_available ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary', 'দানের উপলব্ধতা', data.is_available ? 'উপলব্ধ' : 'এখন উপলব্ধ নয়');
            html += buildInfoRow('bi-heart-pulse', data.can_donate ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning', 'দান করতে পারবেন', data.can_donate ? 'হ্যাঁ' : 'না (৩ মাস পর)');

            if (data.gender) {
                const genderMap = { male: 'পুরুষ', female: 'মহিলা', other: 'অন্যান্য' };
                html += buildInfoRow('bi-gender-ambiguous', 'bg-info bg-opacity-10 text-info', 'লিঙ্গ', genderMap[data.gender] || data.gender);
            }

            // Full member details
            if (data.can_see_full) {
                if (data.last_donation_date) {
                    html += buildInfoRow('bi-calendar-check', 'bg-primary bg-opacity-10 text-primary', 'সর্বশেষ দান', data.last_donation_date);
                }
                if (data.district) {
                    html += buildInfoRow('bi-geo-alt', 'bg-info bg-opacity-10 text-info', 'জেলা / বিভাগ', `${data.district}${data.division ? ', ' + data.division : ''}`);
                }
                if (data.phone) {
                    html += buildInfoRow('bi-telephone-fill', 'bg-success bg-opacity-10 text-success', 'ফোন', `<a href="tel:${data.phone}" class="text-decoration-none">${data.phone}</a>`);
                }
                if (data.emergency_contact) {
                    html += buildInfoRow('bi-telephone-forward', 'bg-warning bg-opacity-10 text-warning', 'জরুরি যোগাযোগ', data.emergency_contact);
                }
                if (data.address) {
                    html += buildInfoRow('bi-house', 'bg-secondary bg-opacity-10 text-secondary', 'ঠিকানা', `${data.address}${data.city ? ', ' + data.city : ''}`);
                }
            } else {
                html += `
                    <div class="alert alert-warning mt-3 py-2 text-center" style="border-radius:10px;">
                        <i class="bi bi-lock me-1"></i>
                        <small>পূর্ণ সদস্য হলে যোগাযোগ ও ঠিকানার তথ্য দেখতে পারবেন। অ্যাডমিন অনুমোদনের অপেক্ষায় থাকুন।</small>
                    </div>
                `;
            }

            document.getElementById('modalContent').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('modalLoading').innerHTML = '<p class="text-danger">তথ্য লোড করতে সমস্যা হয়েছে।</p>';
            console.error(err);
        });
    }

    function buildInfoRow(icon, iconBg, label, value) {
        return `
            <div class="info-row">
                <div class="info-icon ${iconBg}"><i class="bi ${icon}"></i></div>
                <div>
                    <small class="text-muted">${label}</small>
                    <div class="fw-semibold">${value}</div>
                </div>
            </div>
        `;
    }
</script>
@endpush
@endsection

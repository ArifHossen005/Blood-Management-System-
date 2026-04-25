@extends('layouts.app')
@section('title', __('ui.dashboard.admin_title'))
@section('page-title', __('ui.nav.dashboard'))

@section('content')
{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['total_donors'] }}</div>
                    <div class="stat-label">{{ __('ui.dashboard.total_donors') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-patch-check-fill"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['approved_donors'] }}</div>
                    <div class="stat-label">{{ __('ui.dashboard.approved_donors') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['pending_approvals'] }}</div>
                    <div class="stat-label">{{ __('ui.dashboard.pending_approvals') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-droplet-fill"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['total_donations'] }}</div>
                    <div class="stat-label">{{ __('ui.dashboard.total_donations') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Blood Group Stats --}}
    <div class="col-lg-5">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart-fill text-danger me-2"></i>{{ __('ui.dashboard.by_blood_group') }}</h6>
            <div class="row g-2">
                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                    @php $count = $bloodGroupStats[$bg] ?? 0; @endphp
                    <div class="col-6">
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background:#f8f9fa;">
                            <span class="badge-blood">{{ $bg }}</span>
                            <span class="fw-bold">{{ $count }} {{ __('ui.common.people') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="table-card p-4 mt-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning-fill text-warning me-2"></i>{{ __('ui.dashboard.quick_actions') }}</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.donors', ['status' => 'temporary']) }}" class="btn btn-outline-warning btn-sm text-start">
                    <i class="bi bi-hourglass-split me-2"></i>{{ __('ui.dashboard.pending_approvals_cta') }} ({{ $stats['pending_approvals'] }})
                </a>
                <a href="{{ route('admin.blood-requests', ['status' => 'pending']) }}" class="btn btn-outline-info btn-sm text-start">
                    <i class="bi bi-heart-pulse me-2"></i>{{ __('ui.dashboard.pending_requests_cta') }} ({{ $stats['pending_requests'] }})
                </a>
                <a href="{{ route('admin.donors') }}" class="btn btn-outline-danger btn-sm text-start">
                    <i class="bi bi-people me-2"></i>{{ __('ui.dashboard.all_donors_cta') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Pending Donors --}}
    <div class="col-lg-7">
        <div class="table-card">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="bi bi-hourglass-split text-warning me-2"></i>{{ __('ui.dashboard.pending_donors_title') }}</h6>
                <a href="{{ route('admin.donors', ['status' => 'temporary']) }}" class="btn btn-sm btn-blood-outline">{{ __('ui.common.view_all') }}</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('ui.fields.name') }}</th>
                            <th>{{ __('ui.fields.blood_group') }}</th>
                            <th>{{ __('ui.fields.phone') }}</th>
                            <th>{{ __('ui.fields.district') }}</th>
                            <th>{{ __('ui.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingDonors as $donor)
                            <tr>
                                <td class="fw-semibold">{{ $donor->name }}</td>
                                <td><span class="badge-blood">{{ $donor->blood_group }}</span></td>
                                <td>{{ $donor->phone ?? '—' }}</td>
                                <td>{{ $donor->district ?? '—' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.donors.approve', $donor) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-success" title="{{ __('ui.common.approve') }}"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.donors.reject', $donor) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-danger" title="{{ __('ui.common.reject') }}"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">{{ __('ui.dashboard.no_pending_donors') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Donors --}}
        <div class="table-card mt-4">
            <div class="p-3 border-bottom">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-fill text-info me-2"></i>{{ __('ui.dashboard.recent_donors') }}</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('ui.fields.name') }}</th>
                            <th>{{ __('ui.fields.blood_group') }}</th>
                            <th>{{ __('ui.fields.status') }}</th>
                            <th>{{ __('ui.common.joined') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDonors as $donor)
                            <tr>
                                <td class="fw-semibold">{{ $donor->name }}</td>
                                <td><span class="badge-blood">{{ $donor->blood_group }}</span></td>
                                <td>
                                    <span class="badge badge-{{ $donor->status }}">
                                        {{ in_array($donor->status, ['approved','temporary','rejected','banned']) ? __('ui.donor_status.'.$donor->status) : $donor->status }}
                                    </span>
                                </td>
                                <td>{{ $donor->created_at->format('d M, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

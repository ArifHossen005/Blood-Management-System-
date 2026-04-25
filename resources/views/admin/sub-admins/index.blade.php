@extends('layouts.app')
@section('title', __('ui.sub_admins.page_title'))
@section('page-title', __('ui.sub_admins.page_title'))

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
    </div>
@endif

{{-- Promote donor form --}}
<div class="table-card p-3 mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-person-plus-fill me-2"></i>{{ __('ui.sub_admins.promote_new') }}</h6>

    <form method="GET" action="{{ route('admin.sub-admins') }}" class="row g-2 align-items-end mb-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold" style="font-size:0.82rem;">{{ __('ui.sub_admins.find_approved_donors') }}</label>
            <input type="text" class="form-control form-control-sm" name="q" value="{{ request('q') }}" placeholder="{{ __('ui.sub_admins.find_placeholder') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-blood btn-sm w-100"><i class="bi bi-search me-1"></i>{{ __('ui.common.search_cta') }}</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.sub-admins') }}" class="btn btn-outline-secondary btn-sm w-100">{{ __('ui.common.reset') }}</a>
        </div>
    </form>

    @if(request('q'))
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('ui.role.donor') }}</th>
                        <th>{{ __('ui.fields.phone') }}</th>
                        <th>{{ __('ui.fields.district') }}</th>
                        <th style="width:45%;">{{ __('ui.sub_admins.initial_permissions') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eligibleDonors as $donor)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $donor->name }}</div>
                                <small class="text-muted">{{ $donor->email }}</small>
                            </td>
                            <td>{{ $donor->phone ?? '—' }}</td>
                            <td>{{ $donor->district ?? '—' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.sub-admins.promote', $donor) }}" id="promote-{{ $donor->id }}">
                                    @csrf
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($availablePermissions as $key => $label)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $key }}" id="perm-{{ $donor->id }}-{{ $key }}">
                                                <label class="form-check-label" for="perm-{{ $donor->id }}-{{ $key }}" style="font-size:0.78rem;">{{ __('ui.permissions.'.$key) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </form>
                            </td>
                            <td>
                                <button form="promote-{{ $donor->id }}" type="submit" class="btn btn-sm btn-success"
                                        onclick="return confirm('{{ __('ui.sub_admins.confirm_promote', ['name' => $donor->name]) }}')">
                                    <i class="bi bi-check-lg me-1"></i>{{ __('ui.sub_admins.promote_cta') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">{{ __('ui.sub_admins.no_donors_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>{{ __('ui.sub_admins.use_search_hint') }}</small>
    @endif
</div>

{{-- Existing sub-admins --}}
<div class="table-card">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="fw-bold mb-0"><i class="bi bi-shield-check me-2"></i>{{ __('ui.sub_admins.current_count', ['count' => $subAdmins->total()]) }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('ui.fields.name') }}</th>
                    <th>{{ __('ui.fields.phone') }}</th>
                    <th>{{ __('ui.sub_admins.permissions_col') }}</th>
                    <th>{{ __('ui.sub_admins.created_col') }}</th>
                    <th>{{ __('ui.common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subAdmins as $sa)
                    <tr>
                        <td>{{ $loop->iteration + ($subAdmins->currentPage() - 1) * $subAdmins->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $sa->profile_image_url }}" width="32" height="32" class="rounded-circle" style="object-fit:cover;">
                                <div>
                                    <div class="fw-semibold">{{ $sa->name }}</div>
                                    <small class="text-muted">{{ $sa->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $sa->phone ?? '—' }}</td>
                        <td>
                            @php $perms = (array) $sa->permissions; @endphp
                            @if(empty($perms))
                                <span class="badge bg-secondary">{{ __('ui.sub_admins.no_permissions') }}</span>
                            @else
                                @foreach($perms as $p)
                                    <span class="badge bg-info text-dark me-1" style="font-size:0.7rem;">
                                        {{ __('ui.permissions.'.$p) }}
                                    </span>
                                @endforeach
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $sa->updated_at->diffForHumans() }}</small></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.sub-admins.edit', $sa) }}" class="btn btn-sm btn-outline-primary" title="{{ __('ui.sub_admins.edit_permissions') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.sub-admins.revoke', $sa) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="{{ __('ui.sub_admins.revoke') }}"
                                            onclick="return confirm('{{ __('ui.sub_admins.confirm_revoke', ['name' => $sa->name]) }}')">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">{{ __('ui.sub_admins.none') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subAdmins->hasPages())
        <div class="p-3 border-top">
            {{ $subAdmins->links() }}
        </div>
    @endif
</div>
@endsection

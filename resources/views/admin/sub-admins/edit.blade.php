@extends('layouts.app')
@section('title', __('ui.sub_admins.page_title_edit'))
@section('page-title', __('ui.sub_admins.page_title_edit'))

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-3">
    <div class="col-lg-4">
        <div class="table-card p-3 text-center">
            <img src="{{ $user->profile_image_url }}" width="90" height="90" class="rounded-circle mb-3" style="object-fit:cover; border:3px solid #dc3545;">
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <div class="text-muted mb-2" style="font-size:0.85rem;">{{ $user->email }}</div>
            <span class="badge bg-primary mb-2"><i class="bi bi-shield-check me-1"></i>{{ __('ui.role.sub_admin') }}</span>
            <div class="text-muted" style="font-size:0.8rem;">
                <div><i class="bi bi-telephone me-1"></i>{{ $user->phone ?? '—' }}</div>
                <div><i class="bi bi-geo-alt me-1"></i>{{ $user->district ?? '—' }}</div>
                <div><i class="bi bi-droplet-fill me-1"></i>{{ $user->blood_group ?? '—' }}</div>
            </div>

            <hr>

            <form method="POST" action="{{ route('admin.sub-admins.revoke', $user) }}">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm w-100"
                        onclick="return confirm('{{ __('ui.sub_admins.confirm_revoke', ['name' => $user->name]) }}')">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>{{ __('ui.sub_admins.revoke_cta') }}
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-check2-square me-2"></i>{{ __('ui.sub_admins.select_permissions') }}</h6>
            <p class="text-muted" style="font-size:0.85rem;">
                {{ __('ui.sub_admins.permissions_help') }}
            </p>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.sub-admins.update', $user) }}">
                @csrf @method('PUT')

                @php $currentPerms = (array) $user->permissions; @endphp

                <div class="list-group mb-4">
                    @foreach($availablePermissions as $key => $label)
                        <label class="list-group-item d-flex align-items-center gap-3" style="cursor:pointer;">
                            <input type="checkbox"
                                   class="form-check-input m-0"
                                   name="permissions[]"
                                   value="{{ $key }}"
                                   {{ in_array($key, $currentPerms) ? 'checked' : '' }}>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ __('ui.permissions.'.$key) }}</div>
                                <small class="text-muted"><code>{{ $key }}</code></small>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-blood">
                        <i class="bi bi-save me-1"></i>{{ __('ui.sub_admins.save_permissions') }}
                    </button>
                    <a href="{{ route('admin.sub-admins') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>{{ __('ui.common.back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

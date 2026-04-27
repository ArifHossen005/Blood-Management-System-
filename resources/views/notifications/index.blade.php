@extends('layouts.app')
@section('title', __('ui.notifications.title'))
@section('page-title', __('ui.notifications.title'))

@section('content')
<div class="table-card p-0">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
        <span class="fw-semibold">{{ __('ui.notifications.title') }}</span>
        @if($notifications->total() > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-check2-all me-1"></i>{{ __('ui.notifications.mark_all_read') }}
                </button>
            </form>
        @endif
    </div>

    {{-- List --}}
    @forelse($notifications as $notif)
        @php
            $data    = $notif->data;
            $type    = $data['type'] ?? '';
            $isUnread = is_null($notif->read_at);
            $color   = $data['color'] ?? 'secondary';
            $icon    = $data['icon']  ?? 'bi-bell';
        @endphp
        <div class="d-flex align-items-start gap-3 px-4 py-3 border-bottom {{ $isUnread ? 'bg-light' : '' }}">
            {{-- Icon --}}
            <div class="flex-shrink-0 mt-1">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-{{ $color }} bg-opacity-10"
                     style="width:2.5rem;height:2.5rem;">
                    <i class="bi {{ $icon }} text-{{ $color }}" style="font-size:1.1rem;"></i>
                </div>
            </div>

            {{-- Content --}}
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="fw-semibold" style="font-size:0.9rem;">
                        {{ __('ui.notifications.'.$type.'.title', [], app()->getLocale()) ?? __('ui.notifications.title') }}
                    </span>
                    @if($isUnread)
                        <span class="badge bg-danger rounded-pill" style="font-size:0.6rem;">New</span>
                    @endif
                </div>
                <p class="mb-1 text-muted" style="font-size:0.85rem;">
                    @include('notifications._message', ['data' => $data, 'type' => $type])
                </p>
                <small class="text-muted" style="font-size:0.75rem;">
                    <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                    &nbsp;·&nbsp; {{ $notif->created_at->format('d M Y, h:i A') }}
                </small>
            </div>

            {{-- Actions --}}
            <div class="flex-shrink-0 d-flex gap-2 align-items-center">
                @if($isUnread)
                    <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success" title="{{ __('ui.notifications.mark_all_read') }}">
                            <i class="bi bi-check2"></i>
                        </button>
                    </form>
                @endif
                @if(!empty($data['action_url']))
                    <a href="{{ $data['action_url'] }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                @endif
                <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash" style="font-size:3rem;opacity:.3;"></i>
            <p class="mt-3 mb-0">{{ __('ui.notifications.none') }}</p>
        </div>
    @endforelse
</div>

@if($notifications->hasPages())
    <div class="mt-4 d-flex justify-content-center">{{ $notifications->links() }}</div>
@endif
@endsection

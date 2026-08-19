@extends('admin.layout')

@php
    $title = 'Live Chat';
@endphp

@section('content')
    <h2 class="text-xl font-bold text-white">Conversations</h2>

    <div class="admin-card mt-6">
        <div class="divide-y divide-white/5">
            @forelse($conversations as $c)
                <a href="{{ route('admin.chat.show', $c->id) }}" class="flex items-center justify-between py-4 hover:bg-white/5">
                    <div>
                        <div class="font-medium text-white">{{ $c->name }} <span class="text-xs text-ink-500">({{ $c->email ?? 'no email' }})</span></div>
                        <div class="text-sm text-ink-400">{{ $c->messages_count }} messages · {{ $c->last_activity_at?->diffForHumans() ?? '—' }}</div>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs {{ $c->status === 'open' ? 'bg-brand-500/15 text-brand-300' : 'bg-white/5 text-ink-400' }}">{{ ucfirst($c->status) }}</span>
                </a>
            @empty
                <p class="py-6 text-center text-ink-400">No conversations yet.</p>
            @endforelse
        </div>
    </div>

    <div class="mt-6">
        {{ $conversations->links() }}
    </div>
@endsection

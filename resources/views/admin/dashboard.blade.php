@extends('admin.layout')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="admin-stat"><div class="admin-stat__num">{{ $stats['projects'] }}</div><div class="admin-stat__label">Projects</div></div>
        <div class="admin-stat"><div class="admin-stat__num text-brand-400">{{ $stats['quotes_new'] }}</div><div class="admin-stat__label">New Quotes</div></div>
        <div class="admin-stat"><div class="admin-stat__num text-brand-400">{{ $stats['contacts_new'] }}</div><div class="admin-stat__label">New Messages</div></div>
        <div class="admin-stat"><div class="admin-stat__num text-brand-400">{{ $stats['chats_open'] }}</div><div class="admin-stat__label">Open Chats</div></div>
        <div class="admin-stat"><div class="admin-stat__num">{{ $stats['visits'] }}</div><div class="admin-stat__label">Site Visits</div></div>
        <div class="admin-stat"><div class="admin-stat__num">{{ $stats['meetings'] }}</div><div class="admin-stat__label">Meetings</div></div>
        <div class="admin-stat"><div class="admin-stat__num">{{ $stats['applications'] }}</div><div class="admin-stat__label">Applications</div></div>
        <div class="admin-stat"><div class="admin-stat__num">{{ $stats['projects'] }}</div><div class="admin-stat__label">Total Projects</div></div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <div class="admin-card lg:col-span-2">
            <h2 class="mb-4 text-lg font-semibold text-white">Recent Quote Requests</h2>
            <div class="divide-y divide-white/5">
                @forelse($recentQuotes as $q)
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <div class="font-medium text-white">{{ $q->name }}</div>
                            <div class="text-sm text-ink-400">{{ $q->email }} · {{ $q->service_type ?? 'General' }}</div>
                        </div>
                        <span class="rounded-full bg-white/5 px-3 py-1 text-xs text-ink-300">{{ ucfirst($q->status) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-ink-400">No quotes yet.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-card">
            <h2 class="mb-4 text-lg font-semibold text-white">Open Chats</h2>
            <div class="divide-y divide-white/5">
                @forelse($openChats as $c)
                    <a href="{{ route('admin.chat.show', $c->id) }}" class="flex items-center justify-between py-3 hover:text-brand-300">
                        <div>
                            <div class="font-medium text-white">{{ $c->name }}</div>
                            <div class="text-sm text-ink-400">{{ $c->messages_count }} messages</div>
                        </div>
                        <span class="text-xs text-ink-500">&rarr;</span>
                    </a>
                @empty
                    <p class="text-sm text-ink-400">No open chats.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

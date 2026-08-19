@extends('admin.layout')

@php
    $title = $config['label'];
@endphp

@section('content')
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">{{ $config['label'] }}</h2>
        <span class="text-sm text-ink-400">{{ $items->total() }} total</span>
    </div>

    <div class="admin-card mt-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-ink-400">
                <tr class="border-b border-white/10">
                    <th class="py-3 pr-4">Name</th>
                    <th class="py-3 pr-4">Contact</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3 pr-4">Received</th>
                    <th class="py-3 pr-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="border-b border-white/5">
                        <td class="py-3 pr-4 font-medium text-white">{{ $item->name }}</td>
                        <td class="py-3 pr-4 text-ink-300">{{ $item->email }}<br>{{ $item->phone ?? '' }}</td>
                        <td class="py-3 pr-4">
                            <span class="rounded-full bg-white/5 px-3 py-1 text-xs text-ink-300">{{ ucfirst($item->status) }}</span>
                        </td>
                        <td class="py-3 pr-4 text-ink-400">{{ $item->created_at->format('M d, Y') }}</td>
                        <td class="py-3 pr-4">
                            <a href="{{ route('admin.submissions.show', [$type, $item->id]) }}" class="text-brand-300 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-6 text-center text-ink-400">No submissions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
@endsection

@extends('admin.layout')

@php
    $title = $config['label'];
@endphp

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-white">{{ $config['label'] }}</h2>
            <p class="text-sm text-ink-400">{{ $items->count() }} item(s)</p>
        </div>
        <a href="{{ route('admin.content.create', $type) }}" class="btn-primary btn">+ Add New</a>
    </div>

    <div class="admin-card mt-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-ink-400">
                <tr class="border-b border-white/10">
                    <th class="py-3 pr-4">#</th>
                    <th class="py-3 pr-4">Title / Name</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3 pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="border-b border-white/5">
                        <td class="py-3 pr-4 text-ink-400">{{ $item->sort_order ?? $item->id }}</td>
                        <td class="py-3 pr-4 font-medium text-white">
                            {{ $item->title ?? $item->name ?? ('#' . $item->id) }}
                            @if($type === 'projects')<span class="ml-2 text-xs text-ink-500">/{{ $item->slug }}</span>@endif
                        </td>
                        <td class="py-3 pr-4">
                            @if(isset($item->active))
                                <span class="rounded-full px-3 py-1 text-xs {{ $item->active ? 'bg-brand-500/15 text-brand-300' : 'bg-white/5 text-ink-400' }}">{{ $item->active ? 'Active' : 'Hidden' }}</span>
                            @else
                                <span class="text-ink-500">—</span>
                            @endif
                        </td>
                        <td class="py-3 pr-4">
                            <div class="flex gap-3">
                                <a href="{{ route('admin.content.edit', [$type, $item->id]) }}" class="text-brand-300 hover:underline">Edit</a>
                                @if($type === 'projects')
                                    <a href="{{ route('admin.content.updates', $item->id) }}" class="text-ink-300 hover:underline">Updates</a>
                                @endif
                                <form action="{{ route('admin.content.destroy', [$type, $item->id]) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-6 text-center text-ink-400">No items yet. Click "Add New" to create one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

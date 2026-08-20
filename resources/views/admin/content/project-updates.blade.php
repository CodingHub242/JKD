@extends('admin.layout')

@php
    $title = 'Updates · ' . $project->title;
@endphp

@section('content')
    <a href="{{ route('admin.content.index', 'projects') }}" class="text-sm text-ink-400 hover:text-white">&larr; Back to Projects</a>

    <div class="admin-card mt-4">
        <h2 class="text-lg font-semibold text-white">Post a Progress Update</h2>
        <p class="mt-1 text-sm text-ink-400">Updates appear in the client's Building Tracker timeline.</p>

        <form class="mt-5 space-y-4" action="{{ route('admin.content.updates.store', $project->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <input type="text" name="title" required placeholder="Update title" class="field">
                <input type="number" name="progress" min="0" max="100" placeholder="Progress %" class="field">
                <input type="date" name="posted_at" class="field">
                <input type="file" name="image" class="field">
            </div>
            <textarea name="body" rows="4" placeholder="What happened in this update?" class="field"></textarea>
            <button type="submit" class="btn-primary btn">Post Update</button>
        </form>
    </div>

    <div class="admin-card mt-6">
        <h3 class="mb-4 text-lg font-semibold text-white">Posted Updates</h3>
        <div class="space-y-4">
            @forelse($updates as $update)
                <div class="flex items-start justify-between gap-4 rounded-xl border border-white/10 p-4">
                    <div>
                        <div class="font-medium text-white">{{ $update->title }}</div>
                        <div class="text-xs text-ink-400">{{ $update->posted_at->format('M d, Y') }} · {{ $update->progress }}%</div>
                        @if($update->body)<p class="mt-2 text-sm text-ink-200">{{ $update->body }}</p>@endif
                        @if($update->image)<img src="{{ url('storage/'.$update->image) }}" class="mt-3 h-32 rounded-lg">@endif
                    </div>
                    <form action="{{ route('admin.content.updates.destroy', [$project->id, $update->id]) }}" method="POST" onsubmit="return confirm('Delete this update?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:underline">Delete</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-ink-400">No updates posted yet.</p>
            @endforelse
        </div>
    </div>
@endsection

@extends('admin.layout')

@php
    $title = $config['label'] . ' · ' . $item->name;
@endphp

@section('content')
    <a href="{{ route('admin.submissions.index', $type) }}" class="text-sm text-ink-400 hover:text-white">&larr; Back to {{ $config['label'] }}</a>

    <div class="admin-card mt-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-white">{{ $item->name }}</h2>
            <span class="rounded-full bg-white/5 px-3 py-1 text-xs text-ink-300">{{ ucfirst($item->status) }}</span>
        </div>

        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            @foreach($item->getAttributes() as $key => $value)
                @if(in_array($key, ['id', 'created_at', 'updated_at'])) @continue @endif
                <div class="rounded-xl border border-white/10 p-4">
                    <dt class="text-xs uppercase tracking-wider text-ink-500">{{ Str::headline($key) }}</dt>
                    <dd class="mt-1 text-ink-100">
                        @if($key === 'cv_path')
                            <a href="{{ url('storage/'.$value) }}" target="_blank" class="text-brand-300 hover:underline">Download CV</a>
                        @elseif(is_null($value) || $value === '')
                            <span class="text-ink-500">—</span>
                        @else
                            {{ $value }}
                        @endif
                    </dd>
                </div>
            @endforeach
        </dl>

        @if($type === 'meetings' && $item->jitsi_room)
            <div class="mt-4 rounded-xl border border-brand-400/30 bg-brand-500/10 p-4 text-sm text-brand-200">
                Meeting room: {{ $item->jitsi_room }} ·
                <a href="https://{{ \App\Models\Setting::getValue('jitsi_domain', 'meet.jit.si') }}/{{ $item->jitsi_room }}" target="_blank" class="underline">Open room</a>
            </div>
        @endif

        <form class="mt-6 flex flex-wrap items-end gap-4" action="{{ route('admin.submissions.update', [$type, $item->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label class="field-label">Update Status</label>
                <select name="status" class="field">
                    @foreach($config['statuses'] as $val => $label)
                        <option value="{{ $val }}" {{ $item->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary btn">Save Status</button>
        </form>
    </div>
@endsection

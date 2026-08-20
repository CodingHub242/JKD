@extends('admin.layout')

@php
    $isEdit = optional($item)->exists;
    $title = $isEdit ? 'Edit ' . $config['label'] : 'Add ' . $config['label'];
@endphp

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('admin.content.index', $type) }}" class="text-sm text-ink-400 hover:text-white">&larr; Back to {{ $config['label'] }}</a>

        <div class="admin-card mt-4">
            <h2 class="text-lg font-semibold text-white">{{ $title }}</h2>

            <form class="mt-6 space-y-5"
                  action="{{ $isEdit ? route('admin.content.update', [$type, $item->id]) : route('admin.content.store', $type) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @if($isEdit) @method('PUT') @endif

                @foreach($config['fields'] as $key => $field)
                    <div>
                        <label class="field-label">{{ $field['label'] }}</label>

                        @if($field['type'] === 'textarea')
                            <textarea name="{{ $key }}" rows="4" class="field">{{ old($key, optional($item)->{$key} ?? '') }}</textarea>

                        @elseif($field['type'] === 'select')
                            <select name="{{ $key }}" class="field">
                                @foreach($field['options'] as $val => $label)
                                    <option value="{{ $val }}" {{ old($key, optional($item)->{$key} ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>

                        @elseif($field['type'] === 'checkbox')
                            <label class="inline-flex items-center gap-2 text-sm text-ink-200">
                                <input type="checkbox" name="{{ $key }}" value="1" {{ old($key, optional($item)->{$key} ?? false) ? 'checked' : '' }} class="h-4 w-4 rounded border-white/20 bg-ink-900">
                                {{ $field['label'] }}
                            </label>

                        @elseif($field['type'] === 'file')
                            <input type="file" name="{{ $key }}" class="field">
                            @if($isEdit && optional($item)->{$key})
                                <p class="mt-2 text-xs text-ink-400">Current: <a href="{{ asset('storage/'.optional($item)->{$key}) }}" target="_blank" class="text-brand-300">view</a></p>
                            @endif

                        @elseif($field['type'] === 'files')
                            <input type="file" name="{{ $key }}[]" multiple class="field">
                            @if($isEdit && is_array(optional($item)->{$key}) && count(optional($item)->{$key}))
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach(optional($item)->{$key} as $img)
                                        <img src="{{ asset('storage/'.$img) }}" class="h-16 w-16 rounded object-cover">
                                    @endforeach
                                </div>
                            @endif

                        @elseif($field['type'] === 'relation')
                            <select name="{{ $key }}[]" multiple class="field h-40">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ ($item && $item->clients->contains($client->id)) ? 'selected' : '' }}>{{ $client->name }} ({{ $client->email }})</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-ink-400">Hold Ctrl/Cmd to select multiple. These clients can track this project.</p>

                        @else
                            <input type="{{ $field['type'] }}" name="{{ $key }}" class="field" value="{{ old($key, optional($item)->{$key} ?? '') }}">
                        @endif
                    </div>
                @endforeach

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary btn">Save</button>
                    <a href="{{ route('admin.content.index', $type) }}" class="btn-outline btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

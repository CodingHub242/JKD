@extends('admin.layout')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="admin-card">
            <h2 class="text-lg font-semibold text-white">Site Settings</h2>
            <p class="mt-1 text-sm text-ink-400">These control the text and links shown across the website.</p>

            <form class="mt-6 space-y-5" action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @foreach($fields as $key => $field)
                    <div>
                        <label class="field-label">{{ $field['label'] }}</label>
                        @if($field['type'] === 'textarea')
                            <textarea name="{{ $key }}" rows="3" class="field">{{ old($key, $settings[$key] ?? '') }}</textarea>
                        @else
                            <input type="{{ $field['type'] }}" name="{{ $key }}" class="field" value="{{ old($key, $settings[$key] ?? '') }}">
                        @endif
                    </div>
                @endforeach
                <button type="submit" class="btn-primary btn">Save Settings</button>
            </form>
        </div>
    </div>
@endsection

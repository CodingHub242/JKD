@extends('layouts.app')

@section('content')
    <section class="page-hero page-hero--short">
        <div class="container-x flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="eyebrow reveal">Building Tracker</p>
                <h1 class="page-hero__title reveal reveal-delay-1 mt-3">Your Projects</h1>
            </div>
            <form action="{{ route('client.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-outline btn">Log Out</button>
            </form>
        </div>
    </section>

    <section class="section container-x">
        @if($projects->isEmpty())
            <div class="surface reveal p-10 text-center">
                <p class="text-ink-300">You don't have any projects assigned yet. Our team will link your building here once work begins.</p>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($projects as $project)
                    @php $progress = $project->latest_progress; @endphp
                    <a href="{{ route('tracker.show', $project->slug) }}" class="surface reveal block p-6 transition hover:border-brand-400/40">
                        @if($project->cover_image)
                            <img src="{{ storage_url($project->cover_image) }}" alt="{{ $project->title }}" class="mb-4 h-44 w-full rounded-xl object-cover">
                        @endif
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-white">{{ $project->title }}</h3>
                            <span class="rounded-full bg-white/5 px-3 py-1 text-xs text-ink-300">{{ ucfirst($project->status) }}</span>
                        </div>
                        <p class="mt-1 text-sm text-ink-300">{{ $project->location }}</p>

                        <div class="mt-4">
                            <div class="mb-1 flex justify-between text-xs text-ink-400">
                                <span>Progress</span>
                                <span>{{ $progress }}%</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-white/10">
                                <div class="h-full rounded-full bg-brand-500" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        @if($project->updates->isNotEmpty())
                            <p class="mt-4 text-sm text-ink-400">Latest: {{ $project->updates->first()->title }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endsection

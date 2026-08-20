@extends('layouts.app')

@section('content')
    <section class="page-hero page-hero--short">
        <div class="container-x">
            <a href="{{ route('projects') }}" class="eyebrow reveal">&larr; Back to Projects</a>
            <h1 class="page-hero__title reveal reveal-delay-1 mt-3">{{ $project->title }}</h1>
            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-ink-300 reveal reveal-delay-2">
                <span class="rounded-full bg-brand-500/10 px-3 py-1 text-brand-300">{{ $project->category }}</span>
                <span>{{ $project->location }}</span>
                @if($project->client_name)<span>· Client: {{ $project->client_name }}</span>@endif
                <span class="rounded-full bg-white/5 px-3 py-1">{{ ucfirst($project->status) }}</span>
            </div>
        </div>
    </section>

    <section class="section container-x">
        <div class="grid gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if($project->cover_image)
                    <img src="{{ asset($project->cover_image) }}" alt="{{ $project->title }}" class="w-full rounded-2xl border border-white/10">
                @endif

                <div class="mt-8 prose-invert max-w-none">
                    <p class="text-lg text-ink-200">{{ $project->description }}</p>
                </div>

                @if(count($gallery))
                    <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3">
                        @foreach($gallery as $image)
                            <img src="{{ asset($image) }}" alt="{{ $project->title }}" class="h-40 w-full rounded-xl object-cover">
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="surface p-6">
                    <h3 class="text-lg font-semibold text-white">Project Details</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between"><dt class="text-ink-400">Category</dt><dd class="text-white">{{ $project->category }}</dd></div>
                        <div class="flex justify-between"><dt class="text-ink-400">Location</dt><dd class="text-white">{{ $project->location }}</dd></div>
                        <div class="flex justify-between"><dt class="text-ink-400">Status</dt><dd class="text-white">{{ ucfirst($project->status) }}</dd></div>
                        @if($project->client_name)<div class="flex justify-between"><dt class="text-ink-400">Client</dt><dd class="text-white">{{ $project->client_name }}</dd></div>@endif
                    </dl>
                    <a href="{{ url('/quote') }}" class="btn-primary btn mt-6 w-full">Start a Similar Project</a>
                </div>

                @if($project->latitude && $project->longitude)
                    <div class="surface overflow-hidden p-0">
                        <div class="px-6 pt-6"><h3 class="text-lg font-semibold text-white">Location</h3></div>
                        <iframe
                            class="mt-4 h-56 w-full border-0"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.openstreetmap.org/export/embed.html?bbox={{ $project->longitude - 0.01 }}%2C{{ $project->latitude - 0.01 }}%2C{{ $project->longitude + 0.01 }}%2C{{ $project->latitude + 0.01 }}&layer=mapnik&marker={{ $project->latitude }}%2C{{ $project->longitude }}">
                        </iframe>
                        <a href="https://www.openstreetmap.org/?mlat={{ $project->latitude }}&mlon={{ $project->longitude }}#map=15/{{ $project->latitude }}/{{ $project->longitude }}" target="_blank" rel="noopener" class="block px-6 py-3 text-sm text-brand-300 hover:text-brand-200">Open in maps &rarr;</a>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    @if($related->isNotEmpty())
        <section class="border-t border-white/5 bg-ink-900/40 section container-x">
            <h2 class="text-2xl font-bold">More like this</h2>
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($related as $p)
                    <a href="{{ url('/projects/'.$p->slug) }}" class="project-card group">
                        <div class="overflow-hidden">
                            <img src="{{ $p->cover_image ? asset($p->cover_image) : asset('slide1.jpg') }}" alt="{{ $p->title }}" class="project-card__img">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-white">{{ $p->title }}</h3>
                            <p class="mt-1 text-sm text-ink-300">{{ $p->location }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection

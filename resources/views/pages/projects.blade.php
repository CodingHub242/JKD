@extends('layouts.app')

@section('content')
    <section class="page-hero">
        <div class="container-x">
            <p class="eyebrow reveal">Our Portfolio</p>
            <h1 class="page-hero__title reveal reveal-delay-1">Projects built to last.</h1>
            <p class="mt-4 max-w-2xl text-ink-300 reveal reveal-delay-2">Explore a selection of residential, commercial and industrial work delivered with precision.</p>
        </div>
    </section>

    <section class="section container-x">
        @if($categories->isNotEmpty())
            <div class="mb-10 flex flex-wrap gap-2">
                <a href="{{ route('projects') }}" class="rounded-full border px-4 py-2 text-sm transition-colors {{ !$category ? 'border-brand-400 bg-brand-500/10 text-brand-300' : 'border-white/10 text-ink-300 hover:border-white/30' }}">All</a>
                @foreach($categories as $cat)
                    <a href="{{ route('projects', ['category' => $cat]) }}" class="rounded-full border px-4 py-2 text-sm transition-colors {{ $category === $cat ? 'border-brand-400 bg-brand-500/10 text-brand-300' : 'border-white/10 text-ink-300 hover:border-white/30' }}">{{ $cat }}</a>
                @endforeach
            </div>
        @endif

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($projects as $project)
                <a href="{{ url('/projects/'.$project->slug) }}" class="project-card group reveal">
                    <div class="overflow-hidden">
                        <img src="{{ $project->cover_image ? url('storage/'.$project->cover_image) : asset('slide1.jpg') }}" alt="{{ $project->title }}" class="project-card__img">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-brand-400">{{ $project->category }}</span>
                            <span class="rounded-full bg-white/5 px-3 py-1 text-xs text-ink-300">{{ ucfirst($project->status) }}</span>
                        </div>
                        <h3 class="mt-3 text-xl font-semibold text-white">{{ $project->title }}</h3>
                        <p class="mt-1 text-sm text-ink-300">{{ $project->location }}</p>
                    </div>
                </a>
            @empty
                <p class="text-ink-300">No projects found in this category yet.</p>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $projects->links() }}
        </div>
    </section>
@endsection

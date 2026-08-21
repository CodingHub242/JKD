@extends('layouts.app')

@section('content')
    <section class="page-hero page-hero--short">
        <div class="container-x flex flex-wrap items-center justify-between gap-4">
            <div>
                <a href="{{ route('tracker.index') }}" class="eyebrow reveal">&larr; All Projects</a>
                <h1 class="page-hero__title reveal reveal-delay-1 mt-3">{{ $project->title }}</h1>
                <p class="mt-2 text-sm text-ink-300">{{ $project->location }} · {{ ucfirst($project->status) }}</p>
            </div>
            <form action="{{ route('client.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-outline btn">Log Out</button>
            </form>
        </div>
    </section>

    <section class="section container-x">
        <div class="grid gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="surface p-6">
                    <div class="mb-2 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Overall Progress</h3>
                        <span class="text-brand-300">{{ $progress }}%</span>
                    </div>
                    <div class="h-3 w-full overflow-hidden rounded-full bg-white/10">
                        <div class="h-full rounded-full bg-brand-500" style="width: {{ $progress }}%"></div>
                    </div>
                    @if($project->description)
                        <p class="mt-5 text-ink-200">{{ $project->description }}</p>
                    @endif
                </div>

                <h2 class="mt-10 text-2xl font-bold">Update Timeline</h2>
                <div class="mt-6 space-y-6 border-l border-white/10 pl-6">
                    @forelse($project->updates as $update)
                        <div class="relative">
                            <span class="absolute -left-[31px] top-1.5 grid h-4 w-4 place-items-center rounded-full bg-brand-500"></span>
                            <div class="surface p-5">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <h3 class="font-semibold text-white">{{ $update->title }}</h3>
                                    <span class="text-xs text-ink-400">{{ $update->posted_at->format('M d, Y') }}</span>
                                </div>
                                @if($update->progress)
                                    <span class="mt-1 inline-block rounded-full bg-brand-500/10 px-3 py-1 text-xs text-brand-300">{{ $update->progress }}% complete</span>
                                @endif
                                @if($update->body)
                                    <p class="mt-3 text-sm text-ink-200">{{ $update->body }}</p>
                                @endif
                                @if($update->image)
                                    <img src="{{ storage_url($update->image) }}" alt="{{ $update->title }}" class="mt-4 w-full rounded-xl">
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-ink-300">No updates posted yet. We'll share progress here as work progresses.</p>
                    @endforelse
                </div>
            </div>

            <aside class="space-y-6">
                @if($project->cover_image)
                    <img src="{{ storage_url($project->cover_image) }}" alt="{{ $project->title }}" class="w-full rounded-2xl border border-white/10">
                @endif

                @if(count($gallery))
                    <div class="surface p-6">
                        <h3 class="text-lg font-semibold text-white">Gallery</h3>
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            @foreach($gallery as $image)
                                <img src="{{ storage_url($image) }}" alt="" class="h-28 w-full rounded-lg object-cover">
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="surface p-6">
                    <h3 class="text-lg font-semibold text-white">Need help?</h3>
                    <p class="mt-2 text-sm text-ink-300">Contact your project manager or reach us anytime.</p>
                    <a href="{{ route('contact') }}" class="btn-outline btn mt-4 w-full">Contact Us</a>
                </div>
            </aside>
        </div>
    </section>
@endsection

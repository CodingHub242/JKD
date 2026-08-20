@extends('layouts.app')

@section('content')
    <section class="page-hero">
        <div class="container-x">
            <p class="eyebrow reveal">About Us</p>
            <h1 class="page-hero__title reveal reveal-delay-1">Building with purpose, precision & pride.</h1>
        </div>
    </section>

    <section class="section container-x">
        <div class="grid gap-10 lg:grid-cols-3">
            <div class="surface reveal p-8">
                <h3 class="text-lg font-semibold text-brand-300">Our Story</h3>
                <p class="mt-3 text-ink-200">{{ $story }}</p>
            </div>
            <div class="surface reveal reveal-delay-1 p-8">
                <h3 class="text-lg font-semibold text-brand-300">Mission</h3>
                <p class="mt-3 text-ink-200">{{ $mission }}</p>
            </div>
            <div class="surface reveal reveal-delay-2 p-8">
                <h3 class="text-lg font-semibold text-brand-300">Vision</h3>
                <p class="mt-3 text-ink-200">{{ $vision }}</p>
            </div>
        </div>
    </section>

    <section class="border-t border-white/5 bg-ink-900/40 section container-x">
        <div class="max-w-2xl">
            <p class="eyebrow reveal">The Team</p>
            <h2 class="mt-4 text-3xl font-bold sm:text-4xl">People behind the craft.</h2>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($team as $member)
                <div class="surface reveal overflow-hidden">
                    @if($member->photo)
                        <img src="{{ asset($member->photo) }}" alt="{{ $member->name }}" class="h-56 w-full object-cover">
                    @else
                        <div class="grid h-56 w-full place-items-center bg-brand-500/10 text-4xl font-bold text-brand-400">{{ substr($member->name, 0, 1) }}</div>
                    @endif
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-white">{{ $member->name }}</h3>
                        <p class="text-sm text-brand-300">{{ $member->role }}</p>
                        @if($member->bio)<p class="mt-3 text-sm text-ink-300">{{ $member->bio }}</p>@endif
                    </div>
                </div>
            @empty
                <p class="text-ink-300">Our team profiles are being updated.</p>
            @endforelse
        </div>
    </section>

    <section class="section container-x">
        <div class="surface reveal relative overflow-hidden rounded-3xl px-8 py-16 text-center sm:px-16">
            <h2 class="text-3xl font-bold sm:text-4xl">Want to build with us?</h2>
            <p class="mx-auto mt-4 max-w-xl text-ink-300">Whether you're a client or looking to join the team, we'd love to hear from you.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ url('/quote') }}" class="btn-primary btn">Request a Quote</a>
                <a href="{{ url('/careers') }}" class="btn-outline btn">Join the Team</a>
            </div>
        </div>
    </section>
@endsection

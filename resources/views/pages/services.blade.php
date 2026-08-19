@extends('layouts.app')

@section('content')
    <section class="page-hero">
        <div class="container-x">
            <p class="eyebrow reveal">Our Services</p>
            <h1 class="page-hero__title reveal reveal-delay-1">What we do, end to end.</h1>
            <p class="mt-4 max-w-2xl text-ink-300 reveal reveal-delay-2">From the first sketch to final handover, {{ $site['company_name'] ?? 'JKD PINNacle' }} delivers construction and design solutions with uncompromising quality.</p>
        </div>
    </section>

    <section class="section container-x">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($services as $service)
                <div class="service-card reveal">
                    <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand-500/15 text-brand-400">
                        {!! $service->icon ?: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/></svg>' !!}
                    </div>
                    <h3 class="mt-5 text-xl font-semibold">{{ $service->title }}</h3>
                    <p class="mt-2 text-sm text-ink-300">{{ $service->short_description }}</p>
                    @if($service->description)
                        <p class="mt-3 text-sm text-ink-400">{{ $service->description }}</p>
                    @endif
                </div>
            @empty
                <p class="text-ink-300">Our services are being updated. Please check back soon.</p>
            @endforelse
        </div>
    </section>

    <section class="section container-x">
        <div class="surface reveal relative overflow-hidden rounded-3xl px-8 py-16 text-center sm:px-16">
            <h2 class="text-3xl font-bold sm:text-4xl">Have a project in mind?</h2>
            <p class="mx-auto mt-4 max-w-xl text-ink-300">Tell us what you're building and we'll craft a plan that fits your vision and budget.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ url('/quote') }}" class="btn-primary btn">Request a Quote</a>
                <a href="{{ url('/contact') }}" class="btn-outline btn">Talk to Us</a>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section
        class="hero"
        x-data="{ active: 0, count: 3 }"
        x-init="setInterval(() => active = (active + 1) % count, 5500)"
    >
        <div class="hero__slide is-active" :class="active === 0 && 'is-active'" style="background-image:url('{{ asset('slide1.jpg') }}'); background-size:cover; background-position:center;"></div>
        <div class="hero__slide" :class="active === 1 && 'is-active'" style="background-image:url('{{ asset('slide2.jpg') }}'); background-size:cover; background-position:center;"></div>
        <div class="hero__slide" :class="active === 2 && 'is-active'" style="background-image:url('{{ asset('slide3.jpg') }}'); background-size:cover; background-position:center;"></div>

        <div class="hero__overlay"></div>

        <div class="hero__content container-x" data-parallax="-0.06">
            <p class="eyebrow reveal">Construction · Design · Build</p>
            <h1 class="hero__title reveal reveal-delay-1 mt-5 text-balance">
                We build <span class="accent">landmarks</span><br>that outlast generations.
            </h1>
            <p class="mt-6 max-w-xl text-lg text-ink-200 reveal reveal-delay-2">
                JKD PINNacle delivers world-class construction and design expertise — from foundation to finishing, with precision you can trust.
            </p>
            <div class="mt-9 flex flex-wrap gap-3 reveal reveal-delay-3">
                <a href="{{ url('/quote') }}" class="btn-primary btn">Get a Quote</a>
                <a href="{{ url('/projects') }}" class="btn-outline btn">View Projects</a>
            </div>
        </div>

        <div class="hero__dots">
            <template x-for="i in count" :key="i">
                <button class="hero__dot" :class="active === i - 1 && 'is-active'" @click="active = i - 1" :aria-label="'Go to slide ' + i"></button>
            </template>
        </div>
    </section>

    {{-- Stats strip --}}
    <section class="border-y border-white/5 bg-ink-900/40">
        <div class="container-x grid grid-cols-2 gap-6 py-12 md:grid-cols-4">
            <div class="stat reveal">
                <div class="font-display text-4xl font-bold text-brand-400">120+</div>
                <div class="mt-1 text-sm text-ink-300">Projects Delivered</div>
            </div>
            <div class="stat reveal reveal-delay-1">
                <div class="font-display text-4xl font-bold text-brand-400">18</div>
                <div class="mt-1 text-sm text-ink-300">Years of Expertise</div>
            </div>
            <div class="stat reveal reveal-delay-2">
                <div class="font-display text-4xl font-bold text-brand-400">98%</div>
                <div class="mt-1 text-sm text-ink-300">Client Satisfaction</div>
            </div>
            <div class="stat reveal reveal-delay-3">
                <div class="font-display text-4xl font-bold text-brand-400">40+</div>
                <div class="mt-1 text-sm text-ink-300">Skilled Artisans</div>
            </div>
        </div>
    </section>

    {{-- Services teaser --}}
    <section class="section container-x">
        <div class="max-w-2xl">
            <p class="eyebrow reveal">What We Do</p>
            <h2 class="mt-4 text-3xl font-bold sm:text-4xl">End-to-end construction, done right.</h2>
            <p class="mt-4 text-ink-300">From residential homes to commercial complexes, our team handles every stage with craftsmanship and care.</p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            <div class="service-card reveal">
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand-500/15 text-brand-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/></svg>
                </div>
                <h3 class="mt-5 text-xl font-semibold">Design & Build</h3>
                <p class="mt-2 text-sm text-ink-300">Architectural design through to final handover, under one accountable team.</p>
            </div>
            <div class="service-card reveal reveal-delay-1">
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand-500/15 text-brand-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 21v-7h3V9a2 2 0 00-2-2H9a2 2 0 00-2 2v5h3v7"/></svg>
                </div>
                <h3 class="mt-5 text-xl font-semibold">Renovations</h3>
                <p class="mt-2 text-sm text-ink-300">Breathing new life into existing spaces with modern, durable finishes.</p>
            </div>
            <div class="service-card reveal reveal-delay-2">
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand-500/15 text-brand-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v18M3 12h18"/></svg>
                </div>
                <h3 class="mt-5 text-xl font-semibold">Project Management</h3>
                <p class="mt-2 text-sm text-ink-300">Transparent timelines, budgets and quality control from start to finish.</p>
            </div>
        </div>
    </section>

    {{-- Project video boast --}}
    <section class="relative overflow-hidden bg-ink-900">
        <div class="absolute inset-0">
            <img src="{{ asset('slide2.jpg') }}" alt="" class="h-full w-full object-cover opacity-30">
        </div>
        <div class="relative container-x section">
            <div class="max-w-2xl reveal">
                <p class="eyebrow">World-Class Craft</p>
                <h2 class="mt-4 text-3xl font-bold sm:text-4xl">A standard of design ability & expertise few can match.</h2>
                <p class="mt-4 text-ink-200">Every JKD PINNacle project is a statement of engineering excellence and architectural vision — built to be admired for decades.</p>
                <a href="{{ url('/projects') }}" class="btn-primary btn mt-8">Explore Our Work</a>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="section container-x">
        <div class="surface reveal relative overflow-hidden rounded-3xl px-8 py-16 text-center sm:px-16">
            <h2 class="text-3xl font-bold sm:text-4xl">Ready to build something exceptional?</h2>
            <p class="mx-auto mt-4 max-w-xl text-ink-300">Tell us about your project and we'll get back to you with a tailored plan.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ url('/quote') }}" class="btn-primary btn">Request a Quote</a>
                <a href="{{ url('/contact') }}" class="btn-outline btn">Talk to Us</a>
            </div>
        </div>
    </section>
@endsection

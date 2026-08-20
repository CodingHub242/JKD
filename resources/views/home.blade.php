@extends('layouts.app')

@section('content')
    {{-- Hero / slider --}}
    <section
        class="hero"
        x-data="{ active: 0, count: {{ $sliders->isNotEmpty() ? $sliders->count() : 3 }} }"
        x-init="setInterval(() => active = (active + 1) % count, 5500)"
    >
        @if($sliders->isNotEmpty())
            @foreach($sliders as $i => $slide)
                @php
                    $raw = $slide->media_path;
                    $mediaUrl = str_starts_with($raw, 'http')
                        ? $raw
                        : (file_exists(public_path($raw)) ? asset($raw) : url('storage/'.$raw));
                    $posterUrl = $slide->poster
                        ? (str_starts_with($slide->poster, 'http') ? $slide->poster : (file_exists(public_path($slide->poster)) ? asset($slide->poster) : url('storage/'.$slide->poster)))
                        : '';
                @endphp
                <div class="hero__slide" :class="active === {{ $i }} && 'is-active'">
                    @if($slide->media_type === 'video')
                        <video class="hero__media" autoplay muted loop playsinline poster="{{ $posterUrl }}">
                            <source src="{{ $mediaUrl }}" type="video/mp4">
                        </video>
                    @else
                        <div class="hero__media" style="background-image:url('{{ $mediaUrl }}'); background-size:cover; background-position:center;"></div>
                    @endif

                    <div class="hero__content container-x" data-parallax="-0.06">
                        @if($slide->subtitle)<p class="eyebrow reveal">{{ $slide->subtitle }}</p>@endif
                        <h1 class="hero__title reveal reveal-delay-1 mt-5 text-balance">{{ $slide->title }}</h1>
                        @if($slide->button_text)
                            <div class="mt-9 flex flex-wrap gap-3 reveal reveal-delay-3">
                                <a href="{{ $slide->button_url ?: url('/quote') }}" class="btn-primary btn">{{ $slide->button_text }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="hero__slide is-active" style="background-image:url('{{ asset('slide1.jpg') }}'); background-size:cover; background-position:center;"></div>
            <div class="hero__slide" style="background-image:url('{{ asset('slide2.jpg') }}'); background-size:cover; background-position:center;"></div>
            <div class="hero__slide" style="background-image:url('{{ asset('slide3.jpg') }}'); background-size:cover; background-position:center;"></div>

            <div class="hero__content container-x" data-parallax="-0.06">
                <p class="eyebrow reveal">Construction · Design · Build</p>
                <h1 class="hero__title reveal reveal-delay-1 mt-5 text-balance">
                    We build <span class="accent">landmarks</span><br>that outlast generations.
                </h1>
                <p class="mt-6 max-w-xl text-lg text-ink-200 reveal reveal-delay-2">
                    {{ $site['company_name'] ?? 'JKD PINNacle' }} delivers world-class construction and design expertise — from foundation to finishing, with precision you can trust.
                </p>
                <div class="mt-9 flex flex-wrap gap-3 reveal reveal-delay-3">
                    <a href="{{ url('/quote') }}" class="btn-primary btn">Get a Quote</a>
                    <a href="{{ url('/projects') }}" class="btn-outline btn">View Projects</a>
                </div>
            </div>
        @endif

        <div class="hero__overlay"></div>

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
                <div class="font-display text-4xl font-bold text-brand-400">50+</div>
                <div class="mt-1 text-sm text-ink-300">Projects Delivered</div>
            </div>
            <div class="stat reveal reveal-delay-1">
                <div class="font-display text-4xl font-bold text-brand-400">10+</div>
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
            @forelse($services as $service)
                <div class="service-card reveal">
                    <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand-500/15 text-brand-400">
                        {!! $service->icon ?: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/></svg>' !!}
                    </div>
                    <h3 class="mt-5 text-xl font-semibold">{{ $service->title }}</h3>
                    <p class="mt-2 text-sm text-ink-300">{{ $service->short_description }}</p>
                </div>
            @empty
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
            @endforelse
        </div>

        <div class="mt-10">
            <a href="{{ url('/services') }}" class="btn-outline btn">View All Services</a>
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
                <p class="mt-4 text-ink-200">Every {{ $site['company_name'] ?? 'JKD PINNACLE CONSTRUCTION' }} project is a statement of engineering excellence and architectural vision — built to be admired for decades.</p>
                <a href="{{ url('/projects') }}" class="btn-primary btn mt-8">Explore Our Work</a>
            </div>
        </div>
    </section>

    {{-- Portfolio preview --}}
    <section class="section container-x">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="max-w-2xl">
                <p class="eyebrow reveal">Selected Work</p>
                <h2 class="mt-4 text-3xl font-bold sm:text-4xl">Projects we're proud of.</h2>
            </div>
            <a href="{{ url('/projects') }}" class="btn-outline btn reveal">View All Projects</a>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
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
                <p class="text-ink-300">Our portfolio is being updated. Check back soon.</p>
            @endforelse
        </div>
    </section>

    {{-- Testimonials --}}
    @if($testimonials->isNotEmpty())
        <section class="border-t border-white/5 bg-ink-900/40 section container-x">
            <div class="max-w-2xl">
                <p class="eyebrow reveal">Client Voices</p>
                <h2 class="mt-4 text-3xl font-bold sm:text-4xl">What our clients say.</h2>
            </div>
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach($testimonials as $t)
                    <div class="surface reveal p-7">
                        <p class="text-ink-200">“{{ $t->quote }}”</p>
                        <div class="mt-6 flex items-center gap-3">
                            @if($t->avatar)<img src="{{ url('storage/'.$t->avatar) }}" alt="{{ $t->name }}" class="h-11 w-11 rounded-full object-cover">@endif
                            <div>
                                <div class="font-semibold text-white">{{ $t->name }}</div>
                                <div class="text-sm text-ink-400">{{ $t->role }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

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

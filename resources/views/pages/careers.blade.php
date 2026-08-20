@extends('layouts.app')

@section('content')
    <section class="page-hero">
        <div class="container-x">
            <p class="eyebrow reveal">Careers</p>
            <h1 class="page-hero__title reveal reveal-delay-1">Build your career with the best.</h1>
            <p class="mt-4 max-w-2xl text-ink-300 reveal reveal-delay-2">We're always looking for talented professionals and skilled artisans — plumbers, tilers, masons, electricians, engineers and more.</p>
        </div>
    </section>

    <section class="section container-x">
        <div class="mx-auto max-w-3xl">
            <div class="surface reveal p-8 sm:p-10">
                <h2 class="text-2xl font-bold">Apply to join JKD PINNACLE CONSTRUCTION</h2>
                <p class="mt-2 text-sm text-ink-300">Fill in your details and we'll be in touch if there's a fit.</p>

                @if(session('success'))
                    <div class="mt-6 rounded-xl border border-brand-400/40 bg-brand-500/10 px-4 py-3 text-sm text-brand-200">{{ session('success') }}</div>
                @endif

                <form class="mt-8 space-y-5" action="{{ route('inquiry.application') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="field-label">Full Name</label>
                            <input type="text" name="name" required class="field" value="{{ old('name') }}">
                        </div>
                        <div>
                            <label class="field-label">Email</label>
                            <input type="email" name="email" required class="field" value="{{ old('email') }}">
                        </div>
                        <div>
                            <label class="field-label">Phone</label>
                            <input type="text" name="phone" class="field" value="{{ old('phone') }}">
                        </div>
                        <div>
                            <label class="field-label">Position / Role</label>
                            <input type="text" name="position" required class="field" placeholder="e.g. Site Engineer, Tiler" value="{{ old('position') }}">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Trade / Specialty</label>
                            <input type="text" name="trade" class="field" placeholder="e.g. Plumbing, Electrical" value="{{ old('trade') }}">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Experience</label>
                            <textarea name="experience" rows="4" class="field" placeholder="Tell us about your experience...">{{ old('experience') }}</textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Upload CV (PDF/DOC, max 5MB)</label>
                            <input type="file" name="cv" accept=".pdf,.doc,.docx" class="field">
                        </div>
                    </div>
                    <button type="submit" class="btn-primary btn w-full sm:w-auto">Submit Application</button>
                </form>
            </div>
        </div>
    </section>
@endsection

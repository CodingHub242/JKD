@extends('layouts.app')

@section('content')
    <section class="page-hero">
        <div class="container-x">
            <p class="eyebrow reveal">Get a Quote</p>
            <h1 class="page-hero__title reveal reveal-delay-1">Tell us about your project.</h1>
            <p class="mt-4 max-w-2xl text-ink-300 reveal reveal-delay-2">Share a few details and our team will prepare a tailored estimate for you.</p>
        </div>
    </section>

    <section class="section container-x">
        <div class="mx-auto max-w-3xl">
            <div class="surface reveal p-8 sm:p-10">
                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-brand-400/40 bg-brand-500/10 px-4 py-3 text-sm text-brand-200">{{ session('success') }}</div>
                @endif

                <form class="space-y-5" action="{{ route('inquiry.quote') }}" method="POST">
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
                            <label class="field-label">Service Type</label>
                            <select name="service_type" class="field">
                                <option value="">Select a service</option>
                                <option value="Design & Build">Design & Build</option>
                                <option value="Renovation">Renovation</option>
                                <option value="Commercial">Commercial</option>
                                <option value="Residential">Residential</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Estimated Budget (optional)</label>
                            <input type="number" name="budget" min="0" step="100" class="field" placeholder="e.g. 50000" value="{{ old('budget') }}">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Project Details</label>
                            <textarea name="message" rows="5" class="field" placeholder="Describe your project, timeline and any specifics...">{{ old('message') }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary btn w-full sm:w-auto">Request Quote</button>
                </form>
            </div>
        </div>
    </section>
@endsection

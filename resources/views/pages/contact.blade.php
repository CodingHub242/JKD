@extends('layouts.app')

@section('content')
    <section class="page-hero">
        <div class="container-x">
            <p class="eyebrow reveal">Contact Us</p>
            <h1 class="page-hero__title reveal reveal-delay-1">Let's talk about your build.</h1>
            <p class="mt-4 max-w-2xl text-ink-300 reveal reveal-delay-2">Reach us by phone, message, or book a live meeting. We typically respond within one business day.</p>
        </div>
    </section>

    <section class="section container-x">
        <div class="grid gap-10 lg:grid-cols-2">
            {{-- Info + call + chat --}}
            <div class="space-y-6">
                <div class="surface reveal p-8">
                    <h3 class="text-lg font-semibold text-white">Get in touch directly</h3>
                    <ul class="mt-4 space-y-3 text-ink-200">
                        <li><span class="text-ink-400">Email:</span> <a href="mailto:{{ $site['company_email'] ?? '' }}" class="text-brand-300">{{ $site['company_email'] ?? '' }}</a></li>
                        <li><span class="text-ink-400">Phone:</span> <a href="tel:{{ $site['company_phone'] ?? '' }}" class="text-brand-300">{{ $site['company_phone'] ?? '' }}</a></li>
                        <li><span class="text-ink-400">Address:</span> {{ $site['company_address'] ?? '' }}</li>
                    </ul>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="tel:{{ $site['company_phone'] ?? '' }}" class="btn-primary btn">Call Us</a>
                        <button type="button" @click="open = true" class="btn-outline btn">Live Chat</button>
                    </div>
                </div>

                <div class="surface reveal reveal-delay-1 p-8">
                    <h3 class="text-lg font-semibold text-white">Schedule a Site Visit</h3>
                    <p class="mt-2 text-sm text-ink-300">Pick a preferred date and we'll confirm with you.</p>
                    <form class="mt-5 space-y-4" action="{{ route('inquiry.site-visit') }}" method="POST">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <input type="text" name="name" required placeholder="Your name" class="field">
                            <input type="email" name="email" required placeholder="Email" class="field">
                            <input type="text" name="phone" placeholder="Phone" class="field">
                            <input type="date" name="preferred_date" class="field">
                            <input type="text" name="preferred_time" placeholder="Preferred time" class="field">
                        </div>
                        <textarea name="notes" rows="3" placeholder="Address / notes" class="field"></textarea>
                        <button type="submit" class="btn-primary btn w-full sm:w-auto">Request Visit</button>
                    </form>
                </div>

                <div class="surface reveal reveal-delay-2 p-8">
                    <h3 class="text-lg font-semibold text-white">Book a Live Meeting</h3>
                    <p class="mt-2 text-sm text-ink-300">We'll generate a private video room you can join instantly.</p>
                    <form class="mt-5 space-y-4" action="{{ route('inquiry.meeting') }}" method="POST">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <input type="text" name="name" required placeholder="Your name" class="field">
                            <input type="email" name="email" required placeholder="Email" class="field">
                            <input type="text" name="phone" placeholder="Phone" class="field">
                            <input type="text" name="topic" placeholder="Meeting topic" class="field">
                            <input type="datetime-local" name="scheduled_at" class="field">
                        </div>
                        <button type="submit" class="btn-primary btn w-full sm:w-auto">Request Meeting</button>
                    </form>
                    @if(session('success') && Str::contains(session('success'), 'http'))
                        <div class="mt-4 rounded-xl border border-brand-400/40 bg-brand-500/10 px-4 py-3 text-sm text-brand-200 break-words">{{ session('success') }}</div>
                    @endif
                </div>
            </div>

            {{-- Contact form --}}
            <div class="surface reveal p-8 sm:p-10">
                <h2 class="text-2xl font-bold">Send us a message</h2>
                @if(session('success') && !Str::contains(session('success'), 'http'))
                    <div class="mt-4 rounded-xl border border-brand-400/40 bg-brand-500/10 px-4 py-3 text-sm text-brand-200">{{ session('success') }}</div>
                @endif
                <form class="mt-6 space-y-5" action="{{ route('inquiry.contact') }}" method="POST">
                    @csrf
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="field-label">Name</label>
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
                            <label class="field-label">Subject</label>
                            <input type="text" name="subject" class="field" value="{{ old('subject') }}">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Message</label>
                            <textarea name="message" rows="5" required class="field" placeholder="How can we help?">{{ old('message') }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary btn w-full sm:w-auto">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    {{-- Live chat widget --}}
    <div x-data="chatWidget()" class="fixed bottom-5 right-5 z-[60]" x-cloak>
        <button x-show="!open" @click="open = true" class="grid h-14 w-14 place-items-center rounded-full bg-brand-500 text-ink-950 shadow-lg transition hover:bg-brand-400" aria-label="Open chat">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5M21 12a9 9 0 01-9 9 9.75 9.75 0 01-3-.5L3 21l1.5-4.5A9 9 0 1121 12z"/></svg>
        </button>

        <div x-show="open" @click.outside="open = false" class="flex h-[28rem] w-[22rem] max-w-[calc(100vw-2.5rem)] flex-col overflow-hidden rounded-2xl border border-white/10 bg-ink-900 shadow-2xl">
            <div class="flex items-center justify-between bg-brand-500 px-4 py-3 text-ink-950">
                <span class="font-semibold">Live Chat</span>
                <button @click="open = false" class="text-ink-950/70 hover:text-ink-950">&times;</button>
            </div>

            <div class="flex-1 space-y-3 overflow-y-auto p-4" id="chat-log">
                <template x-for="m in messages" :key="m.id">
                    <div :class="m.sender_type === 'visitor' ? 'text-right' : 'text-left'">
                        <span class="inline-block max-w-[80%] rounded-2xl px-3 py-2 text-sm" :class="m.sender_type === 'visitor' ? 'bg-brand-500 text-ink-950' : 'bg-white/10 text-ink-100'" x-text="m.body"></span>
                    </div>
                </template>
                <p x-show="messages.length === 0" class="text-sm text-ink-400">Start the conversation — we usually reply fast.</p>
            </div>

            <div class="border-t border-white/10 p-3">
                <div x-show="!started" class="mb-2 grid grid-cols-2 gap-2">
                    <input type="text" x-model="name" placeholder="Your name" class="field py-2 text-sm">
                    <input type="email" x-model="email" placeholder="Email" class="field py-2 text-sm">
                </div>
                <form @submit.prevent="send">
                    <div class="flex gap-2">
                        <input type="text" x-model="message" placeholder="Type a message..." class="field py-2 text-sm" :disabled="sending">
                        <button type="submit" class="btn-primary btn px-4 py-2" :disabled="sending">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatWidget', () => ({
            open: false,
            name: '',
            email: '',
            message: '',
            messages: [],
            lastId: 0,
            started: false,
            sending: false,
            init() {
                this.poll();
                setInterval(() => this.poll(), 3000);
            },
            async poll() {
                try {
                    const res = await fetch('{{ route('chat.messages') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await res.json();
                    if (data.ok && data.messages.length) {
                        data.messages.forEach(m => this.messages.push(m));
                        this.lastId = data.messages[data.messages.length - 1].id;
                        this.$nextTick(() => { const el = document.getElementById('chat-log'); if (el) el.scrollTop = el.scrollHeight; });
                    }
                } catch (e) {}
            },
            async send() {
                if (!this.message.trim()) return;
                this.sending = true;
                const payload = { message: this.message };
                let url = '{{ route('chat.send') }}';
                if (!this.started) {
                    if (!this.name.trim()) { alert('Please enter your name to start the chat.'); this.sending = false; return; }
                    url = '{{ route('chat.start') }}';
                    payload.name = this.name;
                    payload.email = this.email;
                }
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if (data.ok) {
                        this.started = true;
                        this.message = '';
                        this.poll();
                    }
                } catch (e) {}
                this.sending = false;
            }
        }));
    });
</script>
@endpush

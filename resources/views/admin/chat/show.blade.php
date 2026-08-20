@extends('admin.layout')

@php
    $title = 'Chat · ' . $conversation->name;
@endphp

@section('content')
    <a href="{{ route('admin.chat.index') }}" class="text-sm text-ink-400 hover:text-white">&larr; All Conversations</a>

    <div x-data="adminChat({{ $conversation->id }})" class="admin-card mt-4 flex h-[70vh] flex-col">
        <div class="border-b border-white/10 px-5 py-3">
            <div class="font-semibold text-white">{{ $conversation->name }}</div>
            <div class="text-sm text-ink-400">{{ $conversation->email ?? 'no email' }} · {{ ucfirst($conversation->status) }}</div>
        </div>

        <div class="flex-1 space-y-3 overflow-y-auto p-5" id="admin-chat-log">
            <template x-for="m in messages" :key="m.id">
                <div :class="m.sender_type === 'admin' ? 'text-right' : 'text-left'">
                    <span class="inline-block max-w-[80%] rounded-2xl px-3 py-2 text-sm" :class="m.sender_type === 'admin' ? 'bg-brand-500 text-ink-950' : 'bg-white/10 text-ink-100'" x-text="m.body"></span>
                    <div class="mt-0.5 text-[10px] text-ink-500" x-text="m.created_at"></div>
                </div>
            </template>
            <p x-show="messages.length === 0" class="text-sm text-ink-400">No messages yet.</p>
        </div>

        <form class="border-t border-white/10 p-4" @submit.prevent="send">
            <div class="flex gap-2">
                <input type="text" x-model="message" placeholder="Type your reply..." class="field" :disabled="sending">
                <button type="submit" class="btn-primary btn px-5" :disabled="sending">Send</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminChat', (conversationId) => ({
            message: '',
            messages: [],
            lastId: 0,
            sending: false,
            init() {
                // Listen for new messages via WebSocket
                window.Echo.private(`chat.${conversationId}`)
                    .listen('NewChatMessage', (e) => {
                        if (e.conversation_id === conversationId) {
                            this.messages.push(e);
                            this.lastId = e.id;
                            this.$nextTick(() => {
                                const el = document.getElementById('admin-chat-log');
                                if (el) el.scrollTop = el.scrollHeight;
                            });
                        }
                    });

                // Fallback: load initial messages via HTTP
                this.loadInitial();
            },
            async loadInitial() {
                try {
                    const res = await fetch('{{ url('/admin/chat') }}/' + conversationId + '/messages?last_id=0', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (data.ok && data.messages.length) {
                        data.messages.forEach(m => this.messages.push(m));
                        this.lastId = data.messages[data.messages.length - 1].id;
                        this.$nextTick(() => {
                            const el = document.getElementById('admin-chat-log');
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                    }
                } catch (e) {}
            },
            async send() {
                if (!this.message.trim()) return;
                this.sending = true;
                try {
                    const res = await fetch('{{ url('/admin/chat') }}/' + conversationId + '/reply', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        body: JSON.stringify({ message: this.message })
                    });
                    const data = await res.json();
                    if (data.ok) {
                        this.message = '';
                    }
                } catch (e) {}
                this.sending = false;
            }
        }));
    });
</script>
@endpush

@extends('admin.layout')

@php
    $title = 'Chat · ' . $conversation->name;
@endphp

@section('content')
    <a href="{{ route('admin.chat.index') }}" class="text-sm text-ink-400 hover:text-white">&larr; All Conversations</a>

    <div x-data="adminChat()" data-conversation-id="{{ $conversation->id }}" class="admin-card mt-4 flex h-[70vh] flex-col">
        <div class="border-b border-white/10 px-5 py-3 flex items-center justify-between">
            <div>
                <div class="font-semibold text-white">{{ $conversation->name }}</div>
                <div class="text-sm text-ink-400">{{ $conversation->email ?? 'no email' }} · {{ ucfirst($conversation->status) }}</div>
                <div x-show="visitorTyping" class="text-xs text-brand-300">Visitor is typing...</div>
            </div>
            <div class="flex gap-2">
                <button @click="join" x-show="!agentJoined" class="btn-primary btn px-3 py-1.5 text-sm">Join Chat</button>
                <button @click="leave" x-show="agentJoined" class="btn-outline btn px-3 py-1.5 text-sm">Leave Chat</button>
            </div>
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
                <input type="text" x-model="message" @input.debounce.300ms="onTyping" placeholder="Type your reply..." class="field" :disabled="sending">
                <button type="submit" class="btn-primary btn px-5" :disabled="sending">Send</button>
            </div>
        </form>
    </div>
@endsection

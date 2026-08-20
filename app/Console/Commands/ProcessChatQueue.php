<?php

namespace App\Console\Commands;

use App\Jobs\ChatJob;
use App\Models\Conversation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessChatQueue extends Command
{
    protected $signature = 'chat:process-queue';
    protected $description = 'Process pending chat jobs (SMS notifications, etc.)';

    public function handle(): void
    {
        // Find conversations with new visitor messages that haven't been SMS-notified yet
        // This runs on a schedule to ensure SMS notifications are sent even if the queue worker is down
        $conversations = Conversation::where('agent_joined_at', null)
            ->where('last_activity_at', '>', now()->subHours(2))
            ->get();

        foreach ($conversations as $conversation) {
            $lastVisitorMessage = $conversation->messages()
                ->where('sender_type', 'visitor')
                ->latest()
                ->first();

            if ($lastVisitorMessage) {
                // Dispatch job for background processing
                ChatJob::dispatch($conversation->id, $lastVisitorMessage->body, 'visitor');
            }
        }

        $this->info('Chat queue processed: ' . $conversations->count() . ' conversations checked.');
    }
}

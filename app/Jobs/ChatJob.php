<?php

namespace App\Jobs;

use App\Models\Conversation;
use App\Services\ArkeselSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $conversationId,
        public ?string $message = null,
        public ?string $senderType = null,
    ) {}

    public function handle(ArkeselSmsService $sms): void
    {
        $conversation = Conversation::find($this->conversationId);

        if (! $conversation) {
            return;
        }

        $conversation->update(['last_activity_at' => now()]);

        // Only send SMS for the first visitor message or when admin hasn't joined yet
        if ($this->senderType === 'visitor' && $conversation->agent_joined_at === null) {
            $chatUrl = url('/admin/chat/' . $conversation->id);
            $text = $this->message
                ? "New message from {$conversation->name}: {$this->message}. Open chat: {$chatUrl}"
                : "New live chat from {$conversation->name}. Open chat: {$chatUrl}";

            try {
                $number = \App\Models\Setting::getValue('sms_admin_number');
                if ($number) {
                    $sms->sendSms($number, $text);
                }
            } catch (\Throwable $e) {
                // SMS failures should not break the job.
            }
        }
    }
}

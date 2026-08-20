<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Services\ArkeselSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ChatController extends Controller
{
    public function __construct(protected ArkeselSmsService $sms) {}

    public function start(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $conversation = Conversation::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'status' => 'open',
            'last_activity_at' => now(),
        ]);

        $conversation->messages()->create([
            'sender_type' => 'visitor',
            'body' => $data['message'],
        ]);

        // Send SMS to admin with direct chat link
        $chatUrl = URL::to('/admin/chat/' . $conversation->id);
        $this->notifyAdmin("New live chat from {$conversation->name}. Open chat: {$chatUrl}");

        return response()->json(['ok' => true, 'conversation_id' => $conversation->id]);
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => ['required', 'integer', 'exists:conversations,id'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $conversation = Conversation::findOrFail($data['conversation_id']);

        $message = $conversation->messages()->create([
            'sender_type' => 'visitor',
            'body' => $data['message'],
        ]);

        $conversation->update(['last_activity_at' => now()]);

        // Send SMS to admin on every new visitor message
        $chatUrl = URL::to('/admin/chat/' . $conversation->id);
        $this->notifyAdmin("New message from {$conversation->name}: {$message->body}. Open chat: {$chatUrl}");

        return response()->json(['ok' => true, 'message' => ['id' => $message->id, 'body' => $message->body, 'sender_type' => 'visitor']]);
    }

    public function messages(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);
        $lastId = (int) $request->query('last_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->orderBy('id')
            ->get(['id', 'sender_type', 'body', 'created_at']);

        return response()->json([
            'ok' => true,
            'messages' => $messages,
            'agent_joined' => $conversation->agent_joined_at !== null,
            'agent_typing' => $conversation->agent_typing_at !== null && $conversation->agent_typing_at->greaterThan(now()->subSeconds(3)),
        ]);
    }

    public function typing(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => ['required', 'integer', 'exists:conversations,id'],
            'sender' => ['required', 'in:visitor,agent'],
            'clear' => ['nullable', 'bool'],
        ]);

        $conversation = Conversation::findOrFail($data['conversation_id']);

        if ($data['clear'] ?? false) {
            if ($data['sender'] === 'visitor') {
                $conversation->update(['visitor_typing_at' => null]);
            } else {
                $conversation->update(['agent_typing_at' => null]);
            }
        } else {
            if ($data['sender'] === 'visitor') {
                $conversation->update(['visitor_typing_at' => now()]);
            } else {
                $conversation->update(['agent_typing_at' => now()]);
            }
        }

        return response()->json(['ok' => true]);
    }

    public function agentJoin(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);
        $conversation->update(['agent_joined_at' => now()]);

        return response()->json(['ok' => true, 'agent_joined' => true]);
    }

    public function agentLeave(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);
        $conversation->update(['agent_joined_at' => null]);

        return response()->json(['ok' => true, 'agent_joined' => false]);
    }

    protected function notifyAdmin(string $message): void
    {
        try {
            $number = \App\Models\Setting::getValue('sms_admin_number');
            if ($number) {
                $this->sms->sendSms($number, $message);
            }
        } catch (\Throwable $e) {
            // SMS failures must not break the user's submission.
        }
    }
}

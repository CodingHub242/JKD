<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\JobApplication;
use App\Models\Meeting;
use App\Models\Message;
use App\Models\Quote;
use App\Models\Contact;
use App\Models\SiteVisit;
use App\Services\ArkeselSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class InquiryController extends Controller
{
    public function __construct(protected ArkeselSmsService $sms) {}

    /* ----------------------------- Quote ----------------------------- */
    public function storeQuote(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'service_type' => ['nullable', 'string', 'max:120'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        $quote = Quote::create($data);

        $this->notifyAdmin("New quote request from {$quote->name} ({$quote->email}).");

        return $this->respond($request, 'Thanks! Your quote request has been received. We\'ll be in touch shortly.');
    }

    /* ----------------------------- Contact ----------------------------- */
    public function storeContact(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $contact = Contact::create($data);

        $this->notifyAdmin("New contact message from {$contact->name} ({$contact->email}).");

        return $this->respond($request, 'Your message has been sent. Our team will respond soon.');
    }

    /* ----------------------------- Site visit ----------------------------- */
    public function storeSiteVisit(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_time' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $visit = SiteVisit::create($data);

        $this->notifyAdmin("New site visit request from {$visit->name} for {$visit->preferred_date}.");

        return $this->respond($request, 'Site visit requested. We\'ll confirm the schedule with you.');
    }

    /* ----------------------------- Meeting ----------------------------- */
    public function storeMeeting(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'topic' => ['nullable', 'string', 'max:255'],
            'scheduled_at' => ['nullable', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:15', 'max:240'],
        ]);

        $room = 'jkdpinnacle-' . substr(md5(uniqid()), 0, 12);
        $data['jitsi_room'] = $room;
        $data['duration_minutes'] = $data['duration_minutes'] ?? 30;

        $meeting = Meeting::create($data);

        $this->notifyAdmin("New meeting request from {$meeting->name} for {$meeting->scheduled_at}.");

        $joinUrl = 'https://' . ($this->jitsiDomain()) . '/' . $room;

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Meeting requested. Here is your private room link.',
                'join_url' => $joinUrl,
            ]);
        }

        return redirect()->back()->with('success', 'Meeting requested. Your private room: ' . $joinUrl);
    }

    /* ----------------------------- Job application ----------------------------- */
    public function storeApplication(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['required', 'string', 'max:120'],
            'trade' => ['nullable', 'string', 'max:120'],
            'experience' => ['nullable', 'string', 'max:3000'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('cvs', 'public');
        }

        $application = JobApplication::create($data);

        $this->notifyAdmin("New job application from {$application->name} for {$application->position}.");

        return $this->respond($request, 'Application received! Our HR team will review and get back to you.');
    }

    /* ----------------------------- Live chat ----------------------------- */
    public function chatStart(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $conversation = $this->getOrCreateConversation($data['name'], $data['email'] ?? null);

        $message = $conversation->messages()->create([
            'sender_type' => 'visitor',
            'body' => $data['message'],
        ]);

        $conversation->update(['last_activity_at' => now()]);

        // Send SMS to admin with direct chat link
        $chatUrl = URL::to('/admin/chat/' . $conversation->id);
        $this->notifyAdmin("New live chat from {$conversation->name}. Open chat: {$chatUrl}");

        NewSubmission::dispatch('chats', $conversation);
        $this->broadcastDashboardStats();

        return response()->json(['ok' => true, 'conversation_id' => $conversation->id]);
    }

    public function chatSend(Request $request)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $conversation = $this->conversationFromSession();

        if (! $conversation) {
            return response()->json(['ok' => false, 'error' => 'No conversation.'], 422);
        }

        $message = $conversation->messages()->create([
            'sender_type' => 'visitor',
            'body' => $data['message'],
        ]);

        $conversation->update(['last_activity_at' => now()]);

        // Send SMS to admin on every new visitor message
        $chatUrl = URL::to('/admin/chat/' . $conversation->id);
        $this->notifyAdmin("New message from {$conversation->name}: {$message->body}. Open chat: {$chatUrl}");

        NewSubmission::dispatch('chats', $conversation);
        $this->broadcastDashboardStats();

        return response()->json(['ok' => true, 'message' => ['id' => $message->id, 'body' => $message->body, 'sender_type' => 'visitor']]);
    }

    public function chatMessages(Request $request)
    {
        $conversation = $this->conversationFromSession();

        if (! $conversation) {
            return response()->json(['ok' => true, 'messages' => []]);
        }

        $lastId = (int) $request->query('last_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->orderBy('id')
            ->get(['id', 'sender_type', 'body', 'created_at']);

        return response()->json(['ok' => true, 'messages' => $messages]);
    }

    /* ----------------------------- Helpers ----------------------------- */
    protected function getOrCreateConversation(string $name, ?string $email)
    {
        $id = session('chat_conversation_id');

        if ($id) {
            $conversation = Conversation::find($id);
            if ($conversation) {
                return $conversation;
            }
        }

        $conversation = Conversation::create([
            'name' => $name,
            'email' => $email,
            'status' => 'open',
            'last_activity_at' => now(),
        ]);

        session(['chat_conversation_id' => $conversation->id]);

        return $conversation;
    }

    protected function conversationFromSession(): ?Conversation
    {
        $id = session('chat_conversation_id');

        return $id ? Conversation::find($id) : null;
    }

    protected function jitsiDomain(): string
    {
        return \App\Models\Setting::getValue('jitsi_domain', 'meet.jit.si');
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

    protected function broadcastDashboardStats(): void
    {
        $stats = [
            'projects' => \App\Models\Project::count(),
            'quotes_new' => \App\Models\Quote::where('status', 'new')->count(),
            'contacts_new' => \App\Models\Contact::where('status', 'new')->count(),
            'visits' => \App\Models\SiteVisit::where('status', 'requested')->count(),
            'meetings' => \App\Models\Meeting::where('status', 'requested')->count(),
            'applications' => \App\Models\JobApplication::where('status', 'new')->count(),
            'chats_open' => \App\Models\Conversation::where('status', 'open')->count(),
        ];
        \App\Events\DashboardStatsUpdated::dispatch($stats);
    }

    protected function respond(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }
}

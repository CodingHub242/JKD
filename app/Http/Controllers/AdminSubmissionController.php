<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Contact;
use App\Models\JobApplication;
use App\Models\Meeting;
use App\Models\Message;
use App\Models\Quote;
use App\Models\SiteVisit;
use Illuminate\Http\Request;

class AdminSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    protected function types(): array
    {
        return [
            'quotes' => [
                'model' => Quote::class,
                'label' => 'Quote Requests',
                'statuses' => ['new' => 'New', 'contacted' => 'Contacted', 'quoted' => 'Quoted', 'closed' => 'Closed'],
            ],
            'contacts' => [
                'model' => Contact::class,
                'label' => 'Contact Messages',
                'statuses' => ['new' => 'New', 'replied' => 'Replied', 'closed' => 'Closed'],
            ],
            'site_visits' => [
                'model' => SiteVisit::class,
                'label' => 'Site Visit Requests',
                'statuses' => ['requested' => 'Requested', 'scheduled' => 'Scheduled', 'completed' => 'Completed', 'cancelled' => 'Cancelled'],
            ],
            'meetings' => [
                'model' => Meeting::class,
                'label' => 'Meeting Requests',
                'statuses' => ['requested' => 'Requested', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled'],
            ],
            'applications' => [
                'model' => JobApplication::class,
                'label' => 'Job Applications',
                'statuses' => ['new' => 'New', 'reviewing' => 'Reviewing', 'accepted' => 'Accepted', 'rejected' => 'Rejected'],
            ],
        ];
    }

    protected function typeConfig(string $type): array
    {
        $types = $this->types();
        if (! isset($types[$type])) {
            abort(404);
        }

        return $types[$type];
    }

    public function index($type)
    {
        $config = $this->typeConfig($type);
        $model = $config['model'];

        $items = $model::latest()->paginate(15);

        return view('admin.submissions.index', compact('type', 'config', 'items'));
    }

    public function show($type, $id)
    {
        $config = $this->typeConfig($type);
        $model = $config['model'];
        $item = $model::findOrFail($id);

        return view('admin.submissions.show', compact('type', 'config', 'item'));
    }

    public function update(Request $request, $type, $id)
    {
        $config = $this->typeConfig($type);
        $model = $config['model'];
        $item = $model::findOrFail($id);

        $request->validate(['status' => 'required|string|in:' . implode(',', array_keys($config['statuses']))]);

        $item->update(['status' => $request->input('status')]);

        return redirect()->route('admin.submissions.show', [$type, $id])->with('success', 'Status updated.');
    }

    /* ----------------------------- Live chat console ----------------------------- */
    public function chatIndex()
    {
        $conversations = Conversation::withCount('messages')
            ->orderByRaw("status = 'open' DESC")
            ->orderByDesc('last_activity_at')
            ->paginate(20);

        return view('admin.chat.index', compact('conversations'));
    }

    public function chatShow($id)
    {
        $conversation = Conversation::with(['messages' => fn ($q) => $q->orderBy('id')])->findOrFail($id);

        $conversation->messages()->where('sender_type', 'visitor')->update(['read_at' => now()]);

        return view('admin.chat.show', compact('conversation'));
    }

    public function chatReply(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);

        $data = $request->validate(['message' => 'required|string|max:3000']);

        $message = $conversation->messages()->create([
            'sender_type' => 'admin',
            'body' => $data['message'],
        ]);

        $conversation->update(['last_activity_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => ['id' => $message->id, 'body' => $message->body, 'sender_type' => 'admin', 'created_at' => $message->created_at->toDateTimeString()],
            ]);
        }

        return redirect()->route('admin.chat.show', $id);
    }

    public function chatMessages(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);
        $lastId = (int) $request->query('last_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->orderBy('id')
            ->get(['id', 'sender_type', 'body', 'created_at']);

        return response()->json(['ok' => true, 'messages' => $messages]);
    }
}

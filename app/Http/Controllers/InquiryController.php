<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Meeting;
use App\Models\Quote;
use App\Models\Contact;
use App\Models\SiteVisit;
use App\Services\ArkeselSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $fileName = \Illuminate\Support\Str::random(40) . '.' . $request->file('cv')->getClientOriginalExtension();
            $path = 'cvs/' . $fileName;

            if (env('R2_BUCKET')) {
                Storage::disk('r2')->put($path, $request->file('cv')->getContent(), 'public');
            } else {
                $publicPath = public_path('cvs');
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }
                $request->file('cv')->move($publicPath, $fileName);
            }

            $data['cv_path'] = $path;
        }

        $application = JobApplication::create($data);

        $this->notifyAdmin("New job application from {$application->name} for {$application->position}.");

        return $this->respond($request, 'Application received! Our HR team will review and get back to you.');
    }

    /* ----------------------------- Helpers ----------------------------- */
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

    protected function respond(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }
}

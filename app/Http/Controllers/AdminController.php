<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\JobApplication;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\Quote;
use App\Models\Contact;
use App\Models\SiteVisit;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $stats = [
            'projects' => Project::count(),
            'quotes_new' => Quote::where('status', 'new')->count(),
            'contacts_new' => Contact::where('status', 'new')->count(),
            'visits' => SiteVisit::where('status', 'requested')->count(),
            'meetings' => Meeting::where('status', 'requested')->count(),
            'applications' => JobApplication::where('status', 'new')->count(),
            'chats_open' => Conversation::where('status', 'open')->count(),
        ];

        $recentQuotes = Quote::latest()->take(5)->get();
        $recentContacts = Contact::latest()->take(5)->get();
        $openChats = Conversation::withCount('messages')->where('status', 'open')->latest('last_activity_at')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentQuotes', 'recentContacts', 'openChats'));
    }

    public function settings()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        $fields = [
            'company_name' => ['label' => 'Company Name', 'type' => 'text'],
            'company_tagline' => ['label' => 'Company Tagline', 'type' => 'text'],
            'company_email' => ['label' => 'Company Email', 'type' => 'email'],
            'company_phone' => ['label' => 'Company Phone', 'type' => 'text'],
            'company_address' => ['label' => 'Company Address', 'type' => 'text'],
            'company_description' => ['label' => 'Company Description', 'type' => 'textarea'],
            'loading_text' => ['label' => 'Preloader Text', 'type' => 'text'],
            'loading_subtext' => ['label' => 'Preloader Subtext', 'type' => 'text'],
            'social_facebook' => ['label' => 'Facebook URL', 'type' => 'url'],
            'social_instagram' => ['label' => 'Instagram URL', 'type' => 'url'],
            'social_linkedin' => ['label' => 'LinkedIn URL', 'type' => 'url'],
            'social_twitter' => ['label' => 'Twitter / X URL', 'type' => 'url'],
            'social_youtube' => ['label' => 'YouTube URL', 'type' => 'url'],
            'sms_admin_number' => ['label' => 'Admin SMS Number (Arkesel)', 'type' => 'text'],
            'jitsi_domain' => ['label' => 'Jitsi Domain', 'type' => 'text'],
            'about_story' => ['label' => 'About — Our Story', 'type' => 'textarea'],
            'about_mission' => ['label' => 'About — Mission', 'type' => 'textarea'],
            'about_vision' => ['label' => 'About — Vision', 'type' => 'textarea'],
        ];

        return view('admin.settings', compact('settings', 'fields'));
    }

    public function settingsUpdate(Request $request)
    {
        $rules = [
            'company_name' => 'nullable|string|max:255',
            'company_tagline' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:100',
            'company_address' => 'nullable|string|max:255',
            'company_description' => 'nullable|string|max:2000',
            'loading_text' => 'nullable|string|max:255',
            'loading_subtext' => 'nullable|string|max:255',
            'social_facebook' => 'nullable|string|max:255',
            'social_instagram' => 'nullable|string|max:255',
            'social_linkedin' => 'nullable|string|max:255',
            'social_twitter' => 'nullable|string|max:255',
            'social_youtube' => 'nullable|string|max:255',
            'sms_admin_number' => 'nullable|string|max:50',
            'jitsi_domain' => 'nullable|string|max:100',
            'about_story' => 'nullable|string|max:3000',
            'about_mission' => 'nullable|string|max:2000',
            'about_vision' => 'nullable|string|max:2000',
        ];

        $data = $request->validate($rules);

        foreach ($data as $key => $value) {
            Setting::setValue($key, $value ?? '');
        }

        // Clear cached settings to ensure fresh data is loaded
        Cache::forget('settings.all');
        Cache::forget('site.settings');

        // Clear compiled Blade views so URL changes (e.g. storage -> public) take effect
        Artisan::call('view:clear');

        return redirect()->route('admin.settings')->with('success', 'Settings saved.');
    }
}

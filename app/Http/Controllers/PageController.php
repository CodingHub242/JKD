<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function services()
    {
        $services = Service::active()->ordered()->get();

        return view('pages.services', compact('services'));
    }

    public function projects(Request $request)
    {
        $category = $request->query('category');

        $query = Project::active()->ordered();

        if ($category) {
            $query->where('category', $category);
        }

        $projects = $query->paginate(9)->withQueryString();

        $categories = Project::active()
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category')
            ->sort()
            ->values();

        return view('pages.projects', compact('projects', 'categories', 'category'));
    }

    public function projectShow($slug)
    {
        $project = Project::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $project->load(['updates' => fn ($q) => $q->orderByDesc('posted_at')]);

        $gallery = is_array($project->gallery) ? $project->gallery : [];

        $related = Project::active()
            ->where('id', '!=', $project->id)
            ->where('category', $project->category)
            ->ordered()
            ->take(3)
            ->get();

        return view('pages.project-show', compact('project', 'gallery', 'related'));
    }

    public function about()
    {
        $team = TeamMember::active()->ordered()->get();

        $story = $this->setting('about_story', 'Founded on a simple belief — that great buildings are born from great relationships — JKD PINNacle has grown into one of the region\'s most trusted construction partners.');
        $mission = $this->setting('about_mission', 'To deliver world-class construction and design solutions with uncompromising quality, transparency and care.');
        $vision = $this->setting('about_vision', 'To be the benchmark for excellence in construction across West Africa and beyond.');

        return view('pages.about', compact('team', 'story', 'mission', 'vision'));
    }

    public function careers()
    {
        return view('pages.careers');
    }

    public function quote()
    {
        return view('pages.quote');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    protected function setting(string $key, $default = null)
    {
        return \App\Models\Setting::getValue($key, $default);
    }
}

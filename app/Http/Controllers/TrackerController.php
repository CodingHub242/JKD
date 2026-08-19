<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackerController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $projects = $user->projects()
            ->with(['updates' => fn ($q) => $q->latest('posted_at')->take(1)])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tracker.index', compact('projects'));
    }

    public function show($slug)
    {
        $user = Auth::user();

        $project = $user->projects()
            ->where('slug', $slug)
            ->firstOrFail();

        $project->load(['updates' => fn ($q) => $q->orderByDesc('posted_at')]);

        $gallery = is_array($project->gallery) ? $project->gallery : [];
        $progress = $project->latest_progress;

        return view('tracker.show', compact('project', 'gallery', 'progress'));
    }
}

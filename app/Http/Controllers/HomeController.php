<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::active()->ordered()->get();

        $services = Service::active()->ordered()->take(6)->get();

        $projects = Project::active()->featured()->ordered()->take(6)->get();
        if ($projects->count() < 6) {
            $projects = Project::active()->ordered()->take(6)->get();
        }

        $testimonials = Testimonial::active()->ordered()->take(6)->get();

        return view('home', compact('sliders', 'services', 'projects', 'testimonials'));
    }
}

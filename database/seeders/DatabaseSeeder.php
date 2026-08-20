<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    private function copyAsset(string $publicFile, string $storageDir): string
    {
        $source = public_path($publicFile);
        if (! File::exists($source)) {
            return $publicFile;
        }

        $targetDir = storage_path('app/public/' . $storageDir);
        File::ensureDirectoryExists($targetDir);

        $name = basename($publicFile);
        $target = $targetDir . '/' . $name;
        File::copy($source, $target);

        return $storageDir . '/' . $name;
    }

    public function run(): void
    {
        // /* Admin user */
        // User::updateOrCreate(
        //     ['email' => 'admin@jkdpinnacle.com'],
        //     ['name' => 'Site Admin', 'password' => Hash::make('password'), 'is_admin' => true, 'phone' => '+233 20 000 0000']
        // );

        // /* Demo client (building tracker) */
        // $client = User::updateOrCreate(
        //     ['email' => 'client@demo.com'],
        //     ['name' => 'Kwame Mensah', 'password' => Hash::make('password'), 'is_admin' => false, 'phone' => '+233 24 000 0000']
        // );

        // /* Settings */
        // $settings = [
        //     'company_name' => 'JKD PINNacle',
        //     'company_tagline' => 'Building Excellence',
        //     'company_email' => 'hello@jkdpinnacle.com',
        //     'company_phone' => '+233 30 000 0000',
        //     'company_address' => 'Accra, Ghana',
        //     'company_description' => 'World-class construction, design and build solutions delivered with precision and pride.',
        //     'loading_text' => 'Welcome to JKD PINNacle',
        //     'loading_subtext' => 'Building Excellence',
        //     'jitsi_domain' => 'meet.jit.si',
        //     'about_story' => 'Founded on a simple belief — that great buildings are born from great relationships — JKD PINNacle has grown into one of the region\'s most trusted construction partners.',
        //     'about_mission' => 'To deliver world-class construction and design solutions with uncompromising quality, transparency and care.',
        //     'about_vision' => 'To be the benchmark for excellence in construction across West Africa and beyond.',
        // ];
        // foreach ($settings as $key => $value) {
        //     Setting::setValue($key, $value);
        // }

        // /* Sliders */
        // $slides = [
        //     ['title' => 'We build landmarks that outlast generations.', 'subtitle' => 'Construction · Design · Build', 'button_text' => 'Get a Quote', 'media_path' => 'slide1.jpg'],
        //     ['title' => 'Precision engineering, beautiful results.', 'subtitle' => 'Residential & Commercial', 'button_text' => 'View Projects', 'media_path' => 'slide2.jpg'],
        //     ['title' => 'Your vision, built to perfection.', 'subtitle' => 'End-to-end Project Management', 'button_text' => 'Talk to Us', 'media_path' => 'slide3.jpg'],
        // ];
        // foreach ($slides as $i => $s) {
        //     Slider::updateOrCreate(
        //         ['sort_order' => $i + 1],
        //         [
        //             'title' => $s['title'],
        //             'subtitle' => $s['subtitle'],
        //             'button_text' => $s['button_text'],
        //             'button_url' => '/quote',
        //             'media_type' => 'image',
        //             'media_path' => $this->copyAsset($s['media_path'], 'sliders'),
        //             'active' => true,
        //         ]
        //     );
        // }

        // /* Services */
        // $services = [
        //     ['title' => 'Design & Build', 'short_description' => 'Architectural design through to final handover under one accountable team.', 'icon' => 'M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6'],
        //     ['title' => 'Renovations', 'short_description' => 'Breathing new life into existing spaces with modern, durable finishes.', 'icon' => 'M14 21v-7h3V9a2 2 0 00-2-2H9a2 2 0 00-2 2v5h3v7'],
        //     ['title' => 'Project Management', 'short_description' => 'Transparent timelines, budgets and quality control from start to finish.', 'icon' => 'M12 3v18M3 12h18'],
        //     ['title' => 'Civil & Structural', 'short_description' => 'Foundations, frameworks and infrastructure built to last.', 'icon' => 'M3 21h18M5 21V9l4-3 4 3v12'],
        //     ['title' => 'Interior Finishing', 'short_description' => 'Premium fit-outs, flooring and detailing that elevate every space.', 'icon' => 'M4 4h16v16H4z'],
        //     ['title' => 'Consultancy', 'short_description' => 'Expert advice on planning, costing and compliance.', 'icon' => 'M8 10h8M8 14h8M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        // ];
        // foreach ($services as $i => $s) {
        //     Service::updateOrCreate(
        //         ['slug' => \Illuminate\Support\Str::slug($s['title'])],
        //         [
        //             'title' => $s['title'],
        //             'short_description' => $s['short_description'],
        //             'description' => $s['short_description'] . ' Our experienced team works closely with you at every stage.',
        //             'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="' . $s['icon'] . '"/></svg>',
        //             'sort_order' => $i + 1,
        //             'active' => true,
        //         ]
        //     );
        // }

        // /* Projects */
        // $projectsData = [
        //     ['title' => 'Azure Heights Residence', 'category' => 'Residential', 'location' => 'East Legon, Accra', 'status' => 'completed', 'featured' => true, 'lat' => 5.667, 'lng' => -0.166],
        //     ['title' => 'Riverside Commercial Complex', 'category' => 'Commercial', 'location' => 'Tema, Ghana', 'status' => 'ongoing', 'featured' => true, 'lat' => 5.669, 'lng' => -0.016],
        //     ['title' => 'Heritage Villa Renovation', 'category' => 'Renovation', 'location' => 'Osu, Accra', 'status' => 'completed', 'featured' => true, 'lat' => 5.556, 'lng' => -0.196],
        //     ['title' => 'Maple Court Apartments', 'category' => 'Residential', 'location' => 'Kumasi, Ghana', 'status' => 'planning', 'featured' => false, 'lat' => 6.688, 'lng' => -1.624],
        //     ['title' => 'Industrial Warehouse', 'category' => 'Industrial', 'location' => 'Tema, Ghana', 'status' => 'ongoing', 'featured' => false, 'lat' => 5.670, 'lng' => -0.020],
        //     ['title' => 'Coastal Retreat', 'category' => 'Residential', 'location' => 'Sekondi, Ghana', 'status' => 'completed', 'featured' => true, 'lat' => 4.933, 'lng' => -1.733],
        // ];

        // foreach ($projectsData as $i => $p) {
        //     $cover = $this->copyAsset('slide' . (($i % 3) + 1) . '.jpg', 'projects');
        //     $gallery = [
        //         $this->copyAsset('slide1.jpg', 'projects'),
        //         $this->copyAsset('slide2.jpg', 'projects'),
        //         $this->copyAsset('slide3.jpg', 'projects'),
        //     ];

        //     $project = Project::updateOrCreate(
        //         ['slug' => \Illuminate\Support\Str::slug($p['title'])],
        //         [
        //             'title' => $p['title'],
        //             'category' => $p['category'],
        //             'location' => $p['location'],
        //             'client_name' => 'Confidential',
        //             'description' => 'A flagship ' . strtolower($p['category']) . ' project delivered by JKD PINNacle with world-class craftsmanship, modern finishes and rigorous quality control.',
        //             'cover_image' => $cover,
        //             'gallery' => $gallery,
        //             'latitude' => $p['lat'],
        //             'longitude' => $p['lng'],
        //             'status' => $p['status'],
        //             'featured' => $p['featured'],
        //             'sort_order' => $i + 1,
        //             'active' => true,
        //         ]
        //     );

        //     if ($i === 0) {
        //         $project->clients()->syncWithoutDetaching([$client->id]);
        //         $project->updates()->createMany([
        //             ['title' => 'Foundation complete', 'body' => 'Groundworks and foundation pouring finished ahead of schedule.', 'progress' => 25, 'posted_at' => now()->subWeeks(4)],
        //             ['title' => 'Superstructure underway', 'body' => 'Columns and floor slabs progressing well.', 'progress' => 60, 'posted_at' => now()->subWeeks(2)],
        //             ['title' => 'Roofing started', 'body' => 'Roof structure and waterproofing now in progress.', 'progress' => 80, 'posted_at' => now()->subDays(3)],
        //         ]);
        //     }
        // }

        // /* Team */
        // $team = [
        //     ['name' => 'Eng. Joseph K. D.', 'role' => 'Founder & CEO', 'bio' => '20+ years leading complex construction projects across West Africa.'],
        //     ['name' => 'Ama Owusu', 'role' => 'Head of Design', 'bio' => 'Architect passionate about functional, beautiful spaces.'],
        //     ['name' => 'Kofi Asare', 'role' => 'Site Manager', 'bio' => 'Ensures every build meets our quality and safety standards.'],
        //     ['name' => 'Efua Mensah', 'role' => 'Client Relations', 'bio' => 'Your point of contact from first call to handover.'],
        // ];
        // foreach ($team as $i => $t) {
        //     TeamMember::updateOrCreate(
        //         ['name' => $t['name']],
        //         ['role' => $t['role'], 'bio' => $t['bio'], 'sort_order' => $i + 1, 'active' => true]
        //     );
        // }

        // /* Testimonials */
        // $testimonials = [
        //     ['name' => 'Mrs. Adwoa', 'role' => 'Homeowner, East Legon', 'quote' => 'JKD PINNacle delivered our dream home on time and beyond our expectations.'],
        //     ['name' => 'Mr. Boateng', 'role' => 'Business Owner, Tema', 'quote' => 'Professional, transparent and incredibly skilled. Highly recommended.'],
        //     ['name' => 'Linda K.', 'role' => 'Property Developer', 'quote' => 'The attention to detail on our commercial complex was outstanding.'],
        // ];
        // foreach ($testimonials as $i => $t) {
        //     Testimonial::updateOrCreate(
        //         ['name' => $t['name']],
        //         ['role' => $t['role'], 'quote' => $t['quote'], 'sort_order' => $i + 1, 'active' => true]
        //     );
        // }
    }
}

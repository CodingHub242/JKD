<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share admin-managed site settings with every view.
        view()->composer('*', function ($view) {
            $defaults = [
                'company_name'      => 'JKD PINNACLE CONSTRUCTION',
                'company_tagline'   => 'Building Excellence',
                'company_email'     => 'hello@jkdpinnacle.com',
                'company_phone'     => '+233 00 000 0000',
                'company_address'   => 'Accra, Ghana',
                'company_description'=> 'World-class construction, design and build solutions delivered with precision and pride.',
                'loading_text'      => 'Welcome to JKD PINNacle',
                'loading_subtext'   => 'Building Excellence',
                'social_facebook'   => '',
                'social_instagram'  => '',
                'social_linkedin'   => '',
                'social_twitter'    => '',
                'social_youtube'    => '',
                'sms_admin_number'  => '',
                'jitsi_domain'      => 'meet.jit.si',
                'about_story'       => '',
                'about_mission'     => '',
                'about_vision'      => '',
            ];

            try {
                $stored = \App\Models\Setting::pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                $stored = [];
            }

            $view->with('site', array_merge($defaults, $stored));
        });
    }
}

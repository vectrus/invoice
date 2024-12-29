<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

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
        // Load all settings from the db into the config
        if (Schema::hasTable('settings')) {
            if (!Config('settings')) {
                foreach (\App\Models\Setting::all() as $setting) {
                    Config::set('settings.' . $setting->key, $setting->value);
                }
            }
        }
    }
}

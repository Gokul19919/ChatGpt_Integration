<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ChatGPTService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(ChatGPTService::class, function ($app) {
            return new ChatGPTService();
        });
    }
    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

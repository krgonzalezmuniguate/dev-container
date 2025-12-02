<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    // public function register(): void
    // {
    //     //
    // }
    protected $listen = [
        \App\Events\NewUserCreated::class => [
            \App\Listeners\SendNewUserCredentials::class,
        ],
        \App\Events\EmailEvent::class => [
            \App\Listeners\EmailListener::class
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

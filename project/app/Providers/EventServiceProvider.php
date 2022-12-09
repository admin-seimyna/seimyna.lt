<?php

namespace App\Providers;

use App\Models\Family\Family;
use App\Models\User;
use App\Models\Verification;
use App\Observers\FamilyObserver;
use App\Observers\UserObserver;
use App\Observers\VerificationObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Family::observe(FamilyObserver::class);
        User::observe(UserObserver::class);
        Verification::observe(VerificationObserver::class);
    }
}

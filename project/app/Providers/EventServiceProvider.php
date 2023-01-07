<?php

namespace App\Providers;

use App\Events\NewUserRegistered;
use App\Listeners\SendEmailVerificationCodeListener;
use App\Models\Family\Family;
use App\Models\Family\Finances\Requisition;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Verification;
use App\Observers\FamilyObserver;
use App\Observers\InvitationObserver;
use App\Observers\RequisitionObserver;
use App\Observers\UserObserver;
use App\Observers\VerificationObserver;
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
        NewUserRegistered::class => [
            SendEmailVerificationCodeListener::class,
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
        Invitation::observe(InvitationObserver::class);
        Requisition::observe(RequisitionObserver::class);
    }
}

<?php

namespace App\Listeners;

use App\Events\NewUserCreatedEvent;
use App\Mail\VerificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationCodeListener
{
    /**
     * @param NewUserCreatedEvent $event
     */
    public function handle(NewUserCreatedEvent $event)
    {
        Mail::send(
            new VerificationMail(
                $event->user,
                $event->verification->type,
                $event->verification->getOriginalCode()
            )
        );
    }
}

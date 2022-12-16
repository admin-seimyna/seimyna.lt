<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Mail\VerificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationCodeListener
{
    /**
     * @param NewUserRegistered $event
     */
    public function handle(NewUserRegistered $event)
    {
        Mail::send(
            new VerificationMail(
                $event->user,
                $event->verification->type,
                $event->verification->getOriginalCode(),
                $event->verification->token
            )
        );
    }
}

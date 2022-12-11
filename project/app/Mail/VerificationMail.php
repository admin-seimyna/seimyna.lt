<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    protected User $user;

    /**
     * @var int
     */
    protected int $code;

    /**
     * @var string
     */
    protected string $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $type, int $code)
    {
        $this->user = $user;
        $this->type = $type;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->user->email, $this->user->name)
            ->subject(trans('mail.title.verification.' . $this->type))
            ->markdown('mail.message',[
                'subject' => trans('mail.title.verification.' . $this->type),
                'code' => $this->code,
                'text' => trans('mail.message.verification.' . $this->type, [
                    'email' => $this->user->email
                ]),
            ]);
    }
}

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
     * @var string
     */
    protected string $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $type, int $code, string $token)
    {
        $this->user = $user;
        $this->type = $type;
        $this->code = $code;
        $this->token = $token;
    }

    /***
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
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

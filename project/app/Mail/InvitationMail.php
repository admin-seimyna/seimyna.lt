<?php

namespace App\Mail;

use App\Models\Family\Member;
use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    protected string $code;

    /**
     * @var Invitation
     */
    protected Invitation $invitation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation, string $code)
    {
        $this->invitation = $invitation->load(['author:id,name', 'family']);
        $this->code = $code;
    }

    /**
     * @return Invitation
     */
    public function getInvitation(): Invitation
    {
        return $this->invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $member = null;
        $this->invitation->family->connectMoment(function() use (&$member) {
            $member = Member::find($this->invitation->member_id);
        });
        return $this->to($this->invitation->identifier, $member->name)
            ->subject(trans('mail.title.verification.' . $this->invitation->type))
            ->markdown('mail.message',[
                'subject' => 'Invitation', // todo: change to normal text
                'code' => $this->code,
                'text' => 'jo jo jo ' // todo: change to normal text
            ]);
    }
}

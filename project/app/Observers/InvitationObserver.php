<?php

namespace App\Observers;

use App\Enum\MemberInvitationTypesEnum;
use App\Jobs\SendMail;
use App\Mail\InvitationMail;
use App\Models\Family\MemberInvitation;
use App\Models\Invitation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvitationObserver
{
    /**
     * @param Invitation $invitation
     */
    public function creating(Invitation $invitation): void
    {
        $invitation->generateCode();
        $invitation->invited_by = Auth::user()->member->id;
        $invitation->expires_in = Carbon::now()->addDay();
    }

    /**
     * @param Invitation $invitation
     */
    public function created(Invitation $invitation): void
    {
        if ($invitation->type === MemberInvitationTypesEnum::EMAIL) {
            SendMail::dispatch(new InvitationMail($invitation, $invitation->getOriginalCode()));
        }
    }
}

<?php

namespace App\Observers;

use App\Models\Family\MemberInvitation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MemberInvitationObserver
{
    /**
     * @param MemberInvitation $invitation
     */
    public function creating(MemberInvitation $invitation): void
    {
        $invitation->token = uniqid().time();
        $invitation->invited_by = Auth::user()->member->id;
        $invitation->expires_in = Carbon::now()->addDay();
    }
}

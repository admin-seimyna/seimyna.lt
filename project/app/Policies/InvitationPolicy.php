<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class InvitationPolicy
{
    use HandlesAuthorization;

    /**
     * @param User|null $user
     * @param Invitation $invitation
     * @return Response
     */
    public function accept(?User $user = null, Invitation $invitation): Response
    {
        return Carbon::now()->isBefore($invitation->expires_in) && is_null($invitation->activated_at) ? Response::allow()
            : Response::deny(trans('auth.message.invitation_is_expired'), 404);
    }
}

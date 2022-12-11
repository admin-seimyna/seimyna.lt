<?php

namespace App\Policies;

use App\Enum\VerificationTypesEnum;
use App\Models\User;
use App\Models\Verification;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VerifyPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Verification $verification
     * @param string $type
     * @return Response
     */
    public function access(User $user, Verification $verification, string $type): Response
    {
        return $verification->module_id === $user->id
        && VerificationTypesEnum::values()->contains($type)
        && $verification->type === $type && empty($verification->verified_at)
            ? Response::allow()
            : Response::deny(trans('auth.message.verification_not_found'), 404);
    }

    /**
     * @param User $user
     * @param Verification $verification
     * @return Response
     */
    public function expiration(User $user, Verification $verification): Response
    {
        return Carbon::now()->isBefore($verification->expires_in)
            ? Response::allow()
            : Response::deny(trans('auth.message.verification_expired'), 401);
    }

    /**
     * @param User $user
     * @param Verification $verification
     * @return Response
     */
    public function resend(User $user, Verification $verification): Response
    {
        return Carbon::now()->isAfter(Carbon::parse($verification->updated_at)->addMinutes(config('auth.verification.resend_period')))
            ? Response::allow()
            : Response::deny(trans('auth.message.verification_to_many_attempts'), 429);
    }
}

<?php

namespace App\Observers;

use App\Models\Verification;
use Carbon\Carbon;

class VerificationObserver
{
    /**
     * @param Verification $verification
     */
    public function creating(Verification $verification): void
    {
        $verification->generateCode();
        $verification->token = uniqid().time();
    }
}

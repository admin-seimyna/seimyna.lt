<?php

namespace App\Observers;

use App\Models\Verification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class VerificationObserver
{
    /**
     * @param Verification $verification
     */
    public function creating(Verification $verification): void
    {
        $code = rand(111111, 999999);
        $verification->setOriginalCode($code);
        $verification->token = uniqid().time();
        $verification->expires_in = Carbon::now()->addDay();
        $verification->code = Hash::make($code);
    }
}

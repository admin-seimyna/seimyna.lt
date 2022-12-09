<?php

namespace App\Http\Response\Api\Auth;

use App\Enum\VerificationTypesEnum;
use App\Http\Requests\Api\Auth\SignUpRequest;
use App\Http\Response\Response;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Support\Facades\Auth;

class SignUpResponse extends Response
{
    /**
     * @param SignUpRequest $request
     */
    public function __construct(SignUpRequest $request)
    {
        $this->user = User::create($request->getData());
        $verification = $this->user->verification()->create(['type' => VerificationTypesEnum::EMAIL]);
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return [
            'user' => $this->user->toArray()
        ];
    }
}

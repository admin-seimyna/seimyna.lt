<?php

namespace App\Http\Response\Api\Auth;

use App\Enum\VerificationTypesEnum;
use App\Events\NewUserRegistered;
use App\Http\Requests\Api\Auth\SignUpRequest;
use App\Http\Response\ApiResponse;
use App\Http\Response\Response;
use App\Models\User;

class SignUpResponse extends Response
{
    /**
     * @var string|void
     */
    protected string $token;

    /**
     * @var User
     */
    protected User $user;

    /**
     * @param SignUpRequest $request
     */
    public function __construct(SignUpRequest $request)
    {
        $this->user = User::create($request->getData());
        $verification = $this->user->verification()->create(['type' => VerificationTypesEnum::EMAIL]);
        event(new NewUserRegistered($this->user, $verification));
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->login($this->user);
    }
}

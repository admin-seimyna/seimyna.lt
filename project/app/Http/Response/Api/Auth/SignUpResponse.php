<?php

namespace App\Http\Response\Api\Auth;

use App\Enum\VerificationTypesEnum;
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
     * @param ApiResponse $response
     */
    public function __construct(ApiResponse $response)
    {
        $this->user = User::create($response->request->getData());
        $this->user->verification()->create(['type' => VerificationTypesEnum::EMAIL]);
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->login($this->user);
    }
}

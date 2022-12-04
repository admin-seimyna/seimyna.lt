<?php

namespace App\Http\Response\Api\Auth;

use App\Http\Requests\Api\Auth\SignUpRequest;
use App\Http\Response\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SignUpResponse extends Response
{
    /**
     * @param SignUpRequest $request
     */
    public function __construct(SignUpRequest $request)
    {
        $this->user = User::create($request->getData());
        Auth::login($this->user);
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

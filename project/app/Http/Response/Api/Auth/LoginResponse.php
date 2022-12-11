<?php

namespace App\Http\Response\Api\Auth;

use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Response\ApiResponse;
use App\Http\Response\Response;

class LoginResponse extends Response
{
    /**
     * @var LoginRequest|\Illuminate\Foundation\Http\FormRequest|\Illuminate\Http\Request
     */
    protected LoginRequest $request;

    /**
     * @param ApiResponse $response
     */
    public function __construct(ApiResponse $response)
    {
        $this->request = $response->request;
    }

    /**
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get(): array
    {
        if (!$this->auth()->attempt($this->request->only(['email', 'password']))) {
            $this->throwFormError(['password' => 'required']);
        }
        return $this->login();
    }
}

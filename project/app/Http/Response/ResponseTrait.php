<?php

namespace App\Http\Response;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

trait ResponseTrait
{
    /**
     * @param array $errors
     * @throws ValidationException
     */
    public function throwFormError(array $errors = [])
    {
        throw ValidationException::withMessages($errors);
    }

    /**
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public function auth()
    {
        return Auth::guard('api');
    }

    /**
     * @return Authenticatable|null
     */
    public function user(): ?Authenticatable
    {
        return $this->auth()->user();
    }
}

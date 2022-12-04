<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\SignUpRequest;
use App\Http\Response\Api\Auth\SignUpResponse;
use App\Http\Response\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    /**
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function post(SignUpRequest $request): JsonResponse
    {
        return ApiResponse::create()
            ->handle(new SignUpResponse($request))
            ->json();
    }
}

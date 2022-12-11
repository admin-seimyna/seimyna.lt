<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return ApiResponse::create()
            ->handle(function(ApiResponse $response) {
                Auth::guard('api')->logout();
                return ['auth/user' => null, 'auth/token' => null];
            })->json();
    }
}

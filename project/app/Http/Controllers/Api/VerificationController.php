<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Verification\PostRequest;
use App\Http\Response\ApiResponse;
use App\Models\Verification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VerificationController extends Controller
{
    /**
     * @param string $type
     * @param Verification $verification
     * @param PostRequest $request
     * @return JsonResponse
     */
    public function post(string $type, Verification $verification, PostRequest $request): JsonResponse
    {
        return ApiResponse::create()
            ->authorize('access', [$verification, $type])
            ->authorize('expiration', $verification)
            ->request($request)
            ->handle(function () use ($verification) {
                $verification->verify();
                return $verification->toArray();
            })
            ->json();
    }
}

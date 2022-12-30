<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\InvitationRequest;
use App\Http\Response\ApiResponse;
use Illuminate\Http\JsonResponse;

class InvitationController
{
    /**
     * @param InvitationRequest $request
     * @return JsonResponse
     */
    public function accept(InvitationRequest $request): JsonResponse
    {
        return ApiResponse::create()
            ->handle(function (ApiResponse $apiResponse) {

            })
            ->request($request)
            ->json();
    }
}

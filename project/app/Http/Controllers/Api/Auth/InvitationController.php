<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\InvitationRequest;
use App\Http\Response\Api\Invitation\AcceptResponse;
use App\Http\Response\ApiResponse;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;

class InvitationController
{
    /**
     * @param InvitationRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function accept(InvitationRequest $request): JsonResponse
    {
        $invitation = Invitation::where('identifier', $request->input('identifier'))->first();
        return ApiResponse::create()
            ->authorize('accept', $invitation)
            ->request($request)
            ->handle(AcceptResponse::class)
            ->json();
    }
}

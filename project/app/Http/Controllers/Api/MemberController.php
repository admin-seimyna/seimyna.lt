<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Member\CreateRequest;
use App\Http\Response\ApiResponse;
use App\Models\Family\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return ApiResponse::create()
            ->request($request)
            ->handle(function (ApiResponse $response) {
                if ($response->request->validateOnly()) {
                    return $response->request->getValidatedMemberData();
                }

                $data = $response->request->getMemberData();
                $member = Member::create($data);
                if ($response->request->shouldInviteMember()) {
                    Auth::user()->currentFamily->inviteViaEmail($response->request->input('email'));
                }
                return $member->toArray();
            })->json();
    }
}

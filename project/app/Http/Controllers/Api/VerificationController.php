<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Verification\PostRequest;
use App\Http\Response\ApiResponse;
use App\Mail\VerificationMail;
use App\Models\Verification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{

    /**
     * @param Verification $verification
     * @return JsonResponse
     */
    public function get(Verification $verification): JsonResponse
    {
        return ApiResponse::create()->handle(function() use ($verification) {
            return $verification;
        })->json();
    }

    /**
     * @param string $type
     * @param Verification $verification
     * @param PostRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function post(string $type, Verification $verification, PostRequest $request): JsonResponse
    {
        return ApiResponse::create()
            ->authorize('access', [$verification, $type])
            ->authorize('expiration', $verification)
            ->request($request)
            ->handle(function (ApiResponse $response) use ($verification) {
                if (!Hash::check($response->request->getCode(), $verification->code)) {
                    $response->throwFormError(['code' => trans('validation.code')]);
                }
                $verification->verify();
                return ['auth/verification' => $verification->toArray()];
            })
            ->json();
    }


    /**
     * @param string $type
     * @param Verification $verification
     * @return JsonResponse
     */
    public function resend(string $type, Verification $verification): JsonResponse
    {
        return ApiResponse::create()
            ->authorize('access', [$verification, $type])
            ->authorize('resend', $verification)
            ->handle(function (ApiResponse $apiResponse) use ($verification) {
                $verification->generateCode();
                $verification->save();

                Mail::send(
                    new VerificationMail(
                        $apiResponse->user(),
                        $verification->type,
                        $verification->getOriginalCode(),
                        $verification->token
                    )
                );

                return $verification->toArray();
            })
            ->json();
    }
}

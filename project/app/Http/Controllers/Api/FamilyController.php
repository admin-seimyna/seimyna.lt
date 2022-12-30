<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Family\CreateRequest;
use App\Http\Response\Api\Family\CreateResponse;
use App\Http\Response\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    /**
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return ApiResponse::create()
            ->request($request)
            ->handle(CreateResponse::class)
            ->json();
    }
}

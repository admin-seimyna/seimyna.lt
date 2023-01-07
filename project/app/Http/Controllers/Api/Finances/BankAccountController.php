<?php

namespace App\Http\Controllers\Api\Finances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Finances\Account\CreateRequest;
use App\Http\Response\ApiResponse;
use App\Models\Family\Finances\Requisition;
use App\Services\Nordigen\NordigenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{
    /**
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return ApiResponse::create()
            ->handle(function (ApiResponse $response) use ($request) {
                return collect($request->getAccounts())->map(function (array $account) {
                    return Auth::user()->member->bankAccount()->create($account);
                })->toArray();
            })
            ->request($request)
            ->json();
    }

    /**
     * @param Requisition $requisition
     * @return JsonResponse
     */
    public function requisition(Requisition $requisition): JsonResponse
    {
        return ApiResponse::create()
            ->handle(function (ApiResponse $apiResponse) use ($requisition) {
                $accounts = NordigenService::connect()->getAccounts($requisition->uid);
                dd($accounts);
            })
            ->json();
    }
}

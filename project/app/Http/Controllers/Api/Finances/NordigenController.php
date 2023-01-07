<?php

namespace App\Http\Controllers\Api\Finances;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Models\Bank;
use App\Models\Family\Finances\BankAccount;
use App\Models\Family\Finances\Requisition;
use App\Services\Nordigen\NordigenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NordigenController extends Controller
{
    /**
     * @param Bank $bank
     * @return JsonResponse
     */
    public function requisition(Bank $bank): JsonResponse
    {
        return ApiResponse::create()
            ->handle(function (ApiResponse $apiResponse) use ($bank) {
                $requisition = Auth::user()->member->requisition()->where('bank_id', $bank->id)->first();
                if (!$requisition) {
                    $requisition = Auth::user()->member->requisition()->create(NordigenService::connect()->createRequisition($bank->id));
                }
                return $requisition->only('link', 'id');
            })->json();
    }

    /**
     * @param Requisition $requisition
     * @return JsonResponse
     */
    public function accounts(Requisition $requisition): JsonResponse
    {
        return ApiResponse::create()
            ->handle(function (ApiResponse $apiResponse) use ($requisition) {
                $service = NordigenService::connect();
                $response = $service->requisition($requisition->uid);
                return collect($response['accounts'])->map(static function (string $accountId) use ($service, $requisition) {
                    return $service->details($accountId, $requisition->id);
                })->toArray();
            })
            ->json();
    }

    /**
     * @param BankAccount $account
     * @return JsonResponse
     */
    public function transactions(BankAccount $account): JsonResponse
    {
        return ApiResponse::create()
            ->handle(function (ApiResponse $apiResponse) use ($account) {
                return NordigenService::connect()->transactions($account);
            })
            ->json();
    }

    /**
     * @param BankAccount $account
     * @return JsonResponse
     */
    public function balance(BankAccount $account): JsonResponse
    {
        return ApiResponse::create()
            ->handle(function (ApiResponse $apiResponse) use ($account) {
                return NordigenService::connect()->balance($account);
            })
            ->json();
    }
}

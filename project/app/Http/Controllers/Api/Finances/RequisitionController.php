<?php

namespace App\Http\Controllers\Api\Finances;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Models\Family\Family;
use App\Models\Family\Finances\Requisition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequisitionController extends Controller
{
    /**
     * @param int $userId
     * @param int $familyId
     * @param Request $request
     * @return JsonResponse
     */
    public function activate(int $userId, int $familyId, Request $request): JsonResponse
    {

        $ref = $request->query('ref');
        $error = $request->query('error');
        if (empty($ref) || !empty($error)) {
            abort(404);
        }

        return ApiResponse::create()
            ->handle(function (ApiResponse $apiResponse) use ($userId, $familyId, $ref) {
                Auth::login(User::find($userId));
                Family::find($familyId)->connectMoment(function () use ($ref) {
                    $requisition = Requisition::where('ref', $ref)->first();
                    $requisition->activated_at = Carbon::now();
                    $requisition->save();
                });
            })->json();
    }
}

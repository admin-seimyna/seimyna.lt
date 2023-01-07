<?php

namespace App\Services\Nordigen;

use App\Models\Bank;
use Carbon\Carbon;

class NordigenAccount
{
    /**
     * @param array $data
     * @param int $requisitionId
     * @return array
     */
    public static function parse(array $data, int $requisitionId): array
    {
        return [
            'name' => !empty(trim($data['owner_name'])) ? $data['owner_name'] : trans('bank_account.title.default_name'),
            'uid' => $data['id'],
            'iban' => $data['iban'],
            'created_at' => Carbon::parse($data['created']),
            'bank_id' => Bank::where('uid', $data['institution_id'])->first()->id,
            'requisition_id' => $requisitionId,
        ];
    }
}

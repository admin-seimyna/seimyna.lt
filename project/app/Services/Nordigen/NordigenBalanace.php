<?php

namespace App\Services\Nordigen;

use App\Models\Family\Finances\BankAccount;

class NordigenBalanace
{
    /**
     * @param array $data
     * @param BankAccount $account
     * @return array
     */
    public static function parse(array $data, BankAccount $account): array
    {
        return [
            'bank_account_id' => $account->id,
            'amount' => $data['balanceAmount'],
            'date' => $data['referenceDate']
        ];
    }
}

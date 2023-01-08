<?php

namespace App\Services\Nordigen;

use App\Enum\TransactionStatusEnum;
use Carbon\Carbon;

class NordigenTransaction
{
    /**
     * @param array $data
     * @param string $status
     * @param int $accountId
     * @return array
     */
    public static function parse(array $data, string $status, int $accountId): array
    {
        return [
            'bank_account_id' => $accountId,
            'uid' => $data['transactionId'] ?? null,
            'name' => static::getName($data),
            'date' => static::getDate($data),
            'amount' => $data['transactionAmount']['amount'],
            'code' => $data['bankTransactionCode'] ?? null,
            'description' => static::getDescription($data),
            'creator' => [
                'name' => $data['creditorName'] ?? null,
                'iban' => isset($data['creditorAccount']) ? $data['creditorAccount']['iban'] : null,
            ],
            'debtor' => [
                'name' => $data['debtorName'] ?? null,
                'iban' => isset($data['debtorAccount']) ? $data['debtorAccount']['iban'] : null,
            ],
            'status' => $status === TransactionStatusEnum::PENDING ? TransactionStatusEnum::PENDING : TransactionStatusEnum::COMPLETE,
        ];
    }

    /**
     * @param array $data
     * @return string
     */
    private static function getName(array $data): string
    {
        if (!empty($data['remittanceInformationUnstructuredArray'])) {
            return implode(' ', $data['remittanceInformationUnstructuredArray']) . ' (' . $data['valueDate'] . ')';
        }

        if (!empty($data['remittanceInformationUnstructured'])) {
            return $data['remittanceInformationUnstructured'];
        }

        if (!empty($data['debtorName']) && !empty($data['creditorName'])) {
            return $data['debtorName'] . ' → ' . $data['creditorName'];
        }

        return trans('transaction.title.default_name', [
            'date' => static::getDate($data)->format('Y-m-d')
        ]);
    }

    /**
     * @param array $data
     * @return Carbon
     */
    private static function getDate(array $data): Carbon
    {
        return Carbon::parse($data['valueDate'] ?? $data['bookingDate']);
    }

    /**
     * @param array $data
     * @return string|null
     */
    private static function getDescription(array $data): ?string
    {
        if (!empty($data['debtorName']) && !empty($data['creditorName'])) {
            return $data['debtorName'] . ' → ' . $data['creditorName'];
        }

        return null;
    }
}

<?php

namespace App\Services\Nordigen;

use App\Models\Bank;

class NordigenRequisition
{
    /**
     * @param array $data
     * @return array
     */
    public static function parse(array $data): array
    {
        return [
            'uid' => $data['id'],
            'link' => $data['link'],
            'redirect' => $data['redirect'],
        ];
    }
}

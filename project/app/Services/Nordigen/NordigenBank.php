<?php

namespace App\Services\Nordigen;

use Illuminate\Support\Str;

class NordigenBank
{
    /**
     * @param array $data
     * @return array
     */
    public static function parse(array $data): array
    {
        return [
            'uid' => $data['id'],
            'name' => $data['name'],
            'bic' => $data['bic'],
            'logo' => $data['logo'],
        ];
    }
}

<?php

namespace App\Http\Requests\Api\Finances\Account;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'accounts' => 'required|array',
            'accounts.*.name' => 'required',
            'accounts.*.iban' => 'required',
            'accounts.*.requisition_id' => 'sometimes|exists:requisitions,id',
            'accounts.*.uid' => 'required_with:accounts.*.requisition_id',
            'accounts.*.bank_id' => 'required|exists:main.banks,id',
        ];
    }

    /**
     * @return array
     */
    public function getAccounts(): array
    {
        return collect($this->input('accounts'))->map(static function ($account) {
            return [
                'name' => $account['name'],
                'uid' => $account['uid'] ?? null,
                'iban' => $account['iban'],
                'bank_id' => $account['bank_id'],
                'requisition_id' => $account['requisition_id'] ?? null,
            ];
        })->toArray();
    }
}

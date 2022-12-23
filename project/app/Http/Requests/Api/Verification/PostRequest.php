<?php

namespace App\Http\Requests\Api\Verification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $length = config('auth.verification.code_length');
        return [
            'code' => 'required|array|min:' . $length . '|max:' . $length,
            'code.*' => 'numeric|min:0|max:9'
        ];
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return (int)implode('', $this->input('code'));
    }
}

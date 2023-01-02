<?php

namespace App\Http\Requests\Api\Member;

use App\Enum\GenderEnum;
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
        $rules = [
            'validate_only' => 'boolean',
            'name' => 'required',
            'gender' => 'required|in:' . GenderEnum::values()->join(','),
            'invite' => 'boolean',
        ];

        if (!!$this->input('invite')) {
            $rules['email'] = 'required|email';
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function getValidatedMemberData(): array
    {
        return $this->only(['name', 'gender', 'email', 'invite']);
    }

    /**
     * @return array
     */
    public function getMemberData(): array
    {
        return $this->only(['name', 'gender', 'email']);
    }

    /**
     * @return bool
     */
    public function shouldInviteMember(): bool
    {
        return (bool)$this->input('invite') && !empty($this->input('email'));
    }

    /**
     * @return bool
     */
    public function validateOnly(): bool
    {
        return (boolean)$this->input('validate_only', false);
    }
}

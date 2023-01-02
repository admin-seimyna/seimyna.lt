<?php

namespace App\Http\Requests\Api\Auth;

use App\Enum\MemberInvitationTypesEnum;
use Illuminate\Foundation\Http\FormRequest;

class InvitationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        switch ($this->getStep())
        {
            case 2: return $this->secondStepRules();
            case 3: return $this->thirdStepRules();
            default: return $this->firstStepRules();
        }
    }

    /**
     * @return int
     */
    private function getStep(): int
    {
        return (int)$this->input('step', 1);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return mb_strtoupper(implode('', $this->input('code')));
    }

    /**
     * @return bool
     */
    public function completed(): bool
    {
        return $this->getStep() === 3;
    }

    /**
     * @return bool
     */
    public function isCodeStep(): bool
    {
        return $this->getStep() === 2;
    }

    /**
     * @return string[]
     */
    private function firstStepRules(): array
    {
        $type = $this->route('type');
        $rules = [
            'step' => 'required|numeric|min:1|max:3',
            'identifier' => ['required']
        ];
        if ($type === MemberInvitationTypesEnum::EMAIL) {
            $rules['identifier'][] = 'email';
        }

        $rules['identifier'][] = 'exists:main.invitations';

        return $rules;
    }

    /**
     * @return array
     */
    private function secondStepRules(): array
    {
        $length = config('auth.invitation.code_length');
        return array_merge($this->firstStepRules(), [
            'code' => 'required|array|min:' . $length . '|max:' . $length,
            'code.*' => 'required'
        ]);
    }

    /**
     * @return array
     */
    private function thirdStepRules(): array
    {
        return array_merge($this->secondStepRules(), [
            'password' => 'required',
            'confirmation' => 'required|same:password'
        ]);
    }
}

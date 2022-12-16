<?php

namespace App\Http\Requests\Family;

use App\Enum\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $step = $this->input('step', 1);
        switch ($step) {
            case 2: return $this->secondStepRules();
            default: return $this->firstStepRules();
        }
    }

    /**
     * @return string[]
     */
    private function firstStepRules(): array
    {
        return [
            'step' => 'required|numeric|min:1|max:2',
            'name' => 'required',
        ];
    }

    /**
     * @return array
     */
    private function secondStepRules(): array
    {
        return array_merge($this->firstStepRules(), [
            'members' => 'required|array|min:1',
            'members.0.user_id' => 'required|in:' . Auth::guard('api')->id(),
            'members.*.name' => 'required',
            'members.*.gender' => 'required|in:' . GenderEnum::values()->join(','),
            'members.*.invite' => 'numeric|boolean',
            'members.*.email' => 'required_if:members.*.invite,1|email',
            'members.*.parents' => 'array',
            'members.*.parents.*' => 'numeric',
            'members.*.children' => 'array',
            'members.*.children.*' => 'numeric',
        ]);
    }


    /**
     * @return array
     */
    public function getFamilyData(): array
    {
        return [
            'name' => $this->input('name')
        ];
    }
}

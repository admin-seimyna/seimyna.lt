<?php

namespace App\Http\Requests\Api\Family;

use App\Enum\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
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
            case 3: return $this->thirdStepRules();
            default: return $this->firstStepRules();
        }
    }

    /**
     * @return Collection
     */
    public function getMembers(): Collection
    {
        return collect($this->input('members'));
    }

    /**
     * @return bool
     */
    public function completed(): bool
    {
        return (int)$this->input('step', 1) === 4;
    }

    /**
     * @return int
     */
    public function getNextStep(): int
    {
        return (int)$this->input('step', 1) + 1;
    }

    /**
     * @return string[]
     */
    private function firstStepRules(): array
    {
        return [
            'step' => 'required|numeric|min:1|max:4',
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
            'members.0.user_id' => 'required|in:' . Auth::id(),
            'members.*.name' => 'required',
            'members.*.gender' => 'required|in:' . GenderEnum::values()->join(','),
        ]);
    }

    /**
     * @return array
     */
    private function thirdStepRules(): array
    {
        return array_merge($this->secondStepRules(), [
            'members.*.name' => 'required',
            'members.*.gender' => 'required|in:' . GenderEnum::values()->join(','),
            'members.*.invite' => 'boolean',
            'members.*.email' => 'required_if:members.*.invite,1|email',
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

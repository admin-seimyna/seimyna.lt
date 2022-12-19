<?php

namespace App\Http\Requests\Family;

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
            'members.0.user_id' => 'required|in:' . Auth::id(),
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
     * @return Collection
     */
    public function getMembers(): Collection
    {
        return collect($this->input('members'))->map(static function (&$member, $id) {
            $member['id'] = $id;
            return $member;
        });
    }

    /**
     * @return Collection
     */
    public function getMembersTree(): Collection
    {
        return $this->getMembers()->map(function ($member) {
            return $this->mapMember($member);
        });
    }

    /**
     * @param array $current
     * @return array
     */
    private function mapMember(array $current): array
    {
        return [
            'id' => $current['id'],
            'name' => $current['name'],
            'children' => $this->getChildren($current['id'])->map(static function ($member) {
                return [
                    'id' => $member['id'],
                    'name' => $member['name']
                ];
            }),
            'related' => $this->getRelated($current['id'])->map(function ($member) use ($current) {
                return [
                    'id' => $member['id'],
                    'name' => $member['name'],
                    'relationship' => $member['related'][$current['id']]
                ];
            })
        ];
    }

    /**
     * @param int $id
     * @return Collection
     */
    private function getChildren(int $id): Collection
    {
        return $this->getMembers()->filter(static function ($member) use ($id) {
            return in_array($id, $member['parents']);
        });
    }

    /**
     * @param int $id
     * @return Collection
     */
    private function getRelated(int $id): Collection
    {
        return $this->getMembers()->filter(static function ($member) use ($id) {
            return in_array($id, array_keys($member['related']));
        });
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

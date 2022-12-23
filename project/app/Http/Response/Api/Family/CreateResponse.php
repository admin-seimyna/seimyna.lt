<?php

namespace App\Http\Response\Api\Family;

use App\Enum\MemberInvitationTypesEnum;
use App\Http\Requests\Family\CreateRequest;
use App\Http\Response\ApiResponse;
use App\Http\Response\Response;
use App\Models\Family\Family;
use App\Models\Family\Member;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CreateResponse extends Response
{
    /**
     * @var Collection
     */
    private Collection $members;

    /**
     * @var Family|null
     */
    private ?Family $family = null;

    /**
     * @param CreateRequest $request
     */
    public function __construct(CreateRequest $request)
    {
        $this->createFamily($request->getFamilyData());

        $this->members = collect();
        $request->getMembers()->each(function ($data) {
            $this->createAndInviteMember($data);
        });
        $this->family->setRelation('members', $this->members);

        $request->getMembersTree()->each(function ($data) {
            $this->createMembersRelationships($data);
        });
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return [
            'family/current' => $this->family
        ];
    }

    private function createFamily(array $data)
    {
        $this->family = $this->user()->family()->create($data);
        $this->family->createConnection();
        $this->family->connect();
        $this->family->migrate();
    }


    /**
     * @param array $data
     */
    private function createAndInviteMember(array $data): void
    {
        $member = $this->family->member()->create($data);
        $this->members[$data['id']] = $member;

        if (empty($data['user_id']) && !empty($data['invite'])) {
            $member->invitation()->create([
                'type' => MemberInvitationTypesEnum::EMAIL,
                'identifier' => $data['email']
            ]);
        }
    }

    /**
     * @param array $data
     */
    private function createMembersRelationships(array $data): void
    {
        $member = $this->members[$data['id']];
        $children = collect($data['children'])->map(function ($child) {
            return $this->members[$child['id']]->id;
        })->values()->toArray();
        $member->children()->attach($children);

        $related = collect($data['related'])->mapWithKeys(function ($relatedTo) {
            return [
                $this->members[$relatedTo['id']]->id => [
                    'status' => $relatedTo['relationship']
                ]
            ];
        })->toArray();
        $member->related()->attach($related);
    }
}

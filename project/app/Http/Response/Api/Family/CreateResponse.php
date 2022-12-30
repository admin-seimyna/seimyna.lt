<?php

namespace App\Http\Response\Api\Family;

use App\Http\Requests\Api\Family\CreateRequest;
use App\Http\Response\Response;
use App\Models\Family\Family;
use App\Models\Family\Member;
use Illuminate\Support\Collection;

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
     * @var CreateRequest
     */
    private CreateRequest $request;

    /**
     * @param CreateRequest $request
     */
    public function __construct(CreateRequest $request)
    {
        $this->request = $request;
        if (!$request->completed()) {
            return;
        }

        $this->createFamily($request->getFamilyData());

        $this->members = $request->getMembers();
        $this->members->each(function ($data) {
            $this->createAndInviteMember($data);
        });
        $this->family->setRelation('members', $this->members);
    }

    /**
     * @return array
     */
    public function get(): array
    {
        if (!$this->request->completed()) {
            return [
                'step' => $this->request->getNextStep(),
            ];
        }

        return [
            'family/current' => $this->family
        ];
    }

    private function createFamily(array $data)
    {
        $this->family = $this->user()->family()->create($data);
        $this->family->connect();
    }


    /**
     * @param array $data
     */
    private function createAndInviteMember(array $data): void
    {
        $member = Member::create($data);
        if (empty($data['user_id']) && !empty($data['invite'])) {
            $this->family->inviteViaEmail($data['email'], $member->id);
        }
    }
}

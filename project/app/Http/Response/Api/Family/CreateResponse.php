<?php

namespace App\Http\Response\Api\Family;

use App\Enum\MemberInvitationTypesEnum;
use App\Http\Requests\Family\CreateRequest;
use App\Http\Response\ApiResponse;
use App\Http\Response\Response;
use Illuminate\Support\Facades\DB;

class CreateResponse extends Response
{
    /**
     * @var CreateRequest|\Illuminate\Foundation\Http\FormRequest|\Illuminate\Http\Request
     */
    protected CreateRequest $request;

    /**
     * @param ApiResponse $apiResponse
     */
    public function __construct(ApiResponse $apiResponse)
    {
        $this->request = $apiResponse->request;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $family = $this->user()->family()->create($this->request->getFamilyData());
        $family->createConnection();
        $family->connect();
        $family->migrate();

        $members = collect([]);
        foreach ($this->request->input('members') as $index => $data) {
            $member = $family->member()->create($data);
            $members->push($member);

            if ($index > 0 && !empty($data['invite'])) {
                $member->invitation()->create([
                    'type' => MemberInvitationTypesEnum::EMAIL,
                    'identifier' => $data['email']
                ]);
            }
        }

        return [
            'family/current' => $family->setRelation('members', $members)->toArray()
        ];
    }
}

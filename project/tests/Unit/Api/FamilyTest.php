<?php

namespace Api;

use App\Enum\GenderEnum;
use App\Enum\MemberRelationEnum;
use App\Enum\VerificationTypesEnum;
use App\Models\Family\Member;
use App\Models\Family\MemberInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FamilyTest extends TestCase
{
    use RefreshDatabase;

    public function testCanCreate()
    {
        $this->login();

        $members = [
            0 => [
                'name' => 'Father',
                'gender' => GenderEnum::MALE,
                'user_id' => Auth::id(),
                'parents' => [4,5],
                'related' => [
                    1 => MemberRelationEnum::SPOUSE
                ]
            ],
            1 => [
                'name' => 'Mother',
                'gender' => GenderEnum::FEMALE,
                'invite' => 1,
                'email' => 'mother@test.io',
                'parents' => [6],
                'related' => [
                    0 => MemberRelationEnum::SPOUSE
                ]
            ],
            2 => [
                'name' => 'Son',
                'gender' => GenderEnum::MALE,
                'parents' => [0, 1],
                'related' => [],
            ],
            3 => [
                'name' => 'Daughter',
                'gender' => GenderEnum::FEMALE,
                'parents' => [0, 1],
                'related' => [],
            ],
            4 => [
                'name' => 'Grandmother',
                'gender' => GenderEnum::FEMALE,
                'invite' => 1,
                'email' => 'grandmother@test.io',
                'parents' => [],
                'related' => [
                    5 => MemberRelationEnum::SPOUSE,
                ],
            ],
            5 => [
                'name' => 'Grandfather',
                'gender' => GenderEnum::MALE,
                'invite' => 1,
                'email' => 'grandfather@test.io',
                'parents' => [],
                'related' => [
                    4 => MemberRelationEnum::SPOUSE,
                ],
            ],
            6 => [
                'name' => 'Grandfather 2',
                'gender' => GenderEnum::MALE,
                'invite' => 1,
                'email' => 'grandfather2@test.io',
                'parents' => [],
                'related' => [],
            ],
            7 => [
                'name' => 'Father brother',
                'gender' => GenderEnum::MALE,
                'parents' => [4,5],
                'related' => [
                    0 => MemberRelationEnum::BROTHERS
                ]
            ],
        ];
        $this->actingAsUser()
            ->post(route('api.family.create'), [
            'step' => 2,
            'name' => 'Test',
            'members' => $members
        ])->assertStatus(200);

        $this->assertCount(count($members), Member::get());
        $this->assertCount(4, MemberInvitation::get());
    }

}

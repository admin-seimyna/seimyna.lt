<?php

namespace Api;

use App\Enum\GenderEnum;
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
        $user = User::factory()->create();
        $user->verification()->create(['type' => VerificationTypesEnum::EMAIL])->verify();
        Auth::guard('api')->login($user);

        $members = [
            0 => [
                'name' => 'Father',
                'gender' => GenderEnum::MALE,
                'user_id' => 1,
                'children' => [2,3],
                'parents' => [4,5],
            ],
            1 => [
                'name' => 'Mother',
                'gender' => GenderEnum::FEMALE,
                'invite' => 1,
                'email' => 'mother@test.io',
                'children' => [0],
                'parents' => [],
            ],
            2 => [
                'name' => 'Son',
                'gender' => GenderEnum::MALE,
                'children' => [],
                'parents' => [0, 1]
            ],
            3 => [
                'name' => 'Daughter',
                'gender' => GenderEnum::FEMALE,
                'children' => [],
                'parents' => [0, 1],
            ],
            4 => [
                'name' => 'Grandmother',
                'gender' => GenderEnum::FEMALE,
                'invite' => 1,
                'email' => 'grandmother@test.io',
                'children' => [0],
                'parents' => [],
            ],
            5 => [
                'name' => 'Grandfather',
                'gender' => GenderEnum::MALE,
                'invite' => 1,
                'email' => 'grandfather@test.io',
                'children' => [0],
                'parents' => [],
            ],
            6 => [
                'name' => 'Grandfather 2',
                'gender' => GenderEnum::MALE,
                'invite' => 1,
                'email' => 'grandfather2@test.io',
                'children' => [1],
                'parents' => [],
            ]
        ];
        $this->post(route('api.family.create'), [
            'step' => 2,
            'name' => 'Test',
            'members' => $members
        ])->assertStatus(200);

        $this->assertCount(count($members), Member::get());
        $this->assertCount(4, MemberInvitation::get());
    }

}

<?php

namespace Api\Family;

use App\Enum\GenderEnum;
use App\Jobs\SendMail;
use App\Mail\InvitationMail;
use App\Models\Family\Family;
use App\Models\Family\Member;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class FamilyTest extends TestCase
{
    use RefreshDatabase;

    public function testCanCreate()
    {
        Queue::fake();
        Mail::fake();

        $this->login();

        $members = [
            0 => [
                'name' => 'Father',
                'gender' => GenderEnum::MALE,
                'user_id' => Auth::id(),
            ],
            1 => [
                'name' => 'Mother',
                'gender' => GenderEnum::FEMALE,
                'invite' => 1,
                'email' => 'mother@test.io',
            ],
            2 => [
                'name' => 'Son',
                'gender' => GenderEnum::MALE,
            ],
            3 => [
                'name' => 'Daughter',
                'gender' => GenderEnum::FEMALE,
            ],
            4 => [
                'name' => 'Grandmother',
                'gender' => GenderEnum::FEMALE,
                'invite' => 1,
                'email' => 'grandmother@test.io',
            ],
            5 => [
                'name' => 'Grandfather',
                'gender' => GenderEnum::MALE,
                'invite' => 1,
                'email' => 'grandfather@test.io',
                'parents' => [],
            ],
            6 => [
                'name' => 'Grandfather 2',
                'gender' => GenderEnum::MALE,
                'invite' => 1,
                'email' => 'grandfather2@test.io',
            ],
            7 => [
                'name' => 'Father brother',
                'gender' => GenderEnum::MALE,
            ],
        ];

        $this ->post(route('api.family.create'), [
                'step' => 1,
                'name' => 'Test',
            ])->assertStatus(200);

        $this->assertCount(0, Family::get());

        $this->post(route('api.family.create'), [
                'step' => 2,
                'name' => 'Test',
                'members' => [
                    $members[0]
                ]
            ])->assertStatus(200);

        $this->assertCount(0, Family::get());

        $this->post(route('api.family.create'), [
            'step' => 4, // skip step 3
            'name' => 'Test',
            'members' => $members
        ])->assertStatus(200);

        $family = Family::first();
        $this->assertNotEmpty($family);
        $this->assertCount(count($members), Member::get());
        $this->assertCount(4, $family->invitations()->get());

        $invitedMembersEmails = collect($members)->where('invite', 1)->pluck('email');
        $jobsCount = 0;
        Queue::assertPushed(SendMail::class, function ($job) use ($invitedMembersEmails, &$jobsCount) {
            if ($invitedMembersEmails->contains($job->getMailable()->getInvitation()->identifier)) {
                $jobsCount++;
                $job->handle();
                return true;
            }
            return false;
        });
        $this->assertEquals($invitedMembersEmails->count(), $jobsCount);

        $mailsCount = 0;
        Mail::assertSent(InvitationMail::class, function (InvitationMail $mail) use ($invitedMembersEmails, &$mailsCount) {
            if ($invitedMembersEmails->contains($mail->getInvitation()->identifier)) {
                $mailsCount++;
                return true;
            }
            return false;
        });
        $this->assertEquals($invitedMembersEmails->count(), $mailsCount);
    }

    public function testCanConnectWithFamilyAccount()
    {
        $this->login(User::factory()->create())->connectFamily();
        // only user member
        $this->assertCount(1, Member::get());
        $this->assertEquals(Auth::id(), Member::first()->user_id);
    }

    public function testCanAddMember()
    {
        $this->login(User::factory()->create())->connectFamily();
        $memberData = [
            'name' => 'Member',
            'gender' => GenderEnum::MALE,
            'invite' => 1,
            'email' => 'member@member.io',
        ];

        // check validation
        $this->post(route('api.member.create'), array_merge($memberData, [
            'validate_only' => 1,
        ]))
            ->assertStatus(200)
            ->assertJsonStructure(['name', 'gender', 'invite', 'email']);
        $this->assertCount(1, Member::get());

        $this->post(route('api.member.create'), $memberData)
            ->assertStatus(200)
            ->assertJsonStructure(['name', 'gender', 'id']);

        $member = Member::orderByDesc('id')->first();
        $this->assertEquals($memberData['name'], $member->name);
        $this->assertCount(2, Member::get());
        $this->assertNotEmpty(Invitation::where('member_id', $member->id)->first());
    }

}

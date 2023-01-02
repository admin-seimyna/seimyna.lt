<?php

namespace Api;

use App\Enum\GenderEnum;
use App\Enum\MemberInvitationTypesEnum;
use App\Models\Family\Family;
use App\Models\Family\Member;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function testCanAcceptInvitation()
    {
        $this->login(User::factory()->create())->connectFamily();

        $this->post(route('api.member.create'), [
            'name' => 'Member',
            'gender' => GenderEnum::MALE,
        ])->assertStatus(200);

        $member = Member::orderByDesc('id')->first();
        $invitation = Auth::user()->currentFamily->invitation()->create([
            'name' => $member->name,
            'member_id' => $member->id,
            'identifier' => 'test@test.io'
        ]);

        $this->logout();

        $this->post(route('api.auth.invitation.accept', [
            'type' => MemberInvitationTypesEnum::EMAIL
        ]), [
            'step' => 1,
            'identifier' => $invitation->identifier
        ])->assertStatus(200);

        $code = str_split((string)$invitation->getOriginalCode());
        $this->post(route('api.auth.invitation.accept', [
            'type' => MemberInvitationTypesEnum::EMAIL
        ]), [
            'step' => 2,
            'identifier' => $invitation->identifier,
            'code' => $code
        ])->assertStatus(200);

        $this->post(route('api.auth.invitation.accept', [
            'type' => MemberInvitationTypesEnum::EMAIL
        ]), [
            'step' => 3,
            'identifier' => $invitation->identifier,
            'code' => $code,
            'password' => '123456',
            'confirmation' => '123456'
        ])->assertStatus(200);

        $family = Family::orderByDesc('id')->first();
        $this->assertCount(2, $family->users()->get());
    }

    public function testCannotAcceptExpiredOrAcceptedInvitation()
    {
        $this->login(User::factory()->create())->connectFamily();

        $this->post(route('api.member.create'), [
            'name' => 'Member',
            'gender' => GenderEnum::MALE,
        ])->assertStatus(200);

        $member = Member::orderByDesc('id')->first();
        $invitation = Auth::user()->currentFamily->invitation()->create([
            'name' => $member->name,
            'member_id' => $member->id,
            'identifier' => 'test@test.io'
        ]);

        $invitation->expires_in = Carbon::now()->subDay();
        $invitation->save();
        $this->post(route('api.auth.invitation.accept', [
            'type' => MemberInvitationTypesEnum::EMAIL
        ]), [
            'step' => 1,
            'identifier' => $invitation->identifier
        ])->assertStatus(404);

        $invitation->expires_in = Carbon::now()->addDay();
        $invitation->activated_at = Carbon::now();
        $invitation->save();
        $this->post(route('api.auth.invitation.accept', [
            'type' => MemberInvitationTypesEnum::EMAIL
        ]), [
            'step' => 1,
            'identifier' => $invitation->identifier
        ])->assertStatus(404);
    }
}

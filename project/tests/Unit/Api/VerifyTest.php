<?php

namespace Api;

use App\Enum\VerificationTypesEnum;
use App\Mail\VerificationMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class VerifyTest extends TestCase
{
    use RefreshDatabase;

    public function testVerify()
    {
        $user = User::factory()->create();
        $verification = $user->verification()->create(['type' => VerificationTypesEnum::EMAIL]);
        Auth::login($user);

        $this->post(route('api.verify.post', [
            'type' => VerificationTypesEnum::EMAIL,
            'unverified' => $verification->token
        ]), ['code' => str_split((string)$verification->getOriginalCode())])
            ->assertStatus(200)
            ->assertJsonStructure(['auth/verification']);

        $this->assertNotEmpty(Auth::user()->verification->verified_at);
    }

    public function testTryVerifyWithOtherUserToken()
    {
        $user = User::factory()->create();
        $verification = $user->verification()->create(['type' => VerificationTypesEnum::EMAIL]);

        // login with new user
        Auth::login(User::factory()->create());

        $this->post(route('api.verify.post', [
            'type' => VerificationTypesEnum::EMAIL,
            'unverified' => $verification->token
        ]), ['code' => str_split((string)$verification->getOriginalCode())])
            ->assertStatus(404);
    }

    public function testCannotAccessWithExpiredVerification()
    {
        $user = User::factory()->create();
        $verification = $user->verification()->create(['type' => VerificationTypesEnum::EMAIL]);
        $verification->expires_in = Carbon::now()->subDay();
        $verification->update();
        Auth::login($user);

        $this->post(route('api.verify.post', [
            'type' => VerificationTypesEnum::EMAIL,
            'unverified' => $verification->token
        ]), ['code' => str_split((string)$verification->getOriginalCode())])
            ->assertStatus(401);
    }

    public function testCanNotAccessWithVerifiedVerification()
    {
        $user = User::factory()->create();
        $verification = $user->verification()->create(['type' => VerificationTypesEnum::EMAIL]);
        Auth::login($user);

        $code = str_split((string)$verification->getOriginalCode());

        $this->post(route('api.verify.post', [
            'type' => VerificationTypesEnum::EMAIL,
            'unverified' => $verification->token
        ]), ['code' => $code])
            ->assertStatus(200);

        $this->post(route('api.verify.post', [
            'type' => VerificationTypesEnum::EMAIL,
            'unverified' => $verification->token
        ]), ['code' => $code])
            ->assertStatus(404);
    }

    public function testCanResend()
    {
        Mail::fake();
        $user = User::factory()->create();
        $verification = $user->verification()->create([
            'type' => VerificationTypesEnum::EMAIL
        ]);

        $verification->updated_at = Carbon::now()->subMinutes(config('auth.verification.resend_period'));
        $verification->touch();
        Auth::login($user);

        $code = str_split((string)$verification->getOriginalCode());

        $this->post(route('api.verify.resend', [
            'type' => VerificationTypesEnum::EMAIL,
            'unverified' => $verification->token
        ]), ['code' => $code])
            ->assertStatus(200);

        Mail::assertSent(VerificationMail::class, function ($mail) use ($user) {
            return $mail->getUser()->id === $user->id;
        });
    }

    public function testResendAttempts()
    {
        $user = User::factory()->create();
        $verification = $user->verification()->create([
            'type' => VerificationTypesEnum::EMAIL
        ]);
        Auth::login($user);

        $code = str_split((string)$verification->getOriginalCode());

        $this->post(route('api.verify.resend', [
            'type' => VerificationTypesEnum::EMAIL,
            'unverified' => $verification->token
        ]), ['code' => $code])
            ->assertStatus(429);
    }
}

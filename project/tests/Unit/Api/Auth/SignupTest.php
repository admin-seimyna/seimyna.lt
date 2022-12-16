<?php

namespace Tests\Unit\Api\Auth;

use App\Mail\VerificationMail;
use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SignupTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSignup()
    {
        Mail::fake();
        $this->post(route('api.auth.signup'), [
            'name' => 'test',
            'email' => 'test@test.test',
            'password' => 'test'
        ])
            ->assertJsonStructure(['auth/token', 'auth/user'])
            ->assertStatus(200);

        $this->assertTrue(Auth::guard('api')->check());
        $user = User::with(['verification' => static function ($query) {
            $query->withoutGlobalScope(new VerifiedScope());
        }])->first();
        $this->assertNotEmpty($user);
        $this->assertNotEmpty($user->verification);
        $this->assertEquals(Carbon::now()->addDay()->format('Y-m-d H:i:s'), $user->verification->expires_in);

        Mail::assertSent(VerificationMail::class, function ($mail) use ($user) {
            return $mail->getUser()->id === $user->id && $mail->getToken() === $user->verification->token;
        });
    }
}

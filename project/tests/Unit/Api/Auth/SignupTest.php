<?php

namespace Tests\Unit\Api\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignupTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSignup()
    {
        $this->post(route('api.auth.signup'), [
            'name' => 'test',
            'email' => 'test@test.test',
            'password' => 'test'
        ])->assertStatus(200);

        $user = User::with('verification')->first();
        $this->assertNotEmpty($user);
        $this->assertNotEmpty($user->verification);
        $this->assertEquals(Carbon::now()->addDay()->format('Y-m-d H:i:s'), $user->verification->expires_in);
    }
}

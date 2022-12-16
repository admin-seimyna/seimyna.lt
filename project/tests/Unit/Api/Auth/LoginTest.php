<?php

namespace Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testCanLogin()
    {
        $user = User::factory()->create([
            'password' => 'test',
        ]);

        $this->post(route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'test'
        ])->assertStatus(200);

        $this->assertEquals($user->id, Auth::guard('api')->id());
    }

    public function testCanNotLoginWithWrongPassword()
    {
        $user = User::factory()->create([
            'password' => 'test',
        ]);

        $this->post(route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'test2'
        ])->assertStatus(302);

        $this->assertEmpty(Auth::guard('api')->id());
    }

    public function testUserCanLogout()
    {
        $user = User::factory()->create([
            'password' => 'test',
        ]);

        Auth::guard('api')->login($user);
        $this->post(route('api.auth.logout'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'auth/user',
                'auth/token'
            ]);
        $this->assertEmpty(Auth::guard('api')->id());
    }
}

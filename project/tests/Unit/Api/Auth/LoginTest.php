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
        $user = $this->createUser();
        $this->post(route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'test'
        ])->assertStatus(200);

        $this->assertEquals($user->id, Auth::id());
    }

    public function testCanNotLoginWithWrongPassword()
    {
        $user = $this->createUser();

        $this->post(route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'test2'
        ])->assertStatus(302);

        $this->assertEmpty(Auth::id());
    }

    public function testUserCanLogout()
    {
        $this->actingAsUser()
            ->post(route('api.auth.logout'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'auth/user',
                'auth/token'
            ]);
        $this->assertEmpty(Auth::id());
    }
}

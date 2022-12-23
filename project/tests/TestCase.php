<?php

namespace Tests;

use App\Enum\VerificationTypesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('cache:clear');
        config(['database.connections.main' => config('database.connections.test')]);
    }

    /**
     *
     */
    protected function tearDown(): void
    {
        $this->artisan('cache:clear');
        parent::tearDown();
    }

    /**
     * @return $this
     */
    protected function actingAsUser(): self
    {
        if (!Auth::check()) {
            $user = User::factory()->create();
            $user->verification()->create(['type' => VerificationTypesEnum::EMAIL]);
            Auth::login($user);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function login(): self
    {
        return $this->actingAsUser();
    }

    /**
     * @return User
     */
    protected function createUser(): User
    {
        $user = User::factory()->create(['password' => 'test']);
        $user->verification()->create(['type' => VerificationTypesEnum::EMAIL]);
        return $user;
    }
}

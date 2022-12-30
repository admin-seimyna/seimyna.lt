<?php

namespace Tests;

use App\Enum\VerificationTypesEnum;
use App\Models\Family\Family;
use App\Models\Family\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Refresh a conventional test database.
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        DB::statement('drop database if exists ' . env('DB_DATABASE_FAMILY'));
        parent::refreshApplication();
    }

    /**
     * Set up
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('cache:clear');
        config(['database.connections.main' => config('database.connections.test')]);
    }

    /**
     * Tear down
     */
    protected function tearDown(): void
    {
        $this->artisan('cache:clear');
        parent::tearDown();
    }

    /**
     * @return $this
     */
    protected function actingAsUser(?User $user = null): self
    {
        if (Auth::check()) {
            return $this;
        }

        if (!$user) {
            $user = User::factory()->create();
            $user->verification()->create(['type' => VerificationTypesEnum::EMAIL]);
        }

        Auth::login($user);

        return $this;
    }

    /**
     * @return $this
     */
    protected function login(?User $user = null): self
    {
        return $this->actingAsUser($user);
    }

    /**
     * @return $this
     */
    protected function connectFamily(): self
    {
        $family = Auth::user()->family()->first();
        if (!$family) {
            DB::statement('drop database if exists ' . env('DB_FAMILY_DATABASE'));
            $family = Family::factory()->create();
            $family->connect();
            Member::factory()->create(['user_id' => Auth::id()]);
        } else {
            $family->connect();
        }

        return $this;
    }

    /**
     * @return Model
     */
    protected function createUser(): Model
    {
        $user = User::factory()->create(['password' => 'test']);
        $user->verification()->create(['type' => VerificationTypesEnum::EMAIL]);
        return $user;
    }
}

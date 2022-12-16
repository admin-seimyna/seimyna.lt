<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.connections.main' => config('database.connections.test')]);
    }

    /**
     *
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

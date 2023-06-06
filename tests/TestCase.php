<?php

namespace Tests;

use Dotenv\Dotenv;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../', '.env.testing');
        $dotenv->load();
    }
}

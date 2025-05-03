<?php

namespace MissionX\LaravelBetterHttpFake\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\Eloquent\Factories\Factory;
use MissionX\LaravelBetterHttpFake\Concerns\AllowsMockingHttpRequests;
use MissionX\LaravelBetterHttpFake\LaravelBetterHttpFakeServiceProvider;

class TestCase extends Orchestra
{
    use AllowsMockingHttpRequests;

    protected function getPackageProviders($app)
    {
        return [
            LaravelBetterHttpFakeServiceProvider::class,
        ];
    }
}

<?php

namespace MissionX\LaravelBetterHttpFake\Concerns;

use MissionX\LaravelBetterHttpFake\LaravelBetterHttpFake;

trait AllowsMockingHttpRequests
{
    public function setUpAllowsMockingHttpRequests()
    {
        LaravelBetterHttpFake::setUp();
    }

    public function tearDownAllowsMockingHttpRequests()
    {
        LaravelBetterHttpFake::verify();
    }
}

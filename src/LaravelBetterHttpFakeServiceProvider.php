<?php

namespace MissionX\LaravelBetterHttpFake;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Client\PendingRequest;
use MissionX\LaravelBetterHttpFake\Support\PendingRequestMixin;

class LaravelBetterHttpFakeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        PendingRequest::mixin(new PendingRequestMixin);
    }
}

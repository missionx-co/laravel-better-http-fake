<?php

namespace MissionX\LaravelBetterHttpFake;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use MissionX\LaravelBetterHttpFake\Support\PendingFakeRequest;

class LaravelBetterHttpFake
{
    /**
     * @var PendingFakeRequest[]
     */
    public static array $sent = [];

    public static function setUp()
    {
        static::$sent = [];
    }

    public static function add(PendingFakeRequest $request)
    {
        static::$sent[] = $request;

        Http::fake([
            $request->url => $request->response
        ]);

        $request->pendingRequest->stub(invade(Http::getFacadeRoot())->stubCallbacks);
    }

    public static function verify()
    {
        foreach (static::$sent as $fakeRequest) {
            $fakeRequest->assert();
        }
    }
}

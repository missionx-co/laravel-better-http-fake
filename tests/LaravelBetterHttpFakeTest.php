<?php

namespace MissionX\LaravelBetterHttpFake\Tests;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class LaravelBetterHttpFakeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // This will ensure requests fails if it has no fake
        Http::preventStrayRequests();
    }

    #[Test]
    public function it_fakes_pending_request()
    {
        $client = Http::baseUrl('https://service.com');

        $client->shouldMakePostRequestTo('/instances')
            ->withData(['key' => 'value'])
            ->withHeaders(['Header-Key' => 'Header-Value'])
            ->andRespondWith($response = [
                'instance' => ['ip' => fake()->ipv4()]
            ]);

        $res = $client->withHeader('Header-Key', 'Header-Value')->post('/instances', ['key' => 'value']);
        $this->assertEquals($response, $res->json());
    }

    #[Test]
    public function it_fakes_http()
    {
        Http::baseUrl('https://service.com')->shouldMakeGetRequestTo('/instances')->andRespondWith($response = [
            'instance' => ['ip' => fake()->ipv4()]
        ]);

        $this->assertEquals($response, Http::get('https://service.com/instances')->json());
    }
}

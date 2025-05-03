<?php

namespace MissionX\LaravelBetterHttpFake\Support;

use Illuminate\Support\Str;

class PendingRequestMixin
{
    public function shouldMakeRequest()
    {
        return function (string $method, string $url): PendingFakeRequest {
            /** @var \Illuminate\Http\Client\PendingRequest $this */

            if (! Str::startsWith($url, ['http://', 'https://'])) {
                $url = ltrim(rtrim($this->baseUrl, '/') . '/' . ltrim($url, '/'), '/');
            }

            $url = $this->expandUrlParameters($url);

            return (new PendingFakeRequest($this, $method, $url));
        };
    }

    public function shouldMakeGetRequestTo()
    {
        return function (string $url): PendingFakeRequest {
            return $this->shouldMakeRequest('GET', $url);
        };
    }

    public function shouldMakePostRequestTo()
    {
        return function (string $url): PendingFakeRequest {
            return $this->shouldMakeRequest('POST', $url);
        };
    }

    public function shouldMakePutRequestTo()
    {
        return function (string $url): PendingFakeRequest {
            return $this->shouldMakeRequest('PUT', $url);
        };
    }

    public function shouldMakePatchRequestTo()
    {
        return function (string $url): PendingFakeRequest {
            return $this->shouldMakeRequest('PATCH', $url);
        };
    }

    public function shouldMakeDeleteRequestTo()
    {
        return function (string $url): PendingFakeRequest {
            return $this->shouldMakeRequest('DELETE', $url);
        };
    }
}

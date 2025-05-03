<?php

namespace MissionX\LaravelBetterHttpFake\Support;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\ResponseSequence;
use MissionX\LaravelBetterHttpFake\LaravelBetterHttpFake;

class PendingFakeRequest
{
    public array $data;

    public array $headers;

    public bool $assert = true;

    public array $files = [];

    public PromiseInterface|ResponseSequence $response;

    public function __construct(public PendingRequest $pendingRequest, public string $method, public string $url) {}

    public function withHeaders(array $headers): static
    {
        $this->headers = $headers;
        return $this;
    }

    public function withData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function andRespondWith(array|string|null $body = null, int $status = 200, array $headers = []): static
    {
        $this->response = Http::response($body, $status, $headers);
        return $this;
    }

    public function andResponseWithSequence(ResponseSequence $response): static
    {
        $this->response = $response;
        return $this;
    }

    public function doNotAssertSent(): static
    {
        $this->assert = false;
        return $this;
    }

    public function withFile($name, $value = null, $filename = null): static
    {
        $this->files[] =  [
            'name' => $name,
            'value' => $value,
            'filename' => $filename
        ];
        return $this;
    }

    public function assert()
    {
        if (!$this->assert) {
            return;
        }

        Http::assertSent(function (Request $request) {
            $sent = $request->method() == $this->method
                && $request->url() == $this->url;

            if (isset($this->data)) {
                $sent = $sent && array_intersect($request->data(), $this->data) == $this->data;
            }

            if (isset($this->headers)) {
                $sent = $sent && $request->hasHeaders($this->headers);
            }

            if (!empty($this->files)) {
                foreach ($this->files as $file) {
                    $request->hasFile($file['name'], $file['value'], $file['filename']);
                }
            }

            return $sent;
        });
    }

    public function __destruct()
    {
        LaravelBetterHttpFake::add($this);
    }
}

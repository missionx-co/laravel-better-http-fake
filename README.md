# Laravel Better Http Fake

A package that provides improved helpers for faking Laravel HTTP client requests.

---

## Current Laravel HTTP Fake Workflow

### Example Service Class

```php
class Service {
    public function createInstance($data) {
        return $this->client()->post('/instances', $data)->json();
    }

    public function client(): PendingRequest {
        return Http::baseUrl('https://service.com')
            ->withToken(config('services.service.api_key'));
    }
}
```

### Testing Steps (Without This Package)

#### 1. Fake the response:

```php
Http::fake([
    'https://service.com/instances' => Http::response(['key' => 'value'], 204)
]);
```

#### 2. Trigger the request

```php
(new Service)->createInstance(['bodyKey' => 'bodyValue']);
```

#### 3. Manually verify the request:

```php
Http::assertSent(function($request) {
    return $request->url() == "https://service.com/instances" &&
           $request->method() == 'POST' &&
           $request->data() == ['bodyKey' => 'bodyValue'];
});
```

## With Laravel Better Http Fake

### Basic Usage

```php
(new Service)->client()
    ->shouldMakePostRequestTo('instances')
    ->withData(['bodyKey' => 'bodyValue'])  // Verify payload
    ->andRespondWith(['key' => 'value']);   // Fake response

(new Service)->createInstance($body);  // Execute
```

### Key Features

#### 1. Skip Request Verification

```php
->doNotAssertSent();  // Only fake the response, skip checks
```

#### 2. Multiple Responses (Sequence)

```php
->andRespondWithSequence(
    Http::sequence([
        Http::response([]),  // First response
        Http::response([]),  // Second response
    ])
);
```

#### 3. Headers Validation

```php
->withHeaders(['Header-Key' => 'Header-Value']);  // Verify headers
```

#### 4. File Upload Verification

```php
->withFile('photo', 'profile.jpg');  // Verify file uploads
```

#### 5. Test Multiple HTTP Verbs

Supports all common HTTP methods with dedicated methods:

```php
// GET Request
->shouldMakeGetRequestTo('users/1')
// PUT Request
->shouldMakePutRequestTo('users/1')
// PATCH Request
->shouldMakePatchRequestTo('profile')
// DELETE Request
->shouldMakeDeleteRequestTo('posts/123')
```

### Why Use This Package?

-   **Simpler tests**: Combine faking + assertions in one chain
-   **Clearer syntax**: Reads like plain English instructions
-   **Fewer mistakes**: Automatic request validation by default
-   **Time saver**: No more separate setup/verification steps

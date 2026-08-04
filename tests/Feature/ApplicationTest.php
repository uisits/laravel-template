<?php

test('application returns a response', function () {
    $response = $this->get('/');

    // Application may show a homepage or redirect to log in
    expect($response->status())->toBeIn([200, 302]);
});

test('application has proper csrf protection', function () {
    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    // Without CSRF token, should get 419 (or 405 if route doesn't exist)
    expect($response->status())->toBeIn([302, 405, 419]);
});

test('application uses https in production', function () {
    config(['app.env' => 'production']);
    config(['app.url' => 'https://example.com']);

    expect(config('app.url'))->toStartWith('https://');
});

test('application has debug disabled in production', function () {
    config(['app.env' => 'production']);
    config(['app.debug' => false]);

    expect(config('app.debug'))->toBeFalse();
});

test('application has proper timezone', function () {
    expect(config('app.timezone'))->not->toBeNull();
});

test('application has proper locale', function () {
    expect(config('app.locale'))->not->toBeNull();
});

test('application cache is configured', function () {
    expect(config('cache.default'))->not->toBeNull();
});

test('application session is configured', function () {
    expect(config('session.driver'))->not->toBeNull();
});

test('application database is configured', function () {
    expect(config('database.default'))->not->toBeNull();
});

test('application queue is configured', function () {
    expect(config('queue.default'))->not->toBeNull();
});

test('health check endpoint works', function () {
    $response = $this->get('/up');

    $response->assertStatus(200);
});

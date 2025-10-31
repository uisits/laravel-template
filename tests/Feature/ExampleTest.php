<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    // Application may redirect to login or show homepage
    expect($response->status())->toBeIn([200, 302]);
});

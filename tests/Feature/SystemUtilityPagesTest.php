<?php

use Inertia\Testing\AssertableInertia as Assert;

test('can view global search page', function () {
    $response = $this->get('/search?q=cardiology');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Search')
        ->where('q', 'cardiology')
    );
});

test('can view system maintenance page', function () {
    $response = $this->get('/maintenance');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Maintenance')
    );
});

test('can view custom 404 error page', function () {
    $response = $this->get('/404');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Error')
        ->where('status', 404)
    );
});

test('can view custom 403 error page', function () {
    $response = $this->get('/403');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Error')
        ->where('status', 403)
    );
});

test('can view custom 500 error page', function () {
    $response = $this->get('/500');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Error')
        ->where('status', 500)
    );
});

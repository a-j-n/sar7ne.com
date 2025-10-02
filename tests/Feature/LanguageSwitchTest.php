<?php

use function Pest\Laravel\get;

it('sets the locale in session when switching languages', function () {
    $response = get('/language/en');

    $response->assertRedirect();
    $this->assertEquals('en', session('locale'));
});

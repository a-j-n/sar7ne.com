<?php

use function Pest\Laravel\get;

it('sets the locale in session when switching to arabic', function () {
    $response = get('/language/ar');

    $response->assertRedirect();
    $this->assertEquals('ar', session('locale'));
});

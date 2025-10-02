<?php

use function Pest\Laravel\get;

it('sets the theme cookie when switching themes', function () {
    $response = get('/theme/dark');

    $response->assertRedirect();
    $response->assertCookie('theme');
});

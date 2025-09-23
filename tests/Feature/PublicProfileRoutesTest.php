<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the public profile at /p/{username}', function () {
    $user = User::factory()->create(['username' => 'testuser']);
    $response = $this->get('/p/testuser');
    $response->assertSuccessful();
    $response->assertSee('@testuser');
});

it('shows the public profile at /{username}', function () {
    $user = User::factory()->create(['username' => 'testuser2']);
    $response = $this->get('/testuser2');
    $response->assertSuccessful();
    $response->assertSee('@testuser2');
});


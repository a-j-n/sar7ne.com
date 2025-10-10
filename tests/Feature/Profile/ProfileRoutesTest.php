<?php

declare(strict_types=1);

use App\Models\User;

it('shows profile info page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $res = $this->get(route('profile.info'));
    $res->assertSuccessful();
    $res->assertSee('@'.$user->username);
});

it('shows profile settings page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $res = $this->get(route('profile.settings'));
    $res->assertSuccessful();
    $res->assertSee('@'.$user->username);
});

it('profile route points to info', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $res = $this->get(route('profile'));
    $res->assertSuccessful();
    $res->assertSee('@'.$user->username);
});

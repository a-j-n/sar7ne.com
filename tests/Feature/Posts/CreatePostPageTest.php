<?php

declare(strict_types=1);

use App\Models\User;

it('shows the create post page for guests', function () {
    $this->get(route('posts.create'))
        ->assertOk()
        ->assertSee('Share a post')
        ->assertSee(__('messages.posts.whats_happening'))
        ->assertSee('delete token');
});

it('shows anonymous toggle for authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('posts.create'))
        ->assertOk()
        ->assertSee(__('messages.posts.anonymous'));
});

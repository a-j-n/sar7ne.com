<?php

declare(strict_types=1);

use App\Models\TimelinePost;
use App\Models\User;
use App\Support\Geo;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

it('requires location to create a post', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Timeline\Index::class)
        ->set('body', 'Hello world')
        ->call('create')
        ->assertHasErrors(['lat', 'lng']);
});

it('creates a text-only post with location', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Timeline\Index::class)
        ->set('body', 'Near me!')
        ->set('lat', 40.0)
        ->set('lng', -74.0)
        ->call('create')
        ->assertHasNoErrors();

    expect(TimelinePost::where('user_id', $user->id)->exists())->toBeTrue();
});

it('broadcasts an event when creating a post', function () {
    Event::fake();
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Timeline\Index::class)
        ->set('body', 'Broadcast me!')
        ->set('lat', 10.1)
        ->set('lng', 20.2)
        ->call('create')
        ->assertHasNoErrors();

    Event::assertDispatched(\App\Events\TimelinePostCreated::class);
});

it('feature flag can disable map', function () {
    config()->set('features.timeline_map', false);

    $user = User::factory()->create();
    Livewire::actingAs($user)
        ->test(\App\Livewire\Timeline\Index::class)
        ->assertViewHas('mapEnabled', false);
});

it('renders the timeline page successfully', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/timeline')
        ->assertOk()
        ->assertSee('Create a post');
});

it('haversine helper returns expected distance', function () {
    $km = Geo::haversineKm(48.8566, 2.3522, 51.5074, -0.1278);
    expect($km)->toBeGreaterThan(300)->toBeLessThan(400);
});

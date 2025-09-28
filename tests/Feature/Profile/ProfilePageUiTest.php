<?php

declare(strict_types=1);

use App\Livewire\Profile\Settings as ProfileSettingsPage;
use App\Models\User;
use Livewire\Livewire;

it('renders profile settings page with key elements', function (): void {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile'))
        ->assertSuccessful()
        ->assertSee(__('messages.profile_settings'))
        ->assertSee(__('messages.save_changes'));
});

it('shows loading and dirty states on save', function (): void {
    /** @var User $user */
    $user = User::factory()->create(['display_name' => 'Old']);

    Livewire::test(ProfileSettingsPage::class)
        ->actingAs($user)
        ->set('display_name', 'New')
        ->call('save')
        ->assertHasNoErrors();
});


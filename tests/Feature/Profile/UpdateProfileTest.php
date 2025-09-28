<?php

declare(strict_types=1);

use App\Livewire\Profile\Settings as ProfileSettingsPage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('updates profile via HTTP controller', function (): void {
    Storage::fake();

    /** @var User $user */
    $user = User::factory()->create([
        'username' => 'old_user',
        'display_name' => 'Old Name',
        'bio' => 'Old bio',
        'gender' => null,
        'allow_public_messages' => false,
    ]);

    $avatar = UploadedFile::fake()->image('avatar.jpg', 300, 300);

    $payload = [
        'username' => 'New_User-123', // mixed case + symbols to test normalization
        'display_name' => 'New Name',
        'bio' => 'New bio here',
        'gender' => 'other',
        'allow_public_messages' => '1',
        'avatar' => $avatar,
    ];

    $response = $this->actingAs($user)
        ->from(route('profile'))
        ->put(route('profile.update'), $payload);

    $response->assertRedirect(route('profile'));
    $response->assertSessionHas('status');

    $user->refresh();

    // Username should be normalized by UsernameGenerator::normalize
    expect($user->username)->toBe('new_user_123');
    expect($user->display_name)->toBe('New Name');
    expect($user->bio)->toBe('New bio here');
    expect($user->gender)->toBe('other');
    expect($user->allow_public_messages)->toBeTrue();

    // Avatar stored and URL set via disk->url()
    expect($user->avatar_url)->not()->toBeNull();
});

it('prevents duplicate normalized usernames', function (): void {
    /** @var User $existing */
    $existing = User::factory()->create(['username' => 'taken_name']);
    /** @var User $me */
    $me = User::factory()->create(['username' => 'current']);

    $response = $this->actingAs($me)
        ->from(route('profile'))
        ->put(route('profile.update'), [
            'username' => 'Taken-Name', // normalizes to taken_name
        ]);

    $response->assertRedirect(route('profile'));
    $response->assertSessionHasErrors(['username']);
});

it('updates profile via Livewire settings component', function (): void {
    Storage::fake();

    /** @var User $user */
    $user = User::factory()->create([
        'username' => 'tab_one',
        'display_name' => 'First',
        'bio' => null,
        'allow_public_messages' => false,
    ]);

    $component = Livewire::test(ProfileSettingsPage::class)
        ->actingAs($user)
        ->set('username', 'Second-Tab')
        ->set('display_name', 'Second Name')
        ->set('bio', 'Second tab bio')
        ->set('gender', 'male')
        ->set('allow_public_messages', true)
        ->set('social_links', [
            'twitter' => 'https://twitter.com/example',
            'instagram' => null,
            'tiktok' => null,
            'youtube' => null,
            'linkedin' => null,
            'github' => 'https://github.com/example',
            'website' => 'https://example.com',
        ])
        ->set('social_visibility', [
            'twitter' => true,
            'github' => true,
            'website' => false,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->username)->toBe('second_tab');
    expect($user->display_name)->toBe('Second Name');
    expect($user->bio)->toBe('Second tab bio');
    expect($user->gender)->toBe('male');
    expect($user->allow_public_messages)->toBeTrue();

    expect($user->social_twitter)->toBe('https://twitter.com/example');
    expect($user->social_github)->toBe('https://github.com/example');
    expect($user->social_website)->toBe('https://example.com');

    expect($user->social_twitter_public)->toBeTrue();
    expect($user->social_github_public)->toBeTrue();
});

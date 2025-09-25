<?php

declare(strict_types=1);

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Str;

it('allows recipient to toggle message public/private', function (): void {
    $user = User::factory()->create([
        'allow_public_messages' => true,
    ]);

    $message = Message::factory()->create([
        'receiver_id' => $user->id,
        'status' => Message::STATUS_UNREAD,
        'is_public' => false,
    ]);

    $this->actingAs($user)
        ->from(route('inbox'))
        ->put(route('inbox.messages.toggle-public', $message), ['is_public' => 1])
        ->assertRedirect();

    expect($message->fresh()->is_public)->toBeTrue();

    $this->actingAs($user)
        ->put(route('inbox.messages.toggle-public', $message), ['is_public' => 0])
        ->assertRedirect();

    expect($message->fresh()->is_public)->toBeFalse();
});

it('prevents other users from toggling visibility', function (): void {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $message = Message::factory()->create([
        'receiver_id' => $owner->id,
        'is_public' => false,
    ]);

    $this->actingAs($other)
        ->put(route('inbox.messages.toggle-public', $message), ['is_public' => 1])
        ->assertForbidden();
});

it('shows only public messages on public profile when enabled', function (): void {
    $user = User::factory()->create([
        'allow_public_messages' => true,
        'username' => 'jane',
    ]);

    $privateText = 'pm-'.uniqid();
    Message::factory()->create([
        'receiver_id' => $user->id,
        'message_text' => $privateText,
        'is_public' => false,
    ]);

    $publicText = 'pub-'.uniqid();
    Message::factory()->create([
        'receiver_id' => $user->id,
        'message_text' => $publicText,
        'is_public' => true,
    ]);

    $html = $this->get(route('profiles.show', $user))->getContent();
    $section = Str::between($html, '<!-- PUBLIC_MESSAGES_START -->', '<!-- PUBLIC_MESSAGES_END -->');
    // ensure section exists
    expect($section)->not->toBe('');
    // match only list item contents
    preg_match_all('/<li[^>]*>(.*?)<\\/li>/si', $section, $matches);
    $items = implode("\n", array_map(fn ($s) => trim(strip_tags($s)), $matches[1] ?? []));
    expect($items)->toContain($publicText);
    expect($items)->not()->toContain($privateText);
});

it('hides public messages section when user disabled it', function (): void {
    $user = User::factory()->create([
        'allow_public_messages' => false,
        'username' => 'john',
    ]);

    $publicText = 'pub-'.uniqid();
    Message::factory()->create([
        'receiver_id' => $user->id,
        'message_text' => $publicText,
        'is_public' => true,
    ]);

    $html = $this->get(route('profiles.show', $user))->getContent();
    expect($html)->not->toContain($publicText);
});

<?php

declare(strict_types=1);

use App\Models\User;
use App\Support\UsernameGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('registers a user via email form', function (): void {
    $email = 'New.User+123@example.com';

    $response = post(route('register.store'), [
        'email' => $email,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('inbox'));

    $user = User::query()->where('email', $email)->first();
    expect($user)->not->toBeNull();

    expect($user->username)->toBe(UsernameGenerator::normalize('New.User+123'));
    expect(Hash::check('password123', $user->password))->toBeTrue();
    expect(Auth::id())->toBe($user->id);
});

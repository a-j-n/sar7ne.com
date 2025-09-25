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

it('can send a message with image to public profile', function () {
    $user = User::factory()->create(['username' => 'testuser3']);
    
    // Create a fake image file
    $image = \Illuminate\Http\UploadedFile::fake()->image('test.jpg', 100, 100);
    
    $response = $this->post("/testuser3/messages", [
        'message_text' => 'Test message with image',
        'image' => $image,
    ]);
    
    $response->assertRedirect();
    
    // Check that message was created with image
    $this->assertDatabaseHas('messages', [
        'receiver_id' => $user->id,
        'message_text' => 'Test message with image',
    ]);
    
    $message = \App\Models\Message::where('receiver_id', $user->id)->first();
    expect($message->image_path)->not->toBeNull();
});


<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'sender_ip' => $this->faker->ipv4(),
            'receiver_id' => User::factory(),
            'message_text' => $this->faker->paragraph(),
            'status' => Message::STATUS_UNREAD,
        ];
    }
}

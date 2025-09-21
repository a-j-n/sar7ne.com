<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $displayName = $this->faker->name();
        $username = Str::slug($displayName.Str::random(4));

        return [
            'username' => $username,
            'display_name' => $displayName,
            'avatar_url' => $this->faker->imageUrl(256, 256, 'people'),
            'bio' => $this->faker->optional()->sentence(12),
            'provider_id' => (string) $this->faker->randomNumber(7, true),
            'provider_type' => $this->faker->randomElement(['twitter', 'facebook']),
            'last_login_at' => now(),
        ];
    }
}

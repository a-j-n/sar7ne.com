<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'is_anonymous' => $this->faker->boolean(20),
            'content' => $this->faker->boolean(80)
                ? $this->faker->realTextBetween(60, 220)
                : null,
            'images' => $this->faker->boolean(20)
                ? [$this->faker->imageUrl(800, 600, 'abstract', true)]
                : null,
            'delete_token_hash' => null,
            'anon_key_hash' => null,
            'created_at' => now()->subDays($this->faker->numberBetween(0, 30)),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the post should be anonymous.
     */
    public function anonymous(): self
    {
        return $this->state(fn (): array => [
            'is_anonymous' => true,
        ]);
    }

    /**
     * Indicate the post has images.
     */
    public function withImages(int $count = 1): self
    {
        return $this->state(fn (): array => [
            'images' => collect(range(1, max(1, $count)))
                ->map(fn () => $this->faker->imageUrl(800, 600, 'abstract', true))
                ->all(),
        ]);
    }
}


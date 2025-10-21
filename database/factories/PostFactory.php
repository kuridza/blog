<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(mt_rand(3,33), true),
            'tags' => implode(',', explode(' ', $this->faker->words(mt_rand(0,5), true))),
            'risk_score' => null,
            'risk_level' => null,
            'archived_at' => null,
        ];
    }
}

<?php

namespace Cmdobueno\FilamentExport\Tests\Database\Factories;

use Cmdobueno\FilamentExport\Tests\Models\Post;
use Cmdobueno\FilamentExport\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'content' => $this->faker->paragraph(),
            'tags' => $this->faker->words(),
            'title' => $this->faker->sentence(),
            'rating' => $this->faker->numberBetween(1, 10),
        ];
    }
}

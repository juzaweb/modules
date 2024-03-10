<?php

namespace Juzaweb\CMS\Database\Factories;

use Illuminate\Support\Str;
use Juzaweb\Backend\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Juzaweb\CMS\Models\User;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(10);
        $users = User::active()->inRandomOrder()->limit(10)->get()->pluck('id')->toArray();

        return [
            'title' => $title,
            'content' => $this->randomContent(),
            'thumbnail' => $this->randomThumbnailUrl(),
            'status' => 'publish',
            'type' => 'posts',
            'locale' => 'en',
            'slug' => Str::slug($title),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $users[array_rand($users)],
            'updated_by' => $users[array_rand($users)],
        ];
    }

    protected function randomThumbnailUrl($width = 640, $height = 480): string
    {
        return $this->faker->imageUrl(
            $width,
            $height,
            null,
            false,
            null,
            false,
            'jpg'
        );
    }

    protected function randomContent(): string
    {
        $paragraphs = $this->faker->paragraphs(random_int(10, 50));

        $content = '';
        foreach ($paragraphs as $para) {
            $content .= "<p>{$para}</p>";
        }

        return $content;
    }
}

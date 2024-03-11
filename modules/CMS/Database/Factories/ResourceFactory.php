<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Juzaweb\Backend\Models\Resource;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        return [
            'name' => $name,
            'type' => 'posts',
            'status' => 'publish',
            'post_id' => 1,
            'slug' => Str::slug($name),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories\Blog;

use App\Models\Blog\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
	    return [
		    'name' => 'miscellaneous',
		    'slug' => Str::slug('miscellaneous'),
		    'parent_id' => null,
		    'description' => $this->faker->sentence($nbWords = 8),
		    'meta_title' => $this->faker->sentence($nbWords = 6),
		    'meta_description' => $this->faker->sentence($nbWords = 6),
	    ];
    }
}

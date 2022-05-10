<?php

namespace Database\Factories\Blog;

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\Blog\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition()
    {

		$post_title = $this->faker->unique()->text();
        return [
	        'title' => $post_title,
	        'slug' => Str::slug($post_title),
	        'body' => $this->faker->text(2000),
	        'user_id' => 2,
	        'meta_title'=> $post_title,
	        'meta_description'=> $this->faker->sentence($nbWords = 8),
	        'is_featured'=>rand(0,1),
	        'main_category'=> Category::all()->random()->id,
	        'excerpt'=> $this->faker->sentence($nbWords = 6),
	        'published_status'=> 1,
	        'display_published_at'=> Carbon::now(),
	        'published_at'=> Carbon::now(),
            'type' => '0'
        ];


    }

	/**
	 * Configure the model factory.
	 *
	 * @return $this
	 */
	public function configure()
	{
		return $this->afterCreating(function (Post $post) {
			$post->addMediaFromUrl('https://source.unsplash.com/collection/4738755/1260x720')
			     ->toMediaCollection('featured_post_image');
			$post->categories()->sync(Category::select('id')->inRandomOrder()->take(1)->get()->pluck('id')->toArray());
			$post->tags()->sync(Tag::select('id')->inRandomOrder()->take(1)->get()->pluck('id')->toArray());
		});
	}
}

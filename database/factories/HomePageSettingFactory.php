<?php

namespace Database\Factories;

use App\Models\HomePageSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomePageSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HomePageSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'value' => $this->faker->word,
        ];
    }
}

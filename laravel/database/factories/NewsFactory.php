<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'publisher_no' => 2,
            'news_category_no' => $this->faker->numberBetween($min = 2, $max = 3),
            'name' => 'ニュース! ' . $this->faker->words($nb = 3, $asText = true),
            'value' => $this->faker->realText(700),
            'public_status' => 1,
            'public_date' => $this->faker->dateTime($max = 'now'),
        ];
    }
}
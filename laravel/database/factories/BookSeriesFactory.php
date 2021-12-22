<?php

namespace Database\Factories;

use App\Models\BookSeries;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookSeriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BookSeries::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'book_no' => $this->faker->randomNumber(1,300),
            'series_no' => $this->faker->randomNumber(1,10),
        ];
    }
}

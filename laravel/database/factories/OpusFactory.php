<?php

namespace Database\Factories;

use App\Models\Opus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Opus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'book_no' => $this->faker->randomNumber(1, 300),
            'author_no' => $this->faker->randomNumber(1, 10),
            'author_type_no' => $this->faker->randomNumber(1, 15),
        ];
    }
}

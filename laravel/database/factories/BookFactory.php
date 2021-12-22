<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'publisher_no' => 2,
            'name' => $this->faker->streetName . ' ' . $this->faker->numberBetween(1, 12) . '月号',
            'release_date' => $this->faker->dateTimeBetween($min = '-1 year', $max = '+2 month'),
            'public_status' => 1,
            'public_date' => $this->faker->dateTime($max = 'now'),
            'price' => $this->faker->randomNumber(2, true) * 100,
            'isbn' => $this->faker->isbn13(),
            'content' => $this->faker->realText(250),
            'image' => '/web/img/uploads/book/book' . $this->faker->numberBetween($min = 1, $max = 6) . '.jpg',
            'book_size_no' => $this->faker->randomNumber(1, 21),
        ];
    }
}

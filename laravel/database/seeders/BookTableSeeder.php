<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\Opus;
use App\Models\BookGenre;

class BookTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::factory(300)->create()->each(function($book){
            BookGenre::factory()->create(['book_no' => $book->book_no]);
            BookSeries::factory(2)->create(['book_no' => $book->book_no]);
            Opus::factory(2)->create(['book_no' => $book->book_no]);
        });
    }
}

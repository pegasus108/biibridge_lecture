<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\BookSeries;
use App\Models\BookGenre;
use App\Models\Opus;
use App\Models\Series;
use App\Models\Author;
use App\Repositories\BookRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    private $book_repository;
    private $created_book;
    public function setUp(): void
    {
        parent::setUp();
        $this->book_repository = new BookRepository;

        $book = Book::factory()->create(['name' => 'test_book']);
        $author = Author::factory()->create(['name' => 'test_author']);
        Author::factory()->create();
        Opus::factory()->create([
            'book_no' => $book->book_no,
            'author_no' => $author->author_no,
        ]);

        $series = Series::factory()->create(['name' => 'test_series']);
        Series::factory()->create();
        $series_book = Book::factory()->create(['name' => 'test_series_book']);
        BookSeries::factory()->create([
            'book_no' => $book->book_no,
            'series_no' => $series->series_no,
        ]);
        BookSeries::factory()->create([
            'book_no' => $series_book->book_no,
            'series_no' => $series->series_no,
        ]);
        BookGenre::factory()->create([
            'book_no' => $book->book_no,
            'genre_no' => '1',
        ]);
        $this->created_book = $book;

        Book::factory()->create(['name' => 'test_release_date_asc', 'release_date' => new Carbon('0000-01-01')]);
        Book::factory()->create(['name' => 'test_release_date_desc', 'release_date' => new Carbon('9999-12-31')]);
        Book::factory()->create(['name' => 'test_name_asc']);
        Book::factory()->create(['name' => '0']);
        Book::factory()->create(['price' => '0']);
        Book::factory()->create(['price' => '999999']);
    }

    public function test_getBookDetail_not_exists()
    {
        $book = $this->book_repository->getBookDetail(-1, series_books_limit: 1);
        $this->assertNull($book);
    }

    public function test_getBookDetail_exists()
    {
        $book = $this->book_repository->getBookDetail($this->created_book->book_no, series_books_limit: 1);
        $this->assertTrue($book != null);
    }

    public function test_getBookDetail_series_books_limit_0()
    {
        $book = $this->book_repository->getBookDetail($this->created_book->book_no, series_books_limit: 0);
        $this->assertCount(0, $book->series_books);
    }

    public function test_getBookDetail_series_books_limit_1()
    {
        $book = $this->book_repository->getBookDetail($this->created_book->book_no, series_books_limit: 1);
        $this->assertCount(1, $book->series_books);
    }

    public function test_getBookDetail_type_is_book()
    {
        $book = $this->book_repository->getBookDetail($this->created_book->book_no, series_books_limit: 1);
        $this->assertEquals('App\Models\Book', get_class($book));
        $this->assertEquals($book->book_no, $this->created_book->book_no);
        $this->assertEquals($book->name, 'test_book');
        $this->assertTrue($book->image != null);
        $this->assertEquals($book->price, $this->created_book->price);
        $this->assertEquals($book->content, $this->created_book->content);
        $this->assertEquals($book->isbn, $this->created_book->isbn);
        $this->assertTrue($book->size != null);
        $this->assertTrue($book->authors->contains('name', 'test_author'));
        $this->assertCount(1, $book->authors);
        $this->assertTrue($book->series_books->contains('name', 'test_series_book'));
        $this->assertCount(1, $book->series_books);
    }

    public function test_getNewBooks_release_date_is_this_month()
    {
        Book::factory()->create(['name' => 'test_new_book1', 'release_date' => Carbon::now()->startOfMonth()]);
        Book::factory()->create(['name' => 'test_new_book2', 'release_date' => Carbon::now()->endOfMonth()]);
        $books = $this->book_repository->getNewBooks(limit: 100);
        $this->assertTrue($books->contains('name', 'test_new_book1'));
        $this->assertTrue($books->contains('name', 'test_new_book2'));
    }

    public function test_getNewBooks_release_date_is_not_this_month()
    {
        Book::factory()->create(['name' => 'test_not_new_book1', 'release_date' => Carbon::now()->subMonths(1)->endOfMonth()]);
        Book::factory()->create(['name' => 'test_not_new_book2', 'release_date' => Carbon::now()->addMonths(1)->startOfMonth()]);
        $books = $this->book_repository->getNewBooks(limit: 100);
        $this->assertFalse($books->contains('name', 'test_not_new_book1'));
        $this->assertFalse($books->contains('name', 'test_not_now_new_book2'));
    }

    public function test_getNewBooks_release_date_desc()
    {
        Book::factory()->create(['name' => 'test_new_book2', 'release_date' => Carbon::now()->startOfMonth()->addDay(2)]);
        Book::factory()->create(['name' => 'test_new_book1', 'release_date' => Carbon::now()->startOfMonth()->addDay(1)]);
        $books = $this->book_repository->getNewBooks(limit: 100);
        foreach($books as $book)
        {
            if($book->name == 'test_new_book1'){
                $this->assertTrue(true);
                return;
            }
            if($book->name == 'test_new_book2'){
                $this->assertTrue(false);
                return;
            }
        }
    }

    public function test_getNewBooks_type_is_book()
    {
        Book::factory()->create(['name' => 'test_new_book1', 'release_date' => Carbon::now()->startOfMonth()]);
        $books = $this->book_repository->getNewBooks(limit: 100);
        $book = $books[0];
        $this->assertEquals('App\Models\Book', get_class($book));
        $this->assertTrue($book->book_no != null);
        $this->assertTrue($book->name != null);
        $this->assertTrue($book->image != null);
        $this->assertTrue($book->release_date != null);
        $this->assertTrue($book->price != null);
        $this->assertTrue($book->content != null);
    }

    public function test_getNewBooks_type_is_paginator()
    {
        Book::factory()->create(['name' => 'test_new_book1', 'release_date' => Carbon::now()->startOfMonth()]);
        $books = $this->book_repository->getNewBooks(limit: 100);
        $this->assertEquals('Illuminate\Pagination\LengthAwarePaginator', get_class($books));
    }

    public function test_getBookSalesInfoList_limit_0()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 0);
        $this->assertCount(0, $books);
    }

    public function test_getBookSalesInfoList_limit_1()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 1);
        $this->assertCount(1, $books);
    }

    public function test_getBookSalesInfoList_use_paginate_false()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 0, use_paginate: false);
        $this->assertEquals('Illuminate\Database\Eloquent\Collection', get_class($books));
    }

    public function test_getBookSalesInfoList_use_paginate_true()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 0, use_paginate: true);
        $this->assertEquals('Illuminate\Pagination\LengthAwarePaginator', get_class($books));
    }

    public function test_getBookSalesInfoList_order_rule_name_asc()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 1, use_paginate: false, order_rule: 'name_asc');
        $book = $books[0];
        $this->assertEquals($book->name, '0');
    }

    public function test_getBookSalesInfoList_order_rule_price_asc()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 1, use_paginate: false, order_rule: 'price_asc');
        $book = $books[0];
        $this->assertEquals($book->price, '0');
    }

    public function test_getBookSalesInfoList_order_rule_price_desc()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 1, use_paginate: false, order_rule: 'price_desc');
        $book = $books[0];
        $this->assertEquals($book->price, '999999');
    }

    public function test_getBookSalesInfoList_order_rule_release_date_desc()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 1, use_paginate: false, order_rule: 'release_date_desc');
        $book = $books[0];
        $this->assertEquals($book->name, 'test_release_date_desc');
    }

    public function test_getBookSalesInfoList_order_rule_release_date_asc()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 1, use_paginate: false, order_rule: 'release_date_asc');
        $book = $books[0];
        $this->assertEquals($book->name, 'test_release_date_asc');
    }

    public function test_getBookSalesInfoList_keywords()
    {
        Book::factory()->create(['name' => 'test_keywords']);
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false);
        $this->assertTrue($books->contains('name', 'test_keywords'));
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false, keywords: ['test', 'book']);
        $this->assertFalse($books->contains('name', 'test_keywords'));
    }

    public function test_getBookSalesInfoList_name()
    {
        Book::factory()->create(['name' => 'test_name']);
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false);
        $this->assertTrue($books->contains('name', 'test_name'));
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false, name: 'book');
        $this->assertFalse($books->contains('name', 'test_name'));
    }

    public function test_getBookSalesInfoList_author_name()
    {

        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false);
        $this->assertTrue($books->contains('name', 'test_book'));
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false, author_name: 'test_author_name');
        $this->assertFalse($books->contains('name', 'test_book'));
    }

    public function test_getBookSalesInfoList_series_no()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false);
        $this->assertTrue($books->contains('name', 'test_book'));
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false, series_no: 100);
        $this->assertFalse($books->contains('name', 'test_book'));
    }

    public function test_getBookSalesInfoList_genre_no()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false);
        $this->assertTrue($books->contains('name', 'test_book'));
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false, genre_no: 100);
        $this->assertFalse($books->contains('name', 'test_book'));
    }

    public function test_getBookSalesInfoList_isbn()
    {
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false);
        $this->assertTrue($books->contains('name', 'test_book'));
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false, isbn: '0000000000000');
        $this->assertFalse($books->contains('name', 'test_book'));
    }

    public function test_getBookSalesInfoList_min_release_date()
    {
        Book::factory()->create(['name' => 'test_min_release_date', 'release_date' => Carbon::now()]);
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false);
        $this->assertTrue($books->contains('name', 'test_min_release_date'));
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false, min_release_date: Carbon::tomorrow());
        $this->assertFalse($books->contains('name', 'test__release_date'));
    }

    public function test_getBookSalesInfoList_max_release_date()
    {
        Book::factory()->create(['name' => 'test_max_release_date', 'release_date' => Carbon::now()]);
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false);
        $this->assertTrue($books->contains('name', 'test_max_release_date'));
        $books = $this->book_repository->getBookSalesInfoList(limit: 100, use_paginate: false, max_release_date: Carbon::yesterday());
        $this->assertFalse($books->contains('name', 'test_max_release_date'));
    }

    public function test_getBookSalesInfoList_type_is_book()
    {
        $books = $this->book_repository->getBookSalesInfoList(1);
        $book = $books[0];
        $this->assertEquals('App\Models\Book', get_class($book));
        $this->assertTrue($book->book_no != null);
        $this->assertTrue($book->name != null);
        $this->assertTrue($book->image != null);
        $this->assertTrue($book->release_date != null);
        $this->assertTrue($book->price != null);
    }

    public function test_getGenres()
    {
        DB::table('genre')->insert([
            'name' => 'test_getgenres',
            'lft' => '1',
            'rgt' => '1',
            'depth' => '1',
        ]);
        $genres = $this->book_repository->getGenres();
        $this->assertTrue($genres->contains('name', 'test_getgenres'));
    }

    public function test_getSeriesList()
    {
        Series::factory()->create(['name' => 'test_getSeriesList']);
        $series_list = $this->book_repository->getSeriesList();
        $this->assertTrue($series_list->contains('name', 'test_getSeriesList'));
    }
}

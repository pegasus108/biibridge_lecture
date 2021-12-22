<?php

namespace Tests\Unit;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class BookTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_ScopePublished_public_status_0()
    {
        $book = new Book;
        $book->public_status = 0;
        $book->public_date = Carbon::yesterday();
        $book->save();
        $book_no = $book->book_no;

        $get_book = Book::find($book_no);
        $this->assertNull($get_book);
    }

    public function test_ScopePublished_public_status_1()
    {
        $book = new Book;
        $book->public_status = 1;
        $book->public_date = Carbon::yesterday();
        $book->save();
        $book_no = $book->book_no;

        $get_book = Book::find($book_no);
        $this->assertTrue($get_book != null);
    }

    public function test_ScopePublished_public_date_yesterday()
    {
        $book = new Book;
        $book->public_status = 1;
        $book->public_date = Carbon::yesterday();
        $book->save();
        $book_no = $book->book_no;

        $get_book = Book::find($book_no);
        $this->assertTrue($get_book != null);
    }

    public function test_ScopePublished_public_date_tomorrow()
    {
        $book = new Book;
        $book->public_status = 1;
        $book->public_date = Carbon::tomorrow();
        $book->save();
        $book_no = $book->book_no;

        $get_book = Book::find($book_no);
        $this->assertNull($get_book);
    }
}

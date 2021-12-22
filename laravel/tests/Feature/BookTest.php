<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_book_index()
    {
        $response = $this->get('/book');
        $response->assertStatus(200);
    }

    public function test_book_detail_exists()
    {
        $book = Book::first();
        $response = $this->get("/book/$book->book_no");
        $response->assertStatus(200);
    }

    public function test_book_detail_not_exists()
    {
        $response = $this->get('/book/-1');
        $response->assertStatus(404);
    }

    public function test_book_new_release()
    {
        $response = $this->get('/book/new-release');
        $response->assertStatus(200);
    }
}

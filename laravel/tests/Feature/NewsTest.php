<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\News;
use Tests\TestCase;

class NewsTest extends TestCase
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

    public function test_news_index()
    {
        $response = $this->get('/news');
        $response->assertStatus(200);
    }

    public function test_news_detail_exists()
    {
        $news = News::first();
        $response = $this->get("/news/$news->news_no");
        $response->assertStatus(200);
    }

    public function test_news_detail_not_exists()
    {
        $response = $this->get('/news/-1');
        $response->assertStatus(404);
    }
}

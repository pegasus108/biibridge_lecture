<?php

namespace Tests\Unit;

use App\Models\News;
use App\Repositories\NewsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class NewsRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    private $created_news;
    private $news_repository;
    public function setUp(): void
    {
        parent::setUp();
        $this->created_news = News::factory()->create(['name' => '10', 'public_date' => Carbon::now()->subDay(10)]);
        $this->news_repository = new NewsRepository;
        News::factory()->create(['name' => '5', 'public_date' => Carbon::now()->subDay(5)]);
        News::factory()->create(['name' => '2', 'public_date' => Carbon::now()->subDay(2)]);
        News::factory()->create(['name' => '4', 'public_date' => Carbon::now()->subDay(4)]);
        News::factory()->create(['name' => '8', 'public_date' => Carbon::now()->subDay(8)]);
        News::factory()->create(['name' => '1', 'public_date' => Carbon::now()->subDay(1)]);
        News::factory()->create(['name' => '6', 'public_date' => Carbon::now()->subDay(6)]);
        News::factory()->create(['name' => '9', 'public_date' => Carbon::now()->subDay(9)]);
        News::factory()->create(['name' => '3', 'public_date' => Carbon::now()->subDay(3)]);
        News::factory()->create(['name' => '7', 'public_date' => Carbon::now()->subDay(7)]);
    }

    public function test_getNewsList_public_date_desc()
    {
        $news_list = $this->news_repository->getNewsList(limit: 10);
        for ($i = 0; $i < 10; $i++) {
            $this->assertEquals($news_list[$i]->name, $i + 1);
        }
    }

    public function test_getNewsList_limit_5()
    {
        $news_list = $this->news_repository->getNewsList(limit: 5);
        $this->assertCount(5, $news_list);
    }

    public function test_getNewsList_limit_10()
    {
        $news_list = $this->news_repository->getNewsList(limit: 10);
        $this->assertCount(10, $news_list);
    }

    public function test_getNewsList_use_paginate_true()
    {
        $news_list = $this->news_repository->getNewsList(limit: 1, use_paginate: true);
        $this->assertEquals('Illuminate\Pagination\LengthAwarePaginator', get_class($news_list));
    }

    public function test_getNewsList_use_paginate_false()
    {
        $news_list = $this->news_repository->getNewsList(limit: 1, use_paginate: false);
        $this->assertEquals('Illuminate\Database\Eloquent\Collection', get_class($news_list));
    }

    public function test_getNewsList_collection_type_is_news()
    {
        $news_list = $this->news_repository->getNewsList(limit: 1, use_paginate: false);
        $news = $news_list[0];
        $this->assertEquals('App\Models\News', get_class($news));
        $this->assertTrue($news->news_no != null);
        $this->assertTrue($news->name != null);
        $this->assertTrue($news->public_date != null);
        $this->assertTrue($news->category != null);
    }

    public function test_getNewsDetail_not_exists()
    {
        $news_no = -1;
        $news = $this->news_repository->getNewsDetail($news_no);
        $this->assertNull($news);
    }

    public function test_getNewsDetail_exists()
    {
        $news_no = $this->created_news->news_no;
        $news = $this->news_repository->getNewsDetail($news_no);
        $this->assertTrue($news != null);
    }

    public function test_getNewsDetail_type_is_news()
    {
        $news_no = $this->created_news->news_no;
        $news = $this->news_repository->getNewsDetail($news_no);
        $this->assertEquals('App\Models\News', get_class($news));
        $this->assertEquals($news->name, $this->created_news->name);
        $this->assertEquals($news->value, $this->created_news->value);
        $this->assertEquals($news->update_date, $this->created_news->u_stamp);
    }
}

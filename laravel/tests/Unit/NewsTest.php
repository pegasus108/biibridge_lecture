<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use App\Models\News;

class NewsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_ScopePublished_public_status_0()
    {
        $news = News::factory()->make();
        $news->public_status = 0;
        $news->public_date = Carbon::yesterday();
        $news->save();
        $news_no = $news->news_no;

        $get_news = News::find($news_no);
        $this->assertNull($get_news);
    }

    public function test_ScopePublished_public_status_1()
    {
        $news = News::factory()->make();
        $news->public_status = 1;
        $news->public_date = Carbon::yesterday();
        $news->save();
        $news_no = $news->news_no;

        $get_news = News::find($news_no);
        $this->assertTrue($get_news != null);
    }

    public function test_ScopePublished_public_date_yesterday()
    {
        $news = News::factory()->make();
        $news->public_status = 1;
        $news->public_date = Carbon::yesterday();
        $news->save();
        $news_no = $news->news_no;

        $get_news = News::find($news_no);
        $this->assertTrue($get_news != null);
    }

    public function test_ScopePublished_public_date_tomorrow()
    {
        $news = News::factory()->make();
        $news->public_status = 1;
        $news->public_date = Carbon::tomorrow();
        $news->save();
        $news_no = $news->news_no;

        $get_news = News::find($news_no);
        $this->assertNull($get_news);
    }
}

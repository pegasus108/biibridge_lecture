<?php

namespace Tests\Unit;

use App\Repositories\BannerRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BannerRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    private $banner_repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->banner_repository = new BannerRepository;
    }

    public function test_getBanners_limit_0()
    {
        DB::table('banner_big')->insert([
            'image' => 'test_banner.png',
            'url' => 'test.com',
            'name' =>'test_banner_big',
            'public_status' => 1,
            'publisher_no' => 2,
        ]);
        $banners = $this->banner_repository->getBanners(0);
        $this->assertCount(0,$banners);
    }

    public function test_getBanners_limit_2()
    {
        DB::table('banner_big')->insert([
            'image' => 'test_banner1.png',
            'url' => 'test1.com',
            'name' =>'test_banner1_big',
            'public_status' => 1,
            'publisher_no' => 2,
        ]);

        DB::table('banner_big')->insert([
            'image' => 'test_banner2.png',
            'url' => 'test2.com',
            'name' =>'test_banner2_big',
            'public_status' => 1,
            'publisher_no' => 2,
        ]);

        $banners = $this->banner_repository->getBanners(2);
        $this->assertCount(2,$banners);
    }

    public function test_getBanners_u_stamp_desc()
    {
        DB::table('banner_big')->insert([
            'image' => 'test_banner1.png',
            'url' => 'test1.com',
            'name' =>'test_banner1_big',
            'u_stamp' => Carbon::yesterday(),
            'public_status' => 1,
            'publisher_no' => 2,
        ]);

        DB::table('banner_big')->insert([
            'image' => 'test_banner2.png',
            'url' => 'test2.com',
            'name' =>'test_banner2_big',
            'u_stamp' => Carbon::today(),
            'public_status' => 1,
            'publisher_no' => 2,
        ]);

        $banners = $this->banner_repository->getBanners(100);
        foreach($banners as $banner)
        {
            if($banner->name == 'test_banner2_big'){
                $this->assertTrue(true);
                return;
            }
            if($banner->name == 'test_banner1_big'){
                $this->assertTrue(false);
                return;
            }
        }
    }

    public function test_getBanners_public_status_1()
    {
        DB::table('banner_big')->insert([
            'image' => 'test_banner.png',
            'url' => 'test.com',
            'name' =>'public_status_1',
            'u_stamp' => Carbon::yesterday(),
            'public_status' => 1,
            'publisher_no' => 2,
        ]);
        $banners = $this->banner_repository->getBanners(100);
        $this->assertTrue($banners->contains('name','public_status_1'));
    }

    public function test_getBanners_public_status_0()
    {
        DB::table('banner_big')->insert([
            'image' => 'test_banner.png',
            'url' => 'test.com',
            'name' =>'public_status_0',
            'u_stamp' => Carbon::yesterday(),
            'public_status' => 0,
            'publisher_no' => 2,
        ]);
        $banners = $this->banner_repository->getBanners(100);
        $this->assertFalse($banners->contains('name','public_status_0'));
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerBigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            ['publisher_no' => 2,'name' => 'スライド1','url' => '#','image' => '/web/img/uploads/banner/big/slide_01.jpg','public_status' => 1],
            ['publisher_no' => 2,'name' => 'スライド2','url' => '#','image' => '/web/img/uploads/banner/big/slide_02.jpg','public_status' => 1],
            ['publisher_no' => 2,'name' => 'スライド3','url' => '#','image' => '/web/img/uploads/banner/big/slide_03.jpg','public_status' => 1],
        ];
        DB::table('banner_big')->insert($params);
    }
}

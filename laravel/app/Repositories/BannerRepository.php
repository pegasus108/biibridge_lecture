<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BannerRepository
{
    public function getBanners($limit)
    {
        $image_host = config('app.hondana_url') ?? url('') . ':81';
        return DB::table('banner_big')
            ->select(
                DB::raw('CONCAT(\'' . $image_host . '\',image) as image'),
                'url',
                'name',
            )
            ->where('public_status', 1)
            ->orderBy('u_stamp', 'desc')
            ->limit($limit)
            ->get();
    }
}

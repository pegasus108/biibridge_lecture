<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Series;

class SeriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Series::factory(9)->create();
    }
}

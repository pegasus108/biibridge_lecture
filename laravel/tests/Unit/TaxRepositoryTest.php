<?php

namespace Tests\Unit;

use App\Repositories\TaxRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TaxRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    private $tax_repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->tax_repository = new TaxRepository;
    }

    public function test_getSalesTaxLate_start_date_today()
    {
        DB::table('sales_tax')->insert(['late' => 3.14, 'start_date' => Carbon::today()]);
        $sales_tax_late = $this->tax_repository->getSalesTaxLate();
        $this->assertEquals($sales_tax_late, 3.14);
    }

    public function test_getSalesTaxLate_start_date_tomorrow()
    {
        DB::table('sales_tax')->insert(['late' => 3.14, 'start_date' => Carbon::today()]);
        DB::table('sales_tax')->insert(['late' => 2.71, 'start_date' => Carbon::tomorrow()]);
        $sales_tax_late = $this->tax_repository->getSalesTaxLate();
        $this->assertEquals($sales_tax_late, 3.14);
    }
}

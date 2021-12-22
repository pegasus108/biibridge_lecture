<?php

namespace App\Repositories;

use App\Models\SalesTax;
use Illuminate\Support\Facades\DB;

class TaxRepository
{
    public function getSalesTaxLate()
    {
        $sales_tax = SalesTax::where('start_date', '<=', DB::raw('now()'))
            ->orderBy('start_date', 'desc')
            ->first();

        return $sales_tax->late;
    }
}

<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Repositories\TaxRepository;

class TaxComposer
{
    private $sales_tax_late;

    public function __construct(TaxRepository $tax_repository)
    {
        $this->sales_tax_late = $tax_repository->getSalesTaxLate();
    }

    public function compose(View $view)
    {
        $view->with('sales_tax_late', $this->sales_tax_late);
    }
}

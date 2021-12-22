<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTax extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'sales_tax';
    protected $primarykey = 'sales_tax_no';
}

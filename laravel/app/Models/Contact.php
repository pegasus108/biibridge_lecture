<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\ScopePublished;

class Contact extends HondanaModelBase
{
    use HasFactory;

    protected $table = 'contact';

}

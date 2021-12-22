<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\ScopePublished;

class News extends HondanaModelBase
{
    use HasFactory;

    protected $table = 'news';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ScopePublished);
    }
}

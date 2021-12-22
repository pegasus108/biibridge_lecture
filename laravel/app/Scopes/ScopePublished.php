<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ScopePublished implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder
            ->where('public_status', 1)
            ->where('public_date', '<', DB::raw('NOW()'));
    }
}

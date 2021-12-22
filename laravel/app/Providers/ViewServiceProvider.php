<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\TaxComposer;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TaxComposer::class);
    }

    public function boot()
    {
        View::composers([
            TaxComposer::class => ['components.book.sales-info-card', 'components.book.item', 'book.detail'],
        ]);
    }
}

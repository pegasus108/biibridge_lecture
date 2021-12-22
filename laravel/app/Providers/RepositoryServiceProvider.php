<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\BookRepository;
use App\Repositories\NewsRepository;
use App\Repositories\BannerRepository;
use App\Repositories\TaxRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public $bindings = [
        BookRepository::class => BookRepository::class,
        NewsRepository::class => NewsRepository::class,
        BannerRepository::class => BannerRepository::class,
        TaxRepository::class => TaxRepository::class,
    ];
}

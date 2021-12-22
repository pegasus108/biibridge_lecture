<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Repositories\BookRepository;
use App\Repositories\NewsRepository;
use App\Repositories\BannerRepository;

class TopPageServer extends Controller
{
    public function __invoke(NewsRepository $news_repository, BookRepository $book_repository, BannerRepository $banner_repository)
    {
        $news_list = $news_repository->getNewsList(limit: 5);
        $new_book_sales_info_list = $book_repository->getBookSalesInfoList(limit: 6, max_release_date: Carbon::now(), order_rule: 'release_date_desc');
        $upcoming_book_sales_info_list = $book_repository->getBookSalesInfoList(limit: 6, min_release_date: Carbon::now(), order_rule: 'release_date_asc');
        $recommend_book_sales_info_list = $book_repository->getBookSalesInfoList(limit: 6, recommend_status:1, order_rule: 'release_date_asc');

        $book_genre_info_list = [];
        $genres = $book_repository->getGenres();
        foreach($genres as $genre){
            $book_genre_info_list[$genre->genre_no] = $book_repository->getGenreBooks(genre_no:$genre->genre_no, limit:6);
        }
        $banners = $banner_repository->getBanners(limit: 3);

        $params = [
            'news_list' => $news_list,
            'new_book_sales_info_list' => $new_book_sales_info_list,
            'upcoming_book_sales_info_list' => $upcoming_book_sales_info_list,
            'recommend_book_sales_info_list' => $recommend_book_sales_info_list,
            'book_genre_info_list' => $book_genre_info_list,
            'genres' => $genres,
            'banners' => $banners,
        ];
        return view('index', $params);
    }
}

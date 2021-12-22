<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\NewsRepository;

class NewsController extends Controller
{
    protected $news_repository;

    public function __construct(NewsRepository $news_repository)
    {
        $this->news_repository = $news_repository;
    }

    public function index()
    {
        $news_list = $this->news_repository->getNewsList(limit: 15, use_paginate: true);
        return view('news.index', ['news_list' => $news_list]);
    }

    public function detail($news_no)
    {
        $news = $this->news_repository->getNewsDetail($news_no);
        if (is_null($news)) {
            abort(404);
        }
        return view('news.detail', ['news' => $news]);
    }

    public function blog()
    {
        $news_list = $this->news_repository->getNewsList(limit: 15, use_paginate: true,news_status:'blog');
        return view('blog.index', ['news_list' => $news_list]);
    }

    public function blogdetail($news_no)
    {
        $news = $this->news_repository->getNewsDetail($news_no);
        if (is_null($news)) {
            abort(404);
        }
        return view('news.detail', ['news' => $news]);
    }
}

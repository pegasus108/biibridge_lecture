<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Library\KeywordsParser;
use App\Repositories\BookRepository;

class BookController extends Controller
{
    protected $book_repository;

    public function __construct(BookRepository $book_repository)
    {
        $this->book_repository = $book_repository;
    }

    public function index(Request $request)
    {
        $keywords = KeywordsParser::parseFromString($request->keyword);

        $limit = $request->limit ?? 30;
        if ($limit > 120) {
            $limit = 120;
        }

        $books = $this->book_repository->getBookSalesInfoList(
            limit: $limit,
            use_paginate: true,
            keywords: $keywords,
            max_release_date: Carbon::now(),
            order_rule: $request->order_rule,
            name: $request->name,
            series_no: $request->series,
            genre_no: $request->genre,
            isbn: $request->isbn,
            author_name: $request->author,
        );

        $series_list = $this->book_repository->getSeriesList();
        $genres = $this->book_repository->getGenres();
        $params = [
            'books' => $books,
            'series_list' => $series_list,
            'genres' => $genres,
        ];
        return view('book.index', $params);
    }

    public function newRelease()
    {
        $books = $this->book_repository->getNewBooks(limit: 5);
        return view('book.new-release', ['books' => $books]);
    }

    public function detail($book_no)
    {
        $book = $this->book_repository->getBookDetail($book_no, series_books_limit: 6);
        if(is_null($book)){
            abort(404);
        }
        return view('book.detail', ['book' => $book]);
    }

    public function genre($genre_no)
    {
        $genre = $this->book_repository->getGenresByNo($genre_no);
        $books = $this->book_repository->getGenreBooks($genre_no, limit: 6 );
        if(is_null($genre)){
            abort(404);
        }
        return view('book.genre', ['books' => $books,'genre'=>$genre]);
    }

}

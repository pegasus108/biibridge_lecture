<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TopPageServer;
use App\Http\Controllers\EverigoController;
use App\Http\Controllers\CompanyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', TopPageServer::class)->name('index');

Route::get('book', [BookController::class, 'index'])->name('book.index');
Route::get('book/new-release', [BookController::class, 'newRelease'])->name('book.new-release');
Route::get('book/{book_no}', [BookController::class, 'detail'])->whereNumber('book_no')->name('book.detail');

Route::get('news', [NewsController::class, 'index'])->name('news.index');
Route::get('news/{news_no}', [NewsController::class, 'detail'])->whereNumber('news_no')->name('news.detail');

Route::get('blog', [NewsController::class, 'blog'])->name('blog.index');
Route::get('blog/{news_no}', [NewsController::class, 'blogdetail'])->whereNumber('news_no')->name('blog.detail');

Route::get('genre/{genre_no}', [BookController::class, 'genre'])->whereNumber('genre_no')->name('book.genre');

Route::get('company/recruit', [CompanyController::class, 'recruit'])->name('company.recruit');
Route::get('company', [CompanyController::class, 'about'])->name('company.about');
Route::get('company/privacy', [CompanyController::class, 'privacy'])->name('company.privacy');

Route::get('info', [InfoController::class, 'index'])->name('info.index');
Route::get('info/contact', [InfoController::class, 'contact'])->name('info.contact');
Route::post('info/confirm', [InfoController::class, 'confirm'])->name('info.confirm');
Route::post('info/send', [InfoController::class, 'send'])->name('info.send');
Route::get('info/contact/{info_no}', [InfoController::class, 'detail'])->whereNumber('info_no')->name('info.detail');


Route::get('everigo', [EverigoController::class,'everigo'])->name('everigo.index');
Route::get('everigo/webbasic', [EverigoController::class,'webbasic'])->name('everigo.webbasic');
Route::get('everigo/programbasic', [EverigoController::class,'programbasic'])->name('everigo.programbasic');
Route::get('everigo/feedback', [EverigoController::class,'feedback'])->name('everigo.feedback');

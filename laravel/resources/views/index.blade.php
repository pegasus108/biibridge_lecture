@extends('layouts.main')
@section('top','t')
@section('content')
<div class="id-slide">
    <ul class="slider">
        @foreach ($banners as $banner)
        <li><a href="{{ $banner->url }}"><img src="{{ $banner->image}}" alt="{{ $banner->name }}"></a></li>
        @endforeach
    </ul>
</div>
<article>
    <x-headline title="新刊情報" link="{{ route('book.new-release') }}" />
    <div class="books-single">
        @each('components.book.sales-info-card', $new_book_sales_info_list, 'book')
    </div>

    <x-headline title="刊行予定"  />
    <div class="books-single">
        @each('components.book.sales-info-card', $upcoming_book_sales_info_list, 'book')
    </div>

    <x-headline title="おすすめ"  />
    <div class="books-single">
        @each('components.book.sales-info-card', $recommend_book_sales_info_list, 'book')
    </div>

    @foreach ($genres as $genre)
    <x-headline title="{{$genre->name}}" link="{{ route('book.genre',$genre->genre_no) }}" />
        <div class="books-single">
            @each('components.book.sales-info-card', $book_genre_info_list[$genre->genre_no], 'book')
        </div>
    @endforeach


    <x-headline title="Shibakawaチャンネル" />
    @include('components.youtube.list')

    <x-headline title="おしらせ" link="{{ route('news.index') }}" />
    @include('components.news.list')
</article>
@endsection

@extends('layouts.main')

@section('title','お知らせ')

@section('content')
<article>
    <x-headline title="お知らせ" />
    @include('components.news.list')
    {{ $news_list->links('components.pagenation') }}
</article>
@endsection
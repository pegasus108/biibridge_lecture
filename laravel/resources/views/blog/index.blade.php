@extends('layouts.main')

@section('title','ブログ')

@section('content')
<article>
    <x-headline title="ブログ" />
    @include('components.news.list')
    {{ $news_list->links('components.pagenation') }}
</article>
@endsection

@extends('layouts.main')

@section('title','お問い合わせ')

@section('content')
<article>
    <x-headline title="お問い合わせ" />
    @include('components.news.list')
    {{ $info_list->links('components.pagenation') }}
</article>
@endsection

@extends('layouts.main')

@section('title','新刊情報')

@section('content')
<article>
    <div class="newbook-container">
        <x-headline title="今月の新刊" />
        @each('components.book.item', $books, 'book')
    </div>
    {{ $books->links('components.pagenation') }}
</article>
@endsection
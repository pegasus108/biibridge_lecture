
@extends('layouts.main')

@section('title',$genre->name)

@section('content')
<article>
    <div class="newbook-container">
        <x-headline title="{{$genre->name}}" />
        @each('components.book.item', $books, 'book')
    </div>
    {{ $books->links('components.pagenation') }}
</article>
@endsection

@extends('layouts.main')

@section('title','ブログ')

@section('content')
<article>
    <div class="information-detail">
        <x-headline title="{{ $news->name }}" />
        <div class="information-detail__header">
            <time>更新日：{{ date('Y.m.d',strtotime($news->update_date)) }}</time>
            <x-sns-share title="{{ $news->name }}" link="{{ url()->current() }}"/>
        </div>
        <div class="information-detail__text">
            {!! $news->value !!}
        </div>
        <div class="information-detail__button">
            <ul>
                <li><a href="{{ route('news.index') }}">一覧へ戻る</a></li>
            </ul>
        </div>
    </div>
</article>
@endsection

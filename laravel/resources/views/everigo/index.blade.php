@extends('layouts.main')

@section('title','everigo')

@section('content')
<article>
    <p><a href="{{route('everigo.webbasic')}}">webbasic</a></p>
    <p><a href="{{route('everigo.programbasic')}}">programbasic</a></p>
    <p><a href="{{route('everigo.feedback')}}">今日の振り返り</a></p>
    <p><a href="https://forms.gle/sPhAf5b8i74GZ9NS7" target="_blank" rel="noopener noreferrer">QA票</a></p>
</article>
@endsection

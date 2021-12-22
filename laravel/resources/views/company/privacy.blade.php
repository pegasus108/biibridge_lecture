@extends('layouts.main')

@section('title','プライバシーポリシー')

@section('content')
<article>
    <x-headline title="プライバシーポリシー" />
    {!! $company->value !!}
</article>
@endsection

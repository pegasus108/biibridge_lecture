@extends('layouts.main')

@section('title','採用情報')

@section('content')
<article>
    <x-headline title="採用情報" />
        {!! $company->value !!}
</article>
@endsection

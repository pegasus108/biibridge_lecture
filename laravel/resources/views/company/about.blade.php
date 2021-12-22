@extends('layouts.main')

{{-- @section('title',$company->name) --}}
@section('title','会社概要')

@section('content')
<article>
    {{-- <x-headline title="{{$company->name}}" /> --}}
    <x-headline title="会社概要" />
        {!! $company->value !!}
    </div>
</article>
@endsection

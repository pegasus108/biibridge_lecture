@extends('layouts.main')

@section('title','お問い合わせ内容確認')

@section('content')
<article>
    <x-headline title="お問い合わせ内容確認" />
	<div class="contact-form">
        <form method="POST" action="{{route('company.send')}}">
            @csrf
            <h3 class="section-title">お問い合わせ内容確認</h3>
            <label for="email">メールアドレス（必須）</label>
            <input type="hidden" id="email" name="email" value="{{$contact_data->email}}">
            <p>{{$contact_data->email}}</p>

            <label for="name">名前（必須）</label>
            <input type="hidden" id="name" name="name" value="{{$contact_data->name}}">
            <p>{{$contact_data->name}}</p>

            <label for="title">お問い合わせ件名（必須）</label>
            <input type="hidden" id="title" name="title" value="{{$contact_data->title}}">
            <p>{{$contact_data->title}}</p>
            <label for="value">お問い合わせ内容（必須）</label>

            <input type="hidden" id="value" name="value" value="{{$contact_data->value}}">
            {{-- <p>{!!nl2br(e($contact_data->value))!!}</p> --}}
            {{-- scriptを流してみる --}}
            <p>{!!nl2br(e($contact_data->value))!!}</p>

            <input class="contact-submit" type="submit" value="入力内容送信">
            </form>
    </div>
</article>
@endsection

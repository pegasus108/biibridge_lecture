@extends('layouts.main')

@section('title','送信完了')

@section('content')
<article>
    <x-headline title="送信完了" />
	<div class="contact-form">
            <h3 class="section-title">送信完了</h3>
            <p>お問い合わせありがとうございました。<br>近日中に担当者よりご連絡差し上げます。</p>
            <label for="email">メールアドレス</label>
            <p>{{$contact_data->email}}</p>
            <label for="name">名前</label>
            <p>{{$contact_data->name}}</p>
            <label for="title">お問い合わせ件名</label>
            <p>{{$contact_data->title}}</p>
            <label for="value">お問い合わせ内容</label>
            <p>{!!nl2br(e($contact_data->value))!!}</p>
    </div>
</article>
@endsection

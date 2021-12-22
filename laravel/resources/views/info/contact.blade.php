@extends('layouts.main')

@section('title','お問い合わせ')

@section('content')
<article>
    <x-headline title="お問い合わせ" />
	<div class="contact-form">

        <form method="POST" action="{{route('company.confirm')}}">
            <form method="POST" action="{{route('company.confirm')}}">
            @csrf
            <h3 class="section-title">お問い合わせ</h3>
            <label for="email">メールアドレス（必須）</label>
            <input type="email" id="email" name="email" value="{{old('email')}}" required>
            @error('email')
            <p class="error-message">{{$message}}</p>
            @enderror

            <label for="name">名前（必須）</label>
            @error('name')
                <p class="error-message">{{$message}}</p>
            @enderror
            <input type="text" id="name" name="name" value="{{old('name')}}" required>


            <label for="title">お問い合わせ件名（必須）</label>
            @error('title')
                <p class="error-message">{{$message}}</p>
            @enderror
            <input type="text" id="title" name="title" value="{{old('title')}}" required>

            <label for="value">お問い合わせ内容（必須）</label>
            <textarea id="value" name="value" required>{{old('value')}}</textarea>
            @error('value')
            <p class="error-message">{{$message}}</p>
            @enderror
            <p>※必須項目は必ずご入力ください</p>

            <input class="contact-submit" type="submit" value="入力内容確認">
            </form>
    </div>
</article>
@endsection

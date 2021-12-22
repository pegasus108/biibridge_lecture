@extends('layouts.main')

@section('title','書籍情報')

@section('content')
<article>
    <div class="list-container">
        <div class="list-search">
            <div class="list-search__headline">
                <h2>詳細検索</h2>
            </div>
            <form>
                <label for="search_keyword">キーワード</label><br>
                <input id="search_keyword" type="text" name="keyword" value="{{ request()->query('keyword') }}"><br>
                <label for="search_title">タイトル</label><br>
                <input id="search_title" type="text" name="name" value="{{ request()->query('name') }}"><br>
                <label for="search_author">著者名</label><br>
                <input id="search_author" type="text" name="author" value="{{ request()->query('author') }}"><br>
                <label for="search_genre">ジャンル</label><br>
                <select id="search_genre" name="genre">
                    <option value="" selected>--選択してください--</option>
                    @foreach ($genres as $genre)
                    <option @if(request()->query('genre') == $genre->genre_no) selected @endif
                        value="{{ $genre->genre_no}}" >
                        ├{{ $genre->name }}</option>
                    @endforeach
                </select><br>
                <label for="search_series">シリーズ</label><br>
                <select id="search_series" name="series">
                    <option value="">--選択してください--</option>
                    @foreach ($series_list as $series)
                    <option @if(request()->query('series') == $series->series_no) selected @endif
                        value="{{ $series->series_no}}">├{{ $series->name }}</option>
                    @endforeach
                </select><br>
                <label for="search_isbn">ISBN</label><br>
                <input id="search_isbn" type="text" name="isbn" value="{{ request()->query('isbn') }}"><br>
                <input type="hidden" name="limit" value="{{ request()->query('limit') }}">
                <input type="hidden" name="order_rule" value="{{ request()->query('order_rule') }}">
                <input type="submit" value="検索">
            </form>
        </div>
        <div class="list-result">
            <div class="list-result__headline">
                @if( $books->total() > 0)
                <h2><span>{{ $books->total() }}</span>件の商品が見つかりました</h2>
                @else
                <h2>該当する商品が見つかりませんでした</h2>
                @endif
            </div>
            <form>
                <div class="list-result__property">
                    <div class="list-result__property--sort">
                        <p>表示順</p>
                        <select id="order_rule">
                            @php $order_rules = [
                            'release_date_desc' => '出版年月日の新しい順',
                            'release_date_asc' => '出版年月日の古い順',
                            'price_asc' => '価格の安い順',
                            'price_desc' => '価格の高い順',
                            'name_asc' => 'タイトル順',
                            ]@endphp
                            @foreach ($order_rules as $order_rule => $order_rule_name)
                            <option @if(request()->query('order_rule') == $order_rule) selected @endif
                                value="{{ $order_rule }}">{{ $order_rule_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="list-result__property--number">
                        <p>表示件数</p>
                        <input type="radio" name="limit" id="limit1" value="30" @if(request()->query('limit') ?? 30 ==
                        30 ) checked="checked" @endif>
                        <label for="limit1">30件</label>
                        <input type="radio" name="limit" id="limit2" value="60" @if(request()->query('limit') == 60 )
                        checked="checked" @endif>
                        <label for="limit2">60件</label>
                        <input type="radio" name="limit" id="limit3" value="120" @if(request()->query('limit') == 120 )
                        checked="checked" @endif>
                        <label for="limit3">120件</label>
                    </div>
                </div>
            </form>
            <div class="list-result__books">
                <div class="books-multi">
                    @each('components.book.sales-info-card', $books,'book')
                </div>
            </div>
            {{ $books->links('components.pagenation') }}
        </div>
    </div>
</article>
@endsection

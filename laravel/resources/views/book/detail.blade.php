@extends('layouts.main')

@section('title',$book->name)

@section('content')
<article>
    <div class="detail-container">
        <div class="detail-property">
            <div class="detail-property__image">
                <div class="detail-property__image--cover"><img src="{{ $book->image }}" alt="{{ $book->name }}" />
                </div>
                <div class="detail-property__image--button">
                    <ul>
                        <li><a href="#">立ち読み</a></li>
                    </ul>
                </div>
                <div class="detail-property__image--sns">
                    <x-sns-share title="{{ $book->name }}" link="{{ url()->current() }}" />
                </div>
            </div>
            <div class="detail-property__document">
                <div class="detail-property__document--title">
                    <h2>{{ $book->name }}</h2>
                </div>
                <div class="detail-property__document--attribute">
                    <table>
                        <tbody>
                            @foreach ($book->authors as $author)
                            <tr>
                                <th scope="row">{{ $author->type }}</th>
                                <td>{{ $author->name }}</td>
                            </tr>
                            @endforeach

                            <tr>
                                <th scope="row">ジャンル</th>
                                <td>
                                    @foreach ($book->genres as $genre)
                                    {{ $genre->name }}&#047;
                                    @endforeach
                                </td>
                            </tr>

                            {{-- @if($author->type=='著')
                            <tr>
                                <th scope="row">{{ $author->type }}</th>
                                <td>{{ $author->name }}</td>
                            </tr>
                            @endif --}}

                            <tr>
                                <th scope="row">発売日</th>
                                <td>{{ date("y年n月j日",strtotime($book->release_date))}}</td>
                            </tr>
                            <tr>
                                <th scope="row">価格</th>
                                <td>&yen;{{ number_format( $book->price * ( 1 + $sales_tax_late )) }} （本体 &yen;{{ number_format( $book->price )}}＋税{{ $sales_tax_late * 100 }}%）</td>
                            </tr>
                            <tr>
                                <th scope="row">サイズ</th>
                                <td>{{ $book->size }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ISBN</th>
                                <td>{{ $book->isbn }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ページ数</th>
                                <td>{{ $book->page }}ページ</td>
                            </tr>
                            <tr>
                                <th scope="row">在庫状況</th>
                                <td>{{ $book->stock_status }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="detail-property__document--text">
                    <p>{{ $book->content }}</p>
                </div>
            </div>
        </div>




        <div class="detail-purchase">
            <x-headline title="購入先" />
            <div class="detail-purchase__retailer">
                <ul>
                    <li><a href="#"><img src="{{ asset('image/common/retailer/retailer_01.png') }}" alt="　" /></a></li>
                    <li><a href="#"><img src="{{ asset('image/common/retailer/retailer_02.png') }}" alt="　" /></a></li>
                    <li><a href="#"><img src="{{ asset('image/common/retailer/retailer_03.png') }}" alt="　" /></a></li>
                    <li><a href="#"><img src="{{ asset('image/common/retailer/retailer_04.png') }}" alt="　" /></a></li>
                    <li><a href="#"><img src="{{ asset('image/common/retailer/retailer_05.png') }}" alt="　" /></a></li>
                    <li><a href="#"><img src="{{ asset('image/common/retailer/retailer_06.png') }}" alt="　" /></a></li>
                    <li><a href="#"><img src="{{ asset('image/common/retailer/retailer_07.png') }}" alt="　" /></a></li>
                    <li><a href="#"><img src="{{ asset('image/common/retailer/retailer_08.png') }}" alt="　" /></a></li>
                    <li><a href="#"><img src="{{ asset('image/common/retailer/retailer_09.png') }}" alt="　" /></a></li>
                    <li><a href="#"><img src="{{ asset('image/common/retailer/retailer_10.png') }}" alt="　" /></a></li>
                </ul>
            </div>
        </div>

        <div class="detail-series">
            <x-headline title="シリーズ作品" />
            <div class="books-single">
                @each('components.book.sales-info-card', $book->series_books,'book')
            </div>
        </div>

    </div>
</article>
@endsection

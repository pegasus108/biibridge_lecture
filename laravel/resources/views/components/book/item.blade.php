<div class="newbook-box">
    <div class="newbook-box__image">
        <div class="newbook-box__image--cover"><a href="{{ route('book.detail',$book->book_no) }}">
                <img src="{{ $book->image }}" alt="{{ $book->name }}" /></a>
        </div>
        <div class="newbook-box__image--button">
            <ul>
                <li><a href="#">立ち読み</a></li>
            </ul>
        </div>
    </div>
    <div class="newbook-box__document">
        <div class="newbook-box__document--title">
            <p><a href="{{ route('book.detail',$book->book_no) }}">{{ $book->name }}</a></p>
        </div>
        <div class="newbook-box__document--attribute">
            <ul>
                <li><span>発売日</span><span>{{ date('y年n月j日', strtotime($book->release_date)) }}</span></li>
                <li><span>価格</span><span>&yen;{{ number_format( $book->price * ( 1 + $sales_tax_late )) }}</span></li>
                <li><span>ページ数</span><span>{{$book->page}}ページ</span></li>
                <li><span>在庫状況</span><span>{{$book->stock_status}}</span></li>
            </ul>
        </div>
        <div class="newbook-box__document--text">
            <p>{{ $book->content }}</p>
        </div>
    </div>
</div>

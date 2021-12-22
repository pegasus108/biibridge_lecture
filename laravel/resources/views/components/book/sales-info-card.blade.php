<div class="books__bibl">
    <div class="books__bibl--image">
        <p><a href="{{ route('book.detail',$book->book_no) }}"><img src="{{ $book->image }}"
                    alt="{{ $book->name }}"></a></p>
    </div>
    <div class="books__bibl--title">
        <p><a href="{{ route('book.detail',$book->book_no) }}">{{ $book->name }}</a></p>
    </div>
    <div class="books__bibl--attribute">
        <ul>
            <li><span>発売日</span><span>{{ date('y年n月j日',strtotime($book->release_date)) }}</span></li>
            <li><span>価格</span><span>&yen;{{ number_format( $book->price * ( 1 + $sales_tax_late )) }}</span></li>
            <li><span>ページ数</span><span>{{ $book->page }}</span></li>
            <li><span>在庫状況</span><span>{{ $book->stockname }}</span></li>
        </ul>
    </div>
</div>

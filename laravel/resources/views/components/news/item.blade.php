<li @if($news_item->category=='お知らせ') class="information__title--category1"@endif
    @if($news_item->category=='特典情報') class="information__title--category2"@endif
    @if($news_item->category=='ブログ') class="information__title--category3"@endif
    >
    @if($news_item->category=='ブログ')
    <a href="{{ route('blog.detail',['news_no' => $news_item->news_no ])}}">
    @else
    <a href="{{ route('news.detail',['news_no' => $news_item->news_no ])}}">
    @endif
        <span>{{ $news_item->category }}</span>
        <time>{{ date('Y.m.d',strtotime($news_item->public_date)) }}</time>
        <p>{{ $news_item->name}}</p>
    </a>
</li>

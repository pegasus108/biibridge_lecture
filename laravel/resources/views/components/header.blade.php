<header>
    <div id="Hd">
        <div class="hd-logo">
            <h1><a href="{{ route('index') }}"><img src="{{ asset('image/common/logo.png') }}" alt="" /></a></h1>
            <p><a href="{{ route('everigo.index')}}">everiGO</a>専用のシステム教材です</p>
            <p>知識よりも実践に重きを置いた講座です</p>
            <p>本システムの修正を繰り返すことで開発技術を身に着けます</p>
        </div>
        <div class="hd-global">
            <nav>
                <ul>
                    <li><a href="{{ route('index') }}">TOP</a></li>
                    <li><a href="{{ route('book.new-release') }}">新刊情報</a></li>
                    <li><a href="{{ route('book.index') }}">書籍情報</a></li>
                    <li><a href="{{ route('news.index') }}">お知らせ</a></li>
                    <li><a href="{{ route('blog.index') }}">ブログ</a></li>
                </ul>
            </nav>
        </div>
        <div class="hd-search">
            <form action="{{ route('book.index') }}" method="get">
                <input type="hidden" name="search_menu" value="keyword">
                <input type="hidden" name="tab" value="3">
                <div class="hd-search__box">
                    <input class="hd-search__box--keyword" type="text" name="keyword" value="" id=""
                        placeholder="書籍タイトル・著者名など入力">
                    <input class="hd-search__box--button" type="submit" id="" value="　">
                </div>
            </form>
        </div>
</header>

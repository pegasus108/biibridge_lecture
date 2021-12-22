<footer>
    <div id="Ft">
        <div class="ft-navigation">
            <h1><img src="{{ asset('image/common/logo_white.png') }}" alt=""/></h1>
            {{-- <address>〒100-0014 東京都千代田区永田町1丁目7-1</address> --}}
            @inject('officeaddress','App\Repositories\CompanyRepository')
            {{$officeaddress->getOfficeAddress()->value}}
            <nav>
                <ul>
                    <li><a href="{{ route('company.recruit') }}">採用情報</a></li>
                    <li><a href="{{ route('company.privacy') }}">プライバシーポリシー</a></li>
                    <li><a href="{{ route('company.about') }}">会社概要</a></li>
                    <li><a href="{{ route('info.contact') }}">お問い合わせ</a></li>
                </ul>
            </nav>
        </div>
        <div class="ft-copyright">
            <p>© SHIBAKAWA BOOK STORE All Rights Reserved</p>
        </div>
    </div>
</footer>

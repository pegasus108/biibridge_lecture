<ul>
    <li>
        <a title="Twitterでシェア" rel="nofollow noopener" target="_blank"
            href="https://twitter.com/intent/tweet?text={{ $title }}&url={{ $link }}&hashtags={{ $title }}">
            <img src=" {{ asset('image/common/icons/sns_01.png') }}" alt="twitter" />
        </a>
    </li>
    <li>
        <a title="Facebookでシェア" rel="nofollow noopener" target="_blank"
            href="https://www.facebook.com/sharer.php?u={{ $link }}">
            <img src=" {{ asset('image/common/icons/sns_02.png') }}" alt="facebook" />
        </a>
    </li>
    <li>
        <a title="Lineでシェア" href="https://social-plugins.line.me/lineit/share?url={{ $link }}" target="_blank"
            rel="nofollow noopener">
            <img src=" {{ asset('image/common/icons/sns_03.png') }}" alt="LINE" />
        </a>
    </li>
</ul>
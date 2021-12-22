<li class="information__title--category1">
    <a href="{{ route('info.detail',['info_no' => $info_item->info_no ])}}">
        <span>{{ $info_item->title }}</span>
        <p>{{ $info_item->value}}</p>
    </a>
</li>

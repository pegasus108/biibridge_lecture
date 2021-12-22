@if ($paginator->hasPages())
<div class="pager">
    <ul>
        <li @if($paginator->onFirstPage()) class="current" @endif>
            <a href="{{ $paginator->url(1) }}">最初</a>
        </li>

        @if($paginator->currentPage() > 3)
            <li>…</li>
        @endif

        @php 
            $min = $paginator->currentPage() - 2;
            $max = $paginator->currentPage() + 2; 
            if($min < 1)
            {
                $max -= $min - 1;
            }
            if($max > $paginator->lastPage())
            {
                $min -= $max - $paginator->lastPage();
                $max = $paginator->lastPage();
            }
            if($min < 1)
            {
                $min = 1;
            }
        @endphp
        @for($i = $min; $i <= $max; $i++)
            @if($i == $paginator->currentPage())
                <li class="current"><a href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @continue
            @endif
            <li><a href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
        @endfor

        @if($paginator->currentPage() < $paginator->lastPage() - 2)
            <li>…</li>
        @endif

        <li @if(!$paginator->hasMorePages()) class="current" @endif>
            <a href="{{ $paginator->url($paginator->lastPage()) }}">最後</a>
        </li>
    </ul>
</div>
@endif
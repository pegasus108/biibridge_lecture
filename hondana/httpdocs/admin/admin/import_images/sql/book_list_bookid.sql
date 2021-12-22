select *
from book

where
book_no in ('{$in_bookid|@join:"','"}')
{if $publisher_no}
and publisher_no = '{$publisher_no|escape}'
{/if}
;
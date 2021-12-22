select *
from book

where
isbn in ('{$in_isbn|@join:"','"}')
{if $publisher_no}
and publisher_no = '{$publisher_no|escape}'
{/if}
;
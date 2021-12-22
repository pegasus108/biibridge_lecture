select *
from book
where isbn <> ''
{if $publisher_no}
and publisher_no = {$publisher_no}
{/if}
;
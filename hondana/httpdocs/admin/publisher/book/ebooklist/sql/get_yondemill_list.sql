select SQL_CALC_FOUND_ROWS
b.book_no,
b.name,
b.sub_name,
b.yondemill_book_sales_url

from book as b

where
b.publisher_no = {$publisher_no}
and b.yondemill_book_sales_url is not null
{if $search_title}
and (
	b.name like '%{$search_title}%' or
	b.sub_name like '%{$search_title}%'
)
{/if}

{if !$order || $order == 'title_asc'}
order by
	b.name
{elseif $order == 'title_desc'}
order by
	b.name desc
{/if}

{if $limit}
	limit {if $offset}{$offset}, {/if}{$limit}
{/if}
;
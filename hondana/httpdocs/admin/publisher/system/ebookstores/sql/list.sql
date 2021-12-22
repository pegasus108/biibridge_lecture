select
	e.id, e.name ,e.search_url,e.charset,pe.publisher_ebookstores_no, pe.public_status
{if $book_no}
	,be.id as be_id,be.url,be.public_status as be_status
{/if}

from
	ebookstores as e
	left join (select * from publisher_ebookstores where publisher_no = {$publisher_no}) as pe on e.id = pe.ebookstores_no
{if $book_no}
	left join (select * from book_ebookstores where book_no = {$book_no}) as be on e.id = be.ebookstores_id
{/if}

order by
	pe.display_order
;
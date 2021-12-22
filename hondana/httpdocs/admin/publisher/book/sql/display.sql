{*lock tables book write;*}


update book
set
	public_status = 1
where book_no
	in(
		{foreach name=book_no from=$book_no_list item=book_no }
		{$book_no}{if !$smarty.foreach.book_no.last},{/if}
		{/foreach}
	)
	and publisher_no = {$publisher_no};


{*unlock tables;*}

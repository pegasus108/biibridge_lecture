lock tables linkage_{$cancel_linkage_id},book write;


{foreach from=$book_no_list item=book_no}


update
	linkage_{$cancel_linkage_id}
set
	linkage_{$cancel_linkage_id}.status = if(linkage_{$cancel_linkage_id}.process_date is not null,2,0)
where
	linkage_{$cancel_linkage_id}.book_no = {$book_no}
	and exists(
		select *
		from book
		where
		book.book_no = linkage_{$cancel_linkage_id}.book_no
		and book.publisher_no = {$publisher_no}
	)
;


{/foreach}


unlock tables;

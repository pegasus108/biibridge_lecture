lock tables linkage_commissioner, book write;


UPDATE
	linkage_commissioner

SET
	status = 2,
	process_date = current_timestamp()

WHERE book_no in ({$book_no_list|@join:','}) 
and status in(1,2)
and exists(
	select 1
	from book
	where book.publisher_no = {$publisher_no}
	and book.book_no = linkage_commissioner.book_no
);


unlock tables;

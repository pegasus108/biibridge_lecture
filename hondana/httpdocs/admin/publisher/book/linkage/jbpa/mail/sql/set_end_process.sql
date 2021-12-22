lock tables linkage_jbpa, book write;


UPDATE
	linkage_jbpa

SET
	status = 2,
	process_date = current_timestamp()

WHERE
	status = 1
	and exists(
		SELECT * from book where book.book_no = linkage_jbpa.book_no and book.publisher_no = {$publisher_no}
	);


unlock tables;

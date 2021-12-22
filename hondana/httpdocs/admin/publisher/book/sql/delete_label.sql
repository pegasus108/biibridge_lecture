{*lock tables book_label,book write;*}


delete from book_label
where
	book_no in ( {$book_no_list|@join:','} )
	and label_no = {$label_no}
	and exists(
		select * from book
		where book.book_no = book_label.book_no
		and book.publisher_no = '{$publisher_no|escape}'
	);


{*unlock tables;*}

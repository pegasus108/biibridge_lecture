{*lock tables book_series,book write;*}


delete from book_series
where
	book_no in ( {$book_no_list|@join:','} )
	and series_no = {$series_no}
	and exists(
		select * from book
		where book.book_no = book_series.book_no
		and book.publisher_no = {$publisher_no}
	);



{*unlock tables;*}

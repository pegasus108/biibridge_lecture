{*lock tables book write;*}


update book
	set stock_status_no = {$stock_status_no}
where
	book_no in ( {$book_no_list|@join:','} )
	and publisher_no = {$publisher_no};


{*unlock tables;*}

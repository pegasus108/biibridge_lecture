{*lock tables book write;*}


update book
set
	cart_status = 0
where book_no
	in( {$book_no_list|@join:','} )
	and publisher_no = {$publisher_no};


{*unlock tables;*}

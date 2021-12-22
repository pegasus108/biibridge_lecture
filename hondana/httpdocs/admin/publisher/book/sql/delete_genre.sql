{*lock tables book_genre,book write;*}


delete from book_genre
where
	book_no in ( {$book_no_list|@join:','} )
	and genre_no = {$genre_no}
	and exists(
		select * from book
		where book.book_no = book_genre.book_no
		and book.publisher_no = {$publisher_no}
	);


{*unlock tables;*}

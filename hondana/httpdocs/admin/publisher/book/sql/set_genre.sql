{*lock tables book_genre write;*}

{foreach name=book_no from=$book_no_list item=book_no}


select @myNo := coalesce(max(book_genre_no), 0) + 1
from book_genre;

insert into
	book_genre(
		book_genre_no,
		book_no,
		genre_no,
		c_stamp
	)
	values(
		@myNo,
		{$book_no},
		{$genre_no},
		current_timestamp
	);
{/foreach}


{*unlock tables;*}

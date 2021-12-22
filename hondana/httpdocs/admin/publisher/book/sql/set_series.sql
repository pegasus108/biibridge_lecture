{*lock tables book_series write;*}

{foreach name=book_no from=$book_no_list item=book_no}


select @myNo := coalesce(max(book_series_no), 0) + 1
from book_series;

insert into
	book_series(
		book_series_no,
		book_no,
		series_no,
		c_stamp
	)
	values(
		@myNo,
		{$book_no},
		{$series_no},
		current_timestamp
	);
{/foreach}


{*unlock tables;*}

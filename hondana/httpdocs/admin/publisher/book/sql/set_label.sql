{*lock tables book_label write;*}

{foreach name=book_no from=$book_no_list item=book_no}


select @myNo := coalesce(max(book_label_no), 0) + 1
from book_label;

insert into
	book_label(
		book_label_no,
		book_no,
		label_no,
		c_stamp
	)
	values(
		@myNo,
		{$book_no},
		{$label_no},
		current_timestamp
	);
{/foreach}


{*unlock tables;*}

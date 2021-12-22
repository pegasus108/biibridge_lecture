lock tables author write;


select @myNo := coalesce(max(author_no), 0) + 1
from author;


insert into
	author(
		author_no,
		publisher_no,
		name,
		kana,
		image,
		value,
		c_stamp
	)
	values(
		@myNo,
		{$publisher_no},
		'{$name}',
		'{$kana}',
		'{$image}',
		'{$value}',
		current_timestamp
	);


unlock tables;

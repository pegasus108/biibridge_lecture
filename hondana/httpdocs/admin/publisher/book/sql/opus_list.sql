SELECT
	o.opus_no,
	o.book_no,
	a.name,
	a.author_no,
	if(o.author_type_no=16 and o.author_type_other is not null,o.author_type_other,at.name) as type
from opus as o
	left join author as a
		on o.author_no = a.author_no
		and a.publisher_no = {$publisher_no}
	left join author_type as at
		on o.author_type_no = at.author_type_no

where o.book_no in ({$bookNoList|@join:','})
order by o.c_stamp,o.opus_no
;
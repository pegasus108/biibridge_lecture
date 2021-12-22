select
	nc.*
from
	`book` as nc
	left join
		book_relate as n
		on nc.book_no = n.book_no
where
	nc.book_no in ( {$book_no_list|@join:','} )
	and n.book_relate_no is not null
	and nc.publisher_no = {$publisher_no};
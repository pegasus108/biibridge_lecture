select
	nc.*
from
	`label` as nc
	left join
		book_label as n
		on nc.label_no = n.label_no
where
	nc.label_no in ({$listString})
	and n.book_no is not null
	and nc.publisher_no = {$publisher_no};
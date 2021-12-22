select
	nc.*
from
	`series` as nc
	left join
		book_series as n
		on nc.series_no = n.series_no
where
	nc.series_no in ({$listString})
	and n.book_no is not null
	and nc.publisher_no = {$publisher_no};
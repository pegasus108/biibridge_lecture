select
	nc.*
from
	`author` as nc
	left join
		opus as n
		on nc.author_no = n.author_no
where
	nc.author_no in ({$listString})
	and n.opus_no is not null
	and nc.publisher_no = {$publisher_no};
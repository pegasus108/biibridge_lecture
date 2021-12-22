select
	nc.*
from
	`publisher_account` as nc
	left join
		opus as n
		on nc.publisher_account_no = n.publisher_account_no
where
	nc.publisher_account_no in ({$listString})
	and n.opus_no is not null
	and nc.publisher_no = {$publisher_no};
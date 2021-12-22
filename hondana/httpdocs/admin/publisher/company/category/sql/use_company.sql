select
	nc.*
from
	`company_category` as nc
	left join
		company as n
		on nc.company_category_no = n.company_category_no
where
	nc.company_category_no in ({$listString})
	and n.company_no is not null
	and nc.publisher_no = '{$publisher_no|escape}';
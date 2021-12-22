select
	nc.*
from
	`news_category` as nc
	left join
		news as n
		on nc.news_category_no = n.news_category_no
where
	nc.news_category_no in ({$listString})
	and n.news_no is not null
	and nc.publisher_no = '{$publisher_no|escape}';
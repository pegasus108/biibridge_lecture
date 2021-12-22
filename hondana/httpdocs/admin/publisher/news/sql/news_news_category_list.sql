SELECT
	nnc.news_news_category_no,
	nnc.news_category_no,
	nnc.news_no,
	nc.name
from news_news_category as nnc
	inner join news_category as nc
		on nnc.news_category_no = nc.news_category_no
		and nc.publisher_no = '{$publisher_no|escape}'
		and nc.display <> 0
where nnc.news_no in ({$newsNoList|@join:','})
;
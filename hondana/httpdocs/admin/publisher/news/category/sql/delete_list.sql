SELECT nc.*
FROM `news_category` as nc
where
exists(
	select * from news_category as gc where gc.news_category_no in({$listString})
	and nc.lft >= gc.lft and nc.lft <= gc.rgt
)
and nc.publisher_no = '{$publisher_no|escape}'
order by nc.lft;
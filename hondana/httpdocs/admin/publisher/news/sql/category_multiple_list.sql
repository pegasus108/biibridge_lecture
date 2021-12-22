SELECT
	nnc.*,nc.name as name

FROM news_news_category AS nnc
	left join news_category as nc on nnc.news_category_no = nc.news_category_no

WHERE nc.display = 1
	AND nc.publisher_no = '{$publisher_no|escape}'
	AND nnc.news_no = '{$news_no|escape}'

ORDER BY nc.lft;
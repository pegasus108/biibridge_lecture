SELECT
	nc.news_category_no,
	nc.name

FROM news_category AS nc

WHERE
nc.display = 1
AND nc.publisher_no = '{$publisher_no|escape}'
AND nc.news_category_no in ({$news_category_list})

ORDER BY nc.lft
;
update news
set
	news_category_no = '{$news_category_no|escape}'
where
	news_no in ({$listString})
	and publisher_no = '{$publisher_no|escape}';

SELECT * FROM news_category

where news_category_no = '{$news_category_no|escape}'
	and publisher_no = '{$publisher_no|escape}';

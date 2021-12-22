update news_category
set
	name = '{$category_name}'
where
	news_category_no = '{$news_category_no|escape}';



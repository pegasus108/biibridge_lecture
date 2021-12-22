{foreach name=deleteCategory from=$deleteCategoryList item=deleteCategory}

select @myL := lft from news_category
where
	news_category_no = {$deleteCategory.news_category_no};

delete
from news_relate
where
	exists(
		select * from news
		where news.news_no = news_relate.news_no
		and news.news_category_no = {$deleteCategory.news_category_no}
		and news.publisher_no = '{$publisher_no|escape}'
	);

delete
from news
where
	news_category_no = {$deleteCategory.news_category_no}
	and publisher_no = '{$publisher_no|escape}';

delete
from news_category
where
	news_category_no = {$deleteCategory.news_category_no}
	and publisher_no = '{$publisher_no|escape}';


update news_category
set
	lft = lft - 2
where
	lft > @myL
	and publisher_no = '{$publisher_no|escape}';


update news_category
set
	rgt = rgt - 2
where
	rgt > @myL
	and publisher_no = '{$publisher_no|escape}';

{/foreach}


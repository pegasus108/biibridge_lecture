delete
from news_relate
where
	news_no in ({$listString})
	and exists(
		select * from news
		where news.news_no = news_relate.news_no
		and news.publisher_no = '{$publisher_no|escape}'
	);

{if $publisher.news_category_edit}
	{* news category 複数登録 *}
delete
from news_news_category
where
	news_no in ({$listString})
	and exists(
		select * from news
		where news.news_no = news_news_category.news_no
		and news.publisher_no = '{$publisher_no|escape}'
	);

{/if}

delete
from news
where
	news_no in ({$listString})
	and publisher_no = '{$publisher_no|escape}';

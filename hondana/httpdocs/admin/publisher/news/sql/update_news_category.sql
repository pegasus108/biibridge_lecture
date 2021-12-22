
{if $deleteNewsCategoryList}
delete from news_news_category
where news_news_category_no in( {$deleteNewsCategoryList|@join:','} )
	and exists(
		select * from news_category
		where
		news_category.news_category_no = news_news_category.news_category_no
		and news_category.publisher_no = '{$publisher_no|escape}'
	);
{/if}

{foreach name=insert from=$insertNewsCategoryList item=v}
	select @myNo := coalesce(max(news_news_category_no), 0) + 1
	from news_news_category;

	insert into
		news_news_category(
			news_news_category_no,
			news_no,
			news_category_no,
			c_stamp,
			u_stamp
		)
		values(
			@myNo,
			'{$news_no|escape}',
			{$v|escape},
			current_timestamp,
			current_timestamp
		)
		;
{/foreach}


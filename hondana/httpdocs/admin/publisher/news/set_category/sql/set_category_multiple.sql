lock tables news_news_category write;

{foreach name=news from=$news_no_list item=news_no}
	select @mycategoryNo := coalesce(max(news_news_category_no), 0) + 1
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
			@mycategoryNo,
			{$news_no|escape},
			{$news_category_no|escape},
			current_timestamp,
			current_timestamp
		)
		;
{/foreach}

unlock tables;

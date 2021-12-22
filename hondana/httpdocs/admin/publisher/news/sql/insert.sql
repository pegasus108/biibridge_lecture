
select @myNo := coalesce(max(news_no), 0) + 1
from news;

insert into
	news(
		news_no,
		publisher_no,
		name,
		news_category_no,
		value,
		public_status,
		public_date,
		navi_display,
		c_stamp
	)
	values(
		@myNo,
		'{$publisher_no|escape}',
		'{$name}',
		'{$news_category_no|escape}',
		'{$value}',
		'{$public_status|escape}',
		{if $public_date!='0000-00-00 00:00:00'}'{$public_date}'{else}current_timestamp(){/if},
		'{$navi_display|escape}',
		current_timestamp
	);


{foreach name=news_relate from=$news_relate_list item=news_relate}
select @myRelateNo := coalesce(max(news_relate_no), 0) + 1
from news_relate;

insert into
	news_relate(
		news_relate_no,
		news_no,
		book_no,
		`order`,
		c_stamp
	)
	values(
		@myRelateNo,
		@myNo,
		'{$news_relate|escape}',
		{$smarty.foreach.news_relate.iteration},
		current_timestamp
	);
{/foreach}

{if $publisher.news_category_edit}
	{* news category 複数登録 *}


{foreach name=insert from=$news_category_list item=v}
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
			@myNo,
			{$v|escape},
			current_timestamp,
			current_timestamp
		)
		;
{/foreach}


{/if}

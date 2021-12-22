{if $deleteList}
delete from news_relate
where news_relate_no in( {$deleteList|@join:','} )
	and exists(
		select * from news
		where news.news_no = news_relate.news_no
		and news.publisher_no = '{$publisher_no|escape}'
	);
{/if}

{if $updateList}
{foreach name=update from=$updateList key=k item=v}
	update news_relate set `order` = {$v.order}
	where
		book_no = '{$v.id|escape}'
		and news_no = '{$news_no|escape}'
	;
{/foreach}
{/if}

{foreach name=insert from=$insertList item=news_relate}

select @myNo := coalesce(max(news_relate_no), 0) + 1
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
		@myNo,
		'{$news_no|escape}',
		'{$news_relate.id|escape}',
		'{$news_relate.order|escape}',
		current_timestamp
	)
	;
{/foreach}


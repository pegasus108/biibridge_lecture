update news
set
	navi_display = 1
where news_no
	in(
		{foreach name=news_no from=$news_no_list item=news_no }
		{$news_no}{if !$smarty.foreach.news_no.last},{/if}
		{/foreach}
	)
	and publisher_no = '{$publisher_no|escape}'
	and public_status = 1;


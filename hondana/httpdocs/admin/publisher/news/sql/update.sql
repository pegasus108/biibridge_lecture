update
	news
set
	name = '{$name}',
	news_category_no = '{$news_category_no|escape}',
	value = '{$value}',
	public_status = '{$public_status|escape}',
	navi_display = '{$navi_display|escape}',
	public_date = {if $public_date!='0000-00-00 00:00:00'}'{$public_date}'{else}current_timestamp(){/if}
where
	news_no = '{$news_no|escape}'
	and publisher_no = '{$publisher_no|escape}';

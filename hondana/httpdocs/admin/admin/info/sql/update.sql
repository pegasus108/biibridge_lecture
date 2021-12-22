lock tables info write;


update
	info
set
	name = '{$name}',
	value = '{$value}',
	public_status = {$public_status},
	public_date = {if $public_date!='0000-00-00 00:00:00'}'{$public_date}'{else}current_timestamp(){/if}
where
	info_no = {$info_no};


unlock tables;

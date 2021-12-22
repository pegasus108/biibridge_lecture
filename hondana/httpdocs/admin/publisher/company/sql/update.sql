update
	company
set
	name = '{$name}',
	company_category_no = '{$company_category_no|escape}',
	value = '{$value}',
	public_status = '{$public_status|escape}',
	public_date = {if $public_date!='0000-00-00 00:00:00'}'{$public_date}'{else}current_timestamp(){/if}
where
	company_no = '{$company_no|escape}'
	and publisher_no = '{$publisher_no|escape}';

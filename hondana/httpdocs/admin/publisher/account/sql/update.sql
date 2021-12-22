lock tables publisher_account write;


update
	publisher_account
set
{if !$is_default}
	id = '{$id}',
	password = '{$password}',
	role_no = '{$role_no}',
{/if}
	name = '{$name}'


where
	publisher_account_no = {$publisher_account_no}
	and publisher_no = {$publisher_no};


unlock tables;

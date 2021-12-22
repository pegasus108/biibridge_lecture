select @myNo := coalesce(max(publisher_account_no), 0) + 1
from publisher_account;

insert into
	publisher_account
set
	publisher_account_no = @myNo,
{if !$is_default}
	id = '{$id}',
	password = '{$password}',
	role_no = '{$role_no|escape}',
{/if}
	name = '{$name}',
	publisher_no = '{$publisher_no|escape}'
;

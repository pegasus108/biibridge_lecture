select @myNo := coalesce(max(company_no), 0) + 1
from company;

insert into
	company(
		company_no,
		publisher_no,
		name,
		company_category_no,
		value,
		public_status,
		public_date,
		c_stamp
	)
	values(
		@myNo,
		'{$publisher_no|escape}',
		'{$name}',
		'{$company_category_no|escape}',
		'{$value}',
		'{$public_status|escape}',
		{if $public_date!='0000-00-00 00:00:00'}'{$public_date}'{else}current_timestamp(){/if},
		current_timestamp
	);

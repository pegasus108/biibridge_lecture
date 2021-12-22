lock tables info write;


select @myNo := coalesce(max(info_no), 0) + 1
from info;


insert into
	info(
		info_no,
		name,
		value,
		public_status,
		public_date,
		c_stamp
	)
	values(
		@myNo,
		'{$name}',
		'{$value}',
		{$public_status},
		{if $public_date!='0000-00-00 00:00:00'}'{$public_date}'{else}current_timestamp(){/if},
		current_timestamp
	);

unlock tables;

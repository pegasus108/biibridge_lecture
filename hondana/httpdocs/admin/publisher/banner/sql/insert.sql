select @myNo := coalesce(max(banner_no), 0) + 1
from banner
;

select @myOrder := coalesce(max(display_order), 0) + 1
from banner
where publisher_no = '{$publisher_no|escape}'
;


insert into
	banner(
		banner_no,
		publisher_no,
		name,
		url,
		image,
		public_status,
		display_order,
		target,
		place,
		c_stamp
	)
	values(
		@myNo,
		'{$publisher_no|escape}',
		'{$name}',
		'{$url}',
		'{$image}',
		'{$public_status|escape}',
		@myOrder,
		{if $target}'{$target}'{else}NULL{/if},
		'{$place}',
		current_timestamp
	);


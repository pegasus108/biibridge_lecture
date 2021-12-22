select @myNo := coalesce(max(banner_big_no), 0) + 1
from banner_big
;

select @myOrder := coalesce(max(display_order), 0) + 1
from banner_big
where publisher_no = '{$publisher_no|escape}'
;


insert into
	banner_big(
		banner_big_no,
		publisher_no,
		name,
		url,
		image,
		public_status,
		display_order,
		target,
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
		current_timestamp
	);


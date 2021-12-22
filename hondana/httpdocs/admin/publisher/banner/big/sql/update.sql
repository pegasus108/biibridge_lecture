update banner_big
set
	name='{$name}',
	url='{$url}',
	{if $new_image}
	image='{$new_image}',
	{/if}
	target={if $target}'{$target}'{else}NULL{/if},
	public_status='{$public_status|escape}'
where
	banner_big_no='{$banner_big_no|escape}'
	and publisher_no = '{$publisher_no|escape}';


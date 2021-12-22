update banner
set
	name='{$name}',
	url='{$url}',
	{if $new_image}
	image='{$new_image}',
	{/if}
	target={if $target}'{$target}'{else}NULL{/if},
	place='{$place|escape}',
	public_status='{$public_status|escape}'
where
	banner_no='{$banner_no|escape}'
	and publisher_no = '{$publisher_no|escape}';


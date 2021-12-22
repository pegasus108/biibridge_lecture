lock tables author write;


update
	author
set
	name = '{$name}',
	kana = '{$kana}',
	{if $new_image}
	image='{$new_image}',
	{elseif $clear_image}
	image='',
	{/if}
	value = '{$value}'
where
	author_no = {$author_no}
	and publisher_no = {$publisher_no};


unlock tables;

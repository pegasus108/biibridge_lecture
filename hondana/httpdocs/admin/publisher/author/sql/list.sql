select SQL_CALC_FOUND_ROWS
	author_no, name, kana, image, value

from
	author

where
	publisher_no = {$publisher_no}
	
	{if $search_name != ''}
	and replace(replace(name,' ',''),'　','') like replace(replace('%{$search_name}%',' ',''),'　','')
	{/if}
	
	{if $search_kana != ''}
	and replace(replace(kana,' ',''),'　','') like replace(replace('%{$search_kana}%',' ',''),'　','')
	{/if}

order by
{if $order == 'image_asc'}
	image,

{elseif $order == 'image_desc'}
	image desc,

{elseif $order == 'value_asc'}
	value,

{elseif $order == 'value_desc'}
	value desc,

{elseif $order == 'name_asc'}
	name,

{elseif $order == 'name_desc'}
	name desc,

{elseif $order == 'kana_asc'}
	kana,

{/if}
	kana desc

{if $limit}
	limit {if $offset}{$offset}, {/if}{$limit}
{/if}
;
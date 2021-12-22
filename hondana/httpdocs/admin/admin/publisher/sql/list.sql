select SQL_CALC_FOUND_ROWS
	*,date_format(c_stamp,'%Y/%m/%d') as c_stamp_f
from publisher

where
	d_stamp is null
	{if $search_name != ''}
	and `name` like '%{$search_name}%'
	{/if}
	{if $search_kana != ''}
	and `kana` like '%{$search_kana}%'
	{/if}
	{if $search_url != ''}
	and `url` like '%{$search_url}%'
	{/if}

order by
{if $order == 'name_asc'}
	name,

{elseif $order == 'name_desc'}
	name desc,

{elseif $order == 'url_asc'}
	url,

{elseif $order == 'url_desc'}
	url desc,

{elseif $order == 'kana_asc'}
	kana,

{elseif $order == 'kana_desc'}
	kana desc,

{elseif $order == 'c_stamp_asc'}
	c_stamp,

{elseif $order == 'c_stamp_desc'}
	c_stamp desc,

{/if}
	publisher_no desc

{if $limit}
	limit {if $offset}{$offset}, {/if}{$limit}
{/if}
;
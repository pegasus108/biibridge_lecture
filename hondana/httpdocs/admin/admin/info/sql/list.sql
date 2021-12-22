select *, date_format(public_date, '%Y/%m/%d %H:%i:%s') as p_stamp

from
	info

order by
{if $order == 'display_asc'}
	public_status,

{elseif $order == 'display_desc'}
	public_status desc,

{elseif $order == 'title_asc'}
	name,

{elseif $order == 'title_desc'}
	name desc,

{elseif $order == 'public_date_asc'}
	public_date,

{/if}
	public_date desc
;
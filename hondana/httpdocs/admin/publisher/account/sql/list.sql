select SQL_CALC_FOUND_ROWS
	*

from
	publisher_account

where
	publisher_no = {$publisher_no}

order by
{if $order == 'id_asc'}
	id,

{elseif $order == 'id_desc'}
	id desc,

{elseif $order == 'name_asc'}
	name,

{elseif $order == 'name_desc'}
	name desc,

{elseif $order == 'role_asc'}
	role_no,

{elseif $order == 'role_desc'}
	role_no desc,

{/if}
	id

{if $limit}
	limit {if $offset}{$offset}, {/if}{$limit}
{/if}
;
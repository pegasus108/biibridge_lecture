select SQL_CALC_FOUND_ROWS
	n.*, date_format(n.public_date, '%Y/%m/%d %H:%i:%s') as p_stamp, nc.`name` as c_name

from
	company as n
	left join company_category as nc
	on n.company_category_no = nc.company_category_no

where
	n.publisher_no = '{$publisher_no|escape}'

	{if $search_display != ''}
	and n.public_status = '{$search_display|escape}'
	{/if}

	{if $search_category != ''}
	and exists(
		select *
			from
				(
					select lft,rgt from company_category where company_category_no = '{$search_category|escape}'
				) as ccp,
			company_category as cc
			where
			n.company_category_no = cc.company_category_no
			and cc.lft >= ccp.lft and cc.rgt <= ccp.rgt
	)
	{/if}

	{if $search_title != ''}
	and n.`name` like '%{$search_title}%'
	{/if}

order by
{if $order == 'display_asc'}
	n.public_status,

{elseif $order == 'display_desc'}
	n.public_status desc,

{elseif $order == 'title_asc'}
	n.name,

{elseif $order == 'title_desc'}
	n.name desc,

{elseif $order == 'category_asc'}
	n.company_category_no,

{elseif $order == 'category_desc'}
	n.company_category_no desc,

{elseif $order == 'public_date_asc'}
	n.public_date,

{/if}
	n.public_date desc

{if $limit}
	limit {if $offset}{$offset}, {/if}{$limit}
{/if}
;
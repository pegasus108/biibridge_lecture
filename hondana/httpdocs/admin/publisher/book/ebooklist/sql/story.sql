select
s.*,p.name as pname

from story as s
left join production as p on s.production_id = p.id

where
1

{if $limit}
	limit {if $offset}{$offset}, {/if}{$limit}
{/if}
;


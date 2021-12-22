--delete
--from banner
--where
--	banner_no in ({$listString})
--	and publisher_no = '{$publisher_no}';

{foreach from=$banner_no_list item=banner_no}

select
	@myOrder := display_order
from banner
where banner_no = '{$banner_no|escape}'
and publisher_no = '{$publisher_no|escape}'
;

delete
from banner
where
	banner_no = '{$banner_no|escape}'
	and publisher_no = '{$publisher_no|escape}';

update banner
set
	display_order = display_order - 1
where display_order > @myOrder
and publisher_no = '{$publisher_no|escape}'
;

{/foreach}

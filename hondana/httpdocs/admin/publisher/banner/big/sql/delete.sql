--delete
--from banner_big
--where
--	banner_big_no in ({$listString})
--	and publisher_no = '{$publisher_no}';

{foreach from=$banner_big_no_list item=banner_big_no}

select
	@myOrder := display_order
from banner_big
where banner_big_no = '{$banner_big_no|escape}'
and publisher_no = '{$publisher_no|escape}'
;

delete
from banner_big
where
	banner_big_no = '{$banner_big_no|escape}'
	and publisher_no = '{$publisher_no|escape}';

update banner_big
set
	display_order = display_order - 1
where display_order > @myOrder
and publisher_no = '{$publisher_no|escape}'
;

{/foreach}


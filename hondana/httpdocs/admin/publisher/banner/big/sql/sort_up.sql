select
	@myOrder := display_order
from banner_big
where banner_big_no = '{$banner_big_no|escape}'
and publisher_no = '{$publisher_no|escape}'
;


update banner_big
set
	display_order = display_order * (-1)
where banner_big_no = '{$banner_big_no|escape}'
and publisher_no = '{$publisher_no|escape}'
;


update banner_big
set
	display_order = display_order + 1
where display_order = (@myOrder - 1)
and publisher_no = '{$publisher_no|escape}'
;


update banner_big
set
	display_order = ((display_order * (-1))-1)
where banner_big_no = '{$banner_big_no|escape}'
and publisher_no = '{$publisher_no|escape}'
;


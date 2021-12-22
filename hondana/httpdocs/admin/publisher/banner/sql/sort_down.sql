select
	@myOrder := display_order
from banner
where banner_no = '{$banner_no|escape}'
and publisher_no = '{$publisher_no|escape}'
;


update banner
set
	display_order = display_order * (-1)
where banner_no = '{$banner_no|escape}'
and publisher_no = '{$publisher_no|escape}'
;


update banner
set
	display_order = display_order - 1
where display_order = (@myOrder + 1)
and publisher_no = '{$publisher_no|escape}'
;


update banner
set
	display_order = ((display_order * (-1))+1)
where banner_no = '{$banner_no|escape}'
and publisher_no = '{$publisher_no|escape}'
;

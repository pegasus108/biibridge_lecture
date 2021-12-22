lock tables publisher_netshop write;


select
	@myOrder := display_order
from publisher_netshop
where publisher_netshop_no = {$publisher_netshop_no}
and publisher_no = {$publisher_no}
;


update publisher_netshop
set
	display_order = display_order * (-1)
where publisher_netshop_no = {$publisher_netshop_no}
and publisher_no = {$publisher_no}
;


update publisher_netshop
set
	display_order = display_order + 1
where display_order = (@myOrder - 1)
and publisher_no = {$publisher_no}
;


update publisher_netshop
set
	display_order = ((display_order * (-1))-1)
where publisher_netshop_no = {$publisher_netshop_no}
and publisher_no = {$publisher_no}
;


unlock tables;

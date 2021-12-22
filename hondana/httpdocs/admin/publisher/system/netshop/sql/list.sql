select
	n.netshop_no, pn.publisher_netshop_no, pn.public_status, n.name

from
	publisher_netshop as pn
	left join netshop as n on pn.netshop_no = n.netshop_no

where
	pn.publisher_no = {$publisher_no}
order by
	pn.display_order
;
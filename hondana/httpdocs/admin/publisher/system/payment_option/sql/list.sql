select
	po.payment_option_no, pp.publisher_payment_no, pp.public_status, po.name

from
	publisher_payment as pp
	left join payment_option as po on pp.payment_option_no = po.payment_option_no

where
	pp.publisher_no = {$publisher_no}
order by
	po.payment_option_no
;
select
	banner_no, name, url, image, public_status, display_order,target

from
	banner

where
	publisher_no = '{$publisher_no|escape}'
order by
	display_order
;
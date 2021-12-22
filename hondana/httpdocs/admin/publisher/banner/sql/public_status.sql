SELECT n.*
FROM `banner` as n
WHERE
	n.banner_no in ({$listString})
	and n.publisher_no = '{$publisher_no|escape}'
	and n.public_status = 1;
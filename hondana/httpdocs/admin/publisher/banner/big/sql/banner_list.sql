SELECT n.*
FROM `banner_big` as n
WHERE n.banner_big_no in ({$listString})
	and n.publisher_no = '{$publisher_no|escape}'
;
SELECT *
FROM sites

WHERE
	publisher_no = '{$publisher_no|escape}'

ORDER BY `sort` ASC
;

SELECT nc . *
FROM `series` AS nc
WHERE (
	NOT
	EXISTS (
	
		SELECT nce . *
		FROM `series` AS nce
		WHERE nce.series_no
		IN ( {$listString} )
		AND nce.lft > nc.lft
		AND nce.lft < nc.rgt
	)
	AND (
	nc.rgt - nc.lft
	) <> 1
)
AND nc.series_no
IN ( {$listString} )
AND publisher_no = {$publisher_no};
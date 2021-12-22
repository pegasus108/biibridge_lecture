SELECT nc . *
FROM `label` AS nc
WHERE (
	NOT
	EXISTS (

		SELECT nce . *
		FROM `label` AS nce
		WHERE nce.label_no
		IN ( {$listString} )
		AND nce.lft > nc.lft
		AND nce.lft < nc.rgt
	)
	AND (
	nc.rgt - nc.lft
	) <> 1
)
AND nc.label_no
IN ( {$listString} )
AND publisher_no = {$publisher_no};
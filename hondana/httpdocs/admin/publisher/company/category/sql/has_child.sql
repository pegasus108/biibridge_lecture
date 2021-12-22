SELECT nc . *
FROM `company_category` AS nc
WHERE (
	NOT
	EXISTS (

		SELECT nce . *
		FROM `company_category` AS nce
		WHERE nce.company_category_no
		IN ( {$listString} )
		AND nce.lft > nc.lft
		AND nce.lft < nc.rgt
	)
	AND (
	nc.rgt - nc.lft
	) <> 1
)
AND nc.company_category_no
IN ( {$listString} )
AND publisher_no = '{$publisher_no|escape}';
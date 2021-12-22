SELECT nc . *
FROM `news_category` AS nc
WHERE (
	NOT
	EXISTS (

		SELECT nce . *
		FROM `news_category` AS nce
		WHERE nce.news_category_no
		IN ( {$listString} )
		AND nce.lft > nc.lft
		AND nce.lft < nc.rgt
	)
	AND (
	nc.rgt - nc.lft
	) <> 1
)
AND nc.news_category_no
IN ( {$listString} )
AND publisher_no = '{$publisher_no|escape}';
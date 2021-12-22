SELECT nc . *
FROM `genre` AS nc
WHERE (
	NOT
	EXISTS (
	
		SELECT nce . *
		FROM `genre` AS nce
		WHERE nce.genre_no
		IN ( {$listString} )
		AND nce.lft > nc.lft
		AND nce.lft < nc.rgt
	)
	AND (
	nc.rgt - nc.lft
	) <> 1
)
AND nc.genre_no
IN ( {$listString} )
AND publisher_no = {$publisher_no};
SELECT n.*
FROM `info` as n
WHERE
	n.info_no in ({$listString})
	and n.public_status = 1;
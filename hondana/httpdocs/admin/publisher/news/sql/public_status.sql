SELECT n.*
FROM `news` as n
WHERE
	n.news_no in ({$listString})
	and n.public_status = 1;
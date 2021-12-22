SELECT n.*
FROM `author` as n
WHERE
	n.author_no in ({$listString})
	and n.public_status = 1;
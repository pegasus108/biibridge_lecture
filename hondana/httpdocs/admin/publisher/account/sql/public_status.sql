SELECT n.*
FROM `publisher_account` as n
WHERE
	n.publisher_account_no in ({$listString})
	and n.public_status = 1;
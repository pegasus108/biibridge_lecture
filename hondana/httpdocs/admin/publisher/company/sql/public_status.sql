SELECT n.*
FROM `company` as n
WHERE
	n.company_no in ({$listString})
	and n.public_status = 1;
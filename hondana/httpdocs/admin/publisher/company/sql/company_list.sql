SELECT n.*
FROM `company` as n
WHERE n.company_no in ({$listString})
	and n.publisher_no = '{$publisher_no|escape}';
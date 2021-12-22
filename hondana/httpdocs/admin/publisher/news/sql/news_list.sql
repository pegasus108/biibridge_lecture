SELECT n.*
FROM `news` as n
WHERE n.news_no in ({$listString})
	and publisher_no = '{$publisher_no|escape}';
